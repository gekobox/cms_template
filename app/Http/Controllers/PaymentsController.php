<?php

namespace App\Http\Controllers;

use App\Classes\Helpers\EcommerceIntegrationHelper;
use App\Classes\ResourceManager;
use App\Models\AccountPayment;
use App\Models\AccountSubscription;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mollie;

class PaymentsController extends Controller
{
    private $Account;

    public function __construct()
    {        
        // Set the local account
        //$this->Account = ResourceManager::get('Account', 1);
    }

    public function testPayment(){
        /*$paymentType= new \App\Models\PaymentType(); 
        $paymentType->type="credit_cash";
        $paymentType->name="tarjeta";
        $paymentType->save();*/
        
        /*$paymentType= \App\Models\PaymentType::first();
        return $paymentType->name;*/
        
        return 'ok';
    }
        
    /**
     * Register a payment for a given order
     * @param type $orderId id of the order
     * @param type $paymentType payment method selected on the checkout screen
     * @param type $amount amount paid in the current payment
     */
    public function payOrder(Request $request){
     
        $orderId= $request->orderId;
        $paymentType= $request->paymentType;
        $amount= $request->amount;
        
        $Order= Order::find($orderId);
        $PaymentType= PaymentType::where("type", $paymentType)->first();
        $PaymentStatusPaid= PaymentStatus::where('status', PaymentStatus::$PAID)->first();
        if(isset($Order) && isset($PaymentType)){
            $Payment= new Payment();
            $Payment->order_id= $Order->id;
            $Payment->payment_status_id= $PaymentStatusPaid->id;
            $Payment->payment_type_id= $PaymentType->id;
            $Payment->amount= $amount;
            $Payment->save();
            EcommerceIntegrationHelper::registerPaidOrder($Order->id);
            return "ok";
        }
        
        return response("Invalid order", 403);
    }    
    
    public function registerPaidOrder(Request $request){
        EcommerceIntegrationHelper::registerPaidOrder($request->order_id);
    }
    
    public function startRecurringPayment(Request $request)
    {

        // Create a unique Mollie customer
        $customer = $this->_createCustomer($this->Account);

        // Save the Mollie customer id locally
        $data = [
            'payment_customer_id' => $customer->id
        ];
        $this->Account->saveResource($data);

        // Create a first payment
        $payment = $this->_createFirstPayment($customer->id);

        // Redirect to the payment portal
        return redirect()->to($payment->getPaymentUrl());
    }

    /**
     * This method is called when Mollie issues a status update request
     *
     * @param Request $request
     */
    public function webhook(Request $request){

        // Get the payment id
        $paymentId = $request->input('id');

        // Get the payment object from Mollie
        $payment = Mollie::api()->payments()->get($paymentId);

        // Update the payment status locally
        $AccountPayment = AccountPayment::where('payment_id', '=', $payment->id)->first();
        if(empty($AccountPayment)){
            Log::info('Payment with id '.$paymentId.' not found.');
            abort(500);
        }

        $data = ['status' => $payment->status];
        $AccountPayment->saveResource($data);

        // The payment is completed
        if ($payment->isPaid())
        {

            // If the recurring type is "first", create a subscription
            if($payment->recurringType == 'first'){

                // check for active or pending mandates
                $hasActiveMandate = false;
                $mandates = $this->_getMandates($payment->customerId);

                foreach($mandates->data as $mandate){
                    if($mandate->status = 'valid' || $mandate->status = 'pending'){
                        $hasActiveMandate = true;
                    }
                }

                // Create the subscription
                if($hasActiveMandate === true){
                    $subscription = $this->_createSubscription($payment->customerId);
                }
            }

            // Add the subscription id to the local payment
            if(!empty($payment->subscriptionId)){
                $AccountSubscription = AccountSubscription::where('subscription_id', '=', $payment->subscriptionId)->first();
                $data = [
                    'account_subscription_id' => $AccountSubscription->id
                ];
                $AccountPayment->saveResource($data);
            }

            // Enable the functionalities

        }

        // The payment is aborted
        elseif (!$payment->isOpen())
        {
            // Disable the functionalities
        }
    }

    /**
     * Create a unique Mollie customer
     *
     * @return mixed
     */
    private function _createCustomer(){

        $name = trim($this->Account->first_name.' '.$this->Account->last_name);

        $customer = Mollie::api()->customers()->create([
            "name"  => $name,
            "email" => $this->Account->email,
        ]);
        return $customer;
    }

    /**
     * Create a first payment
     *
     * In order to get started with recurring payments you need to require the customer's consent through a first payment.
     * It's like a regular payment but the customer is shown information about your organization, and they need to complete
     * the payment with the account or card that will be used for recurring charges in the future. After the first payment
     * is completed succesfully the customer's account or card will immediately be chargeable on-demand, or periodically
     * through subscriptions.
     *
     * @param $customerId
     * @return mixed
     */
    private function _createFirstPayment($customerId){
        $payment = Mollie::api()->payments()->create([
            'amount'        => 9.95, // This should have the same amount as the subscription price
            'customerId'    => $customerId,
            'recurringType' => 'first',
            'description'   => 'Your first Vendata payment',
            'redirectUrl'   => 'http://pos.vandersluis.media',
            'webhookUrl'    => 'http://pos.vandersluis.media/mollie/webhook',
        ]);

        // Save the payment locally
        $AccountPayment = ResourceManager::make('AccountPayment');
        $data = [
            'account_id' => $this->Account->id,
            'account_subscription_id' => null,
            'payment_id' => $payment->id,
            'status' => $payment->status
        ];
        $AccountPayment->saveResource($data);

        // Return the Mollie payment
        return $payment;
    }

    /**
     * Make sure the customer has a pending or valid mandate
     *
     * Continue if there's a mandate with its status being either pending or valid, otherwise set up a first payment for
     * the customer first.
     */
    private function _getMandates($customerId){
        $mandates = Mollie::api()->customersMandates()->withParentId($customerId)->all();
        return $mandates;
    }

    /**
     * Create a subscription
     *
     * For simple regular recurring payments with constant amounts, you can create subscriptions with the Subscriptions API.
     * Subscription payments will be spawned automatically at the specified frequency, and will show up in your Dashboard.
     */
    private function _createSubscription($customerId){
        $subscription = Mollie::api()->customersSubscriptions()->withParentId($customerId)->create([
            "amount"      => 9.95,
            "interval"    => "1 month",
            "description" => "Your monthly Vendata subscription",
            "webhookUrl"  => "http://pos.vandersluis.media/mollie/webhook",
        ]);

        // Save the subscription locally
        $AccountSubscription = ResourceManager::make('AccountSubscription');
        $data = [
            'account_id' => $this->Account->id,
            'subscription_id' => $subscription->id
        ];
        $AccountSubscription->saveResource($data);

        // Return the Mollie subscription
        return $subscription;
    }
}
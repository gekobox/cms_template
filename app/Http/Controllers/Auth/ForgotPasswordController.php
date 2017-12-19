<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordRecovery;
use App\User;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Swift_TransportException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
    * Send a reset link to the given user.
    *
    * @param  Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getResetToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {                
            return response()->json([
                'data' => null,
                'message' => trans('auth.invalid_user')
            ], 400);
        }
        $token = $this->broker()->createToken($user);
        
        // Send recovery link to the supplied email
        //$this->sendResetPasswordEmail($token, $request->email);
        Mail::to($request->email)->send(new PasswordRecovery($token));
        
        return response()->json([
                'token' => $token,
        ]);

    }
    
    /**
     * Send the link to reset the password by email
     * @return type
     */
    public function sendResetPasswordEmail($token, $email){
        try{
            Mail::send('emails.reset_password'
                    , ['token' => $token, 'email' => $email]
                    , function($message) use($email) {
                /* @TO-DO webshop name for email 'from' */
                $message->from('support@vendata.io','Vendata');
                $message->to($email);
                $message->subject(trans('auth.password_recovery'));
            });
        } catch (Swift_TransportException $ex) {
            Log::error($ex);
        } catch (RequestException $ex){
            Log::error($ex);
        } catch(ClientException $ex){
            Log::error($ex);
        }
        
    }
}

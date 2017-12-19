<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m6 push-m3">
                <div class="card-top">
                    <span class="title">Payment</span>
                </div>
                <div class="card checkout">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="text-align-right">Amount to pay</div>
                                <div>
                                    <div class="input-field">
                                        <input type="number" v-model="amountToPay" class="total">
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="text-align-right grey-text">Due</div>
                                <div>
                                    <div class="input-field">
                                        <input type="number" v-model="remainder" class="remainder" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col m6">
                                <a class="payment-btn btn-flat teal white-text btn-large waves-effect waves-light" v-on:click="toPaymentComplete('maestro')">Maestro</a>
                            </div>
                            <div class="col m6">
                                <a class="payment-btn btn-flat teal white-text btn-large waves-effect waves-light" v-on:click="toPaymentComplete('visa_mastercard')">Visa/MasterCard</a>
                            </div>
                            <div class="col m6">
                                <a class="payment-btn btn-flat teal white-text btn-large waves-effect waves-light" v-on:click="toPaymentComplete('cash')">Cash</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from "../../../elements/HsEventBus/template.vue";
    import HsInputNumber from "../../../ui/HsInputNumber/template.vue";

    export default {
        components: {
            HsInputNumber
        },
        created: function(){
            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);
            // Set the previous page
            HsEventBus.$emit('prevPage', '/');

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', []);
        },
        mounted: function(){

            // Hide the loader
            window.loader.hide();
        },
        data: function(){
            return {
                total: 10.00,
                amountToPay: 10.00
            }
        },
        computed: {
            remainder: function(){
                return this.total - this.amountToPay;
            }
        },
        methods: {
            toPaymentComplete: function(paymentType){
                var instance = this;
                window.loader.show();
                axios.post('/api/order/pay',{
                    orderId: instance.$route.params.orderId,
                    paymentType: paymentType,                    
                    amount: 10,
                    
                })
                .then(function(response){
                    if(response.status == 200){
                        instance.$router.push('/pos/payment/complete');                        
                    }
                    window.loader.hide();
                })
                .catch(function(r){
                    window.loader.hide();
                });                  
            }
        }
    }
</script>

<style>
    /* Card */
    .card-top {
        padding: 0 0 0.25rem 0;
    }
    .card-top .title {
        text-transform: uppercase;
    }
    .card-content + .card-content {
        border-top: 1px solid rgba(160,160,160,0.2);
    }

    /* Total */
    input.total {
        font-size: 30px;
        line-height: 1.2;
        margin: 0;
        text-align: right;
    }
    input.remainder {
        font-size: 30px;
        line-height: 1.2;
        margin: 0;
        text-align: right;
    }

    /* Payment buttons */
    .payment-btn {
        display: block;
        margin-bottom: 0.75rem;
    }
</style>
<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m9 l9 xl6">
                <hs-card title="Payment details">
                    <div slot="card-content">
                        <div class="row">
                            <div class="col s12">
                                <a class="right hs-button waves-effect waves-light btn teal white-text btn-flat" v-on:click="makePayment">
                                    Confirm your payment
                                </a>
                            </div>
                        </div>
                    </div>
                </hs-card>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../../elements/HsEventBus/template.vue';
    import HsCard from '../../../ui/HsCard/template.vue';
    import HsMyAccountTabs from '../../../elements/Account/HsMyAccountTabs/HsMyAccountTabs.vue';

    export default {
        components: {
            HsCard
        },
        mixins: [
            HsMyAccountTabs
        ],
        created: function(){
            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);
            // Set the previous page
            HsEventBus.$emit('prevPage', false);

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', this.tabs);

            // Hide the loader
            window.loader.hide();
        },
        methods: {
            makePayment: function(){
                window.location.href='/mollie/start-recurring-payment';
//                var instance = this;
//                axios.post('/mollie/make-payment', {})
//                .then(function (response) {
//
//                })
//                .catch(function (error) {
//
//                });
            }
        }
    }
</script>

<template>
    <div>
        <p>{{ 'Auth.password_recovery_form_text' | translate }}</p>
        <div class="row">
            <div class="col s12">
                <hs-input-text v-bind:error="errors.email" v-model="email" :label="emailLabel"></hs-input-text>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <a class="right hs-button waves-effect waves-light btn teal white-text btn-flat" v-on:click="submit">{{ 'Auth.send' | translate }}</a>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsFormHelper from '../../helpers/HsFormHelper/template.vue';
    import HsInputText from '../../ui/HsInputText/template.vue';   
    import {Config} from '../../../config.js';

    export default {
        components: {
            HsInputText
        },
        mixins: [
            HsFormHelper
        ],
        created: function(){
            window.loader.hide();            
        },
        data: function(){
            return {
                email: '',
                emailLabel: this.$t('Auth.email_label'),
                errors: {}
            }
        },
        methods: {

            // Submit the form
            submit: function(){

                // Show the loader
                window.loader.show();

                var instance = this;
                axios.post('/api/password/request-recovery', {                        
                    email: instance.email
                })
                .then(function (response) {

                    // Redirect
                    if(response.status === 200){
                        instance.$router.push('/recover-password-confirmation');
                    }
                    else {
                        instance.errors = {'email': ['Unknown error']};

                        // Hide the loader
                        window.loader.hide();
                    }
                })
                .catch(function (error) {
                    instance.errors = error.response.data;

                    // Hide the loader
                    window.loader.hide();
                });
            }
        }
    }
</script>

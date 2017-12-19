<template>
    <div>
        <div v-if="!password_updated">
            <div class="row">
                <div class="col s12">
                    <hs-input-email v-bind:error="errors.email" v-model="email" :label="emailLabel"></hs-input-email>
                </div>
                <div class="col s12">
                    <hs-input-password v-bind:error="errors.password" v-model="password" :label="passwordLabel"></hs-input-password>
                </div>
                <div class="col s12">
                    <hs-input-password v-bind:error="errors.password_confirmation" v-model="password_confirmation" :label="passwordConfirmationLabel"></hs-input-password>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <a class="right hs-button waves-effect waves-light btn teal white-text btn-flat" v-on:click="submit">{{ 'Auth.save' | translate }}</a>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col s12">
                <p>{{ 'Auth.reset_password_confirmation_text' | translate }}</p>
                <br>
                <a class="hs-button waves-effect waves-light btn teal white-text btn-flat" href="/#/login">{{ 'Auth.go_to_login_page' | translate }}</a>
            </div> 
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsFormHelper from '../../helpers/HsFormHelper/template.vue';
    import HsInputEmail from '../../ui/HsInputEmail/template.vue';   
    import HsInputText from '../../ui/HsInputText/template.vue';   
    import HsInputPassword from '../../ui/HsInputPassword/template.vue'; 
    import {Config} from '../../../config.js';

    export default {
        components: {
            HsInputText,
            HsInputEmail,
            HsInputPassword
        },
        mixins: [
            HsFormHelper,
            HsEventBus
        ],
        created: function(){
            window.loader.hide();  
        },
        data: function(){
            return {
                email: '',
                password: '',
                password_confirmation: '',
                password_updated: false,
                emailLabel: this.$t('Auth.email_label'),
                passwordLabel: this.$t('Auth.password_label'),
                passwordConfirmationLabel: this.$t('Auth.password_confirmation_label'),
                errors: {}
            }
        },
        methods: {

            // Submit the form
            submit: function(){
                var instance = this;

                // Show the loader
                window.loader.show();

                axios.post('/api/password/reset', {                        
                    email: instance.email,
                    password: instance.password,
                    password_confirmation: instance.password_confirmation,
                    token: instance.$route.params.key
                })
                .then(function (response) {

                    // Redirect
                    if(response.status === 202){
                        instance.password_updated=true;
                    }
                    else{
                        instance.errors = response.response.data;
                    }

                    // Hide the loader
                    window.loader.hide();
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

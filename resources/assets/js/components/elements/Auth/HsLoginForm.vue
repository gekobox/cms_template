<template>
    <div>
        <div class="row">
            <div class="col s12">
                <hs-input-text v-bind:error="errors.email" v-model="email" :label="emailLabel"></hs-input-text>
            </div>
            <div class="col s12">
                <hs-input-password v-bind:error="errors.password" v-model="password" :label="passwordLabel"></hs-input-password>
            </div>
            <div class="col s12">
                <a href="/#/recover-password">{{'Auth.forgot_password' | translate}}</a>
            </div> 
        </div>
        <div class="row">
            <div class="col s12">
                <a class="right hs-button waves-effect waves-light btn teal white-text btn-flat" v-on:click="submit">
                    {{'Auth.login_button' | translate}}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsFormHelper from '../../helpers/HsFormHelper/template.vue';
    import HsInputText from '../../ui/HsInputText/template.vue';   
    import HsInputPassword from '../../ui/HsInputPassword/template.vue'; 
    import {Config} from '../../../config.js';

    export default {
        components: {
            HsInputText,
            HsInputPassword
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
                password: '',
                emailLabel: this.$t('Auth.email_label'),
                passwordLabel: this.$t('Auth.password_label'),
                errors: {}
            }
        },
        methods: {

            // Submit the form
            submit: function(){

                // Show the loader
                window.loader.show();

                var instance = this;
                axios.post('/oauth/token', {
                    grant_type: 'password',
                    client_id: Config.client_id,
                    client_secret: Config.client_secret,
                    username: instance.email,
                    password: instance.password
                })
                .then(function (response) {
                    // Redirect
                    if(response.status === 200){
                        if(response.data.access_token !== undefined){
                            window.localStorage.setItem("access_token", response.data.access_token);
                            window.access_token= response.data.access_token;
                            window.axios.defaults.headers.common['Authorization'] = "Bearer "+ window.access_token;
                            window.axios.defaults.headers.common['Accept'] = "application/json";
                            instance.getUserPermissions();
                        }
                    }
                    else{                        
                        instance.email='';
                        instance.password='';
                        instance.errors = response.response.data;

                        // Hide the loader
                        window.loader.hide();
                    }
                })
                .catch(function (error) {
                    instance.errors = error.response.data;

                    // Hide the loader
                    window.loader.hide();
                });
            },
            getUserPermissions: function(){
                var instance= this;
                axios.get('/api/user-permissions')
                .then(function(response){
                    window.localStorage.setItem("user_permissions", JSON.stringify(response.data));
                    window.user_permissions= response.data;
                    instance.$router.push('/');
                })
                .catch(function(response){

                });
            }
        }
    }
</script>

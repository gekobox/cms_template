<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m9 l9 xl6">
                <hs-card title="My account">
                    <div slot="card-content">
                        <div class="row">
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.company" v-model="company" label="Company"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.first_name" v-model="first_name" label="First name"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.last_name" v-model="last_name" label="Last name"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.street" v-model="street" label="Street"></hs-input-text>
                            </div>
                            <div class="col s12 m6">
                                <hs-input-number v-bind:error="errors.house_number" v-model="house_number" label="House number"></hs-input-number>
                            </div>
                            <div class="col s12 m6">
                                <hs-input-text v-bind:error="errors.house_number_addition" v-model="house_number_addition" label="Addition"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.postal_code" v-model="postal_code" label="Postal code"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.city" v-model="city" label="City"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-select v-bind:error="errors.country_id" v-model="country_id" label="Country" data-url="/api/get-resource-dropdown-options/country"></hs-input-select>
                            </div>
                            <div class="col s12">
                                <hs-input-text v-bind:error="errors.phone" v-model="phone" label="Phone"></hs-input-text>
                            </div>
                            <div class="col s12">
                                <hs-input-email v-bind:error="errors.email" v-model="email" label="E-mail address"></hs-input-email>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <a class="right hs-button waves-effect waves-light btn teal white-text btn-flat" v-on:click="submit">
                                    Opslaan
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
    import HsFormHelper from '../../../helpers/HsFormHelper/template.vue';
    import HsInputText from '../../../ui/HsInputText/template.vue';
    import HsInputEmail from '../../../ui/HsInputEmail/template.vue';
    import HsInputNumber from '../../../ui/HsInputNumber/template.vue';
    import HsInputSelect from '../../../ui/HsInputSelect/template.vue';

    export default {
        components: {
            HsCard,
            HsInputText,
            HsInputEmail,
            HsInputSelect,
            HsInputNumber
        },
        mixins: [
            HsMyAccountTabs,
            HsFormHelper
        ],
        created: function(){
            var instance= this;
            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);

            // Set the previous page
            HsEventBus.$emit('prevPage', false);

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', this.tabs);

            //Get the account id
            axios.get('/api/user/get-account')
                .then(function(response){
                    instance.id= response.data.account_id;
                    
                    // Get the form data
                    instance.getFormData();
                });
            
        },
        data: function(){
            return {
                id: null,
                company: '',
                first_name: '',
                last_name: '',
                street: '',
                house_number: null,
                house_number_addition: '',
                postal_code: '',
                city: '',
                country_id: null,
                phone: '',
                email: '',
                errors: {}
            }
        },
        methods: {

            // Submit the form
            submit: function(){
                var instance = this;
                axios.post('/api/save-resource/account', {
                    id: instance.id,
                    company: instance.company,
                    first_name: instance.first_name,
                    last_name: instance.last_name,
                    street: instance.street,
                    house_number: instance.house_number,
                    house_number_addition: instance.house_number_addition,
                    postal_code: instance.postal_code,
                    city: instance.city,
                    country_id: instance.country_id,
                    phone: instance.phone,
                    email: instance.email
                })
                .then(function (response) {

                    // Redirect
                    if(response.status === 200){
                        instance.$router.push('/my-account')
                    }
                })
                .catch(function (error) {

                    // Validation errors
                    if(error.response.status === 422){
                        instance.errors = error.response.data;
                    }
                });
            },

            // Get the form data
            getFormData: function(){
                var instance = this;
                if(instance.id !== undefined) {
                    axios.get('/api/get-resource/account/' + instance.id)
                    .then(function (response) {
                        var data = response.data;

                        // Set the local variables
                        instance.company = data.company;
                        instance.first_name = data.first_name;
                        instance.last_name = data.last_name;
                        instance.street = data.street;
                        instance.house_number = data.house_number;
                        instance.house_number_addition = data.house_number_addition;
                        instance.postal_code = data.postal_code;
                        instance.city = data.city;
                        instance.country_id = data.country_id;
                        instance.phone = data.phone;
                        instance.email = data.email;

                        // Update the Materialize text fields
                        instance.updateMaterializeTextFields();

                        // Hide the loader
                        window.loader.hide();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
                else{
                    window.loader.hide();
                }
            }
        }
    }
</script>

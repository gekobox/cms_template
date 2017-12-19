<template>
    <div>
        <div class="row">            
            <div class="col s12">
                <hs-input-text v-bind:error="errors.first_name" v-model="firstName" :label="firstNameLabel"></hs-input-text>
            </div>
            <div class="col s12">
                <hs-input-text v-bind:error="errors.last_name" v-model="lastName" :label="lastNameLabel"></hs-input-text>
            </div>
            <!--div class="col s12">
                <hs-input-text v-bind:error="errors.phone" v-model="phone" :label="phoneLabel"></hs-input-text>
            </div-->
            <div class="col s12">
                <hs-input-email v-bind:error="errors.email" v-model="email" :label="emailLabel"></hs-input-email>
            </div>            
        </div>
        <div class="row">
            <div class="col s12">
                <a class="right hs-button waves-effect waves-light btn teal white-text btn-flat" v-on:click="submit">
                    {{'Relation.save_button'|translate}}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../HsEventBus/template.vue';
    import HsFormHelper from '../../helpers/HsFormHelper/template.vue';
    import HsInputText from '../../ui/HsInputText/template.vue';    
    import HsInputSwitch from '../../ui/HsInputSwitch/template.vue';    
    import HsInputWysiwyg from '../../ui/HsInputWysiwyg/template.vue';    
    import HsInputEmail from '../../ui/HsInputEmail/template.vue';
    import HsModalHelper from '../../helpers/HsModalHelper/template.vue';

    export default {
        components: {
            HsInputText,
            HsInputSwitch,
            HsInputWysiwyg,
            HsInputEmail
        },        
        props:{
            relationId: null,
            relationCode:null,
        },
        mixins: [
            HsFormHelper,
            HsModalHelper
        ],
        created: function(){
            var instance= this;
            HsEventBus.$on('get_quick_relation_form', function(){
                instance.id= instance.relationId;    
                instance.relation_code= instance.relationCode;
                instance.initWysiwyg = !instance.initWysiwyg;
                instance.clearFields();
                instance.getFormData();
            });
        },
        data: function(){
            return {
                id: null,
                relation_code: null,
                firstName: '',
                lastName: '',
                phone: '',
                email: '',
                customerLabel: this.$t('Relation.customer_label'),
                leadLabel: this.$t('Relation.lead_label'),
                businessLabel: this.$t('Relation.business_label'),
                privateLabel: this.$t('Relation.private_label'),
                firstNameLabel: this.$t('RelationContact.first_name_label'),
                lastNameLabel: this.$t('RelationContact.last_name_label'),
                phoneLabel: this.$t('RelationContact.phone_label'),
                emailLabel: this.$t('RelationContact.email_label'),
                errors: {},
                initWysiwyg: false
            }
        },
        methods: {

            // Submit the form
            submit: function(){
                var instance = this;
                
                instance.closeModal('relation_modal');
                axios.post('/api/save-ecommerce-resource/customer', {
                        id: instance.id,
                        code: instance.relation_code,
                        first_name: this.firstName,
                        last_name: this.lastName,
                        phone: this.phone,
                        email: this.email 
                    })
                    .then(function (response) {

                        // Redirect
                        if(response.status === 200){
                            console.log('saved');
                        }
                        else if(response.status !== 500){
                            instance.errors = response.response.data;                            
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
                 window.loader.show();
                if(instance.id !== undefined && instance.id !== null) {
                    axios.get('/api/get-ecommerce-resource/customer/'+instance.relation_code)
                        .then(function (response) {
                            var data = response.data;
                            // Set the local variables
                            instance.firstName= data.firstname;
                            instance.lastName= data.lastname;
                            instance.phone= data.addresses[0].telephone;
                            instance.email= data.email;
                            
                            // Update the Materialize text fields
                            instance.updateMaterializeTextFields();                            

                            // Hide the loader
                            window.loader.hide();
                        })
                        .catch(function (error) {
                            console.log(error);
                             window.loader.hide();
                        });
                }
                else{
                    window.loader.hide();
                }
            },
            clearFields: function(){
                
                this.firstName= '';
                this.lastName= '';
                this.phone= '';
                this.email= '';
            }
        }
    }
</script>

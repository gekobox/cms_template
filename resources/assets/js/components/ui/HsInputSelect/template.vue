<template>
    <div class="input-field" :id="computedId">
        <select ref="input"
            :class="error === undefined ? '' : 'invalid'">
            <option v-for="option in options" :value="option.value" :selected="value == option.value">{{option.text}}</option>
        </select>
        <label>{{label}}</label>
    </div>
</template>

<script>
    import HsFormInput from "../../helpers/HsFormInputHelper/template.vue";
    import {HsEventBus} from "../../elements/HsEventBus/template.vue";

    export default {        
        mixins: [
            HsFormInput,
            HsEventBus
        ],
        props: {
            dataUrl: String,
            label: String,
            value: null,
            error: Array
        },
        data: function(){
            return {
                options: []
            }
        },
        created: function(){
            this.getData();
        },
        mounted: function(){
            var instance = this;
            $(document).ready(function(){
                $(instance.$refs.input).on('change', function(e){
                    instance.updateValue(e.target.value);
                })
            });
        },
        methods: {
            // Get the select options
            getData: function(){
                var instance = this;
                axios.get(instance.dataUrl)
                    .then(function (response) {
                        if(response.status === 200){

                            // Prepare the data
                            var results = response.data;
                            instance.options = results;

                            // Reinitialize the select box
                            $(document).ready(function() {
                                $(instance.$refs.input).material_select();
                            });
                        }
                    })
                    .catch(function (error) {

                        // Validation errors
                        if(error.response.status === 422){
                            instance.errors = error.response.data;
                        }
                    });
            },

            // Set the value on the input element and emit it
            updateValue: function(value){
                this.$refs.input.value = value;
                this.$emit('input', value);
                var selectedOption={
                    'selectedValue': value,
                    'options': this.options
                }
                HsEventBus.$emit('input-select-selected', selectedOption);
            }
        }
    }
</script>
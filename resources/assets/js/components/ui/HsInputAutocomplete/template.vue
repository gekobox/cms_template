<template>
    <div class="input-field" :class="{ 'has-prefix-icon': prefixIcon }" :id="computedId">
        <div class="prefix-icon valign-wrapper" v-if="prefixIcon">
            <i class="material-icons">{{ prefixIcon }}</i>
        </div>
        <input type="text"
                ref="input"
                :value="value"
                :class="error === undefined ? 'autocomplete' : 'autocomplete invalid'"
                v-on:input="updateValue($event.target.value)"
                v-on:blur="checkValue($event.target.value)"
                v-on:focus="getData()">
        <label>{{label}} <slot name="after-label"></slot></label>
    </div>
</template>

<script>
    import HsFormInput from "../../helpers/HsFormInputHelper/template.vue";

    export default {
        mixins: [
            HsFormInput
        ],
        props: {
            label: String,
            value: String,
            prefixIcon: String,
            placeholder: String,
            error: Array,
            dataUrl: String
        },
        data: function(){
            return {
                values: {}
            }
        },
        mounted: function(){
            this.getData();
        },
        methods: {

            // Get the data for the autocomplete dropdown
            getData: function(){
                var instance = this;
                axios.get(instance.dataUrl)
                    .then(function (response) {
                        if(response.status === 200){

                            // Prepare the data
                            var results = response.data;
                            var values = {};
                            for (var i=0; i<results.length; i++) {
                                values[results[i].name] = results[i].image;
                            }

                            // Set the data
                            instance.values = values;

                            // Init the autocomplete
                            $(document).ready(function(){
                                var input = $(instance.$el).find('input.autocomplete');
                                input.autocomplete({
                                    data: instance.values,
                                    onAutocomplete: function(val) {

                                        // Set the selected value
                                        instance.updateValue(val);
                                    }
                                    // limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
                                    // minLength: 1 // The minimum length of the input for the autocomplete to start. Default: 1.
                                });
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
            },

            // Check if the selected value is valid, otherwise empty it
            checkValue: function(value){
                if(this.values[value] === undefined){
                    this.$refs.input.value = '';
                    this.$emit('input', '');
                }
            }
        }
    }
</script>

<style scoped>
    .input-field.has-prefix-icon input {
        padding-left: 3.25rem;
    }
    .prefix-icon {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        padding: 0 0.75rem;
        color: #9e9ea4;
    }
    .prefix-icon i {
        font-size: 2rem;
    }
</style>
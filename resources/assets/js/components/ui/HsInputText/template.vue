<template>
    <div class="input-field" :class="{ 'has-prefix-icon': prefixIcon }" :id="computedId">
        <div class="prefix-icon valign-wrapper" v-if="prefixIcon">
            <i class="material-icons">{{ prefixIcon }}</i>
        </div>
        <input type="text"
                ref="input"
                :placeholder="placeholder"
                :value="value"
                :class="error === undefined ? '' : 'invalid'"
                v-on:input="updateValue($event.target.value)">
        <label>{{label}}</label>
        <div class="clear-btn-container" v-if="clearButton && value.length > 0" v-on:click="updateValue('')">
            <div class="inner valign-wrapper">
                <i class="material-icons">cancel</i>
            </div>
        </div>
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
            prefixIcon: String,
            placeholder: String,
            clearButton: Boolean,
            value: String,
            error: Array
        },
        methods: {

            // Set the value on the input element and emit it
            updateValue: function(value){
                this.$refs.input.value = value;
                this.$emit('input', value);
            }
        }
    }
</script>

<style scoped>
    .input-field.has-prefix-icon input {
        padding-left: 3rem;
    }
    .prefix-icon {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        padding: 0 0.75rem;
        color: #9e9ea4;
    }
    .clear-btn-container{
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        padding: 0 0.75rem;
        cursor: pointer;
        color: #9e9ea4;

        -webkit-transition: color 0.3s ease;
        -moz-transition: color 0.3s ease;
        -ms-transition: color 0.3s ease;
        -o-transition: color 0.3s ease;
        transition: color 0.3s ease;
    }
    .clear-btn-container:hover {
        color: #009688;
    }
    .clear-btn-container .inner {
        height: 100%;
    }
</style>
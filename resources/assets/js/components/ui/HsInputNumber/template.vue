<template>
    <div class="input-field" :id="computedId">
        <input type="number"
                ref="input"
                :value="value"
                :class="error === undefined ? '' : 'invalid'"
                v-on:input="updateValue($event.target.value)">
        <label>{{label}}</label>
    </div>
</template>

<script>
    import HsFormInput from "../../helpers/HsFormInputHelper/template.vue";

    export default {
        mixins: [
            HsFormInput
        ],
        props: {
            label: '',
            value: null,
            error: Array
        },
        methods: {

            // Set the value on the input element and emit it
            updateValue: function(value){
                this.$refs.input.value = parseFloat(value).toFixed(value.split('.')[1].length);
                this.$emit('input', parseFloat(value).toFixed(value.split('.')[1].length));
            }
        }
    }
</script>
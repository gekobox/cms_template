<template>
    <div class="switch" :id="computedId">
        <label>
            {{offLabel}}
            <input type="checkbox"
                    ref="input"
                    :checked="value"
                    :class="error === undefined ? '' : 'invalid'"
                    v-on:change="updateValue($event.target.checked)">
            <span class="lever"></span>
            {{onLabel}}
        </label>
    </div>
</template>

<script>
    import HsFormInput from "../../helpers/HsFormInputHelper/template.vue";

    export default {
        mixins: [
            HsFormInput
        ],
        props: {
            offLabel: String,
            onLabel: String,
            label: String,
            value: Boolean,
            url: String,
            redirectUrl: String,
            error: Array
        },
        methods: {

            // Set the value on the input element and emit it
            updateValue: function(value){
                var instance= this;
                this.$refs.input.checked = value;
                this.$emit('input', value);
                if(instance.url !== '' && instance.url !== undefined){
                    axios.get(instance.url)
                    .then(function(response){
                        if(response.status == 200){
                            instance.$router.push(instance.redirectUrl);
                        }
                    }).catch();
                }
            }
        }
    }
</script>

<style>
    .switch {
        margin-bottom: 15px;
    }
</style>
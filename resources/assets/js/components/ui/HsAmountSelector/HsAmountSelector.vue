<template>
    <div id="hs-amount-selector" class="modal">
        <div class="modal-header">
            <div class="row">
                <div class="col s6 left-align">
                    <div v-if="discountMode === true">
                        <a class="discount-type-btn btn-flat waves-effect waves-light white-text">%</a>
                        <a class="discount-type-btn active btn-flat waves-effect waves-light white-text">&euro;</a>
                    </div>
                </div>
                <div class="col s6">
                    <h4 class="right-align"><span>{{ value }}</span></h4>
                </div>
            </div>
        </div>
        <div class="modal-content white">
            <div class="row">
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('7')">7</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('8')">8</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('9')">9</a></div>
            </div>
            <div class="row">
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('4')">4</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('5')">5</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('6')">6</a></div>
            </div>
            <div class="row">
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('1')">1</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('2')">2</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('3')">3</a></div>
            </div>
            <div class="row">
                <div class="col s4"></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('0')">0</a></div>
                <div class="col s4"><a class="keypad-btn btn-flat btn-large white waves-effect" type="button" v-on:click="addVal('.')">.</a></div>
            </div>
        </div>
        <div class="modal-footer white">
            <button class="btn-flat white waves-effect teal-text" v-on:click="submit">OK</button>
            <button class="btn-flat white waves-effect teal-text modal-action modal-close" v-on:click="cancel">Cancel</button>
            <button class="btn-flat white waves-effect red-text left" v-on:click="clear">Clear</button>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from "../../elements/HsEventBus/template.vue";

    export default {
        data: function(){
            return {
                value: '',
                discountMode: false
            }
        },
        mounted: function(){
            var instance = this;

            HsEventBus.$on('openAmountSelector', function(value, discountMode){
console.log(discountMode);
                // Set default parameter values
                if(value === null || value === undefined){
                    instance.value = '';
                }
                else {
                    instance.value = value;
                }
                if(discountMode !== null && discountMode !== undefined){
                    instance.discountMode = discountMode;
                }

                // Open the modal
                $(document).ready(function(){
                    var modal = $('#hs-amount-selector');
                    modal.modal({
                        opacity: .32,
                        startingTop: '10%',
                        endingTop: '10%',
                        complete: function(){

                            // Emit cancel on closing the modal
                            HsEventBus.$emit('cancelAmountSelector');
                        }
                    });
                    modal.modal('open');
                });
            });
        },
        methods: {
            addVal: function(value){
                var instance = this;
                instance.value = instance.value + value;
            },
            submit: function(){
                var instance = this;
                HsEventBus.$emit('submitAmountSelector', instance.value);

                // Close the modal
                var modal = $('#hs-amount-selector');
                modal.modal('close');
            },
            cancel: function(){
                var instance = this;
                HsEventBus.$emit('cancelAmountSelector');

                // Close the modal
                var modal = $('#hs-amount-selector');
                modal.modal('close');
            },
            clear: function(){
                var instance = this;
                instance.value = '';
            }
        }
    }
</script>

<style>
    #hs-amount-selector {
        max-width: 350px;
        max-height: none !important;
    }
    #hs-amount-selector .modal-content {
        padding-bottom: 5px;
    }
    #hs-amount-selector .modal-content .row {
        margin-bottom: 10px;
    }
    #hs-amount-selector .modal-header {
        text-align: center;
        background-color: #26a69a;
        color: #fff;
        padding: 18px;
        font-weight: 300;
    }
    #hs-amount-selector .modal-header h4 {
        margin: 0;
    }
    #hs-amount-selector .modal-header .row {
        margin: 0;
    }
    #hs-amount-selector .modal-header .discount-type-btn {
        padding: 0 10px;
        background-color: #26a69a;
        font-weight: 300;
        font-size: 20px;
        opacity: 0.5;
    }
    #hs-amount-selector .modal-header .discount-type-btn.active {
        opacity: 1;
    }
    #hs-amount-selector .keypad-btn {
        display: block;
    }
    #hs-amount-selector .keypad-btn:focus,
    #hs-amount-selector .keypad-btn:hover {
        font-weight: bold;
        background-color: rgba(0,0,0,0.1) !important;
    }
</style>
<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m6 push-m3">
                <div class="svg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-263.5 236.5 26 26">
                        <g class="svg-success">
                            <circle cx="-250.5" cy="249.5" r="12"/>
                            <path d="M-256.46 249.65l3.9 3.74 8.02-7.8"/>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
        <div class="receipt">
            Receipt
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from "../../../elements/HsEventBus/template.vue";

    export default {
        created: function () {
            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);
            // Set the previous page
            HsEventBus.$emit('prevPage', '/pos/payment');

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', []);
        },
        mounted: function () {

            var instance = this;

            // Hide the loader
            window.loader.hide();

            // Print the receipt
            setTimeout(function(){
                instance.printReceipt();
            }, 1500);
        },
        methods: {
            printReceipt: function(){
                window.print();
            }
        }
    }
</script>

<style scoped>
    .svg {
        text-align: center;
        padding-top: 30px;
    }
    .svg svg {
        width: 150px;
        height: 150px;
    }

    @media screen and (max-width: 600px) {
        .svg svg {
            width: 100px;
            height: 100px;
        }
    }
    .svg-success {
        stroke-width: 1px;
        stroke: #26a69a;
        fill: none;
    }
    .svg-success path {
        stroke-dasharray: 17px, 17px;
        stroke-dashoffset: 0px;
        -webkit-animation: checkmark 0.25s ease-in-out 0.7s backwards;
        animation: checkmark 0.25s ease-in-out 0.7s backwards;
    }
    .svg-success circle {
        stroke-dasharray: 76px, 76px;
        stroke-dashoffset: 0px;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        -webkit-animation: checkmark-circle 0.6s ease-in-out forwards;
        animation: checkmark-circle 0.6s ease-in-out forwards;
    }

    @keyframes checkmark {
        0% {
            stroke-dashoffset: 17px;
        }
        100% {
            stroke-dashoffset: 0;
        }
    }
    @keyframes checkmark-circle {
        0% {
            stroke-dashoffset: 76px;
        }
        100% {
            stroke-dashoffset: 0px;
        }
    }

    /* Receipt */
    .receipt {
        display: none;
    }
    @media print {
        .receipt {
            display: block;
            background-color: white;
            height: 100%;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            margin: 0;
            padding: 15px;
            font-size: 14px;
            line-height: 18px;
            z-index: 9999;
        }
    }
</style>
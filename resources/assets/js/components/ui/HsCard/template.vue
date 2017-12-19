<template>
    <div class="hs-card card">
        <div v-if="image !== undefined" :class="imageClasses"><img :src="image"></div>
        <div class="card-content">
            <span class="card-title">{{title}}</span>
            <slot name="card-content"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,
            image: String,
            imageSize: String
        },
        computed: {
            imageClasses: function(){
                var classes = 'card-image valign-wrapper';
                if(this.imageSize === 'small'){
                    classes += ' small';
                }
                return classes;
            }
        }
    }
</script>

<style>
    .hs-card .card-image.small {
        height: 180px;
        overflow: hidden;
    }
</style>
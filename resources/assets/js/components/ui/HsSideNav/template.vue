<template>
    <div v-if="show" class="hs-side-nav side-nav" id="side-nav">
        <div class="logo-container">
            <a href="/"><img :src="logo" alt=""></a>
            <span class="dropdown-button" data-activates="languages-dropdown" data-beloworigin="true" data-constrainwidth="false">
                {{locale}}
                <i class="material-icons right">arrow_drop_down</i>
            </span>
        </div>
        <ul id='languages-dropdown' class='dropdown-content'>
            <li><a href="/change-language/nl">Nederlands</a></li>
            <li><a href="/change-language/en">English</a></li>
        </ul>
        <div class="divider"></div>
        <ul class="side-nav-links">
            <slot name="links"></slot>
        </ul>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';

    export default {
        mixins: [
            HsEventBus
        ],
        data: function(){
            return {
                logo: '/img/logo_vendata_blue_grey.svg',
                show: true,
                content: ''
            }
        },
        
        created: function(){
            HsEventBus.$emit('reloadNav', true);

            HsEventBus.$on('showNav', function(show){                
                var instance= this;
                instance.show= show;
                $(document).ready(function(){                
                    if(window.access_token !== undefined && window.access_token !== null){
                        $.ajax({
                            url: '/api/navigation-menu',
                            method: 'get',
                            headers:{
                                'Authorization': 'Bearer '+ window.access_token,
                                'Accept': 'application/json'
                            },
                            complete: function(data){
                                var NavComponent= Vue.extend({
                                    template: data.responseText
                                });
                                if($('#sideNavLinks').length > 0){
                                    new NavComponent().$mount('#sideNavLinks');
                                }
                                instance.show=true;
                                HsEventBus.$emit('reloadNav', true);
                            }
                        });
                    }
                });
            }.bind(this));
            
            
        },
        computed: {
            locale: function(){
                return document.getElementsByTagName('html')[0].getAttribute('lang').toUpperCase();
            }
        }
    }
</script>

<style>
    .hs-side-nav .logo-container {
        padding: 19px 32px 11px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .hs-side-nav .logo-container i.right {
        margin: 0;
    }
    .hs-side-nav .logo-container img {
        max-width: 130px;
        display: block;
    }
    .hs-side-nav .dropdown-button {
        cursor: pointer;
    }
    .hs-side-nav .dropdown-button .material-icons.right {
        margin-right: 0;
    }
</style>
<template>
    <div class="hs-header" v-if="show">
        <div :class="tabs.length > 0 ? 'navbar-fixed has-tabs' : 'navbar-fixed'">
            <div class="hs-header">
                <div class="navbar-fixed">
                    <nav class="nav-extended blue-grey darken-2">
                        <div class="nav-wrapper">
                            <a class="back-btn waves-effect waves-light" v-if="prevPage" v-on:click="toPrevPage">
                                <i class="material-icons">arrow_back</i>
                            </a>
                            <a href="#" class="brand-logo center"><img class="logo" src="/img/logo_vendata.svg" alt=""></a>
                            <a href="#" data-activates="side-nav" class="button-collapse right" ref="sideNavTrigger" >
                                <i class="material-icons">menu</i>
                            </a>
                        </div>
                        <div class="nav-content" v-if="tabs.length > 0">
                            <hs-tabs :tabs="tabs" :badge="badge" :current-tab='currentTab'></hs-tabs>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from "../../elements/HsEventBus/template.vue";
    import HsTabs from "../HsTabs/template.vue";

    export default {
        components: {
            HsTabs
        },
        data: function(){
            return {
                prevPage: false,
                tabs: [],
                badge:0,
                currentTab:0,
                show: true,
                showNav: true
            }
        },
        watch: {
            showNav: function(){
                var instance = this;
                instance.initSideNav();
            }
        },
        created: function(){
            var instance = this;

            HsEventBus.$on('showHeader', function(show){
                this.show= show
            }.bind(this));
            
            HsEventBus.$on('prevPage', function(value){
                instance.prevPage = value;
            });

            HsEventBus.$on('reloadNav', function(value){
                instance.showNav = !instance.showNav;
            }.bind(this));

            // Create a listener that updates the tabs
            HsEventBus.$on('updateHeaderTabs', function(tabs, badge, currTabIndex){
                // Only re-render the tabs when the current tabs are different

                if(JSON.stringify(tabs) != JSON.stringify(instance.tabs)){
                    instance.tabs = tabs;                    
                }
                //add the badge value
                
                if(badge !== undefined && badge !== null){
                    instance.badge=badge;
                }                

                //select the current tab
                if(currTabIndex !== undefined){
                    instance.currentTab= currTabIndex;
                }
            });
        },
        mounted: function(){
            var instance = this;
            instance.initSideNav();
        },
        methods: {

            // Go back one page
            toPrevPage: function(){
                var instance = this;
                instance.$router.push(instance.prevPage)
            },

            // Bind a click event on the hamburger to show the side nav
            initSideNav: function(){
                var instance = this;
                $(document).ready(function(){

                    // Init the side bar
                    $(instance.$refs.sideNavTrigger).sideNav({
                        edge: 'right',
                        draggable: true
                    });

                    // Init the dropdowns
                    $('.dropdown-button').dropdown();
                });
            }
        }
    }
</script>

<style>
    .hs-header .navbar-fixed.has-tabs {
        height: 112px;
    }
    .hs-header .back-btn {
        position: absolute;
        padding: 0px 18px;
    }
    .hs-header nav a.button-collapse {
        display: block;
        margin: 0;
        padding: 0 18px;
    }
    .hs-header .brand-logo {
        display: block;
        padding-top: 1px;
    }
    .hs-header .logo {
        width: 130px;
    }
</style>
<template>
    <ul class="tabs tabs-transparent">
        <li v-for="tab, key in tabs" class="tab">
            <a :href="'#tab_'+key" v-on:click="redirect(tab.url)" :class="tab.active === true ? 'active' : ''">{{tab.text | translate}}<span class="new badge" v-if="tab.showBadge && badge > 0">{{badge}}</span></a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            tabs: Array,
            badge:0,
            currentTab:0
        },
        methods: {
            redirect: function(url){
                this.$router.push(url);
            },
            updateTabs: function(){
                var instance = this;
                $(document).ready(function(){
                    $(instance.$el).tabs();                    
                    // On tabs change
                    // TODO: instead of always selecting the first tab, add the current tab index to the page component
                    $(instance.$el).tabs('select_tab', 'tab_' + instance.currentTab);
                });
            }
        },        
        mounted: function(){
            this.updateTabs();
        },
        updated: function(){
            this.updateTabs();
        }
    }
</script>

<style scoped>
    span.badge.new:after {
        content: '';
    }
</style>
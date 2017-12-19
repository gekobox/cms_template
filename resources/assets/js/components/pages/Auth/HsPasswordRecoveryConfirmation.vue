<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m6 l4 xl4 push-m3 push-l4 push-xl4">
                <hs-card :title="cardTitle">
                    <p slot="card-content">{{ 'Auth.password_recovery_confirmation_text' | translate }}</p>
                </hs-card>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsCard from '../../ui/HsCard/template.vue';

    export default {
        components: {
            HsCard
        },
        data: function(){
            return {
                cardTitle: this.$t('Auth.password_recovery_confirmation_card_title')
            }
        },
        created: function(){

            HsEventBus.$emit('showNav', false);
            HsEventBus.$emit('showHeader', true);

            // Set the previous page
            HsEventBus.$emit('prevPage', '/recover-password');

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', []);
            
            window.loader.hide();
        }
    }
</script>
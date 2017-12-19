<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m6 l4 xl4 push-m3 push-l4 push-xl4">
                <hs-card :title="cardTitle">
                    <hs-login-form slot="card-content"></hs-login-form>
                </hs-card>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsCard from '../../ui/HsCard/template.vue';
    import HsLoginForm from '../../elements/Auth/HsLoginForm.vue';

    export default {
        components: {
            HsCard,
            HsLoginForm
        },
        created: function(){

            HsEventBus.$emit('showNav', false);
            HsEventBus.$emit('showHeader', true);

            // Set the previous page
            HsEventBus.$emit('prevPage', null);

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', []);
        },
        data: function(){
            return {
                cardTitle: this.$t('Auth.login_card_title')
            };
        }
    }
</script>
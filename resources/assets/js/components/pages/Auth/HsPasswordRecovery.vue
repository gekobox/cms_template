<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12 m6 l4 xl4 push-m3 push-l4 push-xl4">
                <hs-card :title="cardTitle">
                    <hs-password-recovery-form slot="card-content"></hs-password-recovery-form>
                </hs-card>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsCard from '../../ui/HsCard/template.vue';
    import HsPasswordRecoveryForm from '../../elements/Auth/HsPasswordRecoveryForm.vue';

    export default {
        components: {
            HsCard,
            HsPasswordRecoveryForm
        },
        data: function(){
            return {
                cardTitle: this.$t('Auth.forgot_password')
            }
        },
        created: function(){

            HsEventBus.$emit('showNav', false);
            HsEventBus.$emit('showHeader', true);

            // Set the previous page
            HsEventBus.$emit('prevPage', '/login');

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', []);
        }
    }
</script>
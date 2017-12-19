<template>
    <div class="page-content">
        <div class="row">
            <div class="col s12">
                <div class="fixed-action-btn">
                    <router-link to="/products/add" class="btn-floating btn-large teal waves-effect waves-light">
                        <i class="material-icons">add</i>
                    </router-link>
                </div>
                <hs-card :title="mainTitle">
                    <hs-table slot="card-content"
                            :columns="table.columns"
                            :data-url="table.dataUrl"
                            :delete-url="table.deleteUrl"
                            :can-search="table.canSearch"
                            :can-delete="table.canDelete"
                            :search-label= "searchLabel"
                    ></hs-table>
                </hs-card>
            </div>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from "../../../elements/HsEventBus/template.vue";
    import HsTable from "../../../ui/HsTable/template.vue";
    import HsCard from "../../../ui/HsCard/template.vue";
    import HsPermissionHelper from "../../../helpers/HsPermissionHelper/template.vue";
    import HsPosTabs from "../../../elements/Order/HsPosTabs/HsPosTabs.vue";

    export default {
        components: {
            HsTable,
            HsCard
        },
        mixins: [
            HsPermissionHelper,
            HsPosTabs
        ],
        created: function(){
            this.checkPermission('order');

            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);
            // Set the previous page
            HsEventBus.$emit('prevPage', false);

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', this.tabs, null, 1);
        },
        mounted: function(){

            // Hide the loader
            window.loader.hide();
        },
        data: function(){
            return {
                table: {
                    columns: [
                        {                            
                            code: 'date'                            
                        },
                        {
                            code: 'name'                            
                        },
                        {
                            code: 'total price' 
                        },
                        {
                            code: 'open',
                            type: 'inputSwitch',
                            title: 'Open sale',
                            url: '/api/reopen-order'
                        }
                    ],
                    dataUrl: '/api/get-resource-list/order?order_status=closed',
                    deleteUrl: '',
                    canSearch: true,
                    canDelete: false
                },
                mainTitle: this.$t('Order.sales_history_title'),
                searchLabel: this.$t('Product.search')
            }
        }
    }
</script>
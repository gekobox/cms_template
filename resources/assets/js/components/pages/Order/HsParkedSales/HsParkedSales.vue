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
                            :event-to-trigger="'park-order'"
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
            var instance= this;
            HsEventBus.$on('park-order', function(column, entry){
                instance.parkOrder(column.url + '/' + entry['id']);
            });
            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);
            // Set the previous page
            HsEventBus.$emit('prevPage', false);

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', this.tabs, null, 2);
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
                            code: 'open',
                            type: 'inputButton',
                            buttonText: this.$t('Order.btn_open'),
                            title: 'Open sale',
                            url: '/api/reopen-order'
                        }
                    ],
                    dataUrl: '/api/get-resource-list/order?order_status=parked',
                    deleteUrl: '',
                    canSearch: true,
                    canDelete: false
                },
                mainTitle: this.$t('Order.parked_orders_title'),
                searchLabel: this.$t('Product.search')
            }
        },
        methods: {
            /* Park an order */
            parkOrder: function(url){  
                var instance= this;
                if(url !== '' && url !== undefined){
                    axios.get(url)
                    .then(function(response){
                        if(response.status == 200){
                            HsEventBus.$emit('update-parked-badge');
                            instance.$router.push('/');
                        }
                    }).catch();
                }
            }
        }
    }
</script>
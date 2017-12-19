<script>
    import {HsEventBus} from '../../HsEventBus/template.vue';
    export default {
        data: function(){
            var instance=this;
            return {
                tabs: [
                    {
                        text: this.$t('Order.current_sale_tab'),
                        url: '/pos'
                    },
                    {
                        text: this.$t('Order.sales_history_tab'),
                        url: '/pos/sales-history'
                    },
                    {
                        text: this.$t('Order.parked_sales_tab'),
                        url: '/pos/parked-sales',
                        showBadge: true
                    }
                ],
                badge:0
            };
        },
        created: function(){
            var instance= this;
            instance.countParkedSales();
            
            HsEventBus.$on('count_parked_sales', function(){
                instance.countParkedSales();
            });

            HsEventBus.$on('update-parked-badge', function(show){
                instance.countParkedSales();
            });
            
        },
        methods:{
            countParkedSales: function(){
                var instance= this;
                axios.get('/api/get-resource-list/order?order_status=parked')
                .then(function(response){
                    if(response.status == 200){                        
                        var parkedOrders=0;
                        if(response.data.length > 0){
                            parkedOrders= response.data.length;
                        }
                        instance.badge= parkedOrders;                                                         
                        HsEventBus.$emit('updateHeaderTabs', instance.tabs, parkedOrders);
                    }
                });
            }
        }
    }
</script>
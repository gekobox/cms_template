import {HsEventBus} from "../../../elements/HsEventBus/template.vue";
import HsFormHelper from '../../../helpers/HsFormHelper/template.vue';
import HsModalHelper from '../../../helpers/HsModalHelper/template.vue';
import HsInputText from "../../../ui/HsInputText/template.vue";
import HsModal from "../../../ui/HsModal/template.vue";
import HsInputAutocomplete from "../../../ui/HsInputAutocomplete/template.vue";
import HsTable from "../../../ui/HsTable/template.vue";
import HsAmountSelector from "../../../ui/HsAmountSelector/HsAmountSelector.vue";
import HsPosTabs from "../../../elements/Order/HsPosTabs/HsPosTabs.vue";
import HsEcommerceRelationForm from '../../../elements/Relation/HsEcommerceRelationForm.vue';

export default {
        components: {
            HsInputText,
            HsInputAutocomplete,
            HsTable,
            HsAmountSelector,
            HsModal,
            HsEcommerceRelationForm
        },
        mixins: [
            HsPosTabs,
            HsFormHelper,
            HsModalHelper
        ],
        created: function(){
            var instance = this;

            HsEventBus.$emit('showNav', true);
            HsEventBus.$emit('showHeader', true);

            // Set the previous page
            HsEventBus.$emit('prevPage', false);

            // Set the tabs
            HsEventBus.$emit('updateHeaderTabs', this.tabs, null, 0);
            
            instance.lastSearchInputTime = new Date().getTime();
                
            //load open order
            instance.loadOrder();
        },
        mounted: function(){
            var instance = this;
            instance.searchFocus();
            $(document).ready(function(){

                // Re-initialize elements after mounting
                instance.initCollapsible();
                
                Materialize.updateTextFields();
                // Hide the loader
                window.loader.hide();
            });
        },
        data: function(){
            return {
                createRelation: true,
                scanSpeed: 150, // Some people can type this fast, but it's probably scanned
                lastSearchInputTime: undefined,
                thisSearchInputTime: undefined,
                addScannedProductTimeout: undefined,
                productSearchTerm: '',
                relationSearchTerm: '',
                relation: {
                    id: null,
                    name: null
                },
                relations:[],
                orderId: null,
                orderLines: [],
                searchPlaceholder: this.$t('ui.HsTable.search_label'),
                notePlaceholder: this.$t('Order.checkout_collapsible_note'),
                searchRelationPlaceholder: this.$t('Order.search_relation_placeholder'),
                selectedProduct: null,
                products: [],
                cancelPreviousRequest: null,
            }
        },
        computed: {
            subTotal: function () {
                var instance = this;
                var subTotal = 0.00;
                for(var i=0; i < instance.orderLines.length; i++){
                    var linePrice = instance.orderLines[i].price * instance.orderLines[i].amount;
                    linePrice = parseFloat(linePrice.toFixed(2));
                    subTotal = subTotal + linePrice;
                }
                return subTotal;
            },
            discount: function(){
                var instance = this;
                return parseFloat(0.00).toFixed(2);
            },
            total: function(){
                var instance = this;
                var total = instance.subTotal-instance.discount;
                return parseFloat(total.toFixed(2));
            }
        },
        watch: {

            /* Check if the product search term is entered by a scanner or keyboard */
            productSearchTerm: function(){
                var instance = this;
                instance.thisSearchInputTime = new Date().getTime();
                if(instance.lastSearchInputTime !== undefined){
                    if((instance.thisSearchInputTime - instance.lastSearchInputTime) < instance.scanSpeed){

                        // This character is entered by a scanner
                        instance.lastSearchInputTime = instance.thisSearchInputTime;

                        // Set a timeout to add the product to the list
                        if(instance.addScannedProductTimeout !== undefined){
                            clearTimeout(instance.addScannedProductTimeout);
                        }
                        instance.addScannedProductTimeout = setTimeout(function(){
                            instance.searchProduct(true);
                        }, 500);
                    }
                    else {

                        // This character is manually entered
                        instance.lastSearchInputTime = instance.thisSearchInputTime;
                        instance.searchProduct();
                    }
                }
                else {

                    // This is the first entered character
                    instance.lastSearchInputTime = instance.thisSearchInputTime;
                }
            },

            // Search relations
            relationSearchTerm: function(val){
                var instance = this;
                instance.searchRelation();
            }
        },
        methods: {
            initCollapsible: function(){
                var instance = this;
                $(document).ready(function(){
                    $(instance.$refs.collapsible).collapsible();
                    Materialize.updateTextFields();
                });
            },
            searchFocus: function(){
                var instance = this;
                $(document).ready(function(){
                    $(instance.$refs.search).focus();
                });
            },
            removeOrderLine: function(index){

                // Close the collapsible
                var instance = this;
                $(instance.$refs.collapsible).collapsible('close', 0);
                axios.get('/api/remove-order-line/' + instance.orderLines[index].id)
                    .then(function(response){
                        if(response.status == 200){
                            instance.orderLines.splice(index, 1);
                        }
                    })
                    .catch();
                
            },
            toPayment: function(){
                var instance = this;
                instance.$router.push('/pos/payment/'+ instance.orderId);
            },

            searchRelation: function(){
                var instance= this;
                var CancelToken = axios.CancelToken;
                //cancel previous request
                if(instance.cancelPreviousRequest != null){
                    instance.cancelPreviousRequest();
                }
                
                axios.get('/api/get-relations', {
                    params: {searchTerm : instance.relationSearchTerm},
                    cancelToken: new CancelToken(function executor(c) {  
                        instance.cancelPreviousRequest = c;
                    })
                })
                .then(function(response){
                    if(response.status == 200){
                        instance.relations= response.data;
                    }
                })
                .catch();
            },           
            
            searchProduct: function(isScanned){
                
                var instance= this;
                var CancelToken = axios.CancelToken;
                //cancel previous request
                if(instance.cancelPreviousRequest != null){
                    instance.cancelPreviousRequest();
                }
                axios.get('/api/get-product-list',{
                    params: {
                        searchTerm: instance.productSearchTerm
                    },
                    cancelToken: new CancelToken(function executor(c) {  
                        instance.cancelPreviousRequest = c;
                    })
                })
                .then(function(response){
                    if(response.status == 200){
                        instance.products= response.data;
                        // If there's only one result left, and the product was scanned with the barcode reader
                        // , add it to the order lines
                        if(response.data.length == 1 && isScanned !== undefined && isScanned == true){
                            instance.addProduct(0);
                        }
                    }
                })
                .catch(function(error){                    
                    instance.products=[];
                });
            },
            addProduct: function(index){
                var instance = this;
                var product= instance.products[index];
                 
                // Add the product to the order lines
                window.loader.show();
                axios.post('/api/add-order-line',{
                    orderId: instance.orderId,
                    product_id: product.id,
                    product_code: product.sku,
                    amount: 1,
                    price: product.price                    
                })
                .then(function(response){
                    if(response.status == 200){
                        instance.orderId= response.data.order
                        window.loader.hide();
                        instance.loadOrder();                        
                        
                    }
                })
                .catch(function(response){
                    instance.loadOrder();
                    window.loader.hide();
                });                

                // Empty the search box
                instance.productSearchTerm = '';

                
            },
            updateOrderline: function(event, index, field, value){
                //if(event.key != 'Backspace' && event.key != 'Delete'){
                if(event.key == 'Enter'){
                    var instance = this;
                    var orderLine= instance.orderLines[index];

                    if(field === undefined) field='';
                    if(value === undefined) value='';
                    // Check if there's only one result left

                    // Add the product to the order lines
                    axios.post('/api/update-order-line',{
                        orderline: orderLine.id,
                        field: field,
                        value: value
                    })
                    .then(function(response){
                        if(response.status == 200){                        
                            instance.loadOrder();                        
                        }
                    })
                    .catch();                
                }
            },

            openAmountSelector: function(event, index, field, value, discountMode){

                // Prevent focussing the input
                event.preventDefault();

                var instance = this;
                if(value === undefined){ value = ''; }

                // Listen to changes from the amount selector
                HsEventBus.$on('submitAmountSelector', function(value){
                    instance.orderLines[index][field] = value;

                    // Update Materialize text fields
                    instance.updateMaterializeTextFields();

                    // Remove listener
                    HsEventBus.$off('submitAmountSelector');
                });
                HsEventBus.$on('cancelAmountSelector', function(){

                    // Remove listener
                    HsEventBus.$off('submitAmountSelector');
                });

                // Open the amount selector
                HsEventBus.$emit('openAmountSelector', value, discountMode);
            },
            
            loadOrder: function(){
                var instance= this;
                window.loader.show();
                axios.get('/api/load-order',{
                    params:{
                        order: instance.orderId
                    }                    
                })
                .then(function(response){
                    if(response.status == 200){
                        instance.orderId= response.data.id;                        
                        instance.orderLines= response.data.order_lines;
                        if(response.data.ecommerce_relation_code != null){
                            instance.relation.code= response.data.ecommerce_relation_code;
                            window.loader.hide();
                            instance.loadRelation();
                            
                        }
                        // Initialize the collapsible
                        instance.initCollapsible();
                    }
                })
                .catch(function(response){
                    window.loader.hide();
                });
            },
            
            loadRelation: function(){
                var instance= this;
                window.loader.show()
                axios.get('/api/load-relation',{
                    params:{
                        relation: instance.relation.id,
                        relation_code: instance.relation.code
                    }                    
                })
                .then(function(response){
                    if(response.status == 200){                        
                        instance.relation.name= response.data.firstname + " " + response.data.lastname;
                        instance.relation.id= response.data.id; 
                        window.loader.hide();
                    }
                })
                .catch(function(response){
                    window.loader.hide();
                });
            },
            // Select the relation
            selectRelation: function(index){
                var instance = this;
                instance.relation.id = instance.relations[index].id;
                instance.relation.name= instance.relations[index].name;
                instance.relation.code= instance.relations[index].code;
                instance.relationSearchTerm = '';
                axios.post('/api/add-relation-to-order',{
                    'order': instance.orderId,                    
                    'relation_code': instance.relation.code
                    
                }).then(function(response){
                    if(response.status == 200){
                        instance.orderId= response.data.order;
                    }
                }).catch();
            },

            // Deselect the relation
            deselectRelation: function(){
                var instance = this;
                instance.relation.id = null;
                instance.relation.name= null;
                axios.post('/api/remove-relation-from-order', {
                    order: instance.orderId
                }).then(function(response){
                    if(response.status == 200){
                        
                    }
                });
            },

            // Open the Edit Relation modal
            openEditRelationModal: function(){
                var instance = this;
                instance.createRelation=false;                
                instance.openModal('relation_modal');
                HsEventBus.$emit('get_quick_relation_form', []);
            },
            
            discardOrder: function(){
                var instance= this;
                axios.post('/api/discard-order',{
                    order: instance.orderId
                }).then(function(response){
                    if(response.status == 200){
                        instance.orderId=null;
                        instance.relation.id=null;
                        instance.$router.push('/');
                    }
                    instance.closeModal('discard_modal');
                }).catch();
                
            },
            
            parkOrder: function(){
                var instance= this;
                axios.post('/api/park-order',{
                    order: instance.orderId
                }).then(function(response){
                    if(response.status == 200){
                        instance.orderId=null;
                        instance.relation.id=null;
                        instance.$router.push('/');
                    }
                }).catch();
                
            },

            openCashDrawer: function(e){
                e.preventDefault();
                window.print();
            }
        }
    }

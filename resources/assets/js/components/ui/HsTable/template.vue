<template>
    <div class="hs-table" :id="id">
        <div class="no-results" v-if="data.length == 0">
            <div>
                <i class="material-icons">{{noResultsIcon}}</i>
                <div>{{'ui.HsTable.no_results' | translate}}</div>
            </div>
        </div>
        <div v-if="data.length > 0">
            <form id="search" v-if="canSearch === true">
                {{ searchLabel }} <input name="query" :value="searchValue" v-model="filterKey" :placeholder="searchPlaceholder" v-on:keyup="emitKeyUp">
            </form>
            <table class="highlight loading">
                <thead>
                <tr>
                    <th class="checkbox-column" v-if="canDelete === true"></th>
                    <th v-for="column in columns"
                        @click="sortBy(column.code)"
                        :class="{ active: sortKey == column.code }">
                        {{ column.code | capitalize }}
                        <span class="arrow" :class="sortOrders[column.code] > 0 ? 'asc' : 'dsc'"></span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="entry in filteredData" :id="entry['id']">
                    <td class="valign-wrapper" v-if="canDelete === true">
                        <input type="checkbox" class="table-selector" :data-id="entry['id']" :id="'table-selector-'+entry['id']" v-on:click="showSelectionToast" />
                        <label :for="'table-selector-'+entry['id']"></label>
                    </td>
                    <td v-for="column in columns">                                               
                        <router-link v-if="column.link !== undefined" :to="column.link+'/'+entry['id']">
                            <img v-if="column.type !== undefined && column.type === 'image'" :src="entry['image']">
                            <span>{{entry[column.code]}}</span>
                        </router-link>
                        <span v-else-if= "column.type !== undefined && column.type === 'inputButton'">
                            <a class="waves-effect waves-light btn-flat teal white-text" v-on:click="triggerEvent(column, entry)">{{column.buttonText}}</a>
                        </span>
                        <span v-else-if= "column.type !== undefined && column.type === 'inputSwitch'">
                            <hs-input-switch :redirect-url="'/'" :url="column.url + '/' + entry['id']" 
                                :label="'.'" :on-label="''" :off-label="''"></hs-input-switch>
                        </span>
                        <span v-else>
                            <img v-if="column.type !== undefined && column.type === 'image'" :src="column.imagePath+'/'+entry[column.code]">
                            <span>{{entry[column.code]}}</span>
                        </span>
                    </td>
                </tr>
                <tr v-if="filteredData.length == 0">
                    <td v-if="canDelete === true" :colspan="columns.length+1" class="center">
                        Geen resultaten
                    </td>
                    <td v-else :colspan="columns.length" class="center">
                        Geen resultaten
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import {HsEventBus} from '../../elements/HsEventBus/template.vue';
    import HsFormHelper from '../../helpers/HsFormHelper/template.vue';
    import HsInputSwitch from '../../ui/HsInputSwitch/template.vue';  

    export default {
        components: {
            HsInputSwitch
        },
        props: {
            id: {
                type: String,
                default () {
                    return ''
                }
            },
            columns: Array,
            eventToTrigger: String,
            dataUrl: String,
            deleteUrl: String,
            canSearch: Boolean,
            canDelete: Boolean,
            noResultsIcon: {
                type: String,
                default () {
                    return 'notifications_none'
                }
            },
            noResultsText: {
                type: String,
                default () {
                    return this.$t('ui.HsTable.no_results');
                }
            },
            searchLabel: {
                type: String,
                default () {
                    return 'Search'
                }
            },
            searchPlaceholder: {
                type: String
            },
            searchValue: {
                type: String
            }
        },
        mixins:[
            HsFormHelper
        ],
        watch: {
            searchValue: function(value){
                this.filterKey = value;
            }
        },
        data: function () {
            var sortOrders = {};
            this.columns.forEach(function (key) {
                sortOrders[key] = 1
            });

            return {
                filterKey: '',
                sortKey: '',
                sortOrders: sortOrders,
                data: [],
                errors: {}
            }
        },
        computed: {
            filteredData: function () {
                var sortKey = this.sortKey
                var filterKey = this.filterKey && this.filterKey.toLowerCase()
                var order = this.sortOrders[sortKey] || 1
                var data = this.data
                if (filterKey) {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        })
                    })
                }
                if (sortKey) {
                    data = data.slice().sort(function (a, b) {
                        a = a[sortKey]
                        b = b[sortKey]
                        return (a === b ? 0 : a > b ? 1 : -1) * order
                    })
                }
                return data
            }
        },
        mounted: function(){
            this.getData();
        },
        filters: {
            capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1)
            }
        },
        methods: {
            sortBy: function (key) {
                this.sortKey = key
                this.sortOrders[key] = this.sortOrders[key] * -1
            },

            /**
             * Get the data
             */
            getData: function(){
                var instance = this;

                // Get the data
                window.loader.show();
                axios.get(instance.dataUrl)
                    .then(function (response) {
                        window.loader.hide();
                        // Update the dataset
                        if(response.status === 200){
                            instance.data = response.data;
                        }
                        else if(response.status !== 500){
                            instance.errors = response.response.data;                            
                        }                        
                    })
                    .catch(function (error) {
                        console.log(error);
                        window.loader.hide();
                    });
            },

            /**
             * Show the selection toast
             */
            showSelectionToast: function(){

                // Get the current component instance
                var instance = this;

                // Get the number of selected items
                var selectedItems = $(this.$el).find('.table-selector:checked');

                // Get an existing toast
                var toast = $('.selectionToast');
                var amountWord = 'item';
                if(toast.length > 0){

                    // Hide the toast
                    if(selectedItems.length == 0){
                        this.hideSelectionToast();
                    }

                    // Update the existing toast
                    else {
                        if(selectedItems.length > 1){
                            amountWord = 'items';
                        }
                        toast.find('.amount').html(selectedItems.length);
                        toast.find('.amount-word').html(amountWord);
                    }
                }
                else {

                    // Show a new toast
                    if(selectedItems.length > 0){
                        if(selectedItems.length > 1){
                            amountWord = 'items';
                        }
                        Materialize.toast('<span class="amount">'+selectedItems.length+'</span>&#160;<span class="amount-word">'+amountWord+'</span><span><a class="waves-effect waves-light btn"><i class="material-icons left">delete</i>verwijderen</a></span>', undefined, 'selectionToast')
                    }

                    // Attach an on click event on the delete button
                    var toast = $('.selectionToast');
                    toast.off('click.deleteSelection').on('click.deleteSelection', function(){
                        instance.deleteSelection();
                    });
                }
            },

            /**
             * hide the selection toast
             */
            hideSelectionToast: function(){
                $('.selectionToast').remove();
            },

            /**
             * Clear the selection
             */
            clearSelection: function(){
                $(this.$el).find('.table-selector:checked').prop('checked', false);
            },

            /**
             * Delete the selection
             */
            deleteSelection: function(){

                // Get the current component instance
                var instance = this;

                // Hide the toast
                this.hideSelectionToast();

                // Get the selected items
                var selectedItems = $(this.$el).find('.table-selector:checked');

                // Gather the id's
                var ids = [];
                selectedItems.each(function() {
                    ids.push(parseInt($(this).attr('data-id')));
                });

                // Send the delete request
                axios.post(instance.deleteUrl,
                    {
                        ids: ids
                    })
                    .then(function (response) {

                        // Uncheck all checkboxes
                        instance.clearSelection();

                        // Update the data
                        instance.getData();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            /* Hooks */
            emitKeyUp: function(){
                HsEventBus.$emit('key-up', this);
            },
            
            /* emit the event defined in eventToTrigger*/
            triggerEvent: function(column, entry){
                if(this.eventToTrigger !== null){
                    HsEventBus.$emit(this.eventToTrigger, column, entry);
                }
            }
        }
    }
</script>

<style>
    .hs-table [type="checkbox"]+label {
        padding-left: 19px;
    }
    .hs-table .checkbox-column {
        width: 58px;
    }
    .hs-table tbody img {
        max-width: 50px;
        max-height: 50px;
        display: inline-block;
        vertical-align: middle;
    }
    .hs-table .no-results {
        display: flex;
        justify-content: center;
        padding-bottom: 30px;
    }
    .hs-table .no-results i.material-icons {
        font-size: 50px;
        display: block;
        text-align: center;
    }
    .hs-table .no-results div {
        padding-top: 10px;
    }
</style>
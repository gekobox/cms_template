<template>
    <div class="current-sale-page page-content">
        <div class="row">
            <div class="col s12 m8 search-col">
                <hs-input-text :clear-button="true" v-model="productSearchTerm" prefix-icon="search" label="" id="search" :placeholder="searchPlaceholder" class="z-depth-1"></hs-input-text>
                <div class="results" v-if="productSearchTerm.length > 0">
                    <div class="row">
                        <!-- Start results -->
                        <div class="col s6 m3" v-for="(product, index) in products">
                            <div class="card hoverable product-block" v-on:click="addProduct(index)">
                                <div class="card-image">
                                    <div class="inner" :style="'background-image: url('+product.image+');'"></div>
                                </div>
                                <div class="card-content text-align-center">
                                    <p>
                                        {{product.name}}<br>
                                        <span class="grey-text">{{product.sku}}</span>
                                    </p>
                                </div>
                                <div class="card-action">
                                    <div class="price">&euro; {{product.price}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    NO PRODUCTS FOUND
                    <div class="row">
                        <div class="col s12">
                            <div class="no-results">
                                <div>
                                    <i class="material-icons">notifications_none</i>
                                    <div>No products found</div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="col s12 m4">
                <div class="card find-relation" v-if="relation.id === null">
                    <hs-input-text :clear-button="true" v-model="relationSearchTerm" prefix-icon="person" label="" id="relation-search" :placeholder="searchRelationPlaceholder"></hs-input-text>
                    <div class="relation-create-btn-container valign-wrapper">
                        <a class="waves-effect waves-light btn-flat grey white-text" v-on:click="openEditRelationModal">{{'Order.btn_create' |translate}}</a>
                    </div>
                    <div class="results" v-if="relationSearchTerm.length > 0">
                        <div class="inner z-depth-2">
                            <ul>
                                <li v-for="(relation, index) in relations" class="hoverable" v-on:click="selectRelation(index)">
                                    <div class="bold">{{relation.name}}</div>
                                    <div class="grey-text">{{relation.phone}}</div>
                                    <div class="grey-text">{{relation.email}}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card selected-relation" v-if="relation.id !== null">
                    <div class="wrapper valign-wrapper">
                        <div class="prefix-icon valign-wrapper">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="name">{{relation.name}} </div>
                        <div class="edit-btn-container">
                            <div class="inner valign-wrapper" v-on:click="openEditRelationModal">
                                <i class="material-icons">mode_edit</i>
                            </div>
                        </div>
                        <div class="clear-btn-container">
                            <div class="inner valign-wrapper" v-on:click="deselectRelation">
                                <i class="material-icons">cancel</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card checkout">
                    <div class="order-lines">
                        <ul v-if="orderLines.length > 0" class="collapsible" data-collapsible="accordion" ref="collapsible">
                            <li v-for="(orderLine, index) in orderLines">
                                <div class="collapsible-header">
                                    <div class="amount">{{orderLine.amount}}</div>
                                    <div class="product">
                                        <div>{{orderLine.product.name}}</div>
                                        <div class="grey-text">{{orderLine.product.sku}}</div>
                                    </div>
                                    <div class="price">&euro;{{(orderLine.price * orderLine.amount).toLocaleString('nl-NL', {minimumFractionDigits: 2})}}</div>
                                    <div class="collapse"><i class="material-icons">keyboard_arrow_down</i></div>
                                </div>
                                <div class="collapsible-body">
                                    <div class="row">
                                        <div class="col m2">
                                            <div class="input-field" v-on:mousedown="openAmountSelector($event, index,'amount',orderLine.amount, false)" v-on:touchstart="openAmountSelector($event, index, 'amount', orderLine.amount, false)">
                                                <input id="quantity" type="text" v-model="orderLine.amount" tabindex="-1">
                                                <label for="quantity">{{'Order.checkout_collapsible_quantity' | translate}}</label>
                                            </div>
                                        </div>
                                        <div class="col m5">
                                            <div class="input-field" v-on:mousedown="openAmountSelector($event, index,'price',orderLine.price, false)" v-on:touchstart="openAmountSelector($event, index, 'price', orderLine.price, false)">
                                                <input id="price" type="text" v-model="orderLine.price" tabindex="-1">
                                                <label for="price">{{'Order.checkout_collapsible_price' | translate}}</label>
                                            </div>
                                        </div>
                                        <div class="col m5">
                                            <div class="input-field" v-on:mousedown="openAmountSelector($event, index,'discount',orderLine.discount, true)" v-on:touchstart="openAmountSelector($event, index, 'discount', orderLine.discount, true)">
                                                <input id="discount" type="text" v-model="orderLine.discount" tabindex="-1">
                                                <label for="discount">{{'Order.checkout_collapsible_discount' | translate}}</label>
                                            </div>
                                        </div>
                                        <div class="col m12">
                                            <div class="input-field">
                                                <input id="note" type="text" :placeholder="notePlaceholder" v-model="orderLine.note" v-on:keyup="updateOrderline($event, index,'note',orderLine.note)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-details-actions">
                                        <a v-on:click="removeOrderLine(index)" class="btn-flat red white-text waves-effect waves-light">{{'Order.checkout_collapsible_remove' | translate}}</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div v-if="orderLines.length == 0" class="no-results">
                            <div>
                                <i class="material-icons">receipt</i>
                                <div>{{'Order.no_items_added'|translate}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="card z-depth-2">
                <div class="row">
                    <div class="col s12 m4">
                        <div class="left-actions">
                            <a class="waves-effect btn-flat btn-large red-text" v-on:click="openModal('discard_modal')">{{'Order.btn_discard' |translate}}</a>
                            <a class="waves-effect btn-flat btn-large blue-grey-text text-darken-2" v-on:click="parkOrder">{{'Order.btn_park' |translate}}</a>
                        </div>
                    </div>
                    <div class="col s12 m4">
                        <div class="totalContainer">
                            <div class="total">&euro;{{total.toLocaleString('nl-NL', {minimumFractionDigits: 2})}}</div>
                        </div>
                    </div>
                    <div class="col s12 m3">
                        <a class="waves-effect waves-light btn-flat btn-large teal white-text" style="width: 100%;" v-on:click="toPayment">{{'Order.checkout_pay' | translate}}</a>
                    </div>
                    <div class="col s12 m1 more-actions-col">
                        <a id="more-actions-btn" class="waves-effect btn-flat btn-large dropdown-button blue-grey-text text-darken-2" data-activates="more-actions-dropdown" data-alignment="right" data-constrainwidth="false" data-beloworigin="false" style="width: 100%;"><i class="material-icons">keyboard_arrow_down</i></a>
                        <ul id="more-actions-dropdown" class="dropdown-content">
                            <li><a href="#" v-on:click="openCashDrawer">Open cash drawer</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hs-modal id="discard_modal">
            <div slot="modal-content">
                <h5>{{'Order.discard_confirmation_title' |translate}}</h5>
                <p>{{'Order.discard_confirmation_text' |translate}}</p>
                <div>
                    <a class="waves-effect waves-light red btn-flat btn-large white-text" v-on:click="discardOrder">{{'Order.btn_yes' |translate}}</a>
                    <a class="waves-effect waves-light grey btn-flat btn-large white-text" v-on:click="closeModal('discard_modal')">{{'Order.btn_no' | translate}}</a>
                </div>
            </div>
        </hs-modal>
        <hs-modal id="relation_modal">
            <div slot="modal-content">
                <div>
                    <span class="card-title" v-if="createRelation">{{'Order.create_relation_title' |translate}}</span>
                    <span class="card-title" v-else>{{'Order.edit_relation_title' |translate}}</span>
                </div>
                <div class="row">
                    <div class="col s6">
                        <hs-ecommerce-relation-form :relation-id="relation.id" :relation-code="relation.code" slot="card-content"></hs-ecommerce-relation-form>
                    </div>
                </div>
            </div>
        </hs-modal>
        <hs-amount-selector></hs-amount-selector>
    </div>
</template>

<script src="./script.js"></script>
<style src="./style.css"></style>
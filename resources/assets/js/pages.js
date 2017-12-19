module.exports = function(){
    var Pages = {
        HsRoot: require('./components/pages/HsRoot/template.vue'),
        HsCurrentSale: require('./components/pages/Order/HsCurrentSale/template.vue'),
        HsParkedSales: require('./components/pages/Order/HsParkedSales/HsParkedSales.vue'),
        HsSalesHistory: require('./components/pages/Order/HsSalesHistory/HsSalesHistory.vue'),
        HsPayment: require('./components/pages/Order/HsPayment/template.vue'),
        HsPaymentComplete: require('./components/pages/Order/HsPaymentComplete/template.vue'),
        
        /* Login */
        HsLogin: require('./components/pages/Auth/HsLogin.vue'),
        HsPasswordRecovery: require('./components/pages/Auth/HsPasswordRecovery.vue'),
        HsPasswordRecoveryConfirmation: require('./components/pages/Auth/HsPasswordRecoveryConfirmation.vue'),
        HsPasswordReset: require('./components/pages/Auth/HsPasswordReset.vue'),

        /* Account */
        HsMyAccountGeneral: require('./components/pages/Account/HsMyAccountGeneral/HsMyAccountGeneral.vue'),
        HsMyAccountPaymentDetails: require('./components/pages/Account/HsMyAccountPaymentDetails/HsMyAccountPaymentDetails.vue')
    };
    return Pages;
};

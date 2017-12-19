module.exports = function(Pages){
    return [
        { path: '/', component: Pages.HsRoot},
        
        /* Login */
        { path: '/login', component: Pages.HsLogin},
        { path: '/recover-password', component: Pages.HsPasswordRecovery},
        { path: '/recover-password-confirmation', component: Pages.HsPasswordRecoveryConfirmation},
        { path: '/reset-password/:key', component: Pages.HsPasswordReset},

        { path: '/pos', component: Pages.HsCurrentSale},
        { path: '/pos/sales-history', component: Pages.HsSalesHistory },
        { path: '/pos/parked-sales', component: Pages.HsParkedSales },
        { path: '/pos/payment/:orderId', component: Pages.HsPayment },
        { path: '/pos/payment/complete', component: Pages.HsPaymentComplete },

        /* Account */
        { path: '/my-account', component: Pages.HsMyAccountGeneral },
        { path: '/my-account/payment-details', component: Pages.HsMyAccountPaymentDetails }
    ];
};
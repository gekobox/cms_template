<script>
    export default {
        data: function(){
            return {
                tabs: [
                    {
                        text: 'My account',
                        url: '/my-account'
                    },
                    {
                        text: 'My plan',
                        url: '/my-account/my-plan'
                    },
                    {
                        text: 'Payment details',
                        url: '/my-account/payment-details'
                    }
                ]
            };
        }
    }
</script>
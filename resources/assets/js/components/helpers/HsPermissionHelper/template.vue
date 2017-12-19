<script>
    export default {
        methods: {

            checkPermission: function(resource){
                let canView= false;
                if(window.user_permissions !== undefined 
                    && window.user_permissions !== null                      
                    && window.user_permissions[resource] !== undefined){
                    
                        canView= window.user_permissions[resource];                        
                   
                }
                
                if(!canView){
                    window.location.href= '/';
                }
            }
        }
    }
</script>
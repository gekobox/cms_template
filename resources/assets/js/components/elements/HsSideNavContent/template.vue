<template>

</template>
<script>
import {HsEventBus} from '../HsEventBus/template.vue';
export default {
    data: function(){
        return {
            content: ''
        };
    },
    created: function(){
        var instance= this;
        $(document).ready(function(){
            /*if(window.access_token !== undefined && window.access_token !== null){
                $.ajax({
                    url: '/api/navigation-menu',
                    method: 'get',
                    headers:{
                        'Authorization': 'Bearer '+ window.access_token,
                        'Accept': 'application/json'
                    },
                    complete: function(data){
                        $(instance.$refs.sideNavLinksContainer).html($(data.responseText));
                        //instance.$compile($("#sideNavLinksContainer"));

                        HsEventBus.$emit('showNav');                        
                    }
                });
            }*/
        });
    }
}
</script>
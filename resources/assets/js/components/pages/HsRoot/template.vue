<template>
    <div class="page-content">
        Root (for the initial redirect)
    </div>
</template>

<script>
    import {HsEventBus} from "../../elements/HsEventBus/template.vue";

    export default {
        created: function(){
            this.$router.push('/pos');
        }
    }
</script>
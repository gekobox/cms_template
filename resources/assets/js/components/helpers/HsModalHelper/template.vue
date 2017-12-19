<script>
    export default {
        methods: {
            openModal: function(id){
                $('#'+id).modal();
                $('#'+id).modal('open');
            },
            closeModal: function(id){
                $('#'+id).modal('close');
            }
        }
    }
</script>
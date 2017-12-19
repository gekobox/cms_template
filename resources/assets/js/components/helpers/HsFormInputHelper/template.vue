<script>
    export default {
        props: {
            id: String
        },
        computed: {
            computedId: function(){
                if(this.label !== ""){
                    return this.createIdFromString(this.label);
                }
                else if(this.id !== "") {
                    return this.createIdFromString(this.id);
                }
            }
        },
        mounted: function(){

            // Triggers an update on Materialize text fields to prevent the label to overlap the input
            this.updateMaterializeTextFields();
        },
        methods: {

            // Create string containing only alphanumeric and hyphen characters
            createIdFromString: function(string){
                return string.replace(/[^0-9a-zA-Z]/g, '-').toLowerCase();
            },

            // Re-initialize the Materialize text fields so they don't overlap the input fields
            updateMaterializeTextFields: function(){
                $(document).ready(function(){
                    Materialize.updateTextFields();
                });
            }
        }
    }
</script>
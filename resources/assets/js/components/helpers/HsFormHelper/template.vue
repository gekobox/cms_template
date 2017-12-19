<script>
    export default {
        methods: {

            // Re-initialize the Materialize text fields so they don't overlap the input fields
            updateMaterializeTextFields: function(){
                $(document).ready(function(){
                    Materialize.updateTextFields();
                });
            }
        },
        watch: {

            // Show Materialize toasts for each error
            errors: function(errors){
                for (var key in errors) {
                    if (!errors.hasOwnProperty(key)) continue;

                    var obj = errors[key];
                    if(window._.isArray(obj)){
                        for (var prop in obj) {

                            // skip loop if the property is from prototype
                            if(!obj.hasOwnProperty(prop)) continue;

                            // your code
                            Materialize.toast(obj[prop], 4000)
                        }
                    }
                    else{                        
                        Materialize.toast(obj, 4000)
                    }
                }
            }
        }
    }
</script>
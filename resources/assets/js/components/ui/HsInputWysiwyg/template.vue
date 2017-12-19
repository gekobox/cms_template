<template>
    <div class="input-field" :id="computedId">
        <textarea class="wysiwyg-field browser-default"
                ref="input"
                :id="textareaId"
                :class="error === undefined ? '' : 'invalid'"
                :value="value"></textarea>
        <label class="active">{{label}}</label>
    </div>
</template>

<script>
    import HsFormInput from "../../helpers/HsFormInputHelper/template.vue";

    export default {
        mixins: [
            HsFormInput
        ],
        props: {
            label: String,
            value: String,
            error: Array
        },
        data: function(){
            return {
                initialValueIsSet: false,
                initWysiwyg: false,
                textareaId: 'wysiwyg_editor_' + Date.now()
            }
        },        
        updated: function(){
            var instance = this;
            
            //var editor = tinymce.get(`${instance.id}`);
            var editor= tinymce.get(instance.$refs.input.id);

            // The editor's init method has run before the AJAX value was set on the model. Update the editor once with the latest content.
            // TODO: In add-mode, this will run one time upon typing
            if(editor.initialized === true){

                // One time only to prevent the cursor from jumping every time the model is updated (on keyup)
                if(instance.initialValueIsSet === false || instance.value === ''){
                    editor.setContent(instance.value);
                    this.initialValueIsSet = true;
                }
            }

            // The AJAX value is set on the model before the editor is initialized, so it's init method will pick up the latest content
            else {
                this.initialValueIsSet = true;
            }
        },
        watch:{            
            initWysiwyg: function(){
                this.initWysiwygEditor();
            }
        },
        
        mounted: function(){
            var instance = this;

            $(document).ready(function(){

                // Initialize a new editor
                instance.initWysiwygEditor();
            });
        },
        methods:{
            initWysiwygEditor: function(){
                var instance= this;
                tinymce.init({
                    selector: "#" + instance.$refs.input.id,
                    height: 350,
                    theme: 'modern',
                    plugins: [
                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                        'insertdatetime media nonbreaking save table contextmenu directionality',
                        'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                    ],
                    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
                    image_advtab: true,
                    branding: false,
                    templates: [
                        { title: 'Test template 1', content: 'Test 1' },
                        { title: 'Test template 2', content: 'Test 2' }
                    ],
                    content_css: [
                        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                        '//www.tinymce.com/css/codepen.min.css'
                    ],
                    setup: function (editor) {

                        // Set the content to the editor when the editor is initialized
                        editor.on('init', function () {                            
                            editor.setContent(instance.value);
                        });
                        editor.on('keyup', (e) => {
                            instance.$emit('input', editor.getContent());
                        });
                        editor.on('change', (e) => {
                            instance.$emit('input', editor.getContent());
                        });
                    }
                });
            }
        },
        destroyed: function() {
            var instance = this;
            
        }
    }
</script>

<style scoped>
    .input-field {
        margin: 2rem 0 20px 0;
    }
    .input-field label:not(.label-icon).active {
        transform: translateY(-210%);
    }
</style>
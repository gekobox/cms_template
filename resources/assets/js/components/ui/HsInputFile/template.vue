<template>
    <div class="hs-input-file">
        <div class="file-field input-field">
            <div :class="disabled === false ? 'btn' : 'btn disabled'">
                <span>{{label}}</span>
                <input type="file" :disabled="disabled"
                    :class="error === undefined ? '' : 'invalid'"
                    v-on:change="onFileChange">
            </div>
            <div class="file-path-wrapper">
                <input type="text"
                    :class="error == '' ? 'file-path' : 'file-path invalid'"
                    :disabled="disabled">
            </div>
        </div>
        <!--<img class="thumbnail" :src="file">-->
        <div class="progress" v-if="disabled">
            <div class="indeterminate"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            label: String,
            path: String,
            type: String,
            image: String
        },
        data(){
            return {
                file: '',
                fileName: '',
                disabled: false,
                error: ''
            }
        },
        methods: {
            onFileChange: function(e) {console.log('onFileChamge');
                var instance = this;
                instance.disabled = true;
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length){console.log('file ln 0');
                    return;
                }console.log(instance.type);
                if(instance.type === 'image') {
                    instance.createImage(files[0]);
                }
                else if(instance.type === 'file') {
                    instance.createFile(files[0]);
                }
            },
            createFile: function(file) {
                var instance = this;
                instance.fileName = file.name;
                let reader = new FileReader();
                reader.onload = (e) => {
                    instance.file = e.target.result;
                    instance.upload();
                };
                reader.readAsDataURL(file);
            },
            createImage: function(file) {
                var instance = this;
                instance.fileName = file.name;
                console.log('creating file name');
                instance.$emit('update:image', file.name);                
                let reader = new FileReader();
                reader.onload = (e) => {
                    instance.file = e.target.result;
                    instance.upload();
                };
                reader.readAsDataURL(file);

            },
            upload: function(){
                var instance = this;
                axios.post('/api/admin/images/upload', {
                    file: instance.file,
                    fileName: instance.fileName,
                    path: instance.path
                })
                .then(function (response) {
                
                    // Reset form
                    instance.error = '';
                    $(instance.$el).find('input').val('');

                    // Hide loader
                    instance.disabled = false;

                    instance.image= instance.fileName;

                    // Show toast
                    Materialize.toast('Het bestand is ge&uuml;pload.', 4000)
                })
                .catch(function (error) {

                    // Validation errors
                    if(error.response.status === 422){
                        instance.error = error.response.data.errors.file;
                    }

                    // Hide loader
                    instance.disabled = false;
                });
            }
        }
    }
</script>

<style>
    .hs-input-file .thumbnail {
        display: none;
    }
</style>
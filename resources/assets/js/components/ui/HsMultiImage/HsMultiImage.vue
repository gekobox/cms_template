<template>
    <div>
        <ul class="row" ref="sortable" id='imagesBrowser'>            
            <li class="col s3 add-btn">
                <div class="image-container" v-on:click="uploadImage">
                    <div class="inner">
                        <i class="material-icons">add_circle_outline</i>
                    </div>
                </div>
                <input type="file" ref="addInput" @change="onFileChange">
            </li>
        </ul>
        <div style="display:none">
            <li class="col s3" id='imageItemPattern'>
                <div class="image-container">
                    <div class="inner">
                        <img class="materialboxed" src="/img/placeholder.jpg" width="100%" height="100%" alt="">
                    </div>
                </div>
            </li>
        </div>
        <div class="delete-droppable z-depth-5 red lighten-4" ref="droppable" v-bind:class="{ isDragged: dragging }">
            <div>Drop here to delete</div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                dragging: false
            }
        },
        props: [
            'imagesSrc',
            'sortImagesUrl',
            'uploadUrl',
            'deleteUrl',
            'image'            
        ],
        mounted: function(){
            var instance = this;
            $(document).ready(function(){
                
                //load the images
                instance.loadImageBrowser();
                
                // Bind the sortable
                $(instance.$refs.sortable).sortable({
                    items: "li:not(.add-btn)",
                    sort: function() {
                        instance.dragging = true;
                    },
                    stop: function() {
                        instance.dragging = false;
                    },
                    update: function(){
                        var  data= $(this).sortable('serialize');
                        
                        console.log(data);
                        axios.post(instance.sortImagesUrl, data);
                    }
                });

                // Bind the droppable
                $(instance.$refs.droppable).droppable({
                    classes: {
                        "ui-droppable-hover": "over"
                    },
                    drop: function(event, ui) {
                        instance.deleteImage(event, ui);
                    }
                });
            });
        },
        methods: {
            uploadImage: function(){
                var instance = this;
                $(document).ready(function(){

                    // Open the local file browser
                    $(instance.$refs.addInput).trigger('click');
                });
            },
            deleteImage: function(event, ui){
                window.loader.show();
                var instance= this;
                var ids= [ui.draggable.attr('data-id')];
                // Send the delete request
                axios.post(instance.deleteUrl,
                    {
                        ids: ids
                    })
                    .then(function (response) {

                        instance.loadImageBrowser();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                
            },
            //get the images for the image browser
            loadImageBrowser: function(){
                var instance= this;
                axios.get(instance.imagesSrc)
                    .then(function (response) {                       
                        var images= response.data;
                        $('.image-item').remove();
                        $.each(images, function(key, image){
                            var imageItemCloned= $('#imageItemPattern').clone();
                            imageItemCloned.attr('id', 'items_' + image.id);
                            imageItemCloned.attr('data-id', image.id);
                            imageItemCloned.find('img').attr('src', image.name);
                            imageItemCloned.addClass('image-item');
                            //imageItemCloned.appendTo($(instance.$refs.sortable));
                            imageItemCloned.insertBefore(".add-btn");
                        })
                        
                        
                        // Hide the loader
                        window.loader.hide();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                
            },
            onFileChange: function(e) {
                var instance = this;
                instance.disabled = true;
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length){console.log('file ln 0');
                    return;
                }
                
                instance.createImage(files[0]);                
            },
            createImage: function(file) {
                var instance = this;
                instance.fileName = file.name;
                instance.$emit('update:image', file.name);                
                let reader = new FileReader();
                reader.onload = (e) => {
                    instance.file = e.target.result;
                    instance.upload();
                };
                reader.readAsDataURL(file);

            },
            upload: function(){
                window.loader.show();
                var instance = this;
                axios.post(instance.uploadUrl, {
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

                    //reload image browser
                    instance.loadImageBrowser();

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

<style scoped>
    .image-container {
        width: 100%;
        padding-bottom: 100%;
        margin-bottom: 20px;
        position: relative;
        cursor: pointer;
    }
    .image-container .inner {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: #f5f5f5;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center center;
    }
    .image-container .inner {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .add-btn .image-container .inner .material-icons {
        font-size: 40px;
        opacity: 0.5;

        -webkit-transition: opacity 250ms ease;
        -moz-transition: opacity 250ms ease;
        -ms-transition: opacity 250ms ease;
        -o-transition: opacity 250ms ease;
        transition: opacity 250ms ease;
    }
    .add-btn .image-container:hover .inner .material-icons {
        opacity: 1;
    }
    .add-btn input {
        position: absolute;
        right: 50000px;
    }
    .delete-droppable {
        position: fixed;
        bottom: 10px;
        right: 10px;
        width: 25%;
        height: 25%;
        border-radius: 2px;
        outline: 1px dotted #b71c1c;
        color: #b71c1c;
        outline-offset: -10px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        opacity: 0;
        text-transform: uppercase;

        -webkit-transition: opacity 250ms ease;
        -moz-transition: opacity 250ms ease;
        -ms-transition: opacity 250ms ease;
        -o-transition: opacity 250ms ease;
        transition: opacity 250ms ease;
    }
    @media screen and (max-width: 992px){
        .delete-droppable {
            width: 50%;
        }
    }
    .delete-droppable.isDragged {
        opacity: 0.65;
    }
    .delete-droppable.over {
        opacity: 1;
    }
</style>

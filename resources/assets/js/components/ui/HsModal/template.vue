<template>
    <div :id="id" class="modal bottom-sheet">
        <div class="modal-content">
            <slot name="modal-content"></slot>
        </div>
        <div class="modal-footer">
            <slot name="modal-footer"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: String
        }
    }
</script>
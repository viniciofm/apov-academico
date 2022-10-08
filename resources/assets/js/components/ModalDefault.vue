<template>
    <div class="modal fade"
         style="overflow: auto;"
         data-backdrop="static"
         aria-hidden="true"
         :id="id_modal"
         v-bind:id="id"
         v-bind:tabindex="tabIndex"
         ref="modal"
         role="dialog">
        <div class="modal-dialog" role="document" :class="modalSize">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> {{ title }} </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <slot name="modal-body"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'modal-default',
    data: () => ({
        sizeClasses: {
            "large": "modal-lg",
            "small": "modal-sm",
            "medium": "modal-md",
            "full": "modal-full"
        },
    }),
    props: {
        id: {
            required: true,
            type: String
        },
        tabIndex: {
            type: String,
            default: '-1'
        },
        title: {
            type: String,
            required: true
        },
        size: {
            type: String,
            default: ""
        },
        id_modal: {
            type: String,
            default: ""
        }
    },
    computed: {
        modalSize: function() {
            return this.sizeClasses[this.size] || "";
        }
    },
    methods: {
        open() {
            if (this.id_modal!='')
                jQuery('#'+this.id_modal).modal('show')
            else
                jQuery('#modal').modal('show')
        },
        close() {
            if (this.id_modal!='')
                jQuery('#'+this.id_modal).modal('hide')
            else
                jQuery('#modal').modal('hide')
        }
    }
}
</script>

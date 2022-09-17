<template>
    <div class="modal fade"
         data-backdrop="static"
         aria-hidden="true"
         v-bind:id="id"
         v-bind:tabindex="tabIndex"
         ref="modal"
         role="dialog">
        <div class="modal-dialog" role="document" :class="modalSize">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> {{ title }} </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
            }
        },
        computed: {
            modalSize: function() {
                return this.sizeClasses[this.size] || "";
            }
        },
        methods: {
            open() {
                $('#modal-practice-lessons').modal('show')
            },
            close() {
                $('#modal-practice-lessons').modal('hide')
            }
        }
    }
</script>

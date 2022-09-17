<template>
    <div class="mx-auto text-center">
        <h3><i v-bind:class="[icon, stateClass]" v-bind:style="{'font-size': size + 'em'}"></i></h3>

        <slot name="message"></slot>
        <br>
    </div>
</template>

<script>
    export default {
        name: 'empty-lits',
        data: () => ({
            stateClasses: {
        "danger": "m--font-danger",
            "warning": "m--font-warning",
            "success": "m--font-success",
            "info": "m--font-info",
            "brand": "m--font-brand",
            "accent": "m--font-accent",
            "focus": "m--font-focus",
            "metal": "m--font-metal"
    },
    }),
    props: {
        icon: {
            type: String,
        default: 'flaticon-confetti'
        },
        size: {
                type: String,
                default: '8'
            },
            state: {
                type: String,
                default: ''
            }
        },
        computed: {
            stateClass: function() {
                return this.stateClasses[this.state] || "";
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

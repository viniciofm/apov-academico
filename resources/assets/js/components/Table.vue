<template>
    <div class="table-responsive">
        <table class="table table-striped table-hover" :class="classes">
            <thead>
                <tr v-if="custom">
                    <th v-for="(column, key) in columns" :style="column.style" :key="key">{{ column.name }}</th>
                </tr>
                <tr v-else>
                    <th v-for="(column, key) in columns" :style="column" :key="key">{{ column }}</th>
                </tr>
            </thead>
            <tbody>
                <slot></slot>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'Table',
    props: {
        classes: {
            type: String,
            default: 'mt-4'
        },
        custom: {
            type: Boolean,
            default: false,
            required: false

        },
        columns: {
            type: Array,
            required: true
        }
    }
}
</script>

<style scoped>
.table-outline thead {
    background: transparent!important;
    color: #575962!important;
}
</style>

<template>
    <span>{{ value | striphtml }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
    computed: {
        value() {
            return this.field.value[this.field.indexLocale] || '—'
        }
    }
}
</script>

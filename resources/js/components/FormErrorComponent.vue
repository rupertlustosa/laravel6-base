<template>
    <span class="text-danger">
        <slot></slot>
    </span>
</template>

<script>
    export default {
        name: "FormErrorComponent",
        props: ['errors'],
    }
</script>

<style scoped>

</style>

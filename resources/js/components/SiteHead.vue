<template>
    <inertia-head :title="title ? `${title} - Smartoken` : 'Smartoken'">
        <slot/>
    </inertia-head>
</template>

<script>

export default {
    name: "SiteHead",
    props: {
        title: String
    }
}
</script>

<style scoped>

</style>

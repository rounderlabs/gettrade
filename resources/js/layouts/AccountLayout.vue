<template>
    <div class="row row-sm">
        <slot></slot>
    </div>
</template>

<script>

export default {
    name: "AccountLayout",
    props: {
        profile: Object
    },
    data() {
        return {
            activeLink: null
        }
    },
    methods: {
        visitPage(link) {
            this.activeLink = link

            this.$inertia.visit(link, {
                preserveScroll: true
            })
        }
    },
    mounted() {
        this.activeLink = route(route().current())
    }


}
</script>

<style scoped>

</style>

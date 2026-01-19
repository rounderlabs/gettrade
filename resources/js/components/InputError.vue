<template>
    <span class="invalid-feedback" v-if="error">
        {{ error }}
    </span>
</template>

<script>
export default {
    name: "InputError",
    props: {
        error: {
            type: String,
            default() {
                return null
            }
        }
    }
}
</script>

<style scoped>

</style>

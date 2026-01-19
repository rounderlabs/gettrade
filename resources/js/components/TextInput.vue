<template>
    <input class="mh form-control" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
           ref="input">
</template>

<script>
export default {
    name: "TextInput",
    props: ['modelValue'],

    emits: ['update:modelValue'],

    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <label class="my-1 font-medium text-sm text-secondary">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot/></span>
    </label>
</template>

<script>
export default {
    name: 'InputLabel',
    props: ['value']
}
</script>

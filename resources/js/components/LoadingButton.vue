<template>
    <button :type="type" class="btn btn-primary py-3 px-4 w-full align-top">
        <slot/>
    </button>
</template>

<script>
export default {
    name: 'LoadingButton',
    props: {
        type: {
            type: String,
            default: 'submit',
        },
    }
}
</script>

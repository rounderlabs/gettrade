<template>
    <inertia-head :title="title ? `${title} - Winve` : 'My App'">
        <slot/>
    </inertia-head>
</template>

<script>

export default {
    name: 'AppHead',
    props: {
        title: String,
    },
}
</script>

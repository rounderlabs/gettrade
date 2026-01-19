<template>
    <div>
        <img alt="Logo" class="m-0 mb-4 fa-spin" height="90" src="/img/brand/smartIcon.png">
    </div>
</template>

<script>
export default {
    name: "LogoFull"
}
</script>

<style scoped>
.loadingImg {
    animation: spin 9s infinite ease-in-out;
}

@keyframes loadingImg {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(359deg);
    }
}
</style>

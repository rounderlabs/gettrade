<template>
    <div class="transaction-box mb-3">
        <div class="transaction-details">
            <div class="transaction-name pb-0">
                <h5>{{ title }}</h5>
                <h5 class="dark-text fw-semibold">
                    {{ value || 'Not Updated' }}
                </h5>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        value: String
    }
}
</script>

<template>
    <div class="form-group d-flex justify-content-between align-items-center mb-2">
        <label class="mb-0">{{ label }}</label>

        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                :checked="modelValue"
                @change="$emit('update:modelValue', $event.target.checked)"
            />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        modelValue: Boolean,
    },
};
</script>

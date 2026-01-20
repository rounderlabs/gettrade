<script setup>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import { useForm } from "@inertiajs/vue3"

defineOptions({ layout: MainAdminLayout })

const props = defineProps({
    rules: Array,
})

const form = useForm({
    rules: props.rules.length
        ? props.rules
        : [
            {
                rank: 1,
                level1_business: 0,
                required_direct_rank: null,
                required_direct_count: 0,
            },
        ],
})

function addRank() {
    const nextRank = form.rules.length + 1
    form.rules.push({
        rank: nextRank,
        level1_business: 0,
        required_direct_rank: nextRank - 1,
        required_direct_count: 2,
    })
}

function removeRank(index) {
    if (index === 0) return
    form.rules.splice(index, 1)
    reindex()
}

function reindex() {
    form.rules.forEach((r, i) => (r.rank = i + 1))
}
</script>

<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rank Rules</h3>
        </div>

        <div class="card-body">
            <div
                v-for="(rule, index) in form.rules"
                :key="rule.rank"
                class="border p-3 mb-3 rounded"
            >
                <h5>Rank {{ rule.rank }}</h5>

                <!-- Rank 1 -->
                <div v-if="rule.rank === 1" class="form-group">
                    <label>Level 1 Total Business</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model.number="rule.level1_business"
                    />
                </div>

                <!-- Rank 2+ -->
                <div v-else class="row">
                    <div class="col-md-6">
                        <label>Required Direct Rank</label>
                        <input
                            type="number"
                            class="form-control"
                            v-model.number="rule.required_direct_rank"
                        />
                    </div>

                    <div class="col-md-6">
                        <label>Required Count</label>
                        <input
                            type="number"
                            class="form-control"
                            v-model.number="rule.required_direct_count"
                        />
                    </div>
                </div>

                <button
                    class="btn btn-danger btn-sm mt-2"
                    v-if="index !== 0"
                    @click="removeRank(index)"
                >
                    Remove Rank
                </button>
            </div>

            <button class="btn btn-outline-primary" @click="addRank">
                + Add Rank
            </button>
        </div>

        <div class="card-footer text-right">
            <button
                class="btn btn-success"
                :disabled="form.processing"
                @click="form.post(route('admin.ranks.store'))">
                Save Rules
            </button>
        </div>
    </div>
</template>

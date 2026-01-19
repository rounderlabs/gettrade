<template>
    <div class="card">
        <div class="card-header">
            <h4>KYC Submission History</h4>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th>Rejection Reason</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, i) in submissions" :key="row.id">
                    <td>{{ i + 1 }}</td>
                    <td>
                            <span
                                class="badge"
                                :class="{
                                    'bg-success': row.status === 'approved',
                                    'bg-danger': row.status === 'rejected',
                                    'bg-warning': row.status === 'submitted',
                                }"
                            >
                                {{ row.status }}
                            </span>
                    </td>
                    <td>{{ row.created_at }}</td>
                    <td>{{ row.rejection_reason ?? '-' }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    submissions: Array,
});
</script>

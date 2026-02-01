<template>
    <section class="content">
        <div class="container-fluid">

            <!-- ================= HEADER ================= -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Currencies</h3>

                    <button
                        class="btn btn-sm btn-primary"
                        @click="openModal"
                    >
                        <i class="fas fa-plus mr-1"></i>
                        Add Currency
                    </button>
                </div>

                <!-- ================= TABLE ================= -->
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Symbol</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Base</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="c in currencies" :key="c.id">
                            <td><strong>{{ c.code }}</strong></td>
                            <td>{{ c.name }}</td>
                            <td>{{ c.symbol }}</td>
                            <td>
                                <span
                                    class="badge"
                                    :class="(c.currency_type ?? 'fiat') === 'crypto'
                                        ? 'badge-warning'
                                        : 'badge-info'"
                                >
                                    {{ (c.currency_type ?? 'fiat').toUpperCase() }}
                                </span>
                            </td>
                            <td>
                                <span
                                    :class="c.is_active ? 'badge-success' : 'badge-secondary'"
                                    class="badge"
                                >
                                    {{ c.is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td>
                                <span
                                    v-if="c.is_base"
                                    class="badge badge-primary"
                                >
                                    BASE
                                </span>
                            </td>
                        </tr>

                        <tr v-if="!currencies.length">
                            <td class="text-center text-muted" colspan="5">
                                No currencies found
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= ADD CURRENCY MODAL ================= -->
            <div
                v-if="modal.open"
                class="modal fade show"
                role="dialog"
                style="display:block; background: rgba(0,0,0,.5)"
                tabindex="-1"
            >
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Add Currency</h5>
                            <button class="close" type="button" @click="closeModal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label>Currency Code</label>
                                    <input
                                        v-model="form.code"
                                        class="form-control"
                                        maxlength="15"
                                        placeholder="USD"
                                        required
                                        type="text"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>Currency Name</label>
                                    <input
                                        v-model="form.name"
                                        class="form-control"
                                        placeholder="US Dollar"
                                        required
                                        type="text"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>Symbol</label>
                                    <input
                                        v-model="form.symbol"
                                        class="form-control"
                                        maxlength="15"
                                        placeholder="$"
                                        required
                                        type="text"
                                    >
                                </div>

                                <div class="form-group">
                                    <label>Currency Type</label>
                                    <select
                                        v-model="form.currency_type"
                                        class="form-control"
                                        required
                                    >
                                        <option value="fiat">Fiat</option>
                                        <option value="crypto">Crypto</option>
                                    </select>
                                </div>

                                <div class="alert alert-info mb-0">
                                    <strong>Note:</strong>
                                    Exchange rate must be added separately.
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button
                                    class="btn btn-secondary"
                                    type="button"
                                    @click="closeModal"
                                >
                                    Cancel
                                </button>
                                <button class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import {reactive} from "vue"
import {useForm} from "@inertiajs/vue3"

export default {
    layout: MainAdminLayout,

    props: {
        currencies: Array,
    },

    setup() {
        const modal = reactive({
            open: false,
        })

        const form = useForm({
            code: "",
            name: "",
            symbol: "",
            currency_type: "fiat",
        })

        const openModal = () => {
            modal.open = true
        }

        const closeModal = () => {
            modal.open = false
            form.reset()
        }

        const submit = () => {
            form.post(route("admin.currencies.store"), {
                preserveScroll: true,
                onSuccess: () => closeModal(),
            })
        }

        return {
            modal,
            form,
            openModal,
            closeModal,
            submit,
        }
    },
}
</script>

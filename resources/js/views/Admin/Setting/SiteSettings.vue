<script setup>
import MainAdminLayout from "@/layouts/Admin/MainAdminLayout.vue"
import { useForm } from "@inertiajs/vue3"
import { ref } from "vue"

/* =========================
   LAYOUT
========================= */
defineOptions({
    layout: MainAdminLayout,
})

/* =========================
   PROPS
========================= */
const props = defineProps({
    settings: Object,
    groups: Object,
})

/* =========================
   STATE
========================= */
const activeTab = ref("general")
const MAX_LEVELS = 10

/* =========================
   GENERAL FORM
========================= */
const generalForm = useForm({
    site_name: props.settings.site_name ?? "",
    site_tagline: props.settings.site_tagline ?? "",
})

/* =========================
   BRANDING FORM
========================= */
const brandingForm = useForm({
    logo_desktop: null,
    logo_mobile: null,
})

/* =========================
   COMMISSION FORM
========================= */
const commissionForm = useForm({
    direct_percent: props.settings.direct_percent ?? 0,
    level_percentages:
        props.settings.level_percentages?.length
            ? props.settings.level_percentages
            : [{ level: 1, percent: 0 }],
})

/* =========================
   COMMISSION HELPERS
========================= */
function addLevel() {
    if (commissionForm.level_percentages.length >= MAX_LEVELS) return

    commissionForm.level_percentages.push({
        level: commissionForm.level_percentages.length + 1,
        percent: 0,
    })
}

function removeLevel(index) {
    if (index === 0) return
    commissionForm.level_percentages.splice(index, 1)
    reindexLevels()
}

function reindexLevels() {
    commissionForm.level_percentages.forEach((item, index) => {
        item.level = index + 1
    })
}
</script>

<template>
    <div class="card">
        <!-- ================= TABS ================= -->
        <div class="card-header p-0">
            <ul class="nav nav-tabs">
                <li
                    v-for="(label, key) in groups"
                    :key="key"
                    class="nav-item"
                >
                    <a
                        href="#"
                        class="nav-link"
                        :class="{ active: activeTab === key }"
                        @click.prevent="activeTab = key"
                    >
                        {{ label }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <!-- ================= GENERAL TAB ================= -->
            <div v-if="activeTab === 'general'">
                <div class="form-group">
                    <label>Site Name</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="generalForm.site_name"
                    />
                    <div class="text-danger">
                        {{ generalForm.errors.site_name }}
                    </div>
                </div>

                <div class="form-group">
                    <label>Tagline</label>
                    <input
                        type="text"
                        class="form-control"
                        v-model="generalForm.site_tagline"
                    />
                </div>

                <button
                    class="btn btn-primary"
                    :disabled="generalForm.processing"
                    @click="generalForm.post(route('admin.site.settings.update.general'), { preserveScroll: true })"
                >
                    Save General
                </button>
            </div>

            <!-- ================= BRANDING TAB ================= -->
            <div v-if="activeTab === 'branding'">
                <div class="form-group">
                    <label>Desktop Logo</label>
                    <input
                        type="file"
                        class="form-control"
                        @change="e => brandingForm.logo_desktop = e.target.files[0]"
                    />
                </div>

                <div class="form-group">
                    <label>Mobile Logo</label>
                    <input
                        type="file"
                        class="form-control"
                        @change="e => brandingForm.logo_mobile = e.target.files[0]"
                    />
                </div>

                <button
                    class="btn btn-primary"
                    :disabled="brandingForm.processing"
                    @click="brandingForm.post(route('admin.site.settings.update.branding'), { preserveScroll: true })"
                >
                    Save Branding
                </button>
            </div>

            <!-- ================= COMMISSION TAB ================= -->
            <div v-if="activeTab === 'commission'">
                <div class="form-group">
                    <label>Direct Commission (%)</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model.number="commissionForm.direct_percent"
                        min="0"
                        max="100"
                    />
                </div>

                <hr />

                <label class="mb-2">Level Wise Commission</label>

                <div
                    v-for="(item, index) in commissionForm.level_percentages"
                    :key="item.level"
                    class="d-flex align-items-center mb-2"
                >
                    <span class="badge badge-secondary mr-2">
                        Level {{ item.level }}
                    </span>

                    <input
                        type="number"
                        class="form-control mr-2"
                        style="max-width: 120px"
                        v-model.number="item.percent"
                        min="0"
                        max="100"
                    />

                    <button
                        class="btn btn-danger btn-sm"
                        v-if="index !== 0"
                        @click="removeLevel(index)"
                    >
                        Remove
                    </button>
                </div>

                <button
                    class="btn btn-outline-primary btn-sm mt-2"
                    :disabled="commissionForm.level_percentages.length >= MAX_LEVELS"
                    @click="addLevel"
                >
                    + Add Level
                </button>

                <small class="text-muted d-block mt-1">
                    Maximum {{ MAX_LEVELS }} levels allowed
                </small>

                <div class="text-danger mt-2">
                    {{ commissionForm.errors.level_percentages }}
                </div>

                <button
                    class="btn btn-primary mt-3"
                    :disabled="commissionForm.processing"
                    @click="commissionForm.post(route('admin.site.settings.update.commission'), { preserveScroll: true })"
                >
                    Save Commission
                </button>
            </div>
        </div>
    </div>
</template>

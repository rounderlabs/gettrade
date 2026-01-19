<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Permissions</h1>

        <div class="flex justify-between mb-4">
            <input
                v-model="search"
                placeholder="Search permissions..."
                class="border px-3 py-2 rounded w-1/3"
            />
            <button
                @click="showCreate = true"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                Create Permission
            </button>
        </div>

        <table class="w-full border">
            <thead class="bg-gray-100">
            <tr>
                <th class="text-left p-2 border">Name</th>
                <th class="text-left p-2 border">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="perm in filteredPermissions"
                :key="perm.id"
                class="hover:bg-gray-50"
            >
                <td class="p-2 border">{{ perm.name }}</td>
                <td class="p-2 border">
                    <button
                        @click="deletePermission(perm.id)"
                        class="text-red-600 hover:underline"
                    >
                        Delete
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Create Permission Modal -->
        <div
            v-if="showCreate"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded w-96">
                <h2 class="text-xl font-semibold mb-4">Create Permission</h2>

                <input
                    v-model="form.name"
                    placeholder="Permission name"
                    class="w-full border p-2 mb-4 rounded"
                />

                <div class="flex justify-end">
                    <button @click="showCreate = false" class="mr-2">Cancel</button>
                    <button
                        @click="createPermission"
                        class="bg-green-600 text-white px-4 py-2 rounded"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const permissions = ref([])
const showCreate = ref(false)
const form = ref({ name: '' })
const search = ref('')

const loadPermissions = async () => {
    permissions.value = (await axios.get('/api/admin/permissions')).data
}

const createPermission = async () => {
    await axios.post('/api/admin/permissions', form.value)
    form.value.name = ''
    showCreate.value = false
    loadPermissions()
}

const deletePermission = async (id) => {
    await axios.delete(`/api/admin/permissions/${id}`)
    loadPermissions()
}

const filteredPermissions = computed(() => {
    if (!search.value) return permissions.value
    return permissions.value.filter((p) =>
        p.name.toLowerCase().includes(search.value.toLowerCase())
    )
})

onMounted(loadPermissions)
</script>

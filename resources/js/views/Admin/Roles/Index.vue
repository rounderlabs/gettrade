<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Roles</h1>

        <button @click="showCreate = true" class="bg-blue-500 text-white px-4 py-2 rounded">Create Role</button>

        <table class="mt-4 w-full border">
            <thead>
            <tr>
                <th>Name</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="role in roles" :key="role.id">
                <td>{{ role.name }}</td>
                <td>
            <span v-for="perm in role.permissions" :key="perm.id" class="inline-block bg-gray-200 px-2 py-1 rounded mr-1 text-xs">
              {{ perm.name }}
            </span>
                </td>
                <td>
                    <button @click="editRole(role)" class="text-blue-600 mr-2">Edit</button>
                    <button @click="deleteRole(role.id)" class="text-red-600">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Create/Edit Modal -->
        <div v-if="showCreate" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded w-96">
                <h2 class="text-xl font-semibold mb-4">{{ editing ? 'Edit Role' : 'Create Role' }}</h2>

                <input v-model="form.name" placeholder="Role name" class="w-full border p-2 mb-3 rounded" />

                <div>
                    <h3 class="font-semibold mb-2">Permissions</h3>
                    <div class="grid grid-cols-2 gap-1 max-h-48 overflow-y-auto">
                        <label v-for="perm in permissions" :key="perm.id">
                            <input type="checkbox" v-model="form.permissions" :value="perm.name" />
                            {{ perm.name }}
                        </label>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button @click="showCreate = false" class="mr-2">Cancel</button>
                    <button @click="saveRole" class="bg-green-500 text-white px-4 py-2 rounded">
                        {{ editing ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const roles = ref([])
const permissions = ref([])
const showCreate = ref(false)
const editing = ref(false)
const form = ref({ id: null, name: '', permissions: [] })

const loadRoles = async () => {
    roles.value = (await axios.get('/api/admin/roles')).data
}
const loadPermissions = async () => {
    permissions.value = (await axios.get('/api/admin/permissions')).data
}

const saveRole = async () => {
    if (editing.value) {
        await axios.put(`/api/admin/roles/${form.value.id}`, form.value)
    } else {
        await axios.post('/api/admin/roles', form.value)
    }
    showCreate.value = false
    loadRoles()
}

const editRole = (role) => {
    editing.value = true
    form.value = { id: role.id, name: role.name, permissions: role.permissions.map(p => p.name) }
    showCreate.value = true
}

const deleteRole = async (id) => {
    await axios.delete(`/api/admin/roles/${id}`)
    loadRoles()
}

onMounted(() => {
    loadRoles()
    loadPermissions()
})
</script>

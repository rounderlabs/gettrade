<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Assign Roles to Admins</h1>

        <div v-if="admins.length === 0" class="text-gray-600">
            No admins found.
        </div>

        <div
            v-for="admin in admins"
            :key="admin.id"
            class="border p-4 mb-3 rounded shadow-sm"
        >
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-lg">{{ admin.name }}</h2>
                <button
                    @click="saveRoles(admin)"
                    class="bg-green-600 text-white px-3 py-1 rounded"
                >
                    Save
                </button>
            </div>

            <div class="grid grid-cols-3 gap-2 mt-2">
                <label v-for="role in roles" :key="role.id" class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="admin.assignedRoles"
                        :value="role.name"
                        class="mr-2"
                    />
                    {{ role.name }}
                </label>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const admins = ref([])
const roles = ref([])

const loadAdmins = async () => {
    const res = await axios.get('/api/admins') // 👈 You’ll create this route below
    admins.value = res.data.map((admin) => ({
        ...admin,
        assignedRoles: []
    }))
    for (let admin of admins.value) {
        const rolesRes = await axios.get(`/api/admins/${admin.id}/roles`)
        admin.assignedRoles = rolesRes.data
    }
}

const loadRoles = async () => {
    roles.value = (await axios.get('/api/roles')).data
}

const saveRoles = async (admin) => {
    await axios.post(`/api/admins/${admin.id}/roles`, {
        roles: admin.assignedRoles
    })
    alert(`Roles updated for ${admin.name}`)
}

onMounted(() => {
    loadAdmins()
    loadRoles()
})
</script>

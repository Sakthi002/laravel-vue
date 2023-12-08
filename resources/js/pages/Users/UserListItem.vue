<script setup>

import {formatDate} from "../../helpers.js";

import {useToastr} from "../../composables/useToastr.js";
import {ref} from "vue";

let toast = useToastr();

let props = defineProps({
    index: Number,
    user: Object,
    selectAll: Boolean
})

let emit = defineEmits(['editUser', 'toggleSelection','deleteUser'])

let roles = ref([
    { name : 'ADMIN', value: 1 },
    { name : 'USER', value: 2 }
])
const changeRole = (userId, role) =>{

    axios.patch(`/api/users/${userId}/change-role`,{ role: role }).then(res=>{

        toast.success('Role changed successfully!');
    });
}

const toggleSelection = () => {

    emit('toggleSelection', props.user.id)
}

</script>

<template>

    <tr>
        <td>
            <input type="checkbox" :checked="selectAll" @change="toggleSelection">
        </td>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ formatDate(user.created_at)}}</td>
        <td>
            <select class="form-control" @change="changeRole(user.id, $event.target.value)">

                <option v-for="role in roles" :value="role.value" :selected="role.name === user.role"> {{ role.name }}</option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="$emit('editUser',user)">
                <i class="fas fa-edit"></i>
            </a>

            <a href="#" @click.prevent="$emit('deleteUser',user.id)">
                <i class="fas fa-trash text-red ml-2"></i>
            </a>
        </td>
    </tr>
</template>

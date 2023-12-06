<script setup>

import {formatDate} from "../../helpers.js";

import {useToastr} from "../../composables/useToastr.js";
import {ref} from "vue";

let toast = useToastr();

let userIdToDelete = ref(null);

let props = defineProps({
    index: Number,
    user: Object,
    selectAll: Boolean
})

let emit = defineEmits(['userDeleted', 'editUser', 'toggleSelection'])

let roles = ref([
    { name : 'ADMIN', value: 1 },
    { name : 'USER', value: 2 }
])

const deleteUser = (user) => {

    userIdToDelete.value = user.id;

    $('#deleteUserModal').modal('show');
}

const onConfirmDelete = () =>{

    axios.delete('/api/users/'+userIdToDelete.value).then(res=>{

        toast.success('User deleted successfully');

        userIdToDelete.value = null;

        $('#deleteUserModal').modal('hide');

        emit('userDeleted');
    })
}

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

            <a href="#" @click.prevent="deleteUser(user)">
                <i class="fas fa-trash text-red ml-2"></i>
            </a>
        </td>
    </tr>

    <div class="modal fade" id="deleteUserModal" aria-modal="true" role="dialog" data-backdrop="static">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title">Delete User</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">

                    <span class="text-sm">Are you sure? you want delete?</span>
                </div>

                <div class="modal-footer justify-content-between">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <button type="button" @click.prevent="onConfirmDelete" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

</template>

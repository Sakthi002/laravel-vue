<script setup>

import {ref, onMounted, reactive, watch} from "vue";

    import { Form, Field } from "vee-validate";

    import * as yup from 'yup';

    import { useToastr } from "../../composables/useToastr.js";

    import UserListItem from "./UserListItem.vue";

    import { debounce } from 'lodash';

    import { Bootstrap5Pagination } from 'laravel-vue-pagination';

    let toast = useToastr();

    let users = ref({ data : []});

    let editing = ref(false);

    let formValues = ref();

    const userForm = ref();

    function getUsers(page = 1) {

        axios.get(`/api/users?page=`+page).then(res=>{

            users.value = res.data;

            selectedUsers.value = [];

            selectAll.value = false;

        }).catch(err=>{

            console.error(err);
        });
    }

    let createUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().required().min(8)
    });

    let editUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().notRequired().test('password', 'Passwords must be be minimum of 8 characters', function(value) {
            if (!!value) {
                const schema = yup.string().min(8);
                return schema.isValidSync(value);
            }
            return true;
        }),
    });

    const handleSubmit = (values, actions) => {

        if(editing.value) {

            updateUser(values, actions);

        } else {

            createUser(values, actions)
        }
    }

    const updateUser = (values, { setErrors }) => {

        axios.put('/api/users/'+values.id,values).then(res=>{

            getUsers();

            $('#createUserModal').modal('hide');

            toast.success('User updated successfully.')

        }).catch(err=>{

            if(err.response.data.errors) {

                setErrors(err.response.data.errors)
            }
        })
    }

    const createUser = (values, { resetForm, setErrors })=>{

        axios.post('/api/users', values).then(res=>{

            $('#createUserModal').modal('hide');

            getUsers();

            resetForm();

            toast.success('User created successfully.')

        }).catch(err=>{

            if(err.response.data.errors) {

                setErrors(err.response.data.errors)
            }
        });
    }

    const editUser = (user) =>{

        editing.value = true;

        formValues.value = {
            id: user.id,
            name: user.name,
            email: user.email
        };

        $('#createUserModal').modal('show');

        userForm.value.resetForm({ values: { ...formValues.value }})
    }

    let searchQuery = ref('');

    watch(searchQuery, debounce(()=>{

        search();
    },300));

    const search = ()=>{

        axios.get('/api/users/search',{
            params:{
                query : searchQuery.value
            }
        }).then(res=>{

            users.value = res.data;
        })
    }

    const showCreateModal = () =>{

        editing.value = false;

        formValues.value = {
            id: '',
            name: '',
            email: '',
            password: '',
        };

        $('#createUserModal').modal('show');

        userForm.value.resetForm({ values: { ...formValues.value }})
    }


    let selectedUsers = ref([]);
    const toggleSelection = (value) =>{

        let index = selectedUsers.value.indexOf(value);

        if(index === -1) {

            selectedUsers.value.push(value);

        } else {

            selectedUsers.value.splice(index,1);
        }
    }

    const bulkDelete = ()=>{

        axios.delete('/api/users',{
            data : {
                ids : selectedUsers.value
            }
        }).then(res=>{

            toast.success(res.data.message);

            getUsers();

            selectedUsers.value = [];

            selectAll.value = false;

        }).catch(err=>{

            console.log(err);
        })
    }

    let selectAll = ref(false);

    const selectAllUsers = ()=>{

        if(selectAll.value) {

            selectedUsers.value = users.value.data.map(obj => obj.id);
        } else {

            selectedUsers.value = []
        }
    }


    let userIdToDelete = ref(null);

    const deleteUser = (id) => {

        userIdToDelete.value = id;

        $('#deleteUserModal').modal('show');
    }

    const onConfirmDelete = () =>{

        axios.delete('/api/users/'+userIdToDelete.value).then(res=>{

            toast.success('User deleted successfully');

            userIdToDelete.value = null;

            $('#deleteUserModal').modal('hide');

            getUsers();
        })
    }

    onMounted(()=>{

        getUsers();
    })
</script>

<template>

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0">Users</h1>
                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item">

                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>

                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

        <div class="container-fluid">

            <div class="d-flex justify-content-between">

               <div>

                   <button class="mb-2 btn btn-primary" @click="showCreateModal">

                       <i class="fas fa-plus mr-1"></i> Add User
                   </button>

                   <button v-if="selectedUsers.length" class="ml-2 mb-2 btn btn-danger" @click="bulkDelete">

                       <i class="fas fa-trash-alt mr-1"></i> Delete Selected ( {{ selectedUsers.length }} )
                   </button>
               </div>

                <div>

                    <input type="text" v-model="searchQuery" class="form-control" placeholder="Search...">
                </div>
            </div>

            <div class="card">

                <div class="card-body">

                    <table class="table table-bordered">

                        <thead>

                            <tr>
                                <th><input type="checkbox" v-model="selectAll" @change="selectAllUsers"></th>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>

                        <tbody v-if="users.data.length">

                            <UserListItem v-for="(user,index) in users.data" :key="user.id"
                                :user=user
                                :index="index"
                                @edit-user="editUser"
                                @toggle-selection="toggleSelection"
                                :select-all="selectAll"
                                @delete-user="deleteUser"
                            >

                            </UserListItem>
                        </tbody>

                        <tbody v-else>

                            <tr><td colspan="6" class="text-center">No results found...</td></tr>
                        </tbody>
                    </table>

                    <Bootstrap5Pagination class="mt-3"
                        :data="users"
                        @pagination-change-page="getUsers"
                    />
                </div>
            </div>
        </div>

        <div class="modal fade" id="createUserModal" aria-modal="true" role="dialog" data-backdrop="static">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h4 class="modal-title">{{ editing ? "Edit User" : "Add New User" }}</h4>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <Form ref="userForm" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }">

                        <div class="modal-body">

                            <div class="form-group">

                                <label for="name">Name</label>

                                <Field name="name" type="text" class="form-control" id="name"
                                   :class="{'is-invalid' : errors.name }"
                                   placeholder="Enter full name"/>

                                <span class="invalid-feedback">{{ errors.name }}</span>
                            </div>

                            <div class="form-group">

                                <label for="email">Email</label>

                                <Field name="email" type="email" class="form-control" id="email"
                                   :class="{'is-invalid' : errors.email }"
                                   placeholder="Enter email"/>

                                <span class="invalid-feedback">{{ errors.email }}</span>
                            </div>

                            <div class="form-group">

                                <label for="password">Password</label>

                                <Field name="password" type="password" class="form-control" id="password"
                                   :class="{'is-invalid' : errors.password }"
                                   placeholder="Enter password"/>

                                <span class="invalid-feedback">{{ errors.password }}</span>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>


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
    </div>
</template>

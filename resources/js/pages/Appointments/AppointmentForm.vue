<script setup>

    import {onMounted, reactive, ref} from "vue";

    import {useToastr} from "../../composables/useToastr.js";
    let toast = useToastr();

    import {useRoute, useRouter} from "vue-router";
    const router = useRouter();
    const route = useRoute();

    import { Form } from 'vee-validate';

    import flatpickr from "flatpickr";
    import "flatpickr/dist/themes/light.css"

    let form = reactive({
        title : "",
        description: "",
        client_id: "",
        start_time: "",
        end_time: ""
    });

    const isEditForm = ref(false);

    onMounted(()=>{

        if(route.name === 'admin.appointments.edit') {

            isEditForm.value = true;

            getAppointment();
        }

        flatpickr(".flatpickr", {
            enableTime: true,
            dateFormat: "Y-m-d h:m K",
            defaultHour: 10
        });

        getClients();
    });

    const handleSubmit = (values, actions) => {

        if(isEditForm.value) {

            editAppointment(values, actions);

        } else {

            createAppointment(values, actions);
        }
    }

    const createAppointment = (values, actions)=> {

        axios.post('/api/appointments/create',form).then(res=>{

            toast.success('Appointment created successfully!');

            router.push('/admin/appointments');

        }).catch(err=>{

            actions.setErrors(err.response.data.errors);
        });
    }

    const editAppointment = (values, actions)=> {

        axios.put(`/api/appointments/${route.params.id}/edit`,form).then(res=>{

            toast.success('Appointment updated successfully!');

            router.push('/admin/appointments');

        }).catch(err=>{

            actions.setErrors(err.response.data.errors);
        });
    }

    let clients = ref();

    const getClients = () =>{

        axios.get('/api/clients').then(res=>{

            clients.value = res.data;
        });
    }

    const getAppointment = () =>{

        axios.get(`/api/appointments/${route.params.id}/edit`).then(({ data })=>{

            form.title = data.title;

            form.description = data.description;

            form.client_id = data.client_id;

            form.start_time = data.formatted_start_time;

            form.end_time = data.formatted_end_time;
        })
    }

</script>

<template>

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0">{{ isEditForm ? 'Edit' : 'Create' }} Appointment</h1>
                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item">

                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>

                        <li class="breadcrumb-item">

                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>

                        <li class="breadcrumb-item active">{{ isEditForm ? 'Edit' : 'Create' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-12">

                    <div class="card card-light">

                        <div class="card-header">

                            <h3 class="card-title">{{ isEditForm ? 'Edit' : 'Create' }}</h3>
                        </div>

                        <Form @submit="handleSubmit" v-slot="{ errors }">

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="title">Title</label>

                                            <input type="text" v-model="form.title" class="form-control" :class="{'is-invalid' : errors.title }"
                                               id="title" placeholder="Enter Title">

                                            <span class="invalid-feedback">{{ errors.title }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="client">Client Name</label>

                                            <select id="client" v-model="form.client_id" class="form-control" :class="{ 'is-invalid' : errors.client_id }">

                                                <option v-for="client in clients" :value="client.id" :key="client.id">{{ client.name }}</option>
                                            </select>

                                            <span class="invalid-feedback">{{ errors.client_id }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="start-time">Start Time</label>

                                            <input type="text" v-model="form.start_time" class="form-control flatpickr" id="start-time"
                                                :class="{ 'is-invalid' : errors.start_time }">

                                            <span class="invalid-feedback">{{ errors.start_time }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="end-time">End Time</label>

                                            <input type="text" v-model="form.end_time" class="form-control flatpickr" id="end-time"
                                               :class="{ 'is-invalid' : errors.end_time }">

                                            <span class="invalid-feedback">{{ errors.end_time }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <label for="description">Description</label>

                                    <textarea class="form-control" id="description" rows="3"
                                        v-model="form.description" :class="{'is-invalid' : errors.title }"
                                        placeholder="Enter Description"></textarea>

                                    <span class="invalid-feedback">{{ errors.title }}</span>
                                </div>
                            </div>

                            <div class="card-footer">

                                <button type="submit" class="btn btn-primary">{{ isEditForm ? 'Update' : 'Submit' }}</button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

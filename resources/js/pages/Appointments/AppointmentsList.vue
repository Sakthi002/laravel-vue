<script setup>

    import {computed, onMounted, ref} from "vue";

    import {Bootstrap5Pagination} from "laravel-vue-pagination";

    import Swal from 'sweetalert2'

    const appointmentStatus = ref([]);

    let appointments = ref([]);

    let currentPage = ref(1);

    let currentStatus = ref('');

    const getAppointmentStatus = () =>{

        axios.get('/api/appointment-status').then(res=>{

            appointmentStatus.value = res.data;
        });
    }

    const getAppointments = (page = 1, status = "") => {

        currentStatus.value = status;

        let params = {};

        if(status) {

            params['status'] = status
        }

        axios.get('/api/appointments?page='+page, { params : params }).then(res=>{

           appointments.value = res.data;
        });
    }

    const deleteAppointment = (id) =>{

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {

                axios.delete(`/api/appointments/${id}/delete`).then(res=>{

                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });

                    getAppointmentStatus();

                    getAppointments(1, currentStatus.value)
                });
            }
        });
    }

    const onPagination = (page) =>{

        currentPage.value = page;

        getAppointments(page, currentStatus.value)
    }

    let totalAppointmentsCount = computed(()=>{

        return appointmentStatus.value.map(status => status.count).reduce((acc,value) => acc + value, 0);
    })

    onMounted(()=>{

        getAppointmentStatus();

        getAppointments();
    })
</script>

<template>

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0">Appointments</h1>
                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item">

                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>

                        <li class="breadcrumb-item active">Appointments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-12">

                    <div class="d-flex justify-content-between mb-2">

                        <div>

                            <router-link to="/admin/appointments/create">

                                <button class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add New
                                    Appointment
                                </button>
                            </router-link>
                        </div>

                        <div class="btn-group">

                            <button @click="getAppointments(1, '')" type="button" class="btn" :class="!currentStatus ? 'btn-secondary' : 'btn-default'">

                                <span class="mr-1">ALL</span>

                                <span class="badge badge-pill badge-info">{{ totalAppointmentsCount }}</span>
                            </button>

                            <button v-for="status in appointmentStatus" @click="getAppointments(1, status.value)" type="button"
                                class="btn" :class="status.value === currentStatus ? 'btn-secondary' : 'btn-default'">

                                <span class="mr-1">{{ status.name }}</span>

                                <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="card">

                        <div class="card-body">

                            <table class="table table-bordered">

                                <thead>

                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Options</th>
                                </tr>

                                </thead>

                                <tbody>

                                <tr v-for="(appointment,index) in appointments.data" :key="appointment.id">

                                    <td>{{ index + 1}}</td>

                                    <td>{{ appointment.client.first_name }} {{ appointment.client.last_name }}</td>

                                    <td>{{ appointment.start_time }}</td>

                                    <td>{{ appointment.end_time }}</td>

                                    <td>
                                        <span class="badge" :class="`badge-${appointment.status.color}`">{{ appointment.status.name }}</span>
                                    </td>

                                    <td>

                                        <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                            <i class="fa fa-edit mr-2"></i>
                                        </router-link>

                                        <a href="javascript:;" @click="deleteAppointment(appointment.id)">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <Bootstrap5Pagination class="mt-3" :data="appointments" @pagination-change-page="onPagination"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


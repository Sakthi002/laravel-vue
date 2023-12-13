<script setup>

    import {onMounted, ref} from "vue";

    let selectedStatus = ref('all');

    let selectedStatusCount = ref(0);

    const onStatusChange = ()=>{

        axios.get('/api/stats/appointments',{
            params: {
                status: selectedStatus.value
            }
        }).then(res=>{

            selectedStatusCount.value = res.data.count;
        })
    }

    let selectedDate = ref('today');

    let usersCount = ref(0);

    const onDateChange = ()=>{

        axios.get('/api/stats/users',{
            params: {
                date_range: selectedDate.value
            }
        }).then(res=>{

            usersCount.value = res.data.count;
        })
    }

    onMounted(()=>{

        onStatusChange();

        onDateChange();
    });
</script>

<template>

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0">Dashboard</h1>
                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">

                        <div class="inner">

                            <div class="d-flex justify-content-between">

                                <h3>{{ selectedStatusCount }}</h3>

                                <select v-model="selectedStatus" @change="onStatusChange" class="px-1 rounded border-0 appoint_select">
                                    <option value="all">All</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <p>Appointments</p>
                        </div>

                        <div class="icon">

                            <i class="ion ion-bag"></i>
                        </div>

                        <router-link to="/admin/appointments" class="small-box-footer">
                            View Appointments
                            <i class="fas fa-arrow-circle-right"></i>
                        </router-link>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">

                        <div class="inner">

                            <div class="d-flex justify-content-between">

                                <h3> {{ usersCount }}</h3>

                                <select v-model="selectedDate" @change="onDateChange" class="px-1 rounded border-0 appoint_select">
                                    <option value="today">Today</option>
                                    <option value="30_days">30 days</option>
                                    <option value="60_days">60 days</option>
                                    <option value="360_days">360 days</option>
                                    <option value="month_to_date">Month to Date</option>
                                    <option value=year_to_date>Year to Date</option>
                                </select>
                            </div>

                            <p>Users</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>

                        <router-link to="/admin/users" class="small-box-footer">
                            View Users
                            <i class="fas fa-arrow-circle-right"></i>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

    .appoint_select {
        height: 2rem; outline: 2px solid transparent;
    }
</style>

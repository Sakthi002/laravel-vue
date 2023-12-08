<script setup>

    import {reactive} from "vue";
    import {useToastr} from "../../composables/useToastr.js";
    let toast = useToastr();

    import {useRouter} from "vue-router";

    const router = useRouter();

    let form = reactive({
        title : "",
        description: "",
        client_id: "",
        start_date: "",
        start_time: ""
    });

    const handleSubmit = () => {

        axios.post('/api/appointments/create',form).then(res=>{

            toast.success('Appointment created successfully!');

            router.push('/admin/appointments');
        });
    }

</script>

<template>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Appointment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
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

                            <h3 class="card-title">Create</h3>
                        </div>
                        <form @submit.prevent="handleSubmit">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" v-model="form.title" class="form-control" id="title" placeholder="Enter Title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client Name</label>
                                            <select id="client" class="form-control">
                                                <option>Client One</option>
                                                <option>Client Two</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Appointment Date</label>
                                            <input type="date" class="form-control" id="date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time">Appointment Time</label>
                                            <input type="time" class="form-control" id="time">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" rows="3" v-model="form.description"
                                              placeholder="Enter Description"></textarea>
                                </div>
                            </div>

                            <div class="card-footer">

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

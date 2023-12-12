import Dashboard from "./components/Dashboard.vue";
import AppointmentsList from "./pages/Appointments/AppointmentsList.vue";
import UsersList from "./pages/Users/UsersList.vue";
import Settings from "./pages/Settings/Settings.vue";
import Profile from "./pages/Profile/Profile.vue";
import AppointmentForm from "./pages/Appointments/AppointmentForm.vue";

let routes = [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard
    },
    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: AppointmentsList
    },
    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm
    },
    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentForm
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: UsersList
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: Settings
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: Profile
    }
]

export default routes;

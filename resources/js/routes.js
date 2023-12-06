import Dashboard from "./components/Dashboard.vue";
import AnnouncementsList from "./pages/Announcements/List.vue";
import UsersList from "./pages/Users/UsersList.vue";
import Settings from "./pages/Settings/Settings.vue";
import Profile from "./pages/Profile/Profile.vue";

let routes = [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard
    },
    {
        path: '/admin/announcements',
        name: 'admin.announcements',
        component: AnnouncementsList
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

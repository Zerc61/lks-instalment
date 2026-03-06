import { createRouter, createWebHistory } from "vue-router";
import Login from "../pages/Login.vue";
import Dashboard from "@/pages/Dashboard.vue";
import Applications from "@/pages/Applications.vue";


const routes = [
    {
        path: '/',
        name: 'login',
        component: Login
    },

    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard
    },

    {
        path: '/applications',
        name: 'applications',
        component: Applications
    }
];




const router = createRouter({
    history: createWebHistory(),
    routes
});


export default router;
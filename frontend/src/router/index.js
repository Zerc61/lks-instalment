import { createRouter, createWebHistory } from "vue-router";
import Login from "../pages/Login.vue";
import Dashboard from "@/pages/Dashboard.vue";
import Applications from "@/pages/Applications.vue";
import CarDetail from "@/pages/CarDetail.vue";
import AdminApplications from "@/pages/admin/AdminApplications.vue";
import AdminDashboard from "@/pages/admin/AdminDashboard.vue";
import Validation from "@/pages/Validation.vue";
import AdminValidations from "@/pages/admin/AdminValidations.vue";


const routes = [
    {
        path: '/',
        name: 'login',
        component: Login
    },

    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },

    {
        path: '/applications',
        name: 'applications',
        component: Applications,
        meta: { requiresAuth: true }
    },

    {
        path: '/validations',
        name: 'validations',
        component: Validation,
        meta: { requiresAuth: true }
    },

    {
        path: '/cars/:id',
        name: 'cars-detail',
        component: CarDetail,
        meta: { requiresAuth: true }
    },

    {
        path: '/admin/applications',
        name: 'apllications-admin',
        component: AdminApplications,
        meta: { requiresAuth: true }
    },

    {
        path: '/admin/dashboard',
        name: 'dashboard-admin',
        component: AdminDashboard,
        meta: { requiresAuth: true }
    },

    {
     path: "/admin/validations",
     component: AdminValidations,
     meta: { requiresAuth: true }
}


];

const router = createRouter({
    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');

    if(to.meta.requiresAuth && !token) {
        next('/')
    } else (
        next()
    )
});






export default router;
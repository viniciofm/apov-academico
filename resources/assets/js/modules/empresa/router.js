import Vue from 'vue';
import VueRouter from "vue-router";
import List from "./components/List";
import Empresa from "./components/Empresa";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'empresa.create',
        component: Empresa,
    },
]

const router = new VueRouter({
    routes
});

export default router;

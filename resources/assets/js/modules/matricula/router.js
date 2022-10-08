import Vue from 'vue';
import VueRouter from "vue-router";
import Matricula from "./components/Matricula";
import List from "./components/List";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'matricula.create',
        component: Matricula,
    },
    {
        path: '/:matricula_id/editar',
        name: 'matricula.edit',
        component: Matricula,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

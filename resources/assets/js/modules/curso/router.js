import Vue from 'vue';
import VueRouter from "vue-router";
import List from "./components/List";
import Curso from "./components/Curso";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'curso.create',
        component: Curso,
    },
    {
        path: '/editar/:id',
        name: 'curso.edit',
        component: Curso,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

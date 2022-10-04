import Vue from 'vue';
import VueRouter from "vue-router";
import List from "./components/List";
import Curso from "./components/Usuario";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'usuario.create',
        component: Curso,
    },
    {
        path: '/editar/:id',
        name: 'usuario.edit',
        component: Curso,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

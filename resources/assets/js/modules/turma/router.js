import Vue from 'vue';
import VueRouter from "vue-router";
import Turma from "./components/Turma";
import List from "./components/List";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'turma.create',
        component: Turma,
    },
    {
        path: '/:turma_id/editar',
        name: 'turma.edit',
        component: Turma,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

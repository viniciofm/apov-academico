import Vue from 'vue';
import VueRouter from "vue-router";
import List from "./components/List";
import Aluno from "./components/Aluno";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'aluno.create',
        component: Aluno,
    },
    {
        path: '/editar/:id',
        name: 'aluno.edit',
        component: Aluno,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

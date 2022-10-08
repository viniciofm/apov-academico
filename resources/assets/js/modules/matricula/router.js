import Vue from 'vue';
import VueRouter from "vue-router";
import Matricula from "./components/Matricula";
import EditMatricula from "./components/EditMatricula";
import ListDisciplinasMatricula from "./components/ListDisciplinasMatricula";
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
        component: EditMatricula,
        props: true
    },
    {
        path: '/:matricula_id/disciplinas',
        name: 'matricula.disciplines',
        component: ListDisciplinasMatricula,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

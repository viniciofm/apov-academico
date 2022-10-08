import Vue from 'vue';
import VueRouter from "vue-router";
import Turma from "./components/Turma";
import List from "./components/List";
import ListTurmaDisciplina from "./components/ListTurmaDisciplina";
import ListTurmaDisciplinaAlunos from "./components/ListTurmaDisciplinaAlunos";
import ListTurmaAlunos from "./components/ListTurmaAlunos";

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
    {
        path: '/:turma_id/alunos',
        name: 'turma.alunos',
        component: ListTurmaAlunos,
        props: true
    },
    {
        path: '/:turma_id/disciplinas',
        name: 'turma.disciplines',
        component: ListTurmaDisciplina,
        props: true
    },
    {
        path: '/:turma_id/:turma_disciplina_id/alunos',
        name: 'turma.disciplines.alunos',
        component: ListTurmaDisciplinaAlunos,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

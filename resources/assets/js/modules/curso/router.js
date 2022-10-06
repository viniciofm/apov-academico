import Vue from 'vue';
import VueRouter from "vue-router";
import Curso from "./components/Curso";
import List from "./components/List";
import Grade from "./components/Grade";
import ListGrades from "./components/ListGrades";
import ListDisciplinas from "./components/ListDisciplinas";
import Disciplina from "./components/Disciplina";

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
        path: '/:id/editar',
        name: 'curso.edit',
        component: Curso,
        props: true
    },
    {
        path: '/:curso_id/grades',
        name: 'curso.grids',
        component: ListGrades,
        props: true
    },
    {
        path: '/:curso_id/grades/novo',
        name: 'curso.grids.create',
        component: Grade,
        props: true
    },
    {
        path: '/:curso_id/grades/:grade_id/editar',
        name: 'curso.grids.edit',
        component: Grade,
        props: true
    },
    {
        path: '/:curso_id/grades/:grade_id/disciplinas',
        name: 'curso.grids.disciplines',
        component: ListDisciplinas,
        props: true
    },
    {
        path: '/:curso_id/grades/:grade_id/disciplinas/novo',
        name: 'curso.grids.disciplines.create',
        component: Disciplina,
        props: true
    },
    {
        path: '/:curso_id/grades/:grade_id/disciplinas/:disciplina_id/editar',
        name: 'curso.grids.disciplines.edit',
        component: Disciplina,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

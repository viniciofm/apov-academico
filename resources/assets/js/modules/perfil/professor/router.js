import Vue from 'vue';
import VueRouter from "vue-router";
import MyDisciplines from "./components/MyDisciplines";
import ListClasses from "./components/ListClasses";
import Class from "./components/Class";
import ListActivities from "./components/ListActivities";
import Activity from "./components/Activity";
import Notes from "./components/Notes";
import Grades from "./components/Grades";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: MyDisciplines,
    },
    {
        path: '/aulas',
        name: 'professor.classes',
        component: ListClasses,
        props: true
    },
    {
        path: '/aulas/novo',
        name: 'professor.classes.create',
        component: Class,
        props: true
    },
    {
        path: '/aulas/editar/:id',
        name: 'professor.classes.edit',
        component: Class,
        props: true
    },
    {
        path: '/aulas/presencas',
        name: 'professor.classes.grades',
        component: Grades,
        props: true
    },
    {
        path: '/atividades',
        name: 'professor.activities',
        component: ListActivities,
        props: true
    },
    {
        path: '/atividades/novo',
        name: 'professor.activities.create',
        component: Activity,
        props: true
    },
    {
        path: '/atividades/editar/:id',
        name: 'professor.activities.edit',
        component: Activity,
        props: true
    },
    {
        path: '/atividades/notas/:id',
        name: 'professor.activities.notes',
        component: Notes,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

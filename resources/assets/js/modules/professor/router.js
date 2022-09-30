import Vue from 'vue';
import VueRouter from "vue-router";
import List from "./components/List";
import Professor from "./components/Professor";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List,
    },
    {
        path: '/novo',
        name: 'professor.create',
        component: Professor,
    },
    {
        path: '/editar/:id',
        name: 'professor.edit',
        component: Professor,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

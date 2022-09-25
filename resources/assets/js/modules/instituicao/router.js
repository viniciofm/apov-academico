import Vue from 'vue';
import VueRouter from "vue-router";
import Instituicao from "./components/Instituicao";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: Instituicao,
        props: true
    },
]

const router = new VueRouter({
    routes
});

export default router;

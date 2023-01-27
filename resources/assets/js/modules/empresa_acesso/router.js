import Vue from 'vue';
import VueRouter from "vue-router";
import Empresa from "../empresa/components/Empresa";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: Empresa,
        props: { 'empresa_acesso' : true, 'title' : 'Perfil' }
    },
]

const router = new VueRouter({
    routes
});

export default router;

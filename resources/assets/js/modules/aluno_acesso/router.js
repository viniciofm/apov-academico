import Vue from 'vue';
import VueRouter from "vue-router";
import List from "./components/List";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: List
    },
]

const router = new VueRouter({
    routes
});

export default router;

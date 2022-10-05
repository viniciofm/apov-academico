'use strict';

import router from './router';

require('../../bootstrap.js');
import Vue from 'vue';

window.Vue = Vue;

/* validate */
import VeeValidate, { Validator } from 'vee-validate';
import msgBR from '../../lang/vee-validate/pt_BR';
import Content from "../Content";
import Usuario from "./components/Usuario";
import EditarSenha from "./components/EditarSenha";

Validator.localize('pt_BR', msgBR);

Vue.use(VeeValidate, {
    locale: 'pt_BR',
    useConstraintAttrs: false
});

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask);

Vue.component('pagination', require('laravel-vue-pagination'))

if (document.querySelector('#component-content-usuario')) {
    new Vue({
        router,
        render: h => h(Content)
    }).$mount('#component-content-usuario');
}

if (document.querySelector('#component-content-usuario-edit')) {
    Vue.component('Usuario', Usuario);

    new Vue({
        el: '#component-content-usuario-edit'
    });
}

if (document.querySelector('#component-content-senha')) {
    Vue.component('EditarSenha', EditarSenha);

    new Vue({
        el: '#component-content-senha'
    });
}

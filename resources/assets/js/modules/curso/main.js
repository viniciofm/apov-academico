'use strict';

import Acl from '../../common/acl';
import router from './router';

require('../../bootstrap.js');
import Vue from 'vue';

window.Vue = Vue;

/* validate */
import VeeValidate, { Validator } from 'vee-validate';
import msgBR from '../../lang/vee-validate/pt_BR';
import Content from "../Content";

Validator.localize('pt_BR', msgBR);

/* acl */
Vue.use(Acl)

Vue.use(VeeValidate, {
    locale: 'pt_BR',
    useConstraintAttrs: false
});

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask);

Vue.component('pagination', require('laravel-vue-pagination'))

if (document.querySelector('#component-content-curso')) {
    new Vue({
        router,
        render: h => h(Content)
    }).$mount('#component-content-curso');
}

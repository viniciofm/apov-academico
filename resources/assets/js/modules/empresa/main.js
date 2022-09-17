import Vue from 'vue';
import moment from 'moment';
// import router from './router';
import Component from './Test';
import VeeValidate, {Validator} from 'vee-validate'
import msgBR from '../../lang/vee-validate/pt_BR'
import VueCurrencyFilter from "vue-currency-filter";

moment.locale('pt-BR');

Vue.filter('dateFormat', (date) => {
    return moment(date).format('DD/MM/YYYY')
});

Vue.component('pagination', () => import('laravel-vue-pagination'));

Validator.localize('pt_BR', msgBR);

Vue.use(VeeValidate, {
    locale: 'pt_BR'
});

Vue.use(VueCurrencyFilter,
    {
        symbol: 'R$',
        thousandsSeparator: '.',
        fractionCount: 2,
        fractionSeparator: ',',
        symbolPosition: 'front',
        symbolSpacing: true
    }
);

Vue.component('main-teste', Component);

new Vue({
    el: '#vue-empresa',
    name: 'empresa',
    components: {
        'vue-empresa': Component
    },
});

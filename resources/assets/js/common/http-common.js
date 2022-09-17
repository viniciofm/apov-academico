window._ = require('lodash');
import axios from 'axios';

let token = document.head.querySelector('meta[name="csrf-token"]');

export const HTTP = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': token.content
    }
})
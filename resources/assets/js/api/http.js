import axios from 'axios'

const token = document.head.querySelector('meta[name="csrf-token"]')
axios.defaults.withCredentials = true;
export default axios.create({
    defaults: {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token.content
        }
    }
})


<template>
    <router-view @showError="showError" @showMessage="showMessage"></router-view>
</template>

<script>
import Swal from "sweetalert2";

export default {
    name: "Content",
    components: {},
    data: () => ({}),
    async created() {},
    methods: {
        showError(error) {
            console.log(error)
            var errors = '';
            if (!error.response.data.errors && error.response.data.message != null)
                errors += error.response.data.message;
            else{
                const keys = Object.keys(error.response.data.errors);
                keys.forEach(function(element) {
                    const keysItens = Object.keys(error.response.data.errors[element]);
                    keysItens.forEach(function(item) {
                        errors += error.response.data.errors[element][item] +' ';
                    });
                });
            }
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errors
            })
        },
        showMessage(message) {
            Swal.fire({
                icon: 'success',
                title: 'Tudo certo',
                text: message,
                timer: 2000
            })
        }
    }
}
</script>

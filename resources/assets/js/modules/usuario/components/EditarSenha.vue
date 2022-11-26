<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :header-title="'Atualização de Senha Pessoal'" :links="subHeaderLinks" :module="'Usuários'" :title="'Atualização'"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ 'Atualização' }} de Usuário</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Senha do Usuário</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Senha*</label>
                            <Password
                                :class="(errors.has('password') ? 'text-danger' : '')"
                                :style="'max-width: 100%'"
                                v-model="payload.password"
                                :toggle="true"
                                placeholder="Senha de acesso"
                                id="password" name="password"
                                v-validate="'required|min:6'"
                                :data-vv-as="'Senha'"
                            />
                            <div v-show="errors.has('password')" class="text-danger" style="">{{ errors.first('password') }}</div>
                        </div>
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="confirm_password">Confirmação de Senha*</label>
                            <Password
                                :class="(errors.has('confirm_password') ? 'text-danger' : '')"
                                :style="'max-width: 100%'"
                                v-model="payload.confirm_password"
                                :toggle="true"
                                placeholder="Senha de acesso"
                                id="confirm_password" name="confirm_password"
                                v-validate="'required|min:6'"
                                :data-vv-as="'Confirmação de Senha'"
                            />
                            <div v-if="errors.has('confirm_password')" v-show="errors.has('confirm_password')" class="text-danger" style="">{{ errors.first('confirm_password') }}</div>
                            <div v-else-if="payload.password != payload.confirm_password" class="text-danger" style="">Confirmação de senha diferente da senha informada</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="text-right">
                                <button @click="routeHome"
                                        class="btn btn-warning mr-2">
                                    <span>Voltar</span>
                                </button>
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ 'Atualizar' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>
import {submit, toSeek} from "../../../common/send-form";
import http from '../../../api/http'
import SubHeader from "../../../components/SubHeader"
import Swal from "sweetalert2";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';
import Password from "vue-password-strength-meter";
import Endereco from "../../../components/Endereco";

export default {
    name: "Usuario",
    data: () => ({
        subHeaderLinks:[],
        routeHome: route('home'),
        search: '',
        dataPaginate: {},
        payload:{
            'id': '',
            'password': '',
            'confirm_password': ''
        },
        isLoading: false,
        generos: {},
    }),
    props: [
        'id',
        'title',
        'editUser'
    ],
    created() {
        this.getData(true);
    },
    components: {
        SubHeader,
        Loading,
        Endereco,
        Password
    },
    methods: {
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this
                        if(me.payload.password != me.payload.confirm_password){
                            Swal.fire(
                                'Oops...',
                                'Para continuar você deve antes resolver os erros presentes no formulário.',
                                'error'
                            )
                            return
                        }

                        let formData = new FormData();

                        formData.append('id', this.payload.id);
                        formData.append('password', this.payload.password);
                        formData.append('confirm_password', this.payload.confirm_password);

                        let url = route('usuario.update-password');
                        me.isLoading = true;

                        http.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(data => {
                            Swal.fire(
                                'Sucesso!',
                                data.data.message,
                                'success'
                            )
                            setTimeout(function(){
                                me.isLoading = false;
                                window.location.href = me.routeHome;
                            }, 1000);
                        }).catch(error => {
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
                            me.isLoading = false;
                        });
                    }else{
                        Swal.fire(
                            'Oops...',
                            'Para continuar você deve antes resolver os erros presentes no formulário.',
                            'error'
                        )
                    }
                })
        },
        getData(editUser) {
            this.isLoading = true;
            let url = route('admin.usuario.edit-user')
            submit(url, {},'GET').then(
                data => {
                    data.registro.password = '';
                    data.registro.confirm_password = '';
                    this.payload = data.registro;
                }
            ).then(() => {
                this.isLoading = false;
            }).catch(error => {
                Swal.fire(
                    'Erro!',
                    'Encontramos um erro ao consultar os dados!',
                    'error'
                )
                this.isLoading = false;
            });
        }
    }
}
</script>

<style scoped>
.tableDiv {
    max-width: 100%;
    height: auto;
    overflow: auto;
}
</style>

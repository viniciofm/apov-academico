<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :header-title="'Atualização de Dados Pessoais'" :links="editUser ? [] : subHeaderLinks" :module="'Usuários'" :title="title ? title : ((id || editUser) ? 'Atualização' : 'Cadastro')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ ((id || editUser) ? 'Atualização' : 'Cadastro') }} de Usuário</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados do Usuário</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Nome*</label>
                            <input class="form-control" v-model="payload.nome" id="nome" name="nome" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Nome'">
                            <div v-show="errors.has('nome')" class="text-danger" style="">{{ errors.first('nome') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="email">E-mail*</label>
                            <input class="form-control" v-model="payload.email" id="email" name="email" maxlength="150" value="" type="email" placeholder="" required="required"
                                   v-validate="'required|email'"
                                   data-vv-as="'Email'">
                            <div v-show="errors.has('email')" class="text-danger" style="">{{ errors.first('email') }}</div>
                        </div>

                        <div v-if="(id && payload.tipo_usuario && payload.tipo_usuario.nome == 'admin') || !id" class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="tipo_documento">Tipo do Documento*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.tipo_documento" name="tipo_documento" id="tipo_documento" required="required"
                                    v-validate="'required'"
                                    data-vv-as="'Tipo do Documento'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option value="cnpj">CNPJ</option>
                                <option value="cpf">CPF</option>
                            </select>
                            <div v-show="errors.has('tipo_documento')" class="text-danger" style="">{{ errors.first('tipo_documento') }}</div>
                        </div>

                        <div v-else class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="tipo_documento">Tipo do Documento*</label>
                            <label class="form-control">{{ payload.tipo_documento == 'cpf' ? 'CPF' : 'CPNJ'}}</label>
                        </div>

                        <div v-if="(id && payload.tipo_usuario && payload.tipo_usuario.nome == 'admin') || !id" class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="cpf_cnpj">CNPJ/CPF*</label>
                            <input class="form-control" v-mask="payload.tipo_documento == 'cnpj' ? '##.###.###/####-##' : '###.###.###-##'"  v-model="payload.cpf_cnpj" id="cpf_cnpj" name="cpf_cnpj" maxlength="20" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'CNPJ/CPF'">
                            <div v-show="errors.has('cpf_cnpj')" class="text-danger" style="">{{ errors.first('cpf_cnpj') }}</div>
                        </div>

                        <div v-else class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="tipo_documento">Tipo do Documento*</label>
                            <label class="form-control">{{ payload.cpf_cnpj }}</label>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-12">
                            <label for="genero_id">Gênero*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.genero_id" name="genero_id" id="genero_id"
                                    v-validate="'required'"
                                    data-vv-as="'Gênero'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="genero in generos" :value="genero.id">{{genero.nome}}</option>
                            </select>
                            <div v-show="errors.has('genero_id')" class="text-danger" style="">{{ errors.first('genero_id') }}</div>
                        </div>

                        <div v-if="!editUser" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Senha*</label>
                            <Password
                                :class="(errors.has('password') ? 'text-danger' : '')"
                                :style="'max-width: 100%'"
                                v-model="payload.password"
                                :toggle="true"
                                placeholder="Senha de acesso"
                                id="password" name="password"
                                v-validate="(!payload.password && (id || editUser))?'':'required|min:6'"
                                :data-vv-as="'Senha'"
                            />
                            <div v-show="errors.has('password')" class="text-danger" style="">{{ errors.first('password') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <endereco v-if="(editUser && payload.tipo_usuario && payload.tipo_usuario.nome != 'aluno') || !editUser" :endereco="payload.endereco"></endereco>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="text-right">
                                <button @click="!editUser ? $router.go(-1) : routeHome"
                                        class="btn btn-warning mr-2">
                                    <span>Voltar</span>
                                </button>
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ (id || editUser) ? 'Atualizar' : 'Salvar' }}</span>
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
        subHeaderLinks:[['/', 'Usuários']],
        routeHome: route('home'),
        search: '',
        dataPaginate: {},
        payload:{
            'id': '',
            'nome': '',
            'email': '',
            'cpf_cnpj': '',
            'tipo_documento': '',
            'genero_id': '',
            'password': '',
            'blocked': false,
            'endereco': {
                'id': null,
                'rua': '',
                'numero': '',
                'bairro': '',
                'complemento': '',
                'cep': '',
                'cidade_id': '',
                'estado_id': ''
            },
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
        this.getGenders();
        if (this.id || this.editUser) {
            this.getData(this.editUser ? true : false);
        }
    },
    components: {
        SubHeader,
        Loading,
        Endereco,
        Password
    },
    methods: {
        getGenders(){
            this.isLoading = true;
            toSeek(route('api.generos.get')).then(
                data => {
                    this.generos = data;
                }
            ).then(() => {
                this.isLoading = false
            }).catch(error => {
                Swal.fire(
                    'Erro!',
                    'Encontramos um erro ao consultar os dados!',
                    'error'
                )
                me.isLoading = false;
            });
        },
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let formData = new FormData();

                        formData.append('nome', this.payload.nome);
                        formData.append('email', this.payload.email);
                        formData.append('tipo_documento', this.payload.tipo_documento);
                        formData.append('cpf_cnpj', this.payload.cpf_cnpj);
                        formData.append('genero_id', this.payload.genero_id);
                        if(this.payload.password){
                            formData.append('password', this.payload.password);
                        }
                        if(!this.id){
                            formData.append('blocked', this.payload.blocked ? 1 : 0);
                        }
                        formData.append('endereco' , JSON.stringify(this.payload.endereco));

                        let userId = (me.id || me.editUser) ? (me.id ? me.id : me.payload.id) : null;

                        let url = route(userId ? 'admin.usuario.update' : 'admin.usuario.store', userId);
                        me.loading = true;

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
                                me.loading = false;
                                if(me.editUser){
                                    window.location.href = me.routeHome;
                                }else{
                                    me.$router.push({ path: `/` });
                                }
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
                            me.loading = false;
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
            let url = (editUser ? route(('admin.usuario.edit-user')) : route(('admin.usuario.edit'), this.id) )
            submit(url, {},'GET').then(
                data => {
                    data.registro.password = '';
                    if(!data.registro.endereco){
                        data.registro.endereco = {
                            'id': null,
                            'rua': '',
                            'numero': '',
                            'bairro': '',
                            'complemento': '',
                            'cep': '',
                            'cidade_id': '',
                            'estado_id': ''
                        };
                    }
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

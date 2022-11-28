<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Professores'" :title="title ? title : (id ? 'Atualização' : 'Cadastro')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (id ? 'Atualização' : 'Cadastro') }} de Professor</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados do Professor</h5>
                    <div class="row">
                        <div v-if="id" class="mb-3 col-lg-2 col-md-2 col-sm-12">
                            <label for="matricula">Matrícula*</label>
                            <label class="form-control" id="matricula" name="matricula">{{ payload.matricula ? payload.matricula : '-' }}</label>
                        </div>
                        <div v-if="id" class="mb-3 col-lg-10 col-md-10 col-sm-12"></div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Nome*</label>
                            <input class="form-control" v-model="payload.usuario.nome" id="nome" name="nome" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Nome'">
                            <div v-show="errors.has('nome')" class="text-danger" style="">{{ errors.first('nome') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="formacao">Formação*</label>
                            <input class="form-control" v-model="payload.formacao" id="formacao" name="formacao" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Formação'">
                            <div v-show="errors.has('formacao')" class="text-danger" style="">{{ errors.first('formacao') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="email">E-mail*</label>
                            <input class="form-control" v-model="payload.usuario.email" id="email" name="email" maxlength="150" value="" type="email" placeholder="" required="required"
                                   v-validate="'required|email'"
                                   data-vv-as="'Email'">
                            <div v-show="errors.has('email')" class="text-danger" style="">{{ errors.first('email') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="tipo_documento">Tipo do Documento*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.usuario.tipo_documento" name="tipo_documento" id="tipo_documento" required="required"
                                    v-validate="'required'"
                                    data-vv-as="'Tipo do Documento'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option value="cnpj">CNPJ</option>
                                <option value="cpf">CPF</option>
                            </select>
                            <div v-show="errors.has('tipo_documento')" class="text-danger" style="">{{ errors.first('tipo_documento') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="cpf_cnpj">CNPJ/CPF*</label>
                            <input class="form-control" v-mask="payload.usuario.tipo_documento == 'cnpj' ? '##.###.###/####-##' : '###.###.###-##'"  v-model="payload.usuario.cpf_cnpj" id="cpf_cnpj" name="cpf_cnpj" maxlength="20" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'CNPJ/CPF'">
                            <div v-show="errors.has('cpf_cnpj')" class="text-danger" style="">{{ errors.first('cpf_cnpj') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-12">
                            <label for="genero_id">Gênero*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.usuario.genero_id" name="genero_id" id="genero_id"
                                    v-validate="'required'"
                                    data-vv-as="'Gênero'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="genero in generos" :value="genero.id">{{genero.nome}}</option>
                            </select>
                            <div v-show="errors.has('genero_id')" class="text-danger" style="">{{ errors.first('genero_id') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-12">
                            <label for="ativo">Ativo</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" id="ativo" v-model="payload.ativo">
                                <label class="form-check-label" for="ativo">{{payload.ativo ? 'Sim' : 'Não'}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <endereco :endereco="payload.endereco"></endereco>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="text-right">
                                <button @click="$router.go(-1)"
                                        class="btn btn-warning mr-2">
                                    <span>Voltar</span>
                                </button>
                                <button v-can="'can-update'" @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ id ? 'Atualizar' : 'Salvar' }}</span>
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
import Endereco from "../../../components/Endereco"
import Swal from "sweetalert2";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "Professor",
    data: () => ({
        subHeaderLinks:[['/', 'Professores']],
        search: '',
        dataPaginate: {},
        payload:{
            'usuario' : {
                'nome': '',
                'email': '',
                'cpf_cnpj': '',
                'tipo_documento': '',
                'genero_id': '',
            },
            'user_id': '',
            'formacao': '',
            'ativo': true,
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
            old_logomarca: ''
        },
        generos: {},
        isLoading: false,
    }),
    props: [
        'id',
        'title'
    ],
    created() {
        this.getGenders();
        if (this.id) {
            this.getData();
        }
    },
    components: {
        SubHeader,
        Endereco,
        Loading
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

                        formData.append('usuario', JSON.stringify(this.payload.usuario));
                        formData.append('user_id', this.payload.user_id);
                        formData.append('formacao', this.payload.formacao);
                        formData.append('ativo', this.payload.ativo ? 1 : 0);
                        formData.append('endereco' , JSON.stringify(this.payload.endereco));

                        let url = route(me.id ? 'admin.professor.update' : 'admin.professor.store', me.id);
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
                                me.$router.push({ path: `/` });
                            }, 1000);
                        }).catch(error => {
                            this.$emit('showError', error)
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
        getData() {
            this.isLoading = true;
            submit(route('admin.professor.edit', this.id), {},'GET').then(
                data => {
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

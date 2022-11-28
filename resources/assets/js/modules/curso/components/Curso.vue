<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Cursos'" :title="title ? title : (id ? 'Atualização' : 'Cadastro')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (id ? 'Atualização' : 'Cadastro') }} de Curso</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados do Curso</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="sigla">Sigla*</label>
                            <input class="form-control" v-model="payload.sigla" id="sigla" name="sigla" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|max:3|alpha'"
                                   data-vv-as="'Sigla'">
                            <div v-show="errors.has('sigla')" class="text-danger" style="">{{ errors.first('sigla') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Nome*</label>
                            <input class="form-control" v-model="payload.nome" id="nome" name="nome" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Nome'">
                            <div v-show="errors.has('nome')" class="text-danger" style="">{{ errors.first('nome') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Nº CNAP</label>
                            <input class="form-control" v-model="payload.cnap" id="cnap" name="cnap" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="''"
                                   data-vv-as="'Nº CNAP'">
                            <div v-show="errors.has('cnap')" class="text-danger" style="">{{ errors.first('cnap') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="cbo">CBO*</label>
                            <multiselect v-model="payload.cbo"
                                         :options="cbos"
                                         :searchable="true"
                                         :label="'nome'"
                                         placeholder="Selecione uma opção"
                                         deselect-label="Desmarcar"
                                         select-label="Selecionar"
                                         selected-label="Selecionado"
                                         v-validate="''"
                                         data-vv-as="'CBO"
                                         name="cbo"
                                         id="cbo"
                                         track-by="id">
                                <span slot="noResult">Nenhum valor encontrado. Considere mudar a pesquisa.</span>
                                <span slot="noOptions">A lista está vazia.</span>
                            </multiselect>
                            <div v-show="errors.has('cbo')" class="text-danger" style="">{{ errors.first('cbo') }}</div>
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
import Swal from "sweetalert2";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';
import Multiselect from 'vue-multiselect'

export default {
    name: "Curso",
    data: () => ({
        subHeaderLinks:[['/', 'Cursos']],
        search: '',
        dataPaginate: {},
        payload:{
            'sigla': '',
            'nome': '',
            'ativo': true,
            'cbo': '',
        },
        cbos: [],
        isLoading: false,
    }),
    props: [
        'id',
        'title'
    ],
    created() {
        this.getCBOs();
        if (this.id) {
            this.getData();
        }
    },
    components: {
        SubHeader,
        Loading,
        Multiselect
    },
    methods: {
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let formData = new FormData();

                        formData.append('sigla', this.payload.sigla);
                        formData.append('nome', this.payload.nome);
                        formData.append('ativo', this.payload.ativo ? 1 : 0);
                        formData.append('cbo_id', this.payload.cbo ? this.payload.cbo.id : '');
                        formData.append('cnap', this.payload.cnap ? this.payload.cnap : '');

                        let url = route(me.id ? 'admin.curso.update' : 'admin.curso.store', me.id);
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
        getCBOs(){
            this.isLoading = true;
            submit(route('admin.curso.allCbo'), {}, 'GET').then(
                data => {
                    this.cbos = data;
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
        },
        getData() {
            this.isLoading = true;
            submit(route('admin.curso.edit', this.id), {},'GET').then(
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

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

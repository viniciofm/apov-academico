<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Turmas'" :title="title ? title : ((turma_id ? 'Atualização' : 'Cadastro') + ' de Turma')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (turma_id ? 'Atualização' : 'Cadastro') }} de Turma</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Turma</h5>
                    <div class="row">
                        <div v-if="!turma_id" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="curso_id">Curso*</label>
                            <select class="form-control" v-on:change="updateGrade()" data-bs-toggle="select2" v-model="curso_id" name="curso_id" id="curso_id"
                                    v-validate="'required'"
                                    data-vv-as="'Curso'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(curso) in cursos" :value="curso.id">{{curso.sigla}} - {{curso.nome}}</option>
                            </select>
                            <div v-show="errors.has('curso_id')" class="text-danger" style="">{{ errors.first('curso_id') }}</div>
                        </div>

                        <div v-if="!turma_id" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="grade_id">Grade*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.grade_id" name="grade_id" id="curso_id"
                                    v-validate="'required'"
                                    data-vv-as="'Grade'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(grade) in grades" :value="grade.id">{{ grade.codigo }}</option>
                            </select>
                            <div v-show="errors.has('grade_id')" class="text-danger" style="">{{ errors.first('grade_id') }}</div>
                        </div>

                        <div v-if="turma_id && payload.grade" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Curso</label>
                            <label class="form-control">{{ payload.grade.curso.sigla }} / {{ payload.grade.curso.nome }}</label>
                        </div>

                        <div v-if="turma_id && payload.grade" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Grade</label>
                            <label class="form-control">{{ payload.grade.codigo }}</label>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="sigla">Código*</label>
                            <input class="form-control" v-model="payload.codigo" id="codigo" name="codigo" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|alpha_dash|max:15'"
                                   data-vv-as="'Código'">
                            <div v-show="errors.has('codigo')" class="text-danger" style="">{{ errors.first('codigo') }}</div>
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
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ turma_id ? 'Atualizar' : 'Salvar' }}</span>
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

export default {
    name: "Turma",
    data: () => ({
        search: '',
        dataPaginate: {},
        payload:{
            'codigo': '',
            'grade_id': ''
        },
        isLoading: false,
        curso_id: '',
        cursos: {},
        grades: {},
    }),
    props: [
        'turma_id',
        'title'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Turmas']];
        }
    },
    created() {
        if (this.turma_id) {
            this.getData();
        }else{
            this.getCursos();
        }
    },
    components: {
        SubHeader,
        Loading
    },
    methods: {
        updateGrade(){
            this.isLoading = true;
            submit(route('admin.curso.grade.all', this.curso_id), {}, 'GET').then(
                data => {
                    this.grades = data;
                    this.payload.grade_id = '';
                }
            ).then(() => {
                this.isLoading = false;
            }).catch(error => {
                this.grades = {};
                Swal.fire(
                    'Erro!',
                    'Encontramos um erro ao consultar os dados!',
                    'error'
                )
                this.isLoading = false;
            });
        },
        getCursos(){
            this.isLoading = true;
            submit(route('admin.curso.all'), {}, 'GET').then(
                data => {
                    this.cursos = data;
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
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let formData = new FormData();

                        formData.append('grade_id', this.payload.grade_id);
                        formData.append('codigo', this.payload.codigo);

                        let url = route(me.turma_id ? 'admin.turma.update' : 'admin.turma.store', me.turma_id);
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
                                me.$router.push({path: `/`});
                            }, 1000);
                        }).catch(error => {
                            this.$emit('showError', error)
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
        getData() {
            this.isLoading = true;
            submit(route('admin.turma.edit', this.turma_id), {},'GET').then(
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

<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Matrículas'" :title="title ? title : (('Cadastro') + ' de Disciplina em Matrícula')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ ('Cadastro') }} de Disciplina em Matrícula</h5>
                </div>
                <div class="card-body" v-if="payload.matricula">
                    <h5 class="mb-0">Dados de Cadastro</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="aluno">Aluno*</label>
                            <label class="form-control">{{ payload.matricula.aluno.usuario.nome }}</label>
                        </div>

                        <div v-if="payload.matricula" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="curso_id">Curso*</label>
                            <select class="form-control" v-on:change="updateGrade()" data-bs-toggle="select2" v-model="payload.curso_id" name="curso_id" id="curso_id"
                                    v-validate="'required'"
                                    data-vv-as="'Curso'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(curso) in cursos" :value="curso.id">{{curso.sigla}} - {{curso.nome}}</option>
                            </select>
                            <div v-show="errors.has('curso_id')" class="text-danger" style="">{{ errors.first('curso_id') }}</div>
                        </div>

                        <div v-if="payload.matricula && payload.curso_id" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="grade_id">Grade*</label>
                            <select class="form-control" v-on:change="updateTurma()" data-bs-toggle="select2" v-model="payload.grade_id" name="grade_id" id="grade_id"
                                    v-validate="'required'"
                                    data-vv-as="'Grade'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(grade) in grades" :value="grade.id">{{ grade.codigo }}</option>
                            </select>
                            <div v-show="errors.has('grade_id')" class="text-danger" style="">{{ errors.first('grade_id') }}</div>
                        </div>

                        <div v-if="payload.grade_id" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="turma_id">Turma*</label>
                            <select class="form-control" v-on:change="updateDisciplinas()" data-bs-toggle="select2" v-model="payload.turma_id" name="turma_id" id="turma_id"
                                    v-validate="'required'"
                                    data-vv-as="'Turma'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(turma) in turmas" :value="turma.id">{{ turma.codigo }}</option>
                            </select>
                            <div v-show="errors.has('turma_id')" class="text-danger" style="">{{ errors.first('turma_id') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3" v-if="payload.grade_id">
                <div class="card-header">
                    <h5 class="mb-2">Disciplinas</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-1">Obs.: Habilite apenas as disciplinas a serem cursadas pelo aluno.</h6>
                    <div class="row">
                        <div v-for="disciplina in disciplinas" class="mb-3 col-lg-4 col-md-4 col-sm-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" :id="'disciplina_selected_' + disciplina.id" :value="disciplina.selected" v-model="disciplina.selected" checked="">
                                <label class="form-check-label" :for="'disciplina_selected_' + disciplina.id">{{ disciplina.sigla }} / {{ disciplina.nome }}</label>
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
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ 'Salvar' }}</span>
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
    name: "Matricula",
    data: () => ({
        search: '',
        dataPaginate: {},
        payload:{
            'matricula': '',
            'grade_id': '',
            'turma_id': '',
            'curso_id': ''
        },
        isLoading: false,
        empresas: [],
        cursos: {},
        grades: {},
        turmas: {},
        disciplinas: {},
    }),
    props: [
        'matricula_id',
        'title',
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Matrículas'], ['/'+this.matricula_id+'/disciplinas','Disciplinas']];
        }
    },
    created() {
        this.getMatricula();
        this.getCursos();
    },
    components: {
        SubHeader,
        Loading,
        Multiselect
    },
    methods: {
        customLabelAluno({ usuario }) {
            return `${usuario.nome}`
        },
        getMatricula(){
            this.isLoading = true;
            submit(route('admin.matricula.get-by-id', this.matricula_id), {}, 'GET').then(
                data => {
                    this.payload.matricula = data.registro;
                    this.payload.empresa = data.registro.empresa ? data.registro.empresa : [];
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
        updateDisciplinas(){
            this.isLoading = true;
            submit(route('admin.curso.grade.disciplina.all-by-turma', this.payload.turma_id), {}, 'GET').then(
                data => {
                    this.disciplinas = data;
                    this.disciplinas = this.disciplinas.map(item => ({...item, ...{selected: false}}));
                }
            ).then(() => {
                this.isLoading = false;
            }).catch(error => {
                this.disciplinas = {}
                Swal.fire(
                    'Erro!',
                    'Encontramos um erro ao consultar os dados!',
                    'error'
                )
                this.isLoading = false;
            });
        },
        updateGrade(){
            this.isLoading = true;
            submit(route('admin.curso.grade.all', this.payload.curso_id), {}, 'GET').then(
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
        updateTurma(){
            this.isLoading = true;
            submit(route('admin.turma.all-by-grade', this.payload.grade_id), {}, 'GET').then(
                data => {
                    this.turmas = data;
                    this.payload.turma_id = '';
                }
            ).then(() => {
                this.isLoading = false;
            }).catch(error => {
                this.turmas = {};
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

                        let selectedDisciplinas = this.disciplinas.filter( function(item) {
                            return item.selected
                        })
                        selectedDisciplinas = selectedDisciplinas.map(item => item.id)

                        if (selectedDisciplinas.length == 0){
                            Swal.fire(
                                'Oops...',
                                'Para continuar você deve antes selecionar ao menos uma disciplina.',
                                'error'
                            )
                            return;
                        }

                        formData.append('matricula_id', this.matricula_id);
                        formData.append('turma_id', this.payload.turma_id);
                        formData.append('disciplinas', JSON.stringify(selectedDisciplinas));

                        let url = route('admin.matricula.store-disciplinas');
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
                                me.$router.push({path: `/`});
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

<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Matrículas'" :title="title ? title : (('Cadastro') + ' de Matrícula')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ ('Cadastro') }} de Matrícula</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Matrícula</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="aluno">Aluno*</label>
                            <multiselect v-model="payload.aluno"
                                         :options="alunos"
                                         :searchable="true"
                                         :custom-label="customLabelAluno"
                                         placeholder="Selecione uma opção"
                                         deselect-label="Desmarcar"
                                         select-label="Selecionar"
                                         selected-label="Selecionado"
                                         v-validate="'required'"
                                         data-vv-as="'Aluno"
                                         name="aluno"
                                         id="aluno"
                                         track-by="id">
                                <span slot="noResult">Nenhum valor encontrado. Considere mudar a pesquisa.</span>
                                <span slot="noOptions">A lista está vazia.</span>
                            </multiselect>
                            <div v-show="errors.has('aluno')" class="text-danger" style="">{{ errors.first('aluno') }}</div>
                        </div>
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="empresa">Empresa</label>
                            <multiselect v-model="payload.empresa"
                                         :options="empresas"
                                         :searchable="true"
                                         :label="'nome'"
                                         placeholder="Selecione uma opção"
                                         deselect-label="Desmarcar"
                                         select-label="Selecionar"
                                         selected-label="Selecionado"
                                         v-validate="''"
                                         data-vv-as="'Empresa"
                                         name="empresa"
                                         id="empresa"
                                         track-by="id">
                                <span slot="noResult">Nenhum valor encontrado. Considere mudar a pesquisa.</span>
                                <span slot="noOptions">A lista está vazia.</span>
                            </multiselect>
                            <div v-show="errors.has('empresa')" class="text-danger" style="">{{ errors.first('empresa') }}</div>
                        </div>

                        <div v-if="payload.aluno" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="curso_id">Curso*</label>
                            <select class="form-control" v-on:change="updateGrade()" data-bs-toggle="select2" v-model="payload.curso_id" name="curso_id" id="curso_id"
                                    v-validate="'required'"
                                    data-vv-as="'Curso'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(curso) in cursos" :value="curso.id">{{curso.sigla}} - {{curso.nome}}</option>
                            </select>
                            <div v-show="errors.has('curso_id')" class="text-danger" style="">{{ errors.first('curso_id') }}</div>
                        </div>

                        <div v-if="payload.aluno && payload.curso_id" class="mb-3 col-lg-6 col-md-6 col-sm-12">
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

                        <div v-if="payload.aluno && payload.curso_id" class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="status">Status</label>
                            <label class="form-control">Matrículado</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3"v-if="payload.grade_id">
                <div class="card-header">
                    <h5 class="mb-2">Disciplinas</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-1">Obs.: Habilite apenas as disciplinas a serem cursadas pelo aluno.</h6>
                    <div class="row">
                        <div v-for="disciplina in disciplinas" class="mb-3 col-lg-4 col-md-4 col-sm-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" :id="'disciplina_selected_' + disciplina.id" :value="disciplina.selected" v-model="disciplina.selected" id="flexSwitchCheckChecked" checked="">
                                <label class="form-check-label" for="flexSwitchCheckChecked">{{ disciplina.sigla }} / {{ disciplina.nome }}</label>
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
            'aluno': '',
            'empresa': '',
            'grade_id': '',
            'turma_id': '',
            'curso_id': ''
        },
        isLoading: false,
        alunos: [],
        empresas: [],
        cursos: {},
        grades: {},
        turmas: {},
        disciplinas: {},
    }),
    props: [
        'title'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Turmas']];
        }
    },
    created() {
        this.getAlunos();
        this.getCursos();
        this.getEmpresas();
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
        updateDisciplinas(){
            this.isLoading = true;
            submit(route('admin.curso.grade.disciplina.all-by-turma', this.payload.turma_id), {}, 'GET').then(
                data => {
                    this.disciplinas = data;
                    this.disciplinas = this.disciplinas.map(item => ({...item, ...{selected: true}}));
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
        getAlunos(){
            this.isLoading = true;
            submit(route('admin.aluno.all'), {}, 'GET').then(
                data => {
                    this.alunos = data;
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
        getEmpresas(){
            this.isLoading = true;
            submit(route('admin.empresa.all'), {}, 'GET').then(
                data => {
                    this.empresas = data;
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

                        formData.append('grade_id', this.payload.grade_id);
                        formData.append('aluno_id', this.payload.aluno.id);
                        formData.append('curso_id', this.payload.curso_id);
                        formData.append('turma_id', this.payload.turma_id);
                        if(this.payload.empresa){
                            formData.append('empresa_id', this.payload.empresa.id);
                        }
                        formData.append('disciplinas', JSON.stringify(selectedDisciplinas));

                        let url = route('admin.matricula.store');
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

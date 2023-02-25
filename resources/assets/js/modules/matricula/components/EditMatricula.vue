<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Matrículas'" :title="title ? title : (('Atualização') + ' de Matrícula')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ ('Atualização') }} de Matrícula</h5>
                </div>
                <div class="card-body" v-if="payload.matricula">
                    <h5 class="mb-0">Dados da Matrícula</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="aluno">Aluno*</label>
                            <label class="form-control">{{ payload.matricula.aluno.usuario.nome }}</label>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="aluno">Curso / Turma*</label>
                            <label class="form-control">{{ payload.matricula.curso.nome }} / {{ payload.matricula.turma.codigo }}</label>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="status">Status*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.matricula.status" name="status" id="status"
                                    v-validate="'required'"
                                    data-vv-as="'Status'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(item) in listStatus" :value="item.codigo">{{ item.nome }}</option>
                            </select>
                            <div v-show="errors.has('status')" class="text-danger" style="">{{ errors.first('status') }}</div>
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
                                <button v-can="'can-update'" @click="save()"
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
            matricula: '',
            empresa: '',
        },
        isLoading: false,
        empresas: [],
        listStatus: [
            {codigo: 'matriculado', nome: 'Matriculado'},
            {codigo: 'cancelado', nome: 'Cancelado'},
            {codigo: 'concluido', nome: 'Concluído'},
        ],
    }),
    props: [
        'matricula_id',
        'title'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Matrículas']];
        }
    },
    created() {
        this.getMatricula();
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

                        formData.append('status', this.payload.matricula.status);
                        formData.append('empresa_id', this.payload.empresa ? this.payload.empresa.id : '');

                        let url = route('admin.matricula.update', this.matricula_id);
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

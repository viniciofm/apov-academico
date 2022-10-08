<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="aluno">Professor*</label>
                            <multiselect v-model="payload.professor"
                                         :options="professores"
                                         :searchable="true"
                                         :custom-label="customLabelProfessor"
                                         placeholder="Selecione uma opção"
                                         deselect-label="Desmarcar"
                                         select-label="Selecionar"
                                         selected-label="Selecionado"
                                         v-validate="'required'"
                                         data-vv-as="'Professor"
                                         name="professor"
                                         id="professor"
                                         track-by="id">
                                <span slot="noResult">Nenhum valor encontrado. Considere mudar a pesquisa.</span>
                                <span slot="noOptions">A lista está vazia.</span>
                            </multiselect>
                            <div v-show="errors.has('professor')" class="text-danger" style="">{{ errors.first('professor') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="text-right">
                                <button data-bs-dismiss="modal" aria-label="Close" class="btn btn-warning mr-2">
                                    <span>Cancelar</span>
                                </button>
                                <button @click="save()" class="btn btn-primary">
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
    name: "TurmaDisciplinaProfessor",
    data: () => ({
        search: '',
        dataPaginate: {},
        payload:{
            'professor': '',
        },
        isLoading: false,
        professores: [],
    }),
    props: [
        'turma',
        'turmaDisciplina'
    ],
    watch: {
        turmaDisciplina: function (val){
            this.payload.professor = val.professor ? val.professor : ''
            this.getProfessores();
        }
    },
    components: {
        SubHeader,
        Loading,
        Multiselect
    },
    methods: {
        customLabelProfessor({ usuario }) {
            return `${usuario.nome}`
        },
        getProfessores(){
            this.isLoading = true;
            submit(route('admin.professor.all'), {}, 'GET').then(
                data => {
                    this.professores = data;
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

                        formData.append('professor_id', me.payload.professor.id);
                        formData.append('turma_disciplina_id', me.turmaDisciplina.id);

                        let url = route('admin.turma.disciplina.update-professor', me.turmaDisciplina.id);
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
                                me.professor = {};
                                me.loading = false;
                                me.$emit('close');
                                me.$emit('getData');
                            }, 1000);
                        }).catch(error => {
                            me.$emit('showError', error)
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

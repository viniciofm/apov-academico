<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Alunos da Disciplina'" :title="'Alunos'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="true"
                            @getData="getData"
                            :withFilters="false">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">ALUNOS NA DISCIPLINA </h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar os alunos vinculados a disciplina selecionada.</h6>
                                </div>
                            </template>

                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.matricula.aluno.matricula }}
                                    </td>
                                    <td scope="col">
                                        {{ item.matricula.aluno.usuario.nome }}
                                    </td>
                                    <td scope="col" v-html="translaterStatus(item.status)"></td>
                                    <td scope="col" class="text-center">
                                        //remover aluno
                                    </td>
                                </tr>
                            </template>

                            <template v-slot=table_body></template>
                        </CardTable>
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
import Swal from "sweetalert2";
import SubHeader from "../../../components/SubHeader"
import CardTable from "../../../components/CardTable"
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';
import ModalDefault from "../../../components/ModalDefault"
import TurmaDisciplinaProfessor from "../components/TurmaDisciplinaProfessor"

export default {
    name: "ListTurmaDisciplinaAlunos",
    data: () => ({
        search: {},
        dataPaginate: {},
        columns: ['Matrícula', 'Aluno', 'Status na Disciplina', 'Ações'],
        isLoading: false,
        routeCreate:'turma.disciplines',
        turmaDisciplina: {},
        listStatus: {
            'matriculado' : {codigo: 'matriculado', nome: 'Matriculado', color: 'info'},
            'cancelado' : {codigo: 'cancelado', nome: 'Cancelado', color: 'danger'},
            'aprovado' : {codigo: 'aprovado', nome: 'Aprovado', color: 'success'}
        },
    }),
    props: [
        'turma_disciplina_id'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Turmas'], ['', this.turmaDisciplina.turma ? this.turmaDisciplina.turma.codigo : ''],
                [`/${this.turmaDisciplina.turma ? this.turmaDisciplina.turma.id : ''}/disciplinas`, 'Disciplinas'], ['', (this.turmaDisciplina.disciplina ? this.turmaDisciplina.disciplina.sigla : '') + '']];
        }
    },
    mounted() {
        this.getTurmaDisciplina();
        this.getData();
    },
    components: {
        CardTable,
        SubHeader,
        Loading,
        ModalDefault,
        TurmaDisciplinaProfessor
    },

    methods: {
        translaterStatus(status){
            return `<span class='badge bg-${this.listStatus[status].color}'>${this.listStatus[status].nome}</span>`;
        },
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        getData(page = 1) {
            this.isLoading = true;
            submit(route('admin.matricula.get-alunos'), {
                page: Number.isInteger(page) ? page : 1,
                perPage: 10,
                paginate: true,
                search: { 'turma_disciplina_id': this.turma_disciplina_id }
            },'POST').then(
                data => {
                    this.dataPaginate = data;
                }
            ).then(() => {
                this.isLoading = false
            }).catch(error => {
                Swal.fire(
                    'Erro!',
                    'Encontramos um erro ao consultar os dados!',
                    'error'
                )
                this.isLoading = false;
            });
        },
        getTurmaDisciplina(){
            this.isLoading = true;
            submit(route('admin.turma.disciplina.get-by-id', this.turma_disciplina_id), {}, 'GET').then(
                data => {
                    this.turmaDisciplina = data.registro;
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

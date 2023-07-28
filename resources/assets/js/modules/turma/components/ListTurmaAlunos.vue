<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Alunos da Turma'" :title="'Alunos'"></sub-header>

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
                                    <h4 class="card-title">ALUNOS NA TURMA </h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar os alunos vinculados a turma selecionada.</h6>
                                </div>
                            </template>

                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.aluno.matricula.toString().padStart(4, '0') }}
                                    </td>
                                    <td scope="col">
                                        {{ item.aluno.usuario.nome }}
                                    </td>
                                    <td scope="col" v-html="translaterStatus(item.status)"></td>
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

export default {
    name: "ListTurmaAlunos",
    data: () => ({
        search: {},
        dataPaginate: {},
        columns: ['Matrícula', 'Aluno', 'Status na Matrícula'],
        isLoading: false,
        routeCreate:'turma.alunos',
        turma: {},
        listStatus: {
            'matriculado' : {codigo: 'matriculado', nome: 'Matriculado', color: 'info'},
            'cancelado' : {codigo: 'cancelado', nome: 'Cancelado', color: 'warning'},
            'concluido' : {codigo: 'concluido', nome: 'Concluído', color: 'success'},
            'reprovado' : {codigo: 'reprovado', nome: 'Reprovado', color: 'danger'},
            'reprovado_falta' : {codigo: 'reprovado_falta', nome: 'Reprovado por Falta', color: 'danger'},
        },
    }),
    props: [
        'turma_id'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Turmas'], ['', this.turma ? this.turma.codigo : '']];
        }
    },
    mounted() {
        this.getTurma();
        this.getData();
    },
    components: {
        CardTable,
        SubHeader,
        Loading,
        ModalDefault
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
            submit(route('admin.matricula.get'), {
                page: Number.isInteger(page) ? page : 1,
                perPage: 10,
                paginate: true,
                search: { 'turma_id': this.turma_id }
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
        getTurma(){
            this.isLoading = true;
            submit(route('admin.turma.get-by-id', this.turma_id), {}, 'GET').then(
                data => {
                    this.turma = data.registro;
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

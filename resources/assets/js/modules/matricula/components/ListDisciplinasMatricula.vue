<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Matrículas'" :title="'Disciplinas'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="false"
                            :routeCreate="routeCreate"
                            :textCreate="textCreate"
                            :withFilters="false">
                            <template v-slot:header-card>
                                <div class="col-md-6" v-if="payload.matricula.aluno">
                                    <h4 class="card-title">DISCIPLINAS NA MATRÍCULA PARA {{ payload.matricula.aluno.usuario.nome}}</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as disciplinas da matrícula registrada.</h6>
                                </div>
                            </template>

                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.turma_disciplina.disciplina.sigla }}
                                    </td>
                                    <td scope="col">
                                        {{ item.turma_disciplina.disciplina.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.turma_disciplina.disciplina.carga_horaria }}
                                    </td>
                                    <td scope="col">
                                        {{ item.turma_disciplina.turma.codigo }}
                                    </td>
                                    <td scope="col" v-html="translaterStatus(item.status)"></td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <a target="_blank" :href="getRoute('relatorio.diario-classe.turma-disciplina', item.turma_disciplina.id)" class="btn col-md-4" title="Diário de Classe para a Turma/Disciplina">
                                                <i class="align-middle text-primary fa-solid fa-book"></i>
                                            </a>

                                            <button v-can="'can-update'" v-on:click="remover(item)"
                                                    class="btn col-md-3" :title="'Remover disciplina'">
                                                <i :class="'align-middle fas fa-fw text-success text-danger fa-trash'"></i>
                                            </button>
                                        </div>
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

export default {
    name: "ListDisciplinasMatricula",
    data: () => ({
        subHeaderLinks:[['/', 'Matrículas']],
        search: {},
        dataPaginate: {},
        columns: ['Sigla', 'Disciplina', 'Carga Horária', 'Turma', 'Status', 'Ações'],
        isLoading: false,
        payload:{
            matricula: '',
        },
        routeCreate:'matricula.disciplines',
        textCreate:'Adicionar Nova',
        listStatus: {
            'matriculado' : {codigo: 'matriculado', nome: 'Matriculado', color: 'info'},
            'cancelado' : {codigo: 'cancelado', nome: 'Cancelado', color: 'warning'},
            'concluido' : {codigo: 'concluido', nome: 'Concluído', color: 'success'},
            'reprovado' : {codigo: 'reprovado', nome: 'Reprovado', color: 'danger'},
            'reprovado_falta' : {codigo: 'reprovado_falta', nome: 'Reprovado por Falta', color: 'danger'},
        },
    }),
    props: [
        'matricula_id'
    ],
    mounted() {
        this.getMatricula();
        this.getDisciplinasMatricula();
    },
    components: {
        CardTable,
        SubHeader,
        Loading
    },
    methods: {
        getRoute(rota, id){
            return route(rota, id)
        },
        remover(item){
            let me = this;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente desvincular a disciplina ' + item.turma_disciplina.disciplina.sigla + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        submit(route('admin.matricula.delete-disciplina'), {'id': item.id, 'matricula_id' : me.matricula_id}, 'DELETE').then(
                            data => {
                                if(data.success){
                                    me.$emit('showMessage', data.message)
                                    me.getDisciplinasMatricula();
                                }else{
                                    me.isLoading = false
                                }
                            }
                        ).then(() => {
                            this.isLoading = false;
                        }).catch(error => {
                            me.$emit('showError', error)
                            me.isLoading = false;
                        });
                    })
                }
            });
        },
        translaterStatus(status){
            return `<span class='badge bg-${this.listStatus[status].color}'>${this.listStatus[status].nome}</span>`;
        },
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        getMatricula(){
            this.isLoading = true;
            submit(route('admin.matricula.get-by-id', this.matricula_id), {}, 'GET').then(
                data => {
                    this.payload.matricula = data.registro;
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
        getDisciplinasMatricula(page = 1) {
            this.isLoading = true;
            submit(route('admin.matricula.get-disciplinas'), {
                page: Number.isInteger(page) ? page : 1,
                perPage: 10,
                paginate: true,
                search: {matricula_id: this.matricula_id}
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

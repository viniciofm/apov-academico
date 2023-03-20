<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Disciplinas da Turma'" :title="'Disciplinas'"></sub-header>

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
                                    <h4 class="card-title">DISCIPLINAS NA TURMA</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as disciplinas cadastradas na turma selecionada.</h6>
                                </div>
                            </template>

                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.disciplina.sigla }}
                                    </td>
                                    <td scope="col">
                                        {{ item.disciplina.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.professor ? item.professor.usuario.nome : '-' }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.alunos`, params: { 'turma_disciplina_id': item.id, 'turma_id': item.turma_id }}"
                                                         class="btn col-md-3" title="Alunos da Disciplina">
                                                <i class="align-middle text-secondary fas fa-fw fa-rectangle-list"></i>
                                            </router-link>

                                            <a target="_blank" :href="getRoute('relatorio.diario-classe.turma-disciplina', item.id)" class="btn col-md-3" title="Diário de Classe para a Turma/Disciplina">
                                                <i class="align-middle text-primary fa-solid fa-book"></i>
                                            </a>

                                            <button  v-on:click='openTheModalProfessor(item)'
                                                         class="btn col-md-3" title="Editar Professor">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </button>

                                            <button v-can="'can-update'" v-on:click="updateStatus(item)"
                                                    class="btn col-md-3" :title="(item.ativo ? 'Desativar' : 'Ativar')">
                                                <i :class="'align-middle fas fa-fw ' + (item.ativo ? 'text-success ' : 'text-danger ') + (item.ativo ? 'fa-check-circle' : 'fa-times-circle')"></i>
                                            </button>
                                            <label v-can="'can-only-select'"
                                                   class="col-md-3" :title="(item.ativo ? 'Ativo' : 'Desativado')">
                                                <i :class="'align-middle fas fa-fw ' + (item.ativo ? 'text-success ' : 'text-danger ') + (item.ativo ? 'fa-check-circle' : 'fa-times-circle')"></i>
                                            </label>
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

        <modal-default :id_modal="'modalTurmaDisciplinaProfessor'" :title="'Professor para a disciplina '+(turmaDisciplina.disciplina ? turmaDisciplina.disciplina.sigla : '')" ref="modalTurmaDisciplinaProfessor" tabIndex="-1" id="modalTurmaDisciplinaProfessor" size='medium'>
            <div slot="modal-body">
                <turma-disciplina-professor :turma="turma" @getData="getData" :turma-disciplina="turmaDisciplina" v-on:close="closeTheModalProfessor"></turma-disciplina-professor>
            </div>
        </modal-default>

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
    name: "ListTurmaDisciplina",
    data: () => ({
        search: {},
        dataPaginate: {},
        columns: ['Sigla', 'Disciplina', 'Professor', 'Ações'],
        isLoading: false,
        routeCreate:'turma.disciplines',
        turma: {},
        turmaDisciplina: {},
    }),
    props: [
        'turma_id'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Turmas'], ['', this.turma.codigo]];
        }
    },
    mounted() {
        this.getData();
        this.getTurma();
    },
    components: {
        CardTable,
        SubHeader,
        Loading,
        ModalDefault,
        TurmaDisciplinaProfessor
    },

    methods: {
        getRoute(rota, id){
            return route(rota, id)
        },
        openTheModalProfessor(turmaDisciplina) {
            this.turmaDisciplina = turmaDisciplina;
            this.$refs.modalTurmaDisciplinaProfessor.open();
        },
        closeTheModalProfessor(){
            this.$refs.modalTurmaDisciplinaProfessor.close();
        },
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        updateStatus(item){
            let me = this;
            let ativo = item.ativo ? 0 : 1;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente alterar o status da turma disciplina ' + item.disciplina.sigla + ' para ' + (ativo ? 'ativo' : 'inativo') + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        toSeek(route('admin.turma.disciplina.active', {'turmaDisciplina': item.id, 'active': ativo})).then(
                            data => {
                                if(data.success){
                                    me.$emit('showMessage', data.message)
                                    me.getData();
                                }else{
                                    me.isLoading = false
                                }
                            }
                        ).then(() => {
                            me.isLoading = false
                        }).catch(error => {
                            me.$emit('showError', error)
                            me.isLoading = false;
                        });
                    })
                }
            });
        },
        getData(page = 1) {
            this.isLoading = true;
            submit(route('admin.turma.disciplina.get'), {
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

<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Matrículas'" :title="'Matrículas'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="true"
                            :routeCreate="routeCreate"
                            @getData="getData"
                            :withFilters="true">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">MATRÍCULAS CADASTRADAS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as matrículas cadastradas.</h6>
                                </div>
                            </template>

                            <template v-slot:filters>
                                <div class="form-group col-lg-2 col-md-2 col-sm-6">
                                    <label for="matricula">Matrícula do Aluno</label>
                                    <input class="form-control"
                                           v-model="search.matricula"
                                           id="matricula" name="matricula" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="nome_aluno">Nome</label>
                                    <input class="form-control"
                                           v-model="search.nome_aluno"
                                           id="nome_aluno" name="nome_aluno" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="nome_curso">Curso</label>
                                    <input class="form-control"
                                           v-model="search.nome_curso"
                                           id="nome_curso" name="nome_curso" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="status">Status</label>
                                    <select class="form-control" data-bs-toggle="select2" v-model="search.status" name="status" id="status">
                                        <option value="" selected>Não selecionado</option>
                                        <option v-for="(item) in listStatus" :value="item.codigo">{{ item.nome }}</option>
                                    </select>
                                </div>
                            </template>
                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.aluno.matricula }}
                                    </td>
                                    <td scope="col">
                                        {{ item.aluno.usuario.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.turma.grade.codigo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.turma.codigo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.curso.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.empresa ? item.empresa.nome : '-' }}
                                    </td>
                                    <td scope="col" v-html="translaterStatus(item.status)"></td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.disciplines`, params: { 'matricula_id': item.id }}"
                                                         class="btn col-md-4" title="Disciplinas da matrícula">
                                                <i class="align-middle text-warning fas fa-fw fa-list-ul"></i>
                                            </router-link><router-link :to="{name: `${routeCreate}.edit`, params: { 'matricula_id': item.id }}"
                                                         class="btn col-md-4" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
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
    name: "ListTurmas",
    data: () => ({
        subHeaderLinks:[],
        search: {matricula: '', status: '', nome_aluno:'', nome_curso:''},
        dataPaginate: {},
        columns: ['Matrícula', 'Aluno', 'Matriz', 'Turma', 'Curso', 'Empresa',  'Status', 'Ações'],
        isLoading: false,
        routeCreate:'matricula',
        listStatus: {
            'matriculado' : {codigo: 'matriculado', nome: 'Matrículado', color: 'info'},
            'cancelado' : {codigo: 'cancelado', nome: 'Cancelado', color: 'danger'},
            'concluido' : {codigo: 'concluido', nome: 'Concluído', color: 'success'}
        },
    }),
    mounted() {
        this.getData();
    },
    components: {
        CardTable,
        SubHeader,
        Loading
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
                search: this.search
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

<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Aluno'" :title="'Painel do Aluno'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="true"
                            @getData="getData"
                            :withFilters="true">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">CURSOS DO ALUNO</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para visualizar os cursos vinculados.</h6>
                                </div>
                            </template>

                            <template v-slot:filters>
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
                                        {{ item.curso.nome }}
                                    </td>
                                    <td scope="col" v-html="translaterStatus(item.status)"></td>
                                    <td scope="col" class="text-center">
                                        <div class="row">

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
    name: "ListCursosAlunos",
    data: () => ({
        subHeaderLinks:[],
        search: { status:''},
        dataPaginate: {},
        columns: ['Matrícula', 'Curso', 'Situação', 'Ações'],
        listStatus: {
            'matriculado' : {codigo: 'matriculado', nome: 'Matrículado', color: 'info'},
            'cancelado' : {codigo: 'cancelado', nome: 'Cancelado', color: 'danger'},
            'aprovado' : {codigo: 'aprovado', nome: 'Aprovado', color: 'success'}
        },
        isLoading: false
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

            submit(route('aluno.get.matriculas'), {
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

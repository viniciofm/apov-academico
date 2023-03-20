<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Disciplinas'" :title="'Disciplinas'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="false"
                            @getData="getData"
                            :withFilters="false">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">DISCIPLINAS VINCULADAS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as disciplinas vinculadas.</h6>
                                </div>
                            </template>

                            <template v-slot:filters>
                                <div class="form-group col-lg-4 col-md-4 col-sm-6">
                                    <label for="nome">Nome</label>
                                    <input class="form-control"
                                           v-model="search.nome"
                                           id="nome" name="nome" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="cpf_cnpj">CPF/CNPJ</label>
                                    <input class="form-control"
                                           v-mask="['###.###.###-##', '##.###.###/####-##']" v-model="search.cpf_cnpj"
                                           id="cpf_cnpj" name="cpf_cnpj" maxlength="20" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="matricula">Matrícula</label>
                                    <input class="form-control"
                                           v-model="search.matricula"
                                           id="matricula" name="matricula" maxlength="200" type="text" placeholder="">
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
                                        {{ item.turma.codigo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.matriculas_turma.length }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.classes`, params: { 'turma_disciplina': item }}"
                                                         class="btn col-md-3" title="Gerenciamento de Aulas">
                                                <i class="align-middle fas fa-fw fa-calendar-alt"></i>
                                            </router-link>
                                            <router-link :to="{name: `${routeCreate}.activities`, params: { 'turma_disciplina': item }}"
                                                         class="btn col-md-3" title="Gerenciamento de Atividades">
                                                <i class="align-middle text-warning fas fa-fw fa-list"></i>
                                            </router-link>

                                            <a target="_blank" :href="getRoute('relatorio.diario-classe.turma-disciplina', item.id)" class="btn col-md-3" title="Diário de Classe para a Turma/Disciplina">
                                                <i class="align-middle text-primary fa-solid fa-book"></i>
                                            </a>
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
import {submit, toSeek} from "../../../../common/send-form";
import Swal from "sweetalert2";
import SubHeader from "../../../../components/SubHeader"
import CardTable from "../../../../components/CardTable"
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "MyDisciplines",
    data: () => ({
        subHeaderLinks:[],
        search: { },
        dataPaginate: {},
        columns: ['Sigla', 'Disciplina', 'Turma', 'Nº de Alunos', 'Ações'],
        isLoading: false,
        routeCreate:'professor'
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
        getRoute(rota, id){
            return route(rota, id)
        },
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        getData(page = 1) {
            this.isLoading = true;

            submit(route('professor.get-my-disciplines'), {
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

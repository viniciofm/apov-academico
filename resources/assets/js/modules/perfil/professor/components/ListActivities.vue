<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section v-if="turma_disciplina">
            <sub-header :links="subHeaderLinks" :module="'Atividades'" :title="'Atividades'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="false"
                            :routeCreate="routeCreate"
                            :routeCreateParams="routeCreateParams"
                            @getData="getData"
                            :withFilters="false">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">ATIVIDADES PARA A DISCIPLINA - {{turma_disciplina.disciplina.sigla}}</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as atividades da disciplina.</h6>
                                </div>
                            </template>

                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ moment(item.data).format('DD/MM/YYYY') }}
                                    </td>
                                    <td scope="col">
                                        {{ item.titulo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.peso.replace('.', ',') }}
                                    </td>
                                    <td scope="col">
                                        {{ item.descricao }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link v-can="'can-update'"  :to="{name: `${routeCreate}.edit`, params: { 'turma_disciplina': turma_disciplina, 'id': item.id }}"
                                                         class="btn col-md-3" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
                                            <button v-can="'can-update'" v-on:click="remover(item)"
                                                    class="btn col-md-3" :title="'Remover atividade'">
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
import {submit, toSeek} from "../../../../common/send-form";
import Swal from "sweetalert2";
import SubHeader from "../../../../components/SubHeader"
import CardTable from "../../../../components/CardTable"
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';
import moment from 'moment';

export default {
    name: "LisActivities",
    data: () => ({
        moment: moment,
        subHeaderLinks:[['/','Disciplinas']],
        search: { },
        dataPaginate: {},
        columns: ['Data', 'Título', 'Peso', 'Descrição', 'Ações'],
        isLoading: false,
        routeCreate:'professor.activities',
        routeCreateParams:{}
    }),
    mounted() {
        if (!this.turma_disciplina){
            this.$router.push({path: `/`});
        }else{
            this.search = { 'turma_disciplina_id': this.turma_disciplina.id };
            this.routeCreateParams = { 'turma_disciplina': this.turma_disciplina };
            this.getData();
        }
    },
    components: {
        CardTable,
        SubHeader,
        Loading
    },
    props: [
        'turma_disciplina'
    ],
    methods: {
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        remover(item){
            let me = this;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente remover a atividade para a disciplina ' + me.turma_disciplina.disciplina.sigla + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        submit(route('content.atividade.delete'), {'id': item.id}, 'DELETE').then(
                            data => {
                                if(data.success){
                                    me.$emit('showMessage', data.message)
                                    me.getData();
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
        getData(page = 1) {
            this.isLoading = true;

            submit(route('content.atividade.get'), {
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

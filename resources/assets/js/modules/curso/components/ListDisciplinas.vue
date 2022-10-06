<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Cursos'" :title="'Disciplinas'"></sub-header>

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
                            :withFilters="false">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">DISCIPLINAS CADASTRADAS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as disciplinas cadastradas na grade.</h6>
                                </div>
                            </template>

                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.codigo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.ano }}
                                    </td>
                                    <td scope="col">
                                        {{ item.periodo }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.disciplines`, params: { 'curso_id': curso_id, 'id': item.id }}"
                                                         class="btn col-md-4" title="Disciplinas da Grade">
                                                <i class="align-middle text-warning fas fa-fw fa-list-ul"></i>
                                            </router-link>
                                            <router-link :to="{name: `${routeCreate}.edit`, params: { 'curso_id': curso_id, 'id': item.id }}"
                                                         class="btn col-md-4" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
                                            <button v-on:click="updateStatus(item)"
                                                         class="btn col-md-4" :title="(item.ativo ? 'Desativar' : 'Ativar')">
                                                <i :class="'align-middle fas fa-fw ' + (item.ativo ? 'text-success ' : 'text-danger ') + (item.ativo ? 'fa-check-circle' : 'fa-times-circle')"></i>
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
    name: "ListGrades",
    data: () => ({
        dataPaginate: {},
        columns: ['Código', 'Ano', 'Período', 'Ações'],
        isLoading: false,
        routeCreate:'curso.grids.disciplines',
        curso: {},
        grade: {}
    }),
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Cursos'], ['/' + this.curso.id + '/grades', 'Grades'], ['', this.grade.codigo]];
        }
    },
    mounted() {
        this.getData();
        this.getGrade();
    },
    components: {
        CardTable,
        SubHeader,
        Loading
    },
    props: [
        'curso_id',
        'grade_id',
    ],
    methods: {
        updateStatus(item){
            let me = this;
            let ativo = item.ativo ? 0 : 1;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente alterar o status da grade ' + item.codigo + ' para ' + (ativo ? 'ativa' : 'inativa') + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.loading = true;
                        toSeek(route('admin.curso.grade.active', {'grade': item.id, 'active': ativo})).then(
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
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        getData(page = 1) {
            this.isLoading = true;
            submit(route('admin.curso.grade.disciplina.get'), {
                page: Number.isInteger(page) ? page : 1,
                perPage: 10,
                paginate: true,
                search: {...this.search, ...{'grade_id': this.grade_id}},
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
        getGrade(){
            if(this.curso_id) {
                this.isLoading = true;
                submit(route('admin.curso.grade.get-by-id', this.grade_id), {}, 'GET').then(
                    data => {
                        this.curso = data.registro.curso;
                        this.grade = data.registro.grade;
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
            }
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

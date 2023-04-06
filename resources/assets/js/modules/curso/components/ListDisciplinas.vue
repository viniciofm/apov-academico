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
                                        {{ item.sigla }}
                                    </td>
                                    <td scope="col">
                                        {{ item.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.carga_horaria }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.edit`, params: { 'curso_id': curso_id, 'disciplina_id': item.id }}"
                                                         class="btn col-md-4" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
                                            <button v-can="'can-update'" v-on:click="remover(item)"
                                                    class="btn col-md-3" :title="'Remover disciplina'">
                                                <i :class="'align-middle fas fa-fw text-danger fa-trash'"></i>
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
        columns: ['Sigla', 'Nome', 'CH (h)', 'Ações'],
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
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        remover(item){
            let me = this;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente remover a disciplina ' + item.sigla + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        submit(route('admin.curso.grade.disciplina.delete'), {'id': item.id}, 'DELETE').then(
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

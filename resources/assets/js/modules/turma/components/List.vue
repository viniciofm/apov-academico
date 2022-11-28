<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Turmas'" :title="'Turmas'"></sub-header>

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
                                    <h4 class="card-title">TURMAS CADASTRADAS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar as turmas cadastradas.</h6>
                                </div>
                            </template>

                            <template v-slot:filters>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="codigo">Código</label>
                                    <input class="form-control"
                                           v-model="search.codigo"
                                           id="codigo" name="codigo" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="codigo_grade">Grade</label>
                                    <input class="form-control"
                                           v-model="search.codigo_grade"
                                           id="codigo_grade" name="codigo_grade" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                    <label for="nome_curso">Curso</label>
                                    <input class="form-control"
                                           v-model="search.nome_curso"
                                           id="nome_curso" name="nome_curso" maxlength="200" type="text" placeholder="">
                                </div>
                            </template>
                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.codigo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.grade.codigo }}
                                    </td>
                                    <td scope="col">
                                        {{ item.grade.curso.nome }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.disciplines`, params: { 'turma_id': item.id }}"
                                                         class="btn col-md-3" title="Disciplinas da Turma">
                                                <i class="align-middle fas fa-fw fa-list"></i>
                                            </router-link>
                                            <router-link :to="{name: `${routeCreate}.alunos`, params: { 'turma_id': item.id }}"
                                                         class="btn col-md-3" title="Alunos da Turma">
                                                <i class="align-middle text-secondary fas fa-fw fa-rectangle-list"></i>
                                            </router-link>
                                            <button v-can="'can-update'" v-on:click="updateStatus(item)"
                                                    class="btn col-md-3" :title="(item.ativo ? 'Desativar' : 'Ativar')">
                                                <i :class="'align-middle fas fa-fw ' + (item.ativo ? 'text-success ' : 'text-danger ') + (item.ativo ? 'fa-check-circle' : 'fa-times-circle')"></i>
                                            </button>
                                            <router-link v-can="'can-update'" :to="{name: `${routeCreate}.edit`, params: { 'turma_id': item.id }}"
                                                         class="btn col-md-3" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
                                            <label v-can="'can-only-select'"
                                                   class="col-md-4" :title="(item.ativo ? 'Ativo' : 'Desativado')">
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
        search: {codigo: '', codigo_grade:'', nome_curso:''},
        dataPaginate: {},
        columns: ['Código', 'Grade', 'Curso', 'Ações'],
        isLoading: false,
        routeCreate:'turma'
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
        updateStatus(item){
            let me = this;
            let ativo = item.ativo ? 0 : 1;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente alterar o status da turma ' + item.codigo + ' para ' + (ativo ? 'ativo' : 'inativo') + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        toSeek(route('admin.turma.active', {'turma': item.id, 'active': ativo})).then(
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

            submit(route('admin.turma.get'), {
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

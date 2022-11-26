<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Professores'" :title="'Professores'"></sub-header>

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
                                    <h4 class="card-title">PROFESSORES CADASTRADOS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para ver os professores cadastrados ou então editar e excluí-los.</h6>
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
                                        {{ item.matricula }}
                                    </td>
                                    <td scope="col">
                                        {{ item.usuario.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.usuario.email }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.edit`, params: { 'id': item.id }}"
                                                         class="btn col-md-6" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
                                            <button v-on:click="updateStatus(item)"
                                                         class="btn col-md-6" :title="(item.ativo ? 'Desativar' : 'Ativar')">
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
    name: "ListProfessores",
    data: () => ({
        subHeaderLinks:[],
        search: {name: '', cpf_cnpj:'', matricula:''},
        dataPaginate: {},
        columns: ['Matrícula', 'Nome', 'E-mail', 'Ações'],
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
        updateStatus(item){
            let me = this;
            let ativo = item.ativo ? 0 : 1;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente alterar o status do professor ' + item.usuario.nome + ' para ' + (ativo ? 'ativo' : 'inativo') + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        toSeek(route('admin.professor.active', {'professor': item.id, 'active': ativo})).then(
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

            submit(route('admin.professor.get'), {
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

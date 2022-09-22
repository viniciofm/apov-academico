<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :title="'Empresas'"></sub-header>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <CardTable
                            :data-paginate="dataPaginate"
                            :columns="columns"
                            :allow-search="true"
                            routeCreate="empresa"
                            @resetSearch="resetSearch"
                            @getData="getData">
                            <template v-slot:header-card>
                                <div class="col-md-6">
                                    <h4 class="card-title">EMPRESAS CADASTRADAS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para ver suas empresas cadastradas ou então editar e excluí-las.</h6>
                                </div>
                            </template>

                            <template v-slot=filters>
                                TESTE
                            </template>
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
import swal from "sweetalert2";
import SubHeader from "../../../components/SubHeader"
import CardTable from "../../../components/CardTable"
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "ListEmpresas",
    data: () => ({
        subHeaderLinks:[],
        search: '',
        dataPaginate: {},
        columns: ['Nome', 'Pastas', 'Extensão', 'Opções'],
        isLoading: false,
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
        resetSearch() {
            this.search = '';

            this.getData();
        },
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        getData(page = 1) {
            this.isLoading = true;

            submit(route('admin.empresa.get'), {
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
                    'Encontramos um erro ao consultar os estados!',
                    'error'
                )
                me.isLoading = false;
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

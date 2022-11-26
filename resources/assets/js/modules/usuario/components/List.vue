<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Usuários'" :title="'Usuários'"></sub-header>

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
                                    <h4 class="card-title">USUÁRIOS CADASTRADOS</h4>
                                    <h6 class="card-subtitle text-muted">Utilize o módulo para gerenciar os usuários cadastrados.</h6>
                                </div>
                            </template>

                            <template v-slot:filters>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                    <label for="nome">Nome</label>
                                    <input class="form-control"
                                           v-model="search.nome"
                                           id="nome" name="nome" maxlength="200" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                    <label for="email">E-mail</label>
                                    <input class="form-control"
                                           v-model="search.email"
                                           id="email" name="email" maxlength="100" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                    <label for="cpf_cnpj">CPF/CNPJ</label>
                                    <input class="form-control"
                                           v-mask="['###.###.###-##', '##.###.###/####-##']" v-model="search.cpf_cnpj"
                                           id="cpf_cnpj" name="cpf_cnpj" maxlength="20" type="text" placeholder="">
                                </div>

                                <div class="form-group col-lg-5 col-md-5 col-sm-6">
                                    <label for="tipo_usuario">Tipo</label>
                                    <select class="form-control" data-bs-toggle="select2" v-model="search.tipo_usuario" name="tipo_usuario" id="tipo_usuario">
                                        <option value="" selected>Não selecionado</option>
                                        <option value="admin">Administrativo</option>
                                        <option value="aluno">Aluno</option>
                                        <option value="empresa">Empresa</option>
                                        <option value="professor">Professor</option>
                                    </select>
                                </div>
                            </template>
                            <template v-slot:table-body>
                                <tr v-for="(item, index) of dataPaginate.data" :key="item.id">
                                    <td scope="col">
                                        {{ item.nome }}
                                    </td>
                                    <td scope="col">
                                        {{ item.email }}
                                    </td>
                                    <td scope="col">
                                        {{ item.cpf_cnpj }}
                                    </td>
                                    <td scope="col" class="text-center">
                                        <span :class="'badge ' + (item.tipo_documento == 'cpf' ? 'bg-info' : 'bg-primary')">{{ item.tipo_documento == 'cpf' ? 'CPF' : 'CNPJ' }}</span>
                                    </td>
                                    <td scope="col" v-html="translaterTipoUsuario(item.tipo_usuario)"></td>
                                    <td scope="col" class="text-center">
                                        <div class="row">
                                            <router-link :to="{name: `${routeCreate}.edit`, params: { 'id': item.id }}"
                                                         class="btn col-md-6" title="Editar">
                                                <i class="align-middle fas fa-fw fa-pen"></i>
                                            </router-link>
                                            <button v-on:click="updateStatus(item)"
                                                         class="btn col-md-6" :title="(item.blocked ? 'Desbloquear' : 'Bloquear')">
                                                <i :class="'align-middle fas fa-fw ' + (item.blocked ? 'text-danger ' : 'text-success ') + (item.blocked ? 'fa-times-circle' : 'fa-check-circle')"></i>
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
    name: "ListUsuarios",
    data: () => ({
        subHeaderLinks:[],
        search: {nome:'', email:'', cpf_cnpj:'', tipo_usuario:''},
        dataPaginate: {},
        columns: ['Nome', 'E-mail', 'CPF/CNPJ','Tipo Documento', 'Tipo', 'Ações'],
        isLoading: false,
        routeCreate:'usuario'
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
        translaterTipoUsuario(tipo_usuario){
            let color = '';
            switch(tipo_usuario.nome){
                case 'empresa':
                    color = 'bg-danger';
                    break;
                case 'admin':
                    color = 'bg-success';
                    break;
                case 'aluno':
                    color = 'bg-info';
                    break;
                case 'professor':
                    color = 'bg-warning';
                    break;
            }
            return `<span class='badge ${color}'>${tipo_usuario.nome.charAt(0).toUpperCase() + tipo_usuario.nome.slice(1)}</span>`;
        },
        updateStatus(item){
            let me = this;
            let blocked = item.blocked ? 0 : 1;

            Swal.fire({
                icon: 'question',
                title: 'Confirmação',
                html: ('Deseja realmente alterar o status do usuário ' + item.nome + ' para ' + (blocked ? 'bloqueado' : 'desbloqueado') + '?'),
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                allowEscapeKey: false,
                preConfirm: () => {
                    return new Promise(() => {
                        me.isLoading = true;
                        toSeek(route('admin.usuario.block', {'user': item.id, 'block': blocked})).then(
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

            submit(route('admin.usuario.get'), {
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

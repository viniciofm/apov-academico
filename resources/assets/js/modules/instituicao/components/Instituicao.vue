<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :title="title ? title : ('Dados da Instituição')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de Atualização da Instituição</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Instituição</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="razaoSocial">Nome*</label>
                            <input class="form-control" v-model="payload.nome" id="nome" name="nome" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Nome'">
                            <div v-show="errors.has('nome')" class="text-danger" style="">{{ errors.first('nome') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="email">E-mail*</label>
                            <input class="form-control" v-model="payload.email" id="email" name="email" maxlength="150" value="" type="email" placeholder="" required="required"
                                   v-validate="'required|email'"
                                   data-vv-as="'Email'">
                            <div v-show="errors.has('email')" class="text-danger" style="">{{ errors.first('email') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="tipo_documento">Tipo do Documento*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.tipo_documento" name="tipo_documento" id="tipo_documento" required="required"
                                    v-validate="'required'"
                                    data-vv-as="'Tipo do Documento'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option value="cnpj">CNPJ</option>
                                <option value="cpf">CPF</option>
                            </select>
                            <div v-show="errors.has('tipo_documento')" class="text-danger" style="">{{ errors.first('tipo_documento') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-6">
                            <label for="cpf_cnpj">CNPJ/CPF*</label>
                            <input class="form-control" v-mask="payload.tipo_documento == 'cnpj' ? '##.###.###/####-##' : '###.###.###-##'"  v-model="payload.cpf_cnpj" id="cpf_cnpj" name="cpf_cnpj" maxlength="20" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'CNPJ/CPF'">
                            <div v-show="errors.has('cpf_cnpj')" class="text-danger" style="">{{ errors.first('cpf_cnpj') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="razaoSocial">Telefone</label>
                            <input class="form-control" v-model="payload.telefone_contato" v-mask="['(##) ####-####', '(##) #####-####']" id='telefone_contato' name="telefone_contato" maxlength="150" value="" type="text" placeholder="" required="required">
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="responsavel">Responsável</label>
                            <input class="form-control" v-model="payload.responsavel" id='responsavel' name="responsavel" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Reponsável'">
                            <div v-show="errors.has('responsavel')" class="text-danger" style="">{{ errors.first('responsavel') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="responsavel">Logomarca*</label>
                            <input type="file" name="logomarca" id="logomarca" ref="image"
                                   v-validate="'ext:png,jpg,jpeg'"
                                   v-on:change="handleFileUpload()"
                                   class="form-control custom-file-input"
                                   data-vv-as="'Logomarca'">
                            <small class="form-text d-block text-muted">Extensões suportadas: <strong>jpg, jpeg ou png</strong></small>
                            <div v-show="errors.has('logomarca')" class="text-danger" style="">{{ errors.first('logomarca') }}</div>
                            <div v-if="payload.old_logomarca">Imagem anterior: <span class="badge rounded-pill bg-success"><a class="text-white" :href="payload.old_logomarca" target="_blank">Clique para visualizar</a></span></div>
                        </div>
                    </div>
                </div>
            </div>

            <endereco :endereco="payload.endereco"></endereco>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="text-right">
                                <a :href="routeHome"
                                        class="btn btn-warning mr-2">
                                    <span>Cancelar</span>
                                </a>
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>Atualizar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import {submit} from "../../../common/send-form";
import http from '../../../api/http'
import SubHeader from "../../../components/SubHeader"
import CardTable from "../../../components/CardTable"
import Endereco from "../../../components/Endereco"
import Swal from "sweetalert2";

export default {
    name: "Instituicao",
    data: () => ({
        routeHome: route('home'),
        subHeaderLinks:[],
        search: '',
        dataPaginate: {},
        payload:{
            'id': '',
            'nome': '',
            'email': '',
            'responsavel': '',
            'telefone_contato': '',
            'cpf_cnpj': '',
            'tipo_documento': '',
            'logomarca': '',
            'endereco': {
                'id': null,
                'rua': '',
                'numero': '',
                'bairro': '',
                'complemento': '',
                'cep': '',
                'cidade_id': null,
                'estado_id': null
            },
            old_logomarca: ''
        }
    }),
    props: [
        'id',
        'title'
    ],
    created() {
        this.getInstituicao();
    },
    components: {
        CardTable,
        SubHeader,
        Endereco
    },
    methods: {
        handleFileUpload() {
            this.payload.logomarca = this.$refs.image.files[0];
        },
        getInstituicao(){
            submit(route('admin.instituicao.get-by-user'), {},'GET').then(
                data => {
                    this.payload = data;
                    this.payload.old_logomarca = data.logomarca;
                    if(!data.endereco){
                        this.payload.endereco = {'estado_id' : '', 'cidade_id' : ''};
                    }
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

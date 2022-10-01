<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'dados da Instituição'" :title="title ? title : ('Dados da Instituição')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de Atualização da Instituição</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Instituição</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Nome*</label>
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
                            <label for="telefone_contato">Telefone</label>
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
                            <label for="logomarca">Logomarca*</label>
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

        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>
import {submit, toSeek} from "../../../common/send-form";
import http from '../../../api/http'
import SubHeader from "../../../components/SubHeader"
import CardTable from "../../../components/CardTable"
import Endereco from "../../../components/Endereco"
import Swal from "sweetalert2";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';

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
        },
        isLoading: false,
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
        Endereco,
        Loading
    },
    methods: {
        handleFileUpload() {
            this.payload.logomarca = this.$refs.image.files[0];
        },
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let formData = new FormData();

                        if (this.payload.logomarca && this.payload.old_logomarca != this.payload.logomarca)
                            formData.append('logomarca', this.payload.logomarca);
                        formData.append('nome', this.payload.nome);
                        formData.append('email', this.payload.email);
                        formData.append('responsavel', this.payload.responsavel);
                        formData.append('telefone_contato', this.payload.telefone_contato ? this.payload.telefone_contato : '');
                        formData.append('cpf_cnpj', this.payload.cpf_cnpj);
                        formData.append('tipo_documento', this.payload.tipo_documento);
                        formData.append('user_id', this.payload.user_id);
                        formData.append('endereco' , JSON.stringify(this.payload.endereco));

                        let url = route('admin.instituicao.update', me.payload.id);
                        me.loading = true;

                        http.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(data => {
                            Swal.fire({
                                icon: 'question',
                                title: 'Sucesso!',
                                html: (data.data.message + '<br>Deseja ir para a página inicial?'),
                                showCancelButton: true,
                                confirmButtonText: 'Sim',
                                cancelButtonText: 'Não',
                                allowOutsideClick: false,
                                showLoaderOnConfirm: true,
                                allowEscapeKey: false,
                                preConfirm: () => {
                                    return new Promise(() => {
                                        window.location.href = me.routeHome;
                                    })
                                },
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.cancel) {
                                    location.reload();
                                }
                            });
                            setTimeout(function(){
                                me.loading = false;
                            }, 1000);
                        }).catch(error => {
                            this.$emit('showError', error)
                            me.loading = false;
                        });
                    }else{
                        Swal.fire(
                            'Oops...',
                            'Para continuar você deve antes resolver os erros presentes no formulário.',
                            'error'
                        )
                    }
                })
        },
        getInstituicao(){
            this.isLoading = true;
            submit(route('admin.instituicao.get-by-user'), {},'GET').then(
                data => {
                    this.payload = data;
                    this.payload.old_logomarca = data.logomarca;
                    if(!data.endereco){
                        this.payload.endereco = {'estado_id' : '', 'cidade_id' : ''};
                    }
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

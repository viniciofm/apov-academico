<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :title="title ? title : (id ? 'Atualização' : 'Cadastro')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (id ? 'Atualização' : 'Cadastro') }} de Empresa</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Empresa</h5>
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
                                <option value="" disabled selected>Nenhum</option>
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
                            <label for="responsavel">Responsável*</label>
                            <input class="form-control" v-model="payload.responsavel" id='responsavel' name="responsavel" maxlength="150" value="" type="text" placeholder="" required="required"
                                   v-validate="'required'"
                                   data-vv-as="'Reponsável'">
                            <div v-show="errors.has('responsavel')" class="text-danger" style="">{{ errors.first('responsavel') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="razaoSocial">Ativo</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" id="ativo" v-model="payload.ativo">
                                <label class="form-check-label" for="ativo">{{payload.ativo ? 'Sim' : 'Não'}}</label>
                            </div>
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
                                <button @click="$router.go(-1)"
                                        class="btn btn-warning mr-2">
                                    <span>Voltar</span>
                                </button>
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ id ? 'Atualizar' : 'Salvar' }}</span>
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
import SubHeader from "../../../components/SubHeader"
import CardTable from "../../../components/CardTable"
import Endereco from "../../../components/Endereco"
import Swal from "sweetalert2";

export default {
    name: "Empresa",
    data: () => ({
        subHeaderLinks:[['empresa', 'Empresas']],
        search: '',
        dataPaginate: {},
        payload:{
            'nome': '',
            'email': '',
            'responsavel': '',
            'telefone_contato': '',
            'cpf_cnpj': '',
            'tipo_documento': '',
            'logomarca': '',
            'user_id': null,
            'ativo': true,
            'endereco': {
                'id': null,
                'rua': '',
                'numero': null,
                'bairro': '',
                'complemento': '',
                'cep': '',
                'cidade_id': null,
                'estado_id': null
            }
        }
    }),
    props: [
        'id',
        'title'
    ],
    created() {
        // this.getCertificate();
    },
    components: {
        CardTable,
        SubHeader,
        Endereco
    },
    methods: {
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let payload = {...me.payload}
                        if(!payload.logomarca){
                            delete payload.logomarca
                        }

                        let url = route(me.id ? 'admin.empresa.update' : 'admin.empresa.store', me.id);
                        me.loading = true;
                        submit(url, payload, 'POST').then(
                            data => {
                                Swal.fire(
                                    'Sucesso!',
                                    data.message,
                                    'success'
                                )

                                setTimeout(function(){
                                    me.loading = false;
                                    me.$router.push({ path: `/` });
                                }, 1000);
                            }
                        ).catch(
                            error => {
                                Swal.fire(
                                    'Oops...',
                                    error.response.data.message,
                                    'error'
                                )
                                me.loading = false;
                            }
                        )
                    }else{
                        Swal.fire(
                            'Oops...',
                            'Para continuar você deve antes resolver os erros presentes no formulário.',
                            'error'
                        )
                    }
                })
        },
        resetSearch() {
            this.search = '';

            this.getData();
        },
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        getData() {

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

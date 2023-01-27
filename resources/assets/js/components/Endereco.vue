<template>
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-2">Endereço</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                    <label for="rua">Rua</label>
                    <input :disabled="read_only" class="form-control" id="rua" v-model="endereco.rua" name="rua" maxlength="150" value="" type="text">
                </div>

                <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                    <label for="numero">Número</label>
                    <input :disabled="read_only" class="form-control" id="numero" v-model="endereco.numero" name="numero" maxlength="150" value="" type="text"
                           v-validate="'numeric:0'"
                           data-vv-as="'Número'">
                    <div v-show="errors.has('numero')" class="text-danger" style="">{{ errors.first('numero') }}</div>
                </div>

                <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                    <label for="bairro">Bairro</label>
                    <input :disabled="read_only" class="form-control" id="bairro" v-model="endereco.bairro" name="bairro" maxlength="150" value="" type="text">
                </div>

                <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                    <label for="complemento">Complemento</label>
                    <input :disabled="read_only" class="form-control" id="complemento" v-model="endereco.complemento" name="complemento" maxlength="150" value="" type="text">
                </div>

                <div class="mb-3 col-lg-4 col-md-4 col-sm-12">
                    <label for="cep">CEP</label>
                    <input :disabled="read_only" class="form-control" v-mask="'#####-###'" v-model="endereco.cep" id="cep" name="cep" maxlength="150" value="" type="text">
                </div>

                <div class="mb-3 col-lg-2 col-md-2 col-sm-12">
                    <label for="estado_id">Estado</label>
                    <select :disabled="read_only" class="form-control" v-on:change="resetCidade()" data-bs-toggle="select2" v-model="endereco.estado_id" name="estado_id" id="estado_id">
                        <option value="" disabled selected>Não selecionado</option>
                        <option v-for="(estado, estado_id) in estados" :value="estado.id">{{estado.nome}}</option>
                    </select>
                </div>

                <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                    <label for="cidade_id">Cidade</label>
                    <select :disabled="read_only" class="form-control" data-bs-toggle="select2" v-model="endereco.cidade_id" name="cidade_id" id="cidade_id">
                        <option value="" disabled selected>Não selecionado</option>
                        <option v-if="endereco.estado_id != '' && estados[endereco.estado_id]" v-for="cidade in cidades" :value="cidade.id">{{cidade.nome}}</option>
                    </select>
                </div>
            </div>
        </div>
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"/>
    </div>
</template>

<script>
import Swal from "sweetalert2";
import { submit, toSeek} from '../common/send-form';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "Endereco",
    data: () => ({
        estados: {},
        isLoading: false,
    }),
    props: [
        'endereco',
        'read_only'
    ],
    computed: {
        cidades: function () {
            return this.endereco.estado_id && this.estados[this.endereco.estado_id] ? this.estados[this.endereco.estado_id].cidades : {}
        }
    },
    watch: {
        endereco: function () {
            if(!this.endereco.cidade_id) {
                this.endereco.estado_id = '';
                this.endereco.cidade_id = '';
            }
        }
    },
    mounted() {
        this.getCities()
    },
    components: {
        Loading
    },
    methods: {
        resetCidade(){
          this.endereco.cidade_id = '';
        },
        getCities(){
            this.isLoading = true;
            toSeek(route('api.cidades.get')).then(
                data => {
                    this.estados = data;
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

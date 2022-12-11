<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section v-if="turma_disciplina">
            <sub-header :links="subHeaderLinks" :module="'Aula'" :title="'Aula'"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (id ? 'Atualização' : 'Cadastro') }} de Aula</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Aula</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="data">Data*</label>
                            <div>
                                <date-picker class=""
                                             placeholder="Selecione uma data"
                                             format="DD/MM/YYYY" v-model="payload.data"
                                             id='data' name="data">
                                </date-picker>
                            </div>
                            <div v-show="errors.has('data')" class="text-danger" style="">{{ errors.first('data') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12" v-if="!id">
                            <label for="numero_aulas">Número de aulas*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.numero_aulas" name="numero_aulas" id="numero_aulas"
                                    v-validate="'required'"
                                    data-vv-as="'Número de aulas'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <div v-show="errors.has('numero_aulas')" class="text-danger" style="">{{ errors.first('numero_aulas') }}</div>
                        </div>

                        <div class="mb-3 col-lg-12 col-md-12 col-sm-12">
                            <label for="conteudo">Conteúdo*</label>
                            <textarea class="form-control" rows="4" v-model="payload.conteudo" id="conteudo" name="conteudo" maxlength="501" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|max:500'"
                                      data-vv-as="'Conteúdo'"></textarea>
                            <div v-show="errors.has('conteudo')" class="text-danger" style="">{{ errors.first('conteudo') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="text-right">
                                <button @click="$router.push({ name: 'professor.classes', params: routeCreateParams })"
                                        class="btn btn-warning mr-2">
                                    <span>Voltar</span>
                                </button>
                                <button v-can="'can-update'" @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ id ? 'Atualizar' : 'Salvar' }}</span>
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
import {submit, toSeek} from "../../../../common/send-form";
import Swal from "sweetalert2";
import SubHeader from "../../../../components/SubHeader"
import CardTable from "../../../../components/CardTable"
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import http from "../../../../api/http";
import moment from 'moment';

export default {
    name: "Class",
    data: () => ({
        moment: moment,
        subHeaderLinks:[['/','Disciplinas'],['/aulas','Aulas']],
        payload:{
            data: '',
            conteudo: '',
            numero_aulas: '',
        },
        isLoading: false,
        routeCreate:'professor.classes',
        routeCreateParams:{}
    }),
    mounted() {
        if (!this.turma_disciplina){
            this.$router.push({path: `/`});
        }else{
            this.routeCreateParams = { 'turma_disciplina': this.turma_disciplina }
        }

        if (this.id) {
            this.getData();
        }
    },
    components: {
        CardTable,
        SubHeader,
        Loading,
        DatePicker
    },
    props: [
        'id',
        'turma_disciplina',
    ],
    methods: {
        dateFormat(value) {
            let date = new Date(value);
            return date.toLocaleDateString();
        },
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let formData = new FormData();

                        formData.append('data', this.payload.data ? this.payload.data.toISOString() : '');
                        formData.append('conteudo', me.payload.conteudo);
                        formData.append('turma_disciplina_id', me.turma_disciplina.id);
                        if (!me.id){
                            formData.append('numero_aulas', me.payload.numero_aulas);
                        }

                        let url = route(me.id ? 'content.aula.update' : 'content.aula.store', me.id);
                        me.isLoading = true;

                        http.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(data => {
                            Swal.fire(
                                'Sucesso!',
                                data.data.message,
                                'success'
                            )
                            setTimeout(function(){
                                me.isLoading = false;
                                me.$router.push({ name: 'professor.classes', params: me.routeCreateParams });
                            }, 1000);
                        }).catch(error => {
                            this.$emit('showError', error)
                            me.isLoading = false;
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
        getData() {
            this.isLoading = true;
            submit(route('content.aula.edit', this.id), {},'GET').then(
                data => {
                    this.payload = data.registro;
                    this.payload.data = data.registro.data ? new Date(this.moment(data.registro.data).toDate()) : '';
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

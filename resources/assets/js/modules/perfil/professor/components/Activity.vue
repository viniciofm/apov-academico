<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section v-if="turma_disciplina">
            <sub-header :links="subHeaderLinks" :module="'Atividade'" :title="'Atividade'"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (id ? 'Atualização' : 'Cadastro') }} de Atividade</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Atividade</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-12 col-md-12 col-sm-12">
                            <label for="data">Data*</label>
                            <div>
                                <date-picker class=""
                                             placeholder="Selecione uma data"
                                             format="DD/MM/YYYY" v-model="payload.data"
                                             v-validate="'required'"
                                             id='data' name="data">
                                </date-picker>
                            </div>
                            <div v-show="errors.has('data')" class="text-danger" style="">{{ errors.first('data') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="titulo">Título*</label>
                            <input class="form-control" v-model="payload.titulo" id="titulo" name="titulo" maxlength="20" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|max:20'"
                                   data-vv-as="'Título'">
                            <div v-show="errors.has('titulo')" class="text-danger" style="">{{ errors.first('titulo') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="peso">Peso*</label>
                            <input class="form-control" v-model="payload.peso" id="peso" name="peso" value="" type="number" placeholder="" required="required"
                                   v-validate="'required|decimal:2|min_value:1|max_value:100'"
                                   data-vv-as="'Peso'">
                            <div v-show="errors.has('peso')" class="text-danger" style="">{{ errors.first('peso') }}</div>
                        </div>

                        <div class="mb-3 col-lg-12 col-md-12 col-sm-12">
                            <label for="descricao">Descrição*</label>
                            <textarea class="form-control" rows="3" v-model="payload.descricao" id="descricao" name="descricao" maxlength="201" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|max:200'"
                                      data-vv-as="'Descrição'"></textarea>
                            <div v-show="errors.has('descricao')" class="text-danger" style="">{{ errors.first('descricao') }}</div>
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
                                <button @click="$router.push({ name: routeCreate, params: routeCreateParams })"
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
    name: "Activity",
    data: () => ({
        moment: moment,
        subHeaderLinks:[['/','Disciplinas'],['/atividades','Atividades']],
        payload:{
            data: '',
            peso: '',
            titulo: '',
            descricao: '',
        },
        isLoading: false,
        routeCreate:'professor.activities',
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
                        formData.append('titulo', me.payload.titulo);
                        formData.append('peso', me.payload.peso);
                        formData.append('descricao', me.payload.descricao);
                        formData.append('turma_disciplina_id', me.turma_disciplina.id);

                        let url = route(me.id ? 'content.atividade.update' : 'content.atividade.store', me.id);
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
                                me.$router.push({ name: me.routeCreate, params: me.routeCreateParams });
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
            submit(route('content.atividade.edit', this.id), {},'GET').then(
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

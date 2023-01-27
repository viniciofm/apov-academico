<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section v-if="turma_disciplina">
            <sub-header :links="subHeaderLinks" :module="'Faltas'" :title="'Faltas'"></sub-header>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-4 col-lg-12 col-md-12 col-sm-12">
                            <label for="status">Data*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="data" name="data" id="data"
                                    v-validate="'required'"
                                    data-vv-as="'Data'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option v-for="(item) in dates" :value="item">{{ moment(item).format('DD/MM/YYYY') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <h4 class="card-title">FALTAS <label v-if="data">PARA O DIA
                                {{ moment(data).format('DD/MM/YYYY') }}</label>
                            </h4>
                            <h6 v-if="data" class="card-subtitle text-muted text-danger">Marque as faltas para os alunos.</h6>
                        </div>

                        <Table :columns="columns">
                            <tr v-for="(item, index) of alunos" :key="item.id">
                                <td scope="col" class="text-center">
                                    {{ item.matricula }}
                                </td>
                                <td scope="col" class="text-center">
                                    {{ item.nome }}
                                </td>
                                <td scope="col" class="text-center">
                                    <input v-for="falta of item.falta_aula" style="margin-right: 10px;" class="form-check-input" type="checkbox"
                                           :id="'falta_' + item.matricula +  '_' + falta.id"
                                           :value="falta.falta" v-model="falta.falta" checked="">
                                </td>
                            </tr>
                        </Table>
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
                                    <span>{{ 'Salvar' }}</span>
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
import Table from '../../../../components/Table';

export default {
    name: "Grades",
    data: () => ({
        moment: moment,
        subHeaderLinks:[['/','Disciplinas'],['/aulas','Aulas']],
        columns: ['Matrícula', 'Aluno', 'Falta(s)'],
        data:'',
        dates:{},
        alunos:{},
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

        if (this.turma_disciplina) {
            this.getDates();
        }
    },
    watch:{
        data: function () {
            this.getData()
        }
    },
    components: {
        CardTable,
        SubHeader,
        Loading,
        DatePicker,
        Table
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

                        let faltas = []
                        me.alunos.forEach(item => {
                            item.falta_aula.forEach(aula => {
                                if(aula.falta || aula.presenca_id){
                                    faltas.push({
                                        presenca_id: aula.presenca_id,
                                        falta: aula.falta,
                                        matricula_id: item.matricula_id,
                                        aula_id: aula.id
                                    })
                                }
                            })
                        })

                        formData.append('faltas', JSON.stringify(faltas));

                        let url = route('content.aula.grades.store', me.turma_disciplina.id);
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
            submit(route('content.aula.get-grades',
                this.turma_disciplina.id
            ), {'data' : this.data},'POST').then(
                data => {
                    this.alunos = data;
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
        },
        getDates() {
            this.isLoading = true;
            submit(route('content.aula.dates',
                this.turma_disciplina.id
            ), {},'GET').then(
                data => {
                    this.dates = data;
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

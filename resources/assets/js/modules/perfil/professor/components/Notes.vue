<template xmlns="http://www.w3.org/1999/html">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section v-if="turma_disciplina && payload.id">
            <sub-header :links="subHeaderLinks" :module="'Atividade'" :title="'Atividade'"></sub-header>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">NOTAS PARA A ATIVIDADE <label style="font-style:italic">{{ payload.titulo }}</label> </h4>
                            <h6 class="card-subtitle text-muted"> Valor: {{ payload.peso.replace('.', ',') }} pontos</h6>
                        </div>

                        <Table :columns="columns">
                            <tr v-for="(item, index) of payload.turma_disciplina.matriculas_ativas_turma" :key="item.id">
                                <td scope="col" class="text-center">
                                    {{ item.matricula.aluno.matricula }}
                                </td>
                                <td scope="col">
                                    {{ item.matricula.aluno.usuario.nome }}
                                </td>
                                <td scope="col" class="text-center">
                                    <input class="form-control" v-model="item.nota_atividade.nota" :id="'nota' + index" :name="'nota' + index" value="" type="number" placeholder="" required="required"
                                           v-validate="'required|decimal:2|min_value:0|max_value:' + payload.peso"
                                           data-vv-as="'Nota'">
                                    <div v-show="errors.has('nota' + index)" class="text-danger" style="">{{ errors.first('nota' + index) }}</div>
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
    name: "Notes",
    data: () => ({
        moment: moment,
        subHeaderLinks:[['/','Disciplinas'],['/atividades','Atividades']],
        columns: ['Matrícula', 'Aluno', 'Nota'],
        payload:{},
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

                        //enviar apenas as notas e o id da atividade
                        // pegar o id da matricula e a nota
                        let notas = []
                        me.payload.turma_disciplina.matriculas_ativas_turma.forEach(item => {
                            notas.push({'nota': item.nota_atividade.nota, 'matricula_id': item.matricula_id, 'id': item.nota_atividade ? item.nota_atividade.id : null })
                        })

                        formData.append('notas', JSON.stringify(notas));

                        let url = route('content.atividade.notes.store', me.id);
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
            submit(route('content.atividade.notes', this.id), {},'GET').then(
                data => {
                    this.payload = data.registro;
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

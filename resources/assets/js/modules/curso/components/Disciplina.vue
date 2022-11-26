<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Cursos'" :title="title ? title : ((disciplina_id ? 'Atualização' : 'Cadastro') + ' de Disciplina')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (disciplina_id ? 'Atualização' : 'Cadastro') }} de Disciplina</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Disciplina</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Curso</label>
                            <label class="form-control">{{ curso.sigla }} / {{ curso.nome }}</label>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Grade</label>
                            <label class="form-control">{{ grade.codigo }}</label>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="sigla">Sigla*</label>
                            <input class="form-control" v-model="payload.sigla" id="sigla" name="sigla" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|alpha_num|length:6'"
                                   data-vv-as="'Sigla'">
                            <div v-show="errors.has('sigla')" class="text-danger" style="">{{ errors.first('sigla') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Nome*</label>
                            <input class="form-control" v-model="payload.nome" id="nome" name="nome" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|max:80'"
                                   data-vv-as="'Nome'">
                            <div v-show="errors.has('nome')" class="text-danger" style="">{{ errors.first('nome') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="carga_horaria">Carga Horária (h)*</label>
                            <input class="form-control" v-model="payload.carga_horaria" id="carga_horaria" name="carga_horaria" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|numeric|max_value:200'"
                                   data-vv-as="'Carga Horária'">
                            <div v-show="errors.has('carga_horaria')" class="text-danger" style="">{{ errors.first('carga_horaria') }}</div>
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
                                <button @click="$router.go(-1)"
                                        class="btn btn-warning mr-2">
                                    <span>Voltar</span>
                                </button>
                                <button @click="save()"
                                        class="btn btn-primary">
                                    <span>{{ disciplina_id ? 'Atualizar' : 'Salvar' }}</span>
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
import Swal from "sweetalert2";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "Curso",
    data: () => ({
        search: '',
        dataPaginate: {},
        payload:{
            'sigla': '',
            'nome': '',
            'carga_horaria': ''
        },
        isLoading: false,
        curso: {},
        grade: {},
    }),
    props: [
        'grade_id',
        'curso_id',
        'disciplina_id',
        'title'
    ],
    computed: {
        subHeaderLinks: function() {
            return [['/', 'Cursos'], ['/' + this.curso.id + '/grades', 'Grades'], ['', this.grade.codigo]];
        }
    },
    created() {
        this.getGrade();

        if (this.disciplina_id) {
            this.getData();
        }
    },
    components: {
        SubHeader,
        Loading
    },
    methods: {
        getGrade(){
            if(this.curso_id) {
                this.isLoading = true;
                submit(route('admin.curso.grade.get-by-id', this.grade_id), {}, 'GET').then(
                    data => {
                        this.curso = data.registro.curso;
                        this.grade = data.registro.grade;
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
        },
        save(){
            this.$validator.validateAll().then(
                res => {
                    if (res) {
                        let me = this

                        let formData = new FormData();

                        formData.append('curso_id', this.curso_id);
                        formData.append('grade_id', this.grade_id);
                        formData.append('sigla', this.payload.sigla);
                        formData.append('nome', this.payload.nome);
                        formData.append('carga_horaria', this.payload.carga_horaria);

                        let url = route(me.disciplina_id ? 'admin.curso.grade.disciplina.update' : 'admin.curso.grade.disciplina.store', me.disciplina_id);
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
                                me.$router.push({name: `curso.grids.disciplines`, params: {'curso_id': me.curso_id, 'grade_id': me.grade_id}});
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
            submit(route('admin.curso.grade.disciplina.edit', this.disciplina_id), {},'GET').then(
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

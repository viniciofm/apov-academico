<template>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <section>
            <sub-header :links="subHeaderLinks" :module="'Cursos'" :title="title ? title : ((grade_id ? 'Atualização' : 'Cadastro') + ' de Grade')"></sub-header>

            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-2">Formulário de {{ (grade_id ? 'Atualização' : 'Cadastro') }} de Grade</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-0">Dados da Grade</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="nome">Curso</label>
                            <label class="form-control">{{ curso.sigla }} / {{ curso.nome }}</label>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="codigo">Código*</label>
                            <input class="form-control" v-model="payload.codigo" id="codigo" name="codigo" maxlength="20" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|alpha_num|max:15'"
                                   data-vv-as="'Código'">
                            <div v-show="errors.has('codigo')" class="text-danger" style="">{{ errors.first('codigo') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="ano">Ano*</label>
                            <input class="form-control" v-model="payload.ano" id="ano" name="ano" maxlength="4" value="" type="text" placeholder="" required="required"
                                   v-validate="'required|numeric:0|length:4'"
                                   data-vv-as="'Ano'">
                            <div v-show="errors.has('ano')" class="text-danger" style="">{{ errors.first('ano') }}</div>
                        </div>

                        <div class="mb-3 col-lg-6 col-md-6 col-sm-12">
                            <label for="periodo">Período*</label>
                            <select class="form-control" data-bs-toggle="select2" v-model="payload.periodo" name="periodo" id="periodo" required="required"
                                    v-validate="'required'"
                                    data-vv-as="'Período'">
                                <option value="" disabled selected>Não selecionado</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                            <div v-show="errors.has('periodo')" class="text-danger" style="">{{ errors.first('periodo') }}</div>
                        </div>

                        <div class="mb-3 col-lg-3 col-md-3 col-sm-12">
                            <label for="ativo">Ativo</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" id="ativo" v-model="payload.ativo">
                                <label class="form-check-label" for="ativo">{{payload.ativo ? 'Sim' : 'Não'}}</label>
                            </div>
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
                                    <span>{{ grade_id ? 'Atualizar' : 'Salvar' }}</span>
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
        subHeaderLinks:[['/', 'Cursos']],
        search: '',
        dataPaginate: {},
        payload:{
            'codigo': '',
            'ano': '',
            'periodo': '',
            'ativo': true,
        },
        isLoading: false,
        curso: {}
    }),
    props: [
        'grade_id',
        'curso_id',
        'title'
    ],
    created() {
        this.getCurso();

        if (this.grade_id) {
            this.getData();
        }
    },
    components: {
        SubHeader,
        Loading
    },
    methods: {
        getCurso(){
            if(this.curso_id) {
                this.isLoading = true;
                submit(route('admin.curso.get-by-id', this.curso_id), {}, 'GET').then(
                    data => {
                        this.curso = data.registro;
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
                        formData.append('codigo', this.payload.codigo);
                        formData.append('ano', this.payload.ano);
                        formData.append('periodo', this.payload.periodo);
                        formData.append('ativo', this.payload.ativo ? 1 : 0);

                        let url = route(me.grade_id ? 'admin.curso.grade.update' : 'admin.curso.grade.store', me.grade_id);
                        me.loading = true;

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
                                me.loading = false;
                                me.$router.push({name: `curso.grids`, params: {'curso_id': me.curso_id}});
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
        getData() {
            this.isLoading = true;
            submit(route('admin.curso.grade.edit', this.grade_id), {},'GET').then(
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

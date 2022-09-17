<template>
    <section>
        <div class="container-fluid">
            <div class="header">
                <h1 class="header-title">
                    Empresas
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">In&iacute;cio</a></li>
                        <li class="breadcrumb-item"><a href="#">Empresas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cadastradas</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mb-3">
            <div class="bg-holder d-none d-lg-block bg-card gb-title">
            </div>
            <!--/.bg-holder-->
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title">EMPRESAS CADASTRADAS</h4>
                                <h6 class="card-subtitle text-muted">Utilize o módulo para ver suas empresas cadastradas ou então editar e excluí-las.</h6>
                            </div>
                            <div style="text-align: right;" class="col-md-6">
                                <button class="btn btn-primary dropdown-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filtro" aria-expanded="false" aria-controls="filtro"><i class="fa fa-filter" aria-hidden="true"></i>
                                    Filtrar
                                </button>
                                <a id="cadastrarEmpresa" type="button" title="Inserir um novo registro" class="btn btn-success" href="<?php echo base_url(); ?>/empresas/new"><i class="fa fa-plus" aria-hidden="true"></i>
                                    Cadastrar empresa
                                </a>
                                <div class="collapse" id="filtro" style="">
                                    <div class="col-md-12 mt-3 row">
                                        <div class="form-group col-lg-7 col-md-7 col-sm-6">
                                            <label for="razaoSocial">Razão Social</label>
                                            <input class="form-control " id="razaoSocial" name="razaoSocial" maxlength="200" value="<?php echo (isset($_GET["razaoSocial"])) ? $_GET["razaoSocial"] : ""; ?>" type="text" placeholder="">
                                        </div>

                                        <div class="form-group col-lg-3 col-md-3 col-sm-6">
                                            <label for="cnpj">CNPJ</label>
                                            <input class="form-control cnpj" id="cnpj" name="cnpj" maxlength="20" value="<?php echo (isset($_GET["cnpj"])) ? $_GET["cnpj"]  : ""; ?>" type="text" placeholder="">
                                        </div>

                                        <div class="form-group col-lg-1 col-md-1 col-sm-6">
                                            <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "Test"
}
</script>

<style scoped>

</style>

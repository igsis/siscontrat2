<?php
    include "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <h3 class="box-title">Eventos - Gerar Excel - Filtrar</h3>
        <div class="box box-primary">
            <form method="POST" action="?perfil=agendao&p=exporta_evento">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="inserido">Inserido por (usuário)</label>
                            <input type="text" class="form-control" id="inserido" name="inserido" placeholder="">
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="projetoEspecial">Projeto Especial</label> <br>
                            <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                <option value="">Selecione uma opção...</option>
                                <?php
                                //geraOpcao("");
                                ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="instituicao">Instituição</label>
                            <select name="instituicao" class="form-control" id="instituicao">
                                <option value="">Seleciona uma Opção...</option>
                                <?php //geraOpcao(""); ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="local">Local</label>
                            <select name="local" class="form-control" id="local">
                                <option value="">Seleciona uma Opção...</option>
                                <?php //geraOpcao(""); ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-3">
                            <label>Data início *</label>
                            <input type="date" name="dataInicio" class="form-control" id="dataInicio"
                                   onchange="desabilitaFiltrar()" placeholder="">
                        </div>
                        <div class="col-md-3">
                            <label>Data encerramento</label>
                            <input type="date" name="dataEncerramento" class="form-control" id="dataEncerramento"
                                   placeholder="">
                            <br>
                        </div>

                        <input type="submit" class="btn btn-theme btn-block" name="filtrar" id="filtrar" value="Filtrar"
                               disabled>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    function desabilitaFiltrar() {

        var inicio = document.querySelector("#dataInicio");
        var filtrar = document.querySelector("#filtrar");

        if (inicio.value.length != 0) {
            filtrar.disabled = false;
        } else {
            filtrar.disabled = true;
        }
    }
</script>
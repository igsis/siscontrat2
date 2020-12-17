<?php
include "includes/menu_principal.php";
?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Gerar Excel - Filtrar</h3>
        <div class="box box-primary" id="filtro">
            <form method="POST" action="?perfil=agendao&p=resultado">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="inserido">Inserido por: (usuário)</label>
                            <input type="text" class="form-control" id="inserido" name="inserido" placeholder="">
                        </div>

                        <div class="col-md-4">
                            <label for="projetoEspecial">Projeto Especial: </label> <br>
                            <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                <option value="">Selecione uma opção...</option>
                                <?php
                                geraOpcaoPublicado("projeto_especiais", "");
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="local">Local: </label>
                            <select name="local" class="form-control" id="local">
                                <option value="">Seleciona uma opção...</option>
                                <?php
                                geraOpcao("locais"); ?>
                            </select>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Data início: </label>
                            <input type="date" name="data_inicio" class="form-control" id="data_inicio">
                        </div>
                        <div class="col-md-6">
                            <label>Data encerramento: </label>
                            <input type="date" name="data_fim" class="form-control" id="data_fim">
                            <br>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right" id="filtrar"
                                name="filtrar">Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>


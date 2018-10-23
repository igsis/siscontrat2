<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="quantidade_contratados">Quantidade de contratados</label><br/>
                                    <input type="text" name="genero" size="25">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipo_contratação">Tipo de contratação</label> <br>
                                    <label><input type="text" size="22" </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cache">Valor do cache</label> <br>
                                    <label><input type="number" size="30"</label>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                <button type="submit" name="salvar" class="btn btn-info pull-right">Salvar</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

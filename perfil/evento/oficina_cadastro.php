<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

$idAtracao = $_POST['idAtracao'];

?>

<script language="JavaScript">
    function barraData(n) {
        if (n.value.length == 2)
            c.value += '/';
        if (n.value.length == 5)
            c.value += '/';
    }
</script>


<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="modalidade">Modalidade:</label>
                                    <input type="text" id="modalidade"  name="modalidade" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="desc_modalidade">Descrição da Modalidade:</label><br/>
                                    <textarea name="desc_modalidade" id="desc_modalidade" class="form-control"
                                              rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>dia da semana1:</label>
                                        <label>dia da semana2:</label>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="valor_hora">Valor hora/aula: </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="tel" name="valor_hora"
                                            onkeypress="return(moeda(this, '.', ',', event))">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="carga_horaria">Carga Horária (em horas): </label><br>
                                    <input class="form-control" style="max-width: 175px;" type="number"
                                           name="carga_horaria"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="data_inicio">Início de inscrição: </label> <br/>
                                    <input class="form-control" style="max-width: 175px;" type="date" name="data_inicio"
                                           onkeyup="barraData(this);"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="data_fim">Encerramento de inscrição: </label> <br>
                                    <input class="form-control" style="max-width: 175px;" type="date" name="data_fim"/>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a href="?perfil=evento&p=atracoes_lista">
                                <button type="button" class="btn btn-default">Voltar</button>
                            </a>
                            <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
                            <button type="submit" name="cadastra" class="btn btn-info pull-right">Cadastrar</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
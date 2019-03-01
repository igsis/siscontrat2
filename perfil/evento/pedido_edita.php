<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idPedido = $_SESSION['idPedido'];

if(isset($_POST['cadastra']) || isset($_POST['edita'])){
    $verba_id = $_POST['verba_id'];
    $forma_pagamento = addslashes($_POST['forma_pagamento']);
    $justificativa = addslashes($_POST['justificativa']);
    $observacao = addslashes($_POST['observacao']);
    $numero_parcelas = $_POST['numero_parcelas'] ?? NULL;
    $data_kit_pagamento = $_POST['data_kit_pagamento'] ?? NULL;
}

if(isset($_POST['cadastra'])){
    if (isset($data_kit_pagamento)) {
        $sql_cadastra = "UPDATE pedidos SET verba_id = '$verba_id', forma_pagamento = '$forma_pagamento', data_kit_pagamento = '$data_kit_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    } else {
        $sql_cadastra = "UPDATE pedidos SET verba_id = '$verba_id', forma_pagamento = '$forma_pagamento', numero_parcelas = '$numero_parcelas', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    }

    if(mysqli_query($con,$sql_cadastra)){
        $mensagem = mensagem("success", "Gravado com sucesso!");
    }
    else{
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

if(isset($_POST['edita'])){
    $valor_total = dinheiroDeBr($_POST['valor_total']);
    $numero_parcelas = $_POST['numero_parcelas'];

    $sql_edita = "UPDATE pedidos SET verba_id = '$verba_id', valor_total = '$valor_total', numero_parcelas = '$numero_parcelas', data_kit_pagamento = null, forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    if(mysqli_query($con,$sql_edita)){
        $mensagem = mensagem("success","Gravado com sucesso.");
    }
    else{
        $mensagem = mensagem("danger","Erro ao gravar: ". die(mysqli_error($con)));
    }
}

if(isset($_POST['data_kit_pagamento'])){
    $data_kit_pagamento = $_POST['data_kit_pagamento'];
    $sql_edita = "UPDATE pedidos SET data_kit_pagamento = '$data_kit_pagamento' WHERE id = '$idPedido'";
    if(mysqli_query($con,$sql_edita)){
        $mensagem = mensagem("success","Gravado com sucesso.");
    }
    else{
        $mensagem = mensagem("danger","Erro ao gravar: ". die(mysqli_error($con)));
    }
}

$pedido = recuperaDados("pedidos","id",$idPedido);

if($pedido['pessoa_tipo_id'] == 2){
    $pj = recuperaDados("pessoa_juridicas","id",$pedido['pessoa_juridica_id']);
    $proponente = $pj['razao_social'];
    $idProponente = $pj['id'];
    $link_edita = "?perfil=evento&p=pj_edita";
    $link_troca = "?perfil=evento&p=pj_pesquisa";
}
else{
    $pf = recuperaDados("pessoa_fisicas","id",$pedido['pessoa_fisica_id']);
    $proponente = $pf['nome'];
    $idProponente = $pf['id'];
    $link_edita = "?perfil=evento&p=pf_edita";
    $link_troca = "?perfil=evento&p=pf_pesquisa";
}

$displayEditar = "display: none";
$displayKit = "display: block";

if (isset($pedido['numero_parcelas'])) {
    if ($pedido['numero_parcelas'] != 1 || $pedido['numero_parcelas'] != "") {
        $displayEditar = "display: block";
        $displayKit = "display: none";
    } else {
        $displayEditar = "display: none";
        $displayKit = "display: block";
    }
}


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row" align="center">
            <?php if(isset($mensagem)){echo $mensagem;};?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->

                <!-- pedido -->
                <div class="box box-info" onload="ocultarBotao()">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalhes</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_edita" name="form_principal" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="verba_id">Verba</label>
                                    <select class="form-control" id="verba_id" name="verba_id">
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("verbas",$pedido['verba_id'])
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" id="valor_total" name="valor_total" class="form-control" value="<?= dinheiroParaBr($pedido['valor_total']) ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="numero_parcelas">Número de Parcelas</label>
                                    <select onchange="ocultarBotao()" class="form-control" id="numero_parcelas" name="numero_parcelas">
                                        <option value="<?= $pedido['numero_parcelas'] ? $pedido['numero_parcelas'] : "0"?>">
                                            <?=$pedido['numero_parcelas'] ? $pedido['numero_parcelas'] : "Selecione..."?>
                                        </option>
                                        <option value="1">Parcela Única</option>
                                        <option value="2">2 parcelas</option>
                                        <option value="3">3 parcelas</option>
                                        <option value="4">4 parcelas</option>
                                        <option value="5">5 parcelas</option>
                                        <option value="6">6 parcelas</option>
                                        <option value="7">7 parcelas</option>
                                        <option value="8">8 parcelas</option>
                                        <option value="9">9 parcelas</option>
                                        <option value="10">10 parcelas</option>
                                        <option value="11">11 parcelas</option>
                                        <option value="12">12 parcelas</option>
                                    </select>
                                </div>
                                    <div class="form-group col-md-2" id="editarParcelas" style="<?=$displayEditar?>">
                                        <input type="submit" value="Editar Parcelas" formaction="?perfil=evento&p=parcelas_cadastro" onclick="alteraPagina()" name="editarParcelas" class="btn btn-info btn-block" style="margin-top: 24px">
                                    </div>
                                    <div class="form-group col-md-2" id="data_kit_pagamento" style="<?=$displayKit?>">
                                        <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                        <input type="date" id="data_kit_pagamento" name="data_kit_pagamento" class="form-control" value="<?= $pedido['data_kit_pagamento'] ?? NULL ?>">
                                    </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="forma_pagamento">Forma de pagamento</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" rows="8"><?= $pedido['forma_pagamento'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa</label><br/>
                                    <textarea id="justificativa" name="justificativa" class="form-control" rows="8"><?= $pedido['justificativa'] ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <input type="text" id="observacao" name="observacao" class="form-control" maxlength="255" value="<?= $pedido['observacao'] ?>">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" onClick="document.form_principal.submit()"  name="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
                <!-- /.pedido -->
                <!-- proponente -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro de Proponente</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="proponente">Proponente</label>
                                <input type="text" id="proponente" name="proponente" class="form-control" disabled value="<?= $proponente ?>">
                            </div>
                            <div class="form-group col-md-2"><label><br></label>
                                <form method="POST" action="<?= $link_edita ?>" role="form">
                                    <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                    <button type="submit" name="editProponente" class="btn btn-primary btn-block">Editar Proponente</button>
                                </form>
                            </div>
                            <div class="form-group col-md-2"><label><br></label>
                                <form method="POST" action="<?= $link_troca ?>" role="form">
                                    <button type="submit" name = "trocar" class="btn btn-primary btn-block">Trocar de Proponente</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- líderes -->
                <?php
                //if($pedido['pessoa_tipo_id'] == 2){
                $sql_atracao = "SELECT * FROM atracoes AS a                                              
                                            LEFT JOIN lideres l on a.id = l.atracao_id
                                            left join pessoa_fisicas pf on l.pessoa_fisica_id = pf.id
                                            WHERE evento_id = '".$_SESSION['idEvento']."'";
                $query_atracao = mysqli_query($con,$sql_atracao);
                ?>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Líder</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Atração</th>
                                    <th>Proponente</th>
                                    <th width="10%">Ação</th>
                                </tr>
                                </thead>
                                <?php
                                echo "<tbody>";
                                while($atracao = mysqli_fetch_array($query_atracao)){
                                    //analisaArray($atracao);
                                    echo "<tr>";
                                    echo "<td>".$atracao['nome_atracao']."</td>";
                                    if($atracao['pessoa_fisica_id'] > 0){
                                        echo "<td>".$atracao['nome']."</td>";
                                        echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pessoa_fisica\" role=\"form\">
                                            <input type='hidden' name='idAtracao' value='".$atracao['id']."'>
                                            <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class='fa fa-refresh'></i> Trocar</button>
                                            </form>
                                        </td>";
                                    }
                                    else{
                                        echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pessoa_fisica\" role=\"form\">
                                            <input type='hidden' name='idAtracao' value='".$atracao['id']."'>
                                            <button type=\"submit\" name='pesquisar' class=\"btn btn-primary\"><i class='fa fa-plus'></i> Adicionar</button>
                                            </form>
                                        </td>";
                                        echo "<td></td>";
                                    }
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                ?>
                            </table>
                        </div>
                    </div>
                <?php
                //}
                ?>
                <!-- parecer -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Parecer artístico</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            Aqui vai o parecer
                        </div>
                        <div class="row">
                            <div class="form-group col-md-offset-4 col-md-2">
                                <form method="POST" action="?perfil=evento&p=parecer_artistico&artista=local" role="form">
                                    <button type="submit" name = "idPedido" value="<?= $idPedido ?>" class="btn btn-primary btn-block">Artista Local</button>
                                </form>
                            </div>
                            <div class="form-group col-md-2">
                                <form method="POST" action="?perfil=evento&p=parecer_artistico&artista=local" role="form">
                                    <button type="submit" name = "idPedido" value="<?= $idPedido ?>" class="btn btn-primary btn-block">Artista Consagrado</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>

<script>
    $('#valor_total').mask('000.000.000.000.000,00', {reverse: true});

    function ocultarBotao() {

        var optionSelect = document.querySelector("#numero_parcelas").value;
        console.log(optionSelect);
        var editarParcelas = document.querySelector('#editarParcelas');
        var dataKit = document.querySelector("#data_kit_pagamento");

        if (optionSelect == "1" || optionSelect == 0) {
            editarParcelas.style.display = "none";
            dataKit.style.display = "block";
        } else {
            editarParcelas.style.display = "block";
            dataKit.style.display = "none";
        }
    }

    function AjaxF()
    {
        var ajax;

        try
        {
            ajax = new XMLHttpRequest();
        }
        catch(e)
        {
            try
            {
                ajax = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e)
            {
                try
                {
                    ajax = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch(e)
                {
                    alert("Seu browser não da suporte à AJAX!");
                    return false;
                }
            }
        }
        return ajax;
    }

    function alteraPagina()
    {
        var ajax = AjaxF();

        ajax.onreadystatechange = function(){
            if(ajax.readyState == 4)
            {
                document.getElementById('numero_parcelas').innerHTML = ajax.responseText;
            }
        }

        // Variável com os dados que serão enviados ao PHP
        var parcelas = "parcelas="+document.getElementById('numero_parcelas').value;

        ajax.open("POST", "?perfil=evento&p=parcelas_cadastro"+parcelas, false);
        ajax.setRequestHeader("Content-Type", "text/html");
        ajax.send();
    }


</script>
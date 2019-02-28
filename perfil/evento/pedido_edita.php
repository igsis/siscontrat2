<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$idPedido = $_SESSION['idPedido'];

if(isset($_POST['cadastra']) || isset($_POST['edita'])){
    $verba_id = $_POST['verba_id'];
    $valor_total = dinheiroDeBr($_POST['valor_total']);
    $numero_parcelas = $_POST['numero_parcelas'];
    $data_kit_pagamento = $_POST['data_kit_pagamento'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $justificativa = $_POST['justificativa'];
    $observacao = $_POST['observacao'];
}

if(isset($_POST['cadastra'])){
    $sql_cadastra = "";
}

if(isset($_POST['edita'])){

    $idPedido = $_SESSION['idPedido'];
    $sql_edita = "UPDATE pedidos SET verba_id = '$verba_id', valor_total = '$valor_total', numero_parcelas = '$numero_parcelas', data_kit_pagamento = '$data_kit_pagamento', forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    if(mysqli_query($con,$sql_edita)){
        $mensagem = mensagem("success","Gravado com sucesso.");
    }
    else{
        $mensagem = mensagem("danger","Erro ao gravar: ". die(mysqli_error($con)));
    }
}

if(isset($_POST['carregar'])){
    $idPedido = $_POST['idPedido'];
    $_SESSION['idPedido'] = $idPedido;
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
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalhes</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
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
                                    <input type="text" id="valor_total" name="valor_total" class="form-control" value="<?= $pedido['valor_total'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="numero_parcelas">Número de Parcelas</label>
                                    <select class="form-control" id="numero_parcelas" name="numero_parcelas">
                                        <option value="">Selecione...</option>
                                        <option value="1">Única</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                    <input type="date" id="data_kit_pagamento" name="data_kit_pagamento" class="form-control" value="<?= $pedido['data_kit_pagamento'] ?>">
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
                            <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>
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
</script>
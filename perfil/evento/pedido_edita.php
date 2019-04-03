<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

if (isset($_POST['idProponente'])) {
    $idProponente = $_POST['idProponente'];
    $tipoPessoa = $_POST['tipoPessoa'];
}

if (isset($_POST['carregar'])) {
    $_SESSION['idPedido'] = $_POST['idPedido'];
    $idPedido = $_SESSION['idPedido'];
    $pedido = recuperaDados("pedidos", "id", $idPedido);
}

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $verba_id = $_POST['verba_id'];
    $valor_total = dinheiroDeBr($_POST['valor_total']);
    $forma_pagamento = addslashes($_POST['forma_pagamento']);
    $justificativa = addslashes($_POST['justificativa']);
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $numero_parcelas = $_POST['numero_parcelas'] ?? NULL;
    $data_kit_pagamento = $_POST['data_kit_pagamento'] ?? NULL;

    if ($tipoPessoa == 1) {
        $campo = "pessoa_fisica_id";
    } else {
        $campo = "pessoa_juridica_id";
    }

    if (isset($_POST['cadastra'])) {
        $sql_cadastra = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, $campo, verba_id, numero_parcelas, valor_total, forma_pagamento, data_kit_pagamento, justificativa, status_pedido_id, observacao) 
                          VALUES ('1', '$idEvento', '$tipoPessoa', '$idProponente', '$verba_id', '$numero_parcelas', '$valor_total', '$forma_pagamento', '$data_kit_pagamento', '$justificativa', 1, '$observacao')";

        if (mysqli_query($con, $sql_cadastra)) {
            gravarLog($sql_cadastra);

            $_SESSION['idPedido'] = recuperaUltimo("pedidos");
            $idPedido = $_SESSION['idPedido'];

            $mensagem = mensagem("success", "Pedido cadastrado com sucesso!");

        } else {
            $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        }
    }

    if (isset($_POST['edita'])) {
        $idPedido = $_SESSION['idPedido'];
        $numero_parcelas = $_POST['numero_parcelas'] ?? $pedido['numero_parcelas'];
        if ($numero_parcelas != 1){
            $data_kit_pagamento = "0000-00-00";
        }

        $sql_edita = "UPDATE pedidos SET verba_id = '$verba_id', valor_total = '$valor_total', numero_parcelas = '$numero_parcelas', data_kit_pagamento = '$data_kit_pagamento', forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";

        if (mysqli_query($con, $sql_edita)) {
            $mensagem = mensagem("success", "Gravado com sucesso.");
            gravarLog($sql_edita);

        } else {
            $mensagem = mensagem("danger", "Erro ao gravar: " . die(mysqli_error($con)));
        }
    }
}

$pedido = recuperaDados("pedidos", "id", $idPedido);

if ($pedido['pessoa_tipo_id'] == 2) {
    $pj = recuperaDados("pessoa_juridicas", "id", $pedido['pessoa_juridica_id']);
    $proponente = $pj['razao_social'];
    $idProponente = $pj['id'];
    $link_edita = "?perfil=evento&p=pj_edita";
    $link_troca = "?perfil=evento&p=pj_pesquisa";
} else {
    $pf = recuperaDados("pessoa_fisicas", "id", $pedido['pessoa_fisica_id']);
    $proponente = $pf['nome'];
    $idProponente = $pf['id'];
    $link_edita = "?perfil=evento&p=pf_edita";
    $link_troca = "?perfil=evento&p=pf_pesquisa";
}

//verificando parcelas
$sqlParcelas = "SELECT * FROM parcelas WHERE pedido_id = '$idPedido'";
$query = mysqli_query($con, $sqlParcelas);
$numRows = mysqli_num_rows($query);

if ($numRows > 0) {
    $somaParcelas = 0;
    while ($parcela = mysqli_fetch_array($query)) {
        $arrayValores[] = dinheiroParaBr($parcela['valor']);
        $arrayDatas[] = $parcela['data_pagamento'];
        $idsParcela [] = $parcela['id'];

        $somaParcelas += $parcela['valor'];
    }

    $StringValores = implode("|", $arrayValores);
    $StringDatas = implode("|", $arrayDatas);

    foreach ($idsParcela as $idParcela) {
        $sqlComplemento = "SELECT * FROM parcela_complementos WHERE parcela_id = '$idParcela'";
        $queryComplemento = mysqli_query($con, $sqlComplemento);
        $nComplemento = mysqli_num_rows($queryComplemento);

        if ($nComplemento > 0) {
            while ($complemento = mysqli_fetch_array($queryComplemento)) {
                $arrayInicio [] = $complemento['data_inicio'];
                $arrayFim [] = $complemento['data_fim'];
                $CargaHoraria [] = $complemento['carga_horaria'];
            }
            $StringInicio = implode("|", $arrayInicio);
            $StringFim = implode("|", $arrayFim);
            $StringCarga = implode("|", $CargaHoraria);
        }
    }
}

$displayEditar = "display: none";
$displayKit = "display: block";

if (isset($pedido['numero_parcelas'])) {
    if ($pedido['numero_parcelas'] != 1) {
        $displayEditar = "display: block";
        $displayKit = "display: none";
    } else {
        $displayEditar = "display: none";
        $displayKit = "display: block";
    }
}

$sqlAtracao = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
$queryAtracao = mysqli_query($con, $sqlAtracao);
//$atracoes = mysqli_fetch_array($queryAtracao);

while ($atracao = mysqli_fetch_array($queryAtracao)) {
    if ($atracao['categoria_atracao_id'] == 4) {
        $oficina = 4;
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
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <!-- pedido -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Detalhes</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=pedido_edita" name="form_principal" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="verba_id">Verba</label>
                                    <select class="form-control" id="verba_id" name="verba_id" required>
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("verbas", $pedido['verba_id'])
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" onkeypress="return(moeda(this, '.', ',', event))"
                                           id="valor_total" name="valor_total" class="form-control"
                                           value="<?= dinheiroParaBr($pedido['valor_total']) ?>" required>
                                </div>

                                <?php
                                if (isset($oficina)) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="numero_parcelas">Número de Parcelas</label>
                                        <select class="form-control" id="numero_parcelas" name="numero_parcelas" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            geraOpcaoParcelas("oficina_opcoes", $pedido['numero_parcelas']);
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Button trigger modal -->
                                    <div class="form-group col-md-2">
                                        <button type="button" style="margin-top: 24px; <?= $displayEditar ?>"
                                                id="editarParcelas" class="btn btn-primary">
                                            Editar Parcelas
                                        </button>
                                    </div>
                                    <div class="form-group col-md-2" id="data_kit_pagamento"
                                         style="margin-left: -10px; <?= $displayKit ?>">
                                        <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                        <input type="date" id="data_kit_pagamento" name="data_kit_pagamento"
                                               class="form-control"
                                               value="<?= $pedido['data_kit_pagamento'] ?? NULL ?>">
                                    </div>
                                    <?php

                                } else {
                                    ?>

                                    <div class="form-group col-md-2">
                                        <label for="numero_parcelas">Número de Parcelas</label>
                                        <select class="form-control" id="numero_parcelas" name="numero_parcelas" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            geraOpcaoParcelas("parcela_opcoes", $pedido['numero_parcelas']);
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Button trigger modal -->
                                    <button type="button" style="margin-top: 24px; <?= $displayEditar ?>"
                                            id="editarParcelas" class="btn btn-primary">
                                        Editar Parcelas
                                    </button>
                                    <div class="form-group col-md-2" id="data_kit_pagamento"
                                         style="margin-left: -10px; <?= $displayKit ?>">
                                        <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                        <input type="date" id="data_kit_pagamento" name="data_kit_pagamento"
                                               class="form-control"
                                               value="<?= $pedido['data_kit_pagamento'] ?? NULL ?>">
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="forma_pagamento">Forma de pagamento</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control"
                                              rows="8"><?= $pedido['forma_pagamento'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa</label><br/>
                                    <textarea id="justificativa" name="justificativa" class="form-control"
                                              rows="8"><?= $pedido['justificativa'] ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <input type="text" id="observacao" name="observacao" class="form-control"
                                       maxlength="255" value="<?= $pedido['observacao'] ?>">
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
                            <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
                            <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                            <button type="submit" name="edita" class="btn btn-primary pull-right">Gravar</button>
                        </div>
                    </form>
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
                                    <input type="text" id="proponente" name="proponente" class="form-control" disabled
                                           value="<?= $proponente ?>">
                                </div>
                                <div class="form-group col-md-2"><label><br></label>
                                    <form method="POST" action="<?= $link_edita ?>" role="form">
                                        <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                                        <button type="submit" name="editProponente" class="btn btn-primary btn-block">
                                            Editar Proponente
                                        </button>
                                    </form>
                                </div>
                                <div class="form-group col-md-2"><label><br></label>
                                    <form method="POST" action="<?= $link_troca ?>" role="form">
                                        <button type="submit" name="trocar" class="btn btn-primary btn-block">Trocar de
                                            Proponente
                                        </button>
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
                                            WHERE evento_id = '" . $_SESSION['idEvento'] . "'";
                    $query_atracao = mysqli_query($con, $sql_atracao);
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
                                while ($atracao = mysqli_fetch_array($query_atracao)) {
                                    //analisaArray($atracao);
                                    echo "<tr>";
                                    echo "<td>" . $atracao['nome_atracao'] . "</td>";
                                    if ($atracao['pessoa_fisica_id'] > 0) {
                                        echo "<td>" . $atracao['nome'] . "</td>";
                                        echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pessoa_fisica\" role=\"form\">
                                            <input type='hidden' name='idAtracao' value='" . $atracao['id'] . "'>
                                            <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class='fa fa-refresh'></i> Trocar</button>
                                            </form>
                                        </td>";
                                    } else {
                                        echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pessoa_fisica\" role=\"form\">
                                            <input type='hidden' name='idAtracao' value='" . $atracao['id'] . "'>
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
                                    <form method="POST" action="?perfil=evento&p=parecer_artistico&artista=local"
                                          role="form">
                                        <button type="submit" name="idPedido" value="<?= $idPedido ?>"
                                                class="btn btn-primary btn-block">Artista Local
                                        </button>
                                    </form>
                                </div>
                                <div class="form-group col-md-2">
                                    <form method="POST" action="?perfil=evento&p=parecer_artistico&artista=local"
                                          role="form">
                                        <button type="submit" name="idPedido" value="<?= $idPedido ?>"
                                                class="btn btn-primary btn-block">Artista Consagrado
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->
    </section>
    <!-- /.content -->
</div>
<!-- Modal -->
<div class="modal fade" id="modalParcelas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin-top: 15px;" class="modal-title text-bold" id="exampleModalLongTitle">Editar
                    Parcelas</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="formParcela">
                </form>
                <div class="row">
                    <h4 class="text-center" id="somaParcelas">Soma das
                        parcelas: <p><?= isset($somaParcelas) ? dinheiroParaBr($somaParcelas) : NULL?></p></h4>
                </div>
                <div class="row">
                    <h4 class="text-center">Valor total do contrato: <?= dinheiroParaBr($pedido['valor_total']) ?></h4>
                </div>
            </div>
            <div class="modal-footer">
                <div class="botoes">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" name="salvar" id="salvarModal">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/x-handlebars-template" id="templateParcela">
    <div class='row'>
        <div class='form-group col-md-2'>
            <label for='parcela'>Parcela </label>
            <input type='number' value="{{count}}" class='form-control' disabled>
        </div>
        <div class='form-group col-md-3'>
            <label for='valor'>Valor </label>
            <input type='text' id='valor' name='valor[{{count}}]' value="{{valor}}" required placeholder="Valor em reais"
                   onkeypress="return(moeda(this, '.', ',', event));" onkeyup="somar()" class='form-control'>
        </div>
        <div class='form-group col-md-4'>
            <label for='modal_data_kit_pagamento'>Data Kit Pagamento</label>
            <input type='date' id='modal_data_kit_pagamento' value="{{kit}}" required
                   name='modal_data_kit_pagamento[{{count}}]'
                   class='form-control'>
        </div>
    </div>
</script>
<!-- Modal Oficinas-->
<style>
    .modal-lg {
        width: 90%;
    }
</style>
<div class="modal fade" id="modalOficina" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin-top: 15px;" class="modal-title text-bold" id="exampleModalLongTitle">Editar
                    Parcelas de Oficina</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="formParcela">
                </form>
                <div class="row">
                    <h4 class="text-center" id="somaParcelas">Soma das
                        parcelas: <p><?= isset($somaParcelas) ? dinheiroParaBr($somaParcelas) : NULL?></p></h4>
                </div>
                <div class="row">
                    <h4 class="text-center">Valor total do contrato: <?= dinheiroParaBr($pedido['valor_total']) ?></h4>
                </div>
            </div>
            <div class="modal-footer">
                <div class="botoes">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" name="salvar" id="salvarModalOficina">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/x-handlebars-template" id="templateOficina">
    <div class='row'>
        <div class='form-group col-md-1'>
            <label for='parcela'>Parcela </label>
            <input type='number' value="{{count}}" class='form-control' disabled>
        </div>
        <div class='form-group col-md-2'>
            <label for='valor'>Valor </label>
            <input type='text' id='valor' name='valor[{{count}}]' value="{{valor}}" placeholder="Valor em reais" onkeyup="somar()"
                   onkeypress="return(moeda(this, '.', ',', event))" class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='data_inicial'>Data Inicial</label>
            <input type='date' id='data_inicial' value="{{inicial}}" name='data_inicial[{{count}}]'
                   class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='data_final'>Data Final</label>
            <input type='date' id='data_final' value="{{final}}" name='data_final[{{count}}]' class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='modal_data_kit_pagamento'>Data Kit Pagamento</label>
            <input type='date' id='modal_data_kit_pagamento' value="{{kit}}"
                   name='modal_data_kit_pagamento[{{count}}]' class='form-control'>
        </div>
        <div class='form-group col-md-2'>
            <label for='horas'>Horas</label>
            <input type='number' id='horas' value="{{horas}}" name='horas[{{count}}]' class='form-control'>
        </div>
    </div>
</script>


<script type="text/javascript">

    $(function () {
        $('#numero_parcelas').on('change', ocultarBotao);

        $('#abrirParcelas').on('click', abrirModal);
        $('#editarParcelas').on('click', abrirModal);

        $('#salvarModal').on('click', salvarModal);
        $('#salvarModalOficina').on('click', salvarModal);

        $('#editarModal').on('click', editarModal);
    });

    function somar () {

        var parcelas = $("#numero_parcelas").val();
        var arrayValor = [];
        let soma = 0;

        for (var i = 1; i <= parcelas; i++) {
            arrayValor [i] = $("input[name='valor[" + i + "]']").val().replace('.', '').replace(',', '.');

            if (arrayValor[i] == "") {
                $("input[name='valor[" + i + "]']").val("0,00");
                continue;
            }
            soma += parseFloat(arrayValor[i]);
        }

        var idAtracao = "<?php if (isset($oficina)) {
            echo $oficina;
        } ?>";

        if (idAtracao == 4) {
            $('#modalOficinas').find('p').html(soma.toFixed(2).replace('.', ','));
        }

        $('#modalParcelas').find('p').html(soma.toFixed(2).replace('.', ','));
    }

    var ocultarBotao = function () {

        var optionSelect = document.querySelector("#numero_parcelas").value;
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

    var abrirModal = function () {

        var source = document.getElementById("templateParcela").innerHTML;
        var template = Handlebars.compile(source);
        var html = '';

        var footer = document.querySelector(".main-footer");
        footer.style.display = "none";

        var parcelasSalvas = "<?php echo $numRows ?>";
        var parcelasSelected = $("#numero_parcelas").val();

        if (parseInt(parcelasSelected) < parseInt(parcelasSalvas)){
            swal("Havia  " + parcelasSalvas + " parcelas nesse pedido!", "Número de parcelas selecionadas menor que quantidade de parcelas salvas, ao edita-lás as demais parcelas seram excluídas!", "warning");
        }

        var StringValores = "<?php if (isset($StringValores)) {
            echo $StringValores;
        } else {
            echo "";
        } ?>";

        var StringDatas = "<?php if (isset($StringDatas)) {
            echo $StringDatas;
        } else {
            echo "";
        } ?>";

        var idAtracao = "<?php if (isset($oficina)) {
            echo $oficina;
        } ?>";

        if (idAtracao == 4) {
            var source = document.getElementById("templateOficina").innerHTML;
            var templateOficina = Handlebars.compile(source);

            if (StringValores != "" && StringDatas != "") {

                var valores = StringValores.split("|");
                var datas = StringDatas.split("|");

                for (var count = 0; count < parcelasSalvas; count++) {
                    html += templateOficina({
                        count: count + 1, // para sincronizar com o array vindo do banco
                        valor: valores [count],
                        kit: datas [count],
                    });
                }

                if (parseInt(parcelasSalvas) < parseInt(parcelasSelected)) {
                    if (parcelasSelected == 3 || parcelasSelected == 4) {
                        //console.log(parcelasSelected + " teste " + parcelasSalvas);
                        let faltando = parcelasSelected - parcelasSalvas;
                        //console.log(faltando);
                        for (var i = 1; i < parseInt(faltando); i++) {
                            let count = parcelasSalvas;
                            html += templateOficina({
                                count: parseInt(count) + 1,
                            });
                        }

                    } else {
                        if (parcelasSelected == 3 || parcelasSelected == 4) {
                            for (var i = 1; i <= parcelasSelected; i++) {
                                html += templateOficina({
                                    count: i + 1
                                });
                            }
                        }
                    }

                    var footer = document.querySelector(".main-footer");
                    footer.style.display = "none";

                    $('#modalOficina').find('#formParcela').html(html);
                    $('#modalOficina').modal('show');

                } else {
                    if (parcelasSelected == 3 || parcelasSelected == 4) {
                        for (var i = 1; i < parcelasSelected; i++) {
                            html += templateOficina({
                                count: parseInt(count)+ 1,
                            });
                        }
                    } else {
                        for (var i = 1; i <= parcelasSelected; i++) {
                            html += templateOficina({
                                count: parseInt(count)+ 1
                            });
                        }
                    }

                    var footer = document.querySelector(".main-footer");
                    footer.style.display = "none";

                    $('#modalOficina').find('#formParcela').html(html);
                    $('#modalOficina').modal('show');
                }
            } else {
                if (parcelasSelected == 3 || parcelasSelected == 4) {
                    for (var count = 1; count < parcelasSelected; count++) {
                        html += templateOficina({
                            count: count,
                        });
                    }
                } else {
                    for (var count = 1; count <= parcelasSelected; count++) {
                        html += templateOficina({
                            count: count
                        });
                    }
                }

                var footer = document.querySelector(".main-footer");
                footer.style.display = "none";

                $('#modalOficina').find('#formParcela').html(html);
                $('#modalOficina').modal('show');
            }

        } else {

            if (StringValores != "" && StringDatas != "") {
                var valores = StringValores.split("|");
                var datas = StringDatas.split("|");

                for (var count = 0; count < parcelasSelected; count++) {
                    html += template({
                        count: count + 1, // para sincronizar com o array vindo do banco
                        valor: valores [count],
                        kit: datas [count],
                    });
                }

                if (parseInt(parcelasSalvas) < parseInt(parcelasSelected)) {
                    var faltando = parcelasSelected - parcelasSalvas;
                    for (var i = 1; count < parseInt(faltando); i++) {
                        var count = parcelasSalvas;
                        html += template({
                            count: parseInt(count)+1,
                        });
                    }
                }

                $('#modalParcelas').find('#formParcela').html(html);

                $(".botoes").html("<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='editar' id='editarModal'>Editar</button>");

                $('#editarModal').on('click', editarModal);
                $('#modalParcelas').modal('show');

            } else {
                for (var count = 1; count <= parcelasSelected; count++) {
                    html += template({
                        count: count
                    });
                }
                $('#modalParcelas').find('#formParcela').html(html);
                $('#modalParcelas').modal('show');
            }
        }
    };


    var salvarModal = function () {
        var idAtracao = "<?php if (isset($oficina)) {
            echo $oficina;
        } ?>";

        if (idAtracao == 4) {
            var count = 0;
            $("#formParcela input").each(function () {
                if ($(this).val() == "") {
                    count++;
                }
            });

            if (count != 0) {
                swal("Preencha todas as informações para salvar!", "", "warning");

            } else {

                var parcelas = $("#numero_parcelas").val();
                var arrayKit = [];
                var arrayValor = [];
                var arrayInicial = [];
                var arrayFinal = [];
                var horas = [];

                for (var i = 1; i <= parcelas; i++) {
                    arrayKit [i] = $("input[name='modal_data_kit_pagamento[" + i + "]']").val();
                    arrayValor [i] = $("input[name='valor[" + i + "]']").val();
                    arrayInicial [i] = $("input[name='data_inicial[" + i + "]']").val();
                    arrayFinal[i] = $("input[name='data_final[" + i + "]']").val();
                    horas[i] = $("input[name='horas[" + i + "]']").val();
                }

                var source = document.getElementById("templateOficina").innerHTML;
                var template = Handlebars.compile(source);
                var html = '';

                var newButtons = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary editar' name='editar' id='editarModal'>Editar</button>";

                $('#modalOficina').slideUp();

                $.post('?perfil=evento&p=parcelas_cadastro', {
                    parcelas: parcelas,
                    arrayValor: arrayValor,
                    arrayKit: arrayKit,
                    arrayInicial: arrayInicial,
                    arrayFinal: arrayFinal,
                    horas: horas
                })
                    .done(function () {
                        for (var count = 1; count <= parcelas; count++) {
                            html += template({
                                count: count,
                                valor: arrayValor [count],
                                kit: arrayKit [count]
                            });
                        }

                        $(".botoes").html(newButtons);
                        $('#editarModal').on('click', salvarModal);

                        swal("" + parcelas + " parcelas gravadas com sucesso!", "", "success")
                            .then(() => {
                                $('#modalOficina').slideDown('slow');
                            });
                    })
                    .fail(function () {
                        swal("danger", "Erro ao gravar");
                    });
            }

        } else {
            var count = 0;
            $("#formParcela input").each(function () {
                if ($(this).val() == "") {
                    count++;
                }
            });

            if (count != 0) {
                swal("Preencha todas as informações para editar as parcelas!", "", "warning");
            } else {
                var parcelas = $("#numero_parcelas").val();
                var arrayKit = [];
                var arrayValor = [];

                for (var i = 1; i <= parcelas; i++) {
                    arrayKit [i] = $("input[name='modal_data_kit_pagamento[" + i + "]']").val();
                    arrayValor [i] = $("input[name='valor[" + i + "]']").val();
                }

                var source = document.getElementById("templateParcela").innerHTML;
                var template = Handlebars.compile(source);
                var html = '';

                var newButtons = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>" + "<button type='button' class='btn btn-primary' name='editar' id='editarModal'>Editar</button>";

                $('#modalParcelas').slideUp();

                $.post('?perfil=evento&p=parcelas_cadastro', {
                    parcelas: parcelas,
                    arrayValor: arrayValor,
                    arrayKit: arrayKit
                })
                    .done(function () {
                        for (var count = 1; count <= parcelas; count++) {
                            html += template({
                                count: count,
                                valor: arrayValor [count],
                                kit: arrayKit [count]
                            });
                        }

                        $(".botoes").html(newButtons);
                        $('#editarModal').on('click', editarModal);

                        swal("" + parcelas + " parcelas gravadas com sucesso!", "", "success")
                            .then(() => {
                                $('#modalParcelas').slideDown('slow');
                                //window.location.href = "?perfil=evento&p=parcelas_cadastro";
                            });
                    })
                    .fail(function () {
                        swal("danger", "Erro ao gravar");
                    });
            }
        }
    };

    var editarModal = function () {

        var count = 0;
        $("#formParcela input").each(function () {
            if ($(this).val() == "") {
                count++;
            }
        });

        if (count != 0) {
            swal("Preencha todas as parcelas para edita-lás!", "", "warning");
        } else {

            var parcelas = $("#numero_parcelas").val();

            var datas = new Array(1);
            var valores = new Array(1);

            var soma = 0;

            for (var i = 1; i <= parcelas; i++) {
                $("input[name='modal_data_kit_pagamento[" + i + "]']").each(function () {
                    datas.push($(this).val());
                });

                $("input[name='valor[" + i + "]']").each(function () {
                    valores.push($(this).val());
                });
                soma += parseFloat(valores[i]);
            }

            var source = document.getElementById("templateParcela").innerHTML;
            var template = Handlebars.compile(source);
            var html = '';

            $("#somaParcelas").html("Soma das parcelas: " + soma);

            $('#modalParcelas').slideUp();

            $.ajax({
                url: "?perfil=evento&p=parcelas_edita",
                method: "POST",
                data: {
                    parcelas: parcelas,
                    valores: valores,
                    datas: datas
                },
            })
                .done(function () {
                    for (var count = 1; count <= parcelas; count++) {
                        html += template({
                            count: count,
                            valor: valores [count],
                            kit: datas [count]
                        });
                    }

                    $('#editarModal').off('click', editarModal);
                    $('#editarModal').on('click', editarModal);

                    swal("" + parcelas + " parcelas editadas com sucesso!", "", "success")
                        .then(() => {
                            location.reload(true);
                        });
                })
                .fail(function () {
                    swal("danger", "Erro ao gravar");
                });
        }
    };


</script>

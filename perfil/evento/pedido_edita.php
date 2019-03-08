<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
if (isset($_POST['carregar'])) {
    $_SESSION['idPedido'] = $_POST['idPedido'];
}
$idPedido = $_SESSION['idPedido'];
$idEvento = $_SESSION['idEvento'];

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $verba_id = $_POST['verba_id'];
    $forma_pagamento = addslashes($_POST['forma_pagamento']);
    $justificativa = addslashes($_POST['justificativa']);
    $observacao = addslashes($_POST['observacao']);
    $numero_parcelas = $_POST['numero_parcelas'] ?? NULL;
    $data_kit_pagamento = $_POST['data_kit_pagamento'] ?? NULL;
}

if (isset($_POST['cadastra'])) {
    if (isset($data_kit_pagamento)) {
        $sql_cadastra = "UPDATE pedidos SET verba_id = '$verba_id', forma_pagamento = '$forma_pagamento', data_kit_pagamento = '$data_kit_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    } else {
        $sql_cadastra = "UPDATE pedidos SET verba_id = '$verba_id', forma_pagamento = '$forma_pagamento', numero_parcelas = '$numero_parcelas', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    }

    if (mysqli_query($con, $sql_cadastra)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

if (isset($_POST['edita'])) {
    $valor_total = dinheiroDeBr($_POST['valor_total']);
    $numero_parcelas = $_POST['numero_parcelas'];

    $sql_edita = "UPDATE pedidos SET verba_id = '$verba_id', valor_total = '$valor_total', numero_parcelas = '$numero_parcelas', data_kit_pagamento = null, forma_pagamento = '$forma_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
    if (mysqli_query($con, $sql_edita)) {
        $mensagem = mensagem("success", "Gravado com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar: " . die(mysqli_error($con)));
    }
}

if (isset($_POST['data_kit_pagamento'])) {
    $data_kit_pagamento = $_POST['data_kit_pagamento'];
    $sql_edita = "UPDATE pedidos SET data_kit_pagamento = '$data_kit_pagamento' WHERE id = '$idPedido'";
    if (mysqli_query($con, $sql_edita)) {
        $mensagem = mensagem("success", "Gravado com sucesso.");
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar: " . die(mysqli_error($con)));
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

$atracao = recuperaDados("atracoes", "evento_id", $idEvento);

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
                                    <select class="form-control" id="verba_id" name="verba_id">
                                        <option value="">Selecione...</option>
                                        <?php
                                        geraOpcao("verbas", $pedido['verba_id'])
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" onkeypress="return(moeda(this, '.', ',', event))" id="valor_total" name="valor_total" class="form-control"
                                           value="<?= dinheiroParaBr($pedido['valor_total']) ?>">
                                </div>

                                <?php
                                if ($numRows > 0) {
                                    ?>
                                    <div class="form-group col-md-4">
                                        <a class="btn btn-primary" href="?perfil=evento&p=parcelas_edita"
                                           style="margin-left: 20px; margin-top: 24px; role=" button">
                                        <?= $numRows ?> parcelas salvas, clique aqui para editá-las.
                                        </a>
                                    </div>
                                    <?php
                                } else if ($atracao['categoria_atracao_id'] == 4) {
                                    ?>

                                    <div class="form-group col-md-2">
                                        <label for="numero_parcelas">Número de Parcelas</label>
                                        <select onchange="ocultarBotao()" class="form-control" id="numero_parcelas"
                                                name="numero_parcelas">
                                            <option value="0">
                                                Selecione...
                                            </option>
                                            <?php
                                            geraOpcaoParcelas("oficina_opcoes", $pedido['numero_parcelas']);
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

                                } else {
                                    ?>

                                    <div class="form-group col-md-2">
                                        <label for="numero_parcelas">Número de Parcelas</label>
                                        <select onchange="ocultarBotao()" class="form-control" id="numero_parcelas"
                                                name="numero_parcelas">
                                            <option value="0">
                                                Selecione...
                                            </option>
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
                            <button type="submit" name="edita" class="btn btn-primary pull-right">Gravar </button>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="salvarModal">Salvar</button>
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
                <input type='text' id='valor' name='valor[{{count}}]' placeholder="Valor em reais"
                       onkeypress="return(moeda(this, '.', ',', event))" class='form-control'>
            </div>
            <div class='form-group col-md-4'>
                <label for='modal_data_kit_pagamento'>Data Kit Pagamento</label>
                <input type='date' id='modal_data_kit_pagamento' name='modal_data_kit_pagamento[{{count}}]'
                       class='form-control'>
            </div>
        </div>
    </script>

    <script type="text/javascript">

        function ocultarBotao() {

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

        $(function () {
            $("#editarParcelas").click(function () {
                var source = document.getElementById("templateParcela").innerHTML;
                var template = Handlebars.compile(source);
                var parcelas = $("#numero_parcelas").val();

                var html = '';

                for (var count = 1; count <= parcelas; count++) {
                    html += template({
                        count: count
                    });
                    console.log(count);
                }

                var footer = document.querySelector(".main-footer");
                footer.style.display = "none";

                $('#modalParcelas').find('#formParcela').html(html);

                $('#modalParcelas').modal('show');
            });

            $("#salvarModal").click(function () {

                var parcelas = $("#numero_parcelas").val();
                var idPedido = "<?php echo $idPedido; ?>";

                var arrayKit = [];
                var arrayValor = [];

                for (var i = 1; i <= parcelas; i++) {
                    arrayKit [i] = $("input[name='modal_data_kit_pagamento[" + i + "]']").val();
                    arrayValor [i] = $("input[name='valor[" + i + "]']").val();

                }


                var dados = $('#templateParcela').serialize();

                console.log(dados);


              /*  $.post('?perfil=evento&p=parcelas_cadastro',dados)
                    .done(function(data){
                        $('.parcelas').html(data);
                    });*/

                $.ajax({
                    url: '?perfil=evento&p=parcelas_cadastro',
                    type: 'POST', // Tipo de requisição, podendo alterar para GET, POST, PUT , DELETE e outros metodos http
                    data: {parcelas: parcelas, idPedido: idPedido, arrayValor: arrayValor, arrayKit: arrayKit},
                    success: function () {
                        $('#modalParcelas').modal('hide');
                        // sweetAlert("Parcelas editadas com sucesso!");
                       // window.location.href = "?perfil=evento&p=parcelas_cadastro";
                    },
                    error: function () {
                        swal("Erro ao gravar");
                    }
                });
            });
        });

    </script>

<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
$conn = bancoPDO();
$idEvento = $_SESSION['idEvento'];

$tipoPessoa = $_POST['pessoa_tipo_id'];
$idProponente = $_POST['pessoa_id'];


if ($tipoPessoa == 2) {
    $pj = recuperaDados("pessoa_juridicas", "id", $idProponente);
    $proponente = $pj['razao_social'];
    $link_edita = "?perfil=evento&p=pj_edita";
    $link_troca = "?perfil=evento&p=pj_pesquisa";
} else {
    $pf = recuperaDados("pessoa_fisicas", "id", $idProponente);
    $proponente = $pf['nome'];
    $link_edita = "?perfil=evento&p=pf_edita";
    $link_troca = "?perfil=evento&p=pf_pesquisa";
}

$displayEditar = "display: none";
$displayKit = "display: block";


$sqlOficina = "SELECT a.valor_individual, aa.acao_id FROM eventos e
                INNER JOIN atracoes a on e.id = a.evento_id
                INNER JOIN acao_atracao aa on a.id = aa.atracao_id
                WHERE e.id = '$idEvento' and a.publicado = 1";
$queryAtracao = mysqli_query($con, $sqlAtracao);
//$atracoes = mysqli_fetch_array($queryAtracao);

while ($atracao = mysqli_fetch_array($queryAtracao)) {
    $valores [] = $atracao['valor_individual'];

    if ($atracao['acao_id'] == 8) {
        $oficina = 4;
    }
}

$soma = 0;
foreach ($valores as $valor) {
    $soma += $valor;
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
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
                                        geraOpcao("verbas")
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="valor_total">Valor Total</label>
                                    <input type="text" onkeypress="return(moeda(this, '.', ',', event))"
                                           id="valor_total" name="valor_total" class="form-control" value="<?=dinheiroParaBr($soma)?>" readonly>
                                </div>

                                <?php
                                 if (isset($oficina)) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="numero_parcelas">Número de Parcelas</label>
                                        <select class="form-control" id="numero_parcelas" name="numero_parcelas" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            geraOpcaoParcelas("oficina_opcoes");
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Button trigger modal -->
                                    <div class="form-group col-md-2">
                                         <p style="margin-top: 22px; display: none" id="editarParcelas">
                                             <em>Edição de parcelas na próxima página!</em>
                                         </p>
                                    </div>
                                    <div class="form-group col-md-2" id="data_kit_pagamento"
                                         style="margin-left: -10px; <?= $displayKit ?>">
                                        <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                        <input type="date" id="data_kit_pagamento" name="data_kit_pagamento" class="form-control">
                                    </div>
                                    <?php
                                } else {
                                     ?>
                                    <div class="form-group col-md-2">
                                        <label for="numero_parcelas">Número de Parcelas</label>
                                        <select class="form-control" id="numero_parcelas" name="numero_parcelas" required>
                                            <option value="">Selecione...</option>
                                            <?php
                                            geraOpcaoParcelas("parcela_opcoes");
                                            ?>
                                        </select>
                                    </div>
                                    <!-- Button trigger modal -->
                                     <p style="margin-top: 22px; display: none" id="editarParcelas">
                                         <em>Edição de parcelas na próxima página!</em>
                                     </p>
                                    <div class="form-group col-md-2" id="data_kit_pagamento"
                                         style="margin-left: -10px; display: block">
                                        <label for="data_kit_pagamento">Data Kit Pagamento</label>
                                        <input type="date" id="data_kit_pagamento" name="data_kit_pagamento" class="form-control" >
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="forma_pagamento">Forma de pagamento</label><br/>
                                    <textarea id="forma_pagamento" name="forma_pagamento" class="form-control" rows="8"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="justificativa">Justificativa</label><br/>
                                    <textarea id="justificativa" name="justificativa" class="form-control" rows="8"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <input type="text" id="observacao" name="observacao" class="form-control" maxlength="255" >
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="tipoPessoa" value="<?= $tipoPessoa ?>">
                            <input type="hidden" name="idProponente" value="<?= $idProponente ?>">
                            <button type="submit" name="cadastra" class="btn btn-primary pull-right">Salvar</button>
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
                                            WHERE evento_id = '" . $_SESSION['idEvento'] . "' AND publicado = 1";
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
                                            <form method=\"POST\" action=\"?perfil=evento&p=pf_pesquisa\" role=\"form\">
                                            <input type='hidden' name='idAtracao' value='" . $atracao['id'] . "'>
                                            <button type=\"submit\" name='carregar' class=\"btn btn-primary\"><i class='fa fa-refresh'></i> Trocar</button>
                                            </form>
                                        </td>";
                                    } else {
                                        echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pf_pesquisa\" role=\"form\">
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
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->
    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">

    $(function () {
        $('#numero_parcelas').on('change', ocultarBotao);
    });

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
</script>
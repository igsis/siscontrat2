<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

if (isset($_POST['adicionar']) || isset($_POST['adicionarLider']) || isset($_POST['adicionaPf'])) {
    $documento = $_POST['documentacao'];
    $tipoDocumento = $_POST['tipoDocumento'];

    if (isset($_POST['adicionarLider'])) {
        $idAtracao = $_POST['idAtracao'];
        $idPedido = $_POST['idPedido'];
        $botoesFooter = "<input type='hidden' name='idPedido' value='$idPedido'>
                            <input type='hidden' name='idAtracao' value='$idAtracao'>
                            <button type='submit' name='cadastraComLider' class='btn btn-info pull-right'>Salvar</button>";

    } else if (isset($_POST['adicionaPf'])) {
        $idPedido = $_POST['idPedido'];
        $botoesFooter = "<input type='hidden' name='idPedido' value='$idPedido'>
                            <button type='submit' name='atualizaPf' class='btn btn-info pull-right'>Salvar</button>";
    } else {
        $botoesFooter = "<button type='submit' name='cadastra' class='btn btn-info pull-right'>Salvar</button>";
    }
}

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('eventos', 'id', $idEvento);

if ($evento['tipo_evento_id'] == 1) {
    $atracoesTipo = array('3', '7', '10', '11');

    $atracao = recuperaDados('atracoes', 'evento_id', $idEvento);
    $atracaoId = $atracao['id'];
    $acao_atracao = recuperaDados('acao_atracao', 'atracao_id', $atracaoId);
    $acaoID = $acao_atracao['acao_id'];

    if (in_array($acaoID, $atracoesTipo)) {
        $mostraDRT = true;
    } else {
        $mostraDRT = false;
    }
} else {
    $mostraDRT = false;
}

$atracao = $con->query("SELECT valor_individual FROM atracoes WHERE evento_id = '$idEvento'")->fetch_array();
?>

<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
    });
</script>
<script language="JavaScript">
    function barraData(n) {
        if (n.value.length == 2)
            c.value += '/';
        if (n.value.length == 5)
            c.value += '/';
    }
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Evento</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Cadastro de pessoa física</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pf_edita" method="post">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" id="nome"
                                           placeholder="Digite o nome" maxlength="70" required
                                           pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,70}"
                                           title="Apenas letras">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nomeArtistico">Nome Artistico:</label>
                                    <input type="text" class="form-control" name="nomeArtistico" id="nomeArtistico"
                                           placeholder="Digite o nome artistico" maxlength="70">
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                if ($tipoDocumento == 1) {
                                    ?>
                                    <div class="form-group col-md-2">
                                        <label for="rg">RG: *</label>
                                        <input type="text" class="form-control" name="rg" id="rg"
                                               placeholder="Digite o RG" maxlength="20" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cpf">CPF: </label>
                                        <input type="text" name="cpf" class="form-control" id="cpf"
                                               value="<?= $documento ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="pis_nit">PIS / NIT: *</label>
                                        <input type="text" name="pis_nit" id="pis_nit" class="form-control"
                                               placeholder="Digite PIS ou NIT" maxlength="11" required>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="passaporte" id="documento">Passaporte: </label>
                                        <input type="text" id="passaporte" name="passaporte" class="form-control"
                                               value="<?= $documento ?>" readonly>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-3">
                                    <label for="dataNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" id="dataNascimento" name="dtNascimento"
                                           onkeyup="barraData(this);" required/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" id="nacionalidade" name="nacionalidade" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("nacionalidades", "");
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua"
                                           placeholder="Digite a rua" maxlength="200" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label> <i>(se não houver, marcar 0)</i>
                                    <input type="number" name="numero" id="numero" class="form-control"
                                           placeholder="Ex.: 10" min="0" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control"
                                           maxlength="20" placeholder="Digite o complemento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Ex.: SP" readonly>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" id="email" class="form-control" maxlength="60"
                                           placeholder="Digite o E-mail" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #1: *</label>
                                    <input type="text" id="telefone" name="telefone[0]" onkeyup="mascara( this, mtel );"
                                           class="form-control" placeholder="Digite o telefone" required maxlength="15"
                                           data-mask="(00) 00000-0000" pattern=".{14,15}" title="14 a 15 caracteres">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #2:</label>
                                    <input type="text" id="telefone1" name="telefone[1]"
                                           onkeyup="mascara( this, mtel );" class="form-control"
                                           placeholder="Digite o telefone" maxlength="15" data-mask="(00) 00000-0000"
                                           pattern=".{14,15}" title="14 a 15 caracteres">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #3:</label>
                                    <input type="text" id="telefone2" name="telefone[2]"
                                           onkeyup="mascara( this, mtel );" class="form-control telefone"
                                           placeholder="Digite o telefone" maxlength="15" data-mask="(00) 00000-0000"
                                           pattern=".{14,15}" title="14 a 15 caracteres">
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                if ($mostraDRT){
                                ?>
                                <div class="form-group col-md-6">
                                    <label for="drt">DRT: </label>
                                    <input type="text" name="drt" id="drt" class="form-control" maxlength="15"
                                           placeholder="Digite o DRT">
                                </div>
                                <div class="form-group col-md-6">
                                    <?php
                                    }
                                    else{
                                    ?>
                                    <div class="form-group col-md-12">
                                        <?php
                                        }
                                        ?>
                                        <label for="nit">NIT: </label>
                                        <input type="text" name="nit" id="nit" class="form-control" maxlength="45"
                                               placeholder="Digite o NIT">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="observacao">Observação: </label>
                                        <textarea id="observacao" name="observacao" rows="3"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                                <?php
                                if ($atracao != null) {
                                    if ($atracao['valor_individual'] > 0 || $evento['tipo_evento_id'] == 2) {
                                        ?>

                                        <hr/>
                                        <div class="row">

                                            <div class="form-group col-md-4">
                                                <label for="banco">Banco:</label>
                                                <select id="banco" name="banco" class="form-control">
                                                    <option value="">Selecione um banco...</option>
                                                    <?php
                                                    geraOpcao("bancos", "");
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="agencia">Agência:</label>
                                                <input type="text" name="agencia" id="agencia" class="form-control"
                                                       placeholder="Digite a Agência" maxlength="12">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="conta">Conta:</label>
                                                <input type="text" name="conta" id="conta" class="form-control"
                                                       placeholder="Digite a Conta" maxlength="12">
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="box-footer">
                                    <?= $botoesFooter ?>
                                </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </section>
    <!-- /.content -->
</div>
<?php
include "includes/menu_interno.php";
$con = bancoMysqli();

if (isset($_POST['adicionar']) || isset($_POST['adicionaPj'])) {
    if (isset($_POST['adicionaPj'])) {
        $idPedido = $_POST['idPedido'];
        $botoesFooter = "<input type='hidden' name='idPedido' value='$idPedido'>
                            <button type='submit' name='atualizaPj' class='btn btn-info pull-right'>Salvar</button>";
    } else {
        $botoesFooter = "<button type='submit' name='cadastra' class='btn btn-info pull-right'>Salvar</button>";
    }
}

$idEvento = $_SESSION['idEvento'];

$evento = $con->query("SELECT tipo_evento_id FROM eventos WHERE id = '$idEvento'")->fetch_array();

$atracao = $con->query("SELECT valor_individual FROM atracoes WHERE evento_id = '$idEvento'")->fetch_array();
?>

<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
    });
</script>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro Pessoa Jurídica</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações Pessoa Jurídica</h3>
                    </div>

                    <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-8">
                                    <label for="razao_social">Razão Social: *</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="cnpj">CNPJ: *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required readonly value="<?= $_POST['cnpj'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm">
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" id="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #1: *</label>
                                    <input type="text" id="telefone" name="telefone[0]" data-mask="(00) 00000-0000" onkeyup="mascara( this, mtel );" class="form-control" placeholder="Digite o telefone" required maxlength="15" pattern=".{14,15}"  title="14 a 15 caracteres">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #2:</label>
                                    <input type="text" id="telefone1" name="telefone[1]" data-mask="(00) 00000-0000" onkeyup="mascara( this, mtel );" class="form-control" placeholder="Digite o telefone" maxlength="15" pattern=".{14,15}"  title="14 a 15 caracteres">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #3:</label>
                                    <input type="text" id="telefone2" name="telefone[2]" data-mask="(00) 00000-0000" onkeyup="mascara( this, mtel );" class="form-control telefone" placeholder="Digite o telefone" maxlength="15" pattern=".{14,15}"  title="14 a 15 caracteres">
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9" placeholder="Digite o CEP" required data-mask="00000-000">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite a rua" maxlength="200" readonly>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label> <i>(se não houver, marcar 0)</i>
                                    <input type="number" name="numero" id="numero" min="0" class="form-control" placeholder="Ex.: 10" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Digite o Bairro" maxlength="80" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Digite a cidade" maxlength="50" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2" placeholder="Ex.: SP" readonly>
                                </div>
                            </div>
                            <?php
                            if($atracao['valor_individual'] > 0 || $evento['tipo_evento_id'] == 2){
                            ?>
                                <input type="hidden" name="bancario">
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
                                        <input type="text" name="agencia" id="agencia" class="form-control" placeholder="Digite a Agência" maxlength="12">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="conta">Conta:</label>
                                        <input type="text" name="conta" id="conta" class="form-control" placeholder="Digite a Conta" maxlength="12">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea id="observacao" name="observacao" rows="3" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <?= $botoesFooter ?>
                            </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- /.modal cartão cnpj -->
        <div class="modal fade" id="modal-cnpj">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Upload do Cartão CNPJ</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <input type="file">
                            <br/>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal cartão cnpj -->

    </section>
</div>

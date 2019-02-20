<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
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
                                <div class="form-group col-md-12">
                                    <label for="razao_social">Razão Social: *</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-2">
                                    <label for="cnpj">CNPJ: *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required readonly value="<?= $_POST['cnpj'] ?>">

                                </div>
                                <div class="form-group col-md-4">
                                    <label>Anexo do Cartão CNPJ</label><br>
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-cnpj">Clique aqui para anexar</button>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Anexo FDC - CCM ou CPOM</label><br>
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-ccm">Clique aqui para anexar</button>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #1: *</label>
                                    <input type="text" id="telefone" name="telefone[]" onkeyup="mascara( this, mtel );"  class="form-control" placeholder="Digite o telefone" required maxlength="15">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #2:</label>
                                    <input type="text" id="telefone" name="telefone[]" onkeyup="mascara( this, mtel );"  class="form-control" placeholder="Digite o telefone" maxlength="15">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #3:</label>
                                    <input type="text" id="telefone" name="telefone[]" onkeyup="mascara( this, mtel );"  class="form-control telefone" placeholder="Digite o telefone" maxlength="15">
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
                                <div class="form-group col-md-4">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite a rua" maxlength="200" readonly>
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Ex.: 10" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Digite o Bairro" maxlength="80" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Digite a cidade" maxlength="50" readonly>
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2" placeholder="Ex.: SP" readonly>
                                </div>
                            </div>
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
                                    <input type="text" name="agencia" class="form-control"  placeholder="Digite a Agência" maxlength="12">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="conta">Conta:</label>
                                    <input type="text" name="conta" class="form-control" placeholder="Digite a Conta" maxlength="12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Gerar FACC</label><br>
                                    <button type="button" class="btn btn-primary btn-block">Clique aqui para gerar a FACC</button>
                                </div>
                                <div class="form-group col-md-5">
                                    <label>&nbsp;</label><br>
                                    <p>A FACC deve ser impressa, datada e assinada nos campos indicados no documento. Logo após, deve-se digitaliza-la e então anexa-la ao sistema no campo correspondente.</p>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Anexo FACC</label><br>
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-facc">Clique aqui para anexar</button>
                                </div>
                            </div>
                            <hr/>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea id="observacao" name="observacao" rows="3" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" name="cadastra" id="cadastra" class="btn btn-info pull-right">
                                    Cadastrar
                                </button>
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
                        <h4 class="modal-title">Upload de arquivo</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <input type="file">
                            <button type="submit">Enviar</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal cartão cnpj -->

    </section>
</div>

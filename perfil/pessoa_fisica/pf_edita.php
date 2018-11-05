<?php

$con = bancoMysqli();
include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])){

    $nome = $_POST['nome'];
    $nomeArtistico = $_POST['nomeArtistico'];
    $rg = $_POST['rg'];
    $documento = $_POST['documento'];
    $ccm = $_POST['ccm'];
    $dtNascimento = $_POST['dtNascimento'];
    $nacionalidade = $_POST['nacionalidade'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['completo'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $email = $_POST['email'];
    $telefone[] = $_POST['telefone[]'];
    $drt = $_POST['drt'] ?? NULL;
    $funcao = $_POST['funcao'] ?? NULL;
    $incricoes = $_POST['inscricaoPissInss'];
    $omb = $_POST['omb'];
    $observacao = $_POST['observacao'] ?? NULL;
    $banco = $_POST['banco'];
    $agencia = $_POST['agencia'];
    $conta = $_POST['conta'];
    $observacaoConta = $_POST['observacaoConta'] ?? NULL;


}

if (isset($_POST['cadastra'])){

    $sql = "INSERT INTO ``";
}

?>
<script language="JavaScript" >
    function barraData(n){
        if(n.value.length==2)
            c.value += '/';
        if(n.value.length==5)
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
                                <div class="col-md-12 form-group">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome" maxlength="70" required value="<?= $nome?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="nomeArtistico">Nome Artistico: *</label>
                                    <input type="text" class="form-control" name="nomeArtistico" placeholder="Digite o nome artistico" maxlength="70" required value="<?= $nomeArtistico?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="rg">RG: *</label>
                                    <input type="text" class="form-control" name="rg" placeholder="Digite o documento" maxlength="20" required value="<?= $rg?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="documento" id="documento">CPF:</label>
                                    <input type="text" name="documento" class="form-control" value="<?= $documento?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ccm">CCM: *</label>
                                    <input type="text" class="form-control" placeholder="Digite o CCM" maxlength="11" required value="<?= $ccm?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="dataNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" name="dataNascimento" onkeyup="barraData(this);" value="<?= $dtNascimento?>"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" name="nacionalidade">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("nacionalidades",'$idPf');
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" name="cep" class="form-control" maxlength="9" placeholder="Digite o CEP" required value="<?= $cep?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="endereco">Endereço: *</label>
                                    <input type="text" name="endereco" class="form-control" placeholder="Digite o endereço" maxlength="200" required value="<?= $endereco?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Digite o número" required value="<?= $numero?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento" value="<?= $complemento?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" name="cidade" class="form-control" placeholder="Digite a cidade" maxlength="50" required value="<?= $cidade?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" name="estado" class="form-control" maxlength="2" placeholder="Digite o estado ex: (SP)" required value="<?= $estado?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required value="<?= $email?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="telefone[]">Telefone #1:</label>
                                    <input type="text" name="telefone[]" class="form-control" placeholder="Digite o telefone" required maxlength="15" value="<?= $telefone[0]?>">
                                </div>
                            </div>
                            <div class="row" id="telefones">
                                <div id="phone1" class="form-group col-md-12">
                                    <label for="telefone[]">Telefone #2:</label>
                                    <input type="text" name="telefone[]" class="form-control" placeholder="Digite o telefone"  maxlength="15" value="<?=$telefone[1]?>">
                                </div>
                            </div>
                            <div class="row" id="telefones">
                                <div class="form-group col-md-12">
                                    <label for="telefone[]">Telefone #3:</label>
                                    <input type="text" name="telefone[]" class="form-control" placeholder="Digite o telefone"  maxlength="15" value="<?= $telefone[2]?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="drt">DRT: </label>
                                    <input type="text" name="drt" class="form-control" maxlength="15" placeholder="Digite o DRT" value="<?= $drt?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="funcao">Função:</label>
                                    <input type="text" class="form-control" name="funcao" value="<?= $funcao?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inscricaoPissInss">Inscrição do INSS ou PIS/PASEP:</label>
                                    <input type="text" class="form-control" name="inscricaoPissInss" placeholder="Digite a inscrição do INSS ou PISS/PASEP" value="<?= $incricoes?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="OMB">OMB:</label>
                                    <input type="text" name="omb" class="form-control" placeholder="Digite o OMB" value="<?= $omb?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="5" class="form-control"><?= $observacao ?></textarea>
                                </div>
                            </div>
                            <div class="box-header">
                                <h3 class="box-title">Dados Bancários</h3>
                            </div>
                            <div class="box-body">
                               <div class="row">
                                 <div class="form-group col-md-12">
                                   <label for="banco">Banco:</label>
                                   <select name="banco" class="form-control">
                                     <option value="">Selecione um banco...</option>
                                        <?php
                                          geraOpcao("bancos",'$idPf');
                                        ?>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                   <div class="form-group col-md-6">
                                       <label for="agencia">Agência: *</label>
                                       <input type="text" name="agencia" class="form-control" placeholder="Digite a Agência" maxlength="12" required value="<?= $agencia?>">
                                   </div>
                                   <div class="form-group col-md-6">
                                       <label for="conta">Conta: *</label>
                                       <input type="text" name="conta" class="form-control" placeholder="Digite a Conta" maxlength="12" required value="<?= $conta?>">
                                   </div>
                               </div>
                               <div class="row">
                                   <div class="form-group col-md-12">
                                       <label for="observacaoConta">Observação:</label>
                                       <textarea name="observacaoConta" rows="5" class="form-control"><?= $observacaoConta?></textarea>
                                   </div>
                               </div>
                            </div>

                            <div class="box-footer">
                                <button type="reset" class="btn btn-default">Cancelar</button>
                                <input type="hidden" name="idPf" value="<?=$idPf?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Alterar</button>
                            </div>
                        </form>
                    </div>
                </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>
<script>

</script>


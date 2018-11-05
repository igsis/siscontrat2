<?php

$con = bancoMysqli();
include "includes/menu_interno.php";

if(isset($_POST['adicionar'])){
    $documento = $_POST['documentacao'] ?? NULL;
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
                                <input type="text" class="form-control" name="nome" placeholder="Digite o nome" maxlength="70" required>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="nomeArtistico">Nome Artistico: *</label>
                                <input type="text" class="form-control" name="nomeArtistico" placeholder="Digite o nome artistico" maxlength="70" required>
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="rg">RG: *</label>
                                    <input type="text" class="form-control" name="rg" placeholder="Digite o documento" maxlength="20" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="documento" id="documento">CPF:</label>
                                    <input type="text" name="documento" class="form-control" value="<?= $documento?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ccm">CCM: *</label>
                                    <input type="text" class="form-control" placeholder="Digite o CCM" maxlength="11" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="dataNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" name="dataNascimento" onkeyup="barraData(this);"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nacionalidade">Nacionalidade: </label>
                                    <select class="form-control" name="nacionalidade" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("nacionalidades","");
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" maxlength="9" placeholder="Digite o CEP" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="endereco">Endereço: *</label>
                                    <input type="text" class="form-control" placeholder="Digite o endereço" maxlength="200" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="numero">Número: *</label>
                                    <input type="number" class="form-control" placeholder="Digite o número" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="complemento">Complemento: *</label>
                                    <input type="text" class="form-control" maxlength="20" placeholder="Digite o complemento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" name="cidade" class="form-control" placeholder="Digite a cidade" maxlength="50" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" name="estado" class="form-control" maxlength="2" placeholder="Digite o estado ex: (SP)">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="telefone[]">Telefone #1:</label>
                                    <input type="text" name="telefone[]" class="form-control" placeholder="Digite o telefone" required maxlength="15">
                                </div>
                            </div>
                            <div class="row" id="telefones">
                                <div id="phone1" class="form-group col-md-12">
                                    <label for="telefone[]">Telefone #2:</label>
                                    <input type="text" name="telefone[]" class="form-control" placeholder="Digite o telefone" required maxlength="15">
                                </div>
                            </div>
                            <div class="row" id="telefones">
                                <div class="form-group col-md-12">
                                    <label for="telefone[]">Telefone #3:</label>
                                    <input type="text" name="telefone[]" class="form-control" placeholder="Digite o telefone" required maxlength="15">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="drt">DRT: </label>
                                    <input type="text" name="drt" class="form-control" maxlength="15" placeholder="Digite o DRT">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="funcao">Função:</label>
                                    <input type="text" class="form-control" name="funcao" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="inscricaoPissInss">Inscrição do INSS ou PIS/PASEP:</label>
                                    <input type="text" class="form-control" name="inscricaoPissInss" placeholder="Digite a inscrição do INSS ou PISS/PASEP">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="OMB">OMB:</label>
                                    <input type="text" name="omb" class="form-control" placeholder="Digite o OMB">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="5" class="form-control"></textarea>
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
                                          geraOpcao("bancos","");
                                        ?>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                   <div class="form-group col-md-6">
                                       <label for="agencia">Agência: *</label>
                                       <input type="text" name="agencia" class="form-control" placeholder="Digite a Agência" maxlength="12" required>
                                   </div>
                                   <div class="form-group col-md-6">
                                       <label for="conta">Conta: *</label>
                                       <input type="text" class="form-control" placeholder="Digite a Conta" maxlength="12" required>
                                   </div>
                               </div>
                               <div class="row">
                                   <div class="form-group col-md-12">
                                       <label for="observacaoConta">Observação:</label>
                                       <textarea name="observacaoConta" rows="5" class="form-control"></textarea>
                                   </div>
                               </div>
                            </div>

                            <div class="box-footer">
                                <button type="reset" class="btn btn-default">Cancelar</button>
                                <button type="submit" name="cadastra" class="btn btn-info pull-right">Salvar</button>
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


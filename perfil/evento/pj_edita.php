<?php
include "includes/menu_interno.php";
$con = bancoMysqli();
date_default_timezone_set('America/Sao_Paulo');

if(isset($_POST['idPj']) || isset($_POST['idProponente'])){
    $idPj = $_POST['idPj'] ?? $_POST['idProponente'];
}

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $razao_social = addslashes($_POST['razao_social']);
    $cnpj = $_POST['cnpj'];
    $ccm = $_POST['ccm'] ?? NULL;
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $cep = $_POST['cep'];
    $logradouro = addslashes($_POST['rua']);
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? NULL;
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $uf = $_POST['estado'];
    $banco = $_POST['banco'] ?? NULL;
    $agencia = $_POST['agencia'] ?? NULL;
    $conta = $_POST['conta'] ?? NULL;
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $ultima_atualizacao = date('Y-m-d H:i:s');
}

if(isset($_POST['cadastra'])) {
    $mensagem = "";
    $sql = "INSERT INTO pessoa_juridicas (razao_social, cnpj, ccm, email, ultima_atualizacao) VALUES ('$razao_social', '$cnpj', '$ccm', '$email', '$ultima_atualizacao')";
    if(mysqli_query($con, $sql)) {
        $idPj = recuperaUltimo('pessoa_juridicas');
        // cadastrar o telefone de pj
        foreach ($telefones AS $telefone){
            if (!empty($telefone)){
                $sqlTel = "INSERT INTO pj_telefones (pessoa_juridica_id, telefone, publicado) VALUES ('$idPj','$telefone',1)";
                mysqli_query($con,$sqlTel);
            }
        }
        // cadastrar endereco de pj
        $sqlEndereco = "INSERT INTO pj_enderecos (pessoa_juridica_id, logradouro, numero, complemento, bairro, cidade, uf, cep) VALUES ('$idPj','$logradouro','$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep')";
        if(!mysqli_query($con, $sqlEndereco)){
            $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.").$sqlEndereco;
        }

        if($banco != NULL){
            $sqlBanco = "INSERT INTO pj_bancos (pessoa_juridica_id, banco_id, agencia, conta) VALUES ('$idPj', '$banco', '$agencia', '$conta')";
            if(!mysqli_query($con, $sqlBanco)){
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.").$sqlBanco;
            }
        }

        if($observacao != NULL){
            $sqlObs = "INSERT INTO pj_observacoes (pessoa_juridica_id, observacao) VALUES ('$idPj','$observacao')";
            if(!mysqli_query($con, $sqlObs)){
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.").$sqlObs;
            }
        }

        $mensagem .= mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $idPj = $_POST['edita'];
    $mensagem = "";
    $sql = "UPDATE pessoa_juridicas SET razao_social = '$razao_social', cnpj = '$cnpj', ccm = '$ccm', email = '$email' WHERE id = '$idPj'";


    if(mysqli_query($con, $sql)) {
        if (isset($_POST['telefone2'])) {
            $telefone2 = $_POST['telefone2'];
            $sqlTelefone2 = "INSERT INTO pj_telefones (pessoa_juridica_id, telefone) VALUES ('$idPessoaJuridica', '$telefone2')";
            $query = mysqli_query($con, $sqlTelefone2);
        }

        if (isset($_POST['telefone3'])) {
            $telefone3 = $_POST['telefone3'];
            $sqlTelefone3 = "INSERT INTO pj_telefones (pessoa_juridica_id, telefone) VALUES ('$idPessoaJuridica', '$telefone3')";
            $query = mysqli_query($con, $sqlTelefone3);
        }

        if (mysqli_query($con, $sql)) {

            foreach ($telefones as $idTelefone => $telefone) {

                if (!strlen($telefone)) {
                    // Deletar telefone do banco se for apagado.
                    $sqlDelete = "DELETE FROM pj_telefones WHERE id = '$idTelefone'";
                    mysqli_query($con, $sqlDelete);
                    gravarLog($sqlDelete);
                }

                if ($telefone != '') {
                    // editar o telefone de pj
                    $sqlTelefone = "UPDATE  pj_telefones SET
                                          telefone = '$telefone'
                                  WHERE id = '$idTelefone'";
                    mysqli_query($con, $sqlTelefone);
                    gravarLog($sqlTelefone);
                }
            }

        $sqlEndereco = "UPDATE pj_enderecos SET logradouro = '$logradouro', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', uf = '$uf', cep = '$cep' WHERE pessoa_juridica_id = '$idPj'";
        if(!mysqli_query($con, $sqlEndereco)){
            $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[E]").$sqlEndereco;
        }

        $banco_existe = verificaExiste("pj_bancos","pessoa_juridica_id",$idPj,0);
        if($banco_existe['numero'] > 0){
            $sqlBanco = "UPDATE pj_bancos SET banco_id = '$banco', agencia = '$agencia', conta = '$conta' WHERE pessoa_juridica_id = '$idPj'";
            if(!mysqli_query($con, $sqlBanco)){
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]").$sqlBanco;
            }
        }
        else{
            $sqlBanco = "INSERT INTO pj_bancos (pessoa_juridica_id, banco_id, agencia, conta) VALUES ('$idPj', '$banco', '$agencia', '$conta')";
            if(!mysqli_query($con, $sqlBanco)){
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.").$sqlBanco;
            }
        }

        if($observacao != NULL){
            $obs_existe = verificaExiste("pj_observacoes","pessoa_juridica_id",$idPj,0);
            if($obs_existe['numero'] > 0){
                $sqlObs = "UPDATE pj_observacoes SET observacao = 'observacao' WHERE pessoa_juridica_id = '$idPj'";
                if(!mysqli_query($con, $sqlObs)){
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]").$sqlObs;
                }
            }
            else{
                $sqlObs = "INSERT INTO pj_observacoes (pessoa_juridica_id, observacao) VALUES ('$idPj','$observacao')";
                if(!mysqli_query($con, $sqlObs)){
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.").$sqlObs;
                }
            }
        }
        $mensagem .= mensagem("success", "Gravado com sucesso!");
    }
    else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

$sqlTelefones = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$pj = recuperaDados("pessoa_juridicas","id",$idPj);
$end = recuperaDados("pj_enderecos","pessoa_juridica_id",$idPj);
$obs = recuperaDados("pj_observacoes","pessoa_juridica_id",$idPj);
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
                    <div class="row" align="center">
                        <?= $mensagem ?? NULL;
                        echo $idPj;
                        ?>
                    </div>

                    <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="razao_social">Razão Social: *</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                           maxlength="100" required value="<?= $pj['razao_social'] ?>">
                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-md-2">
                                    <label for="cnpj">CNPJ: *</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj"
                                           required readonly value="<?= $pj['cnpj'] ?>">

                                </div>
                                <div class="form-group col-md-4">
                                    <label>Anexo do Cartão CNPJ</label><br>
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-cnpj">Clique aqui para anexar</button>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="ccm">CCM: </label>
                                    <input type="text" class="form-control" id="ccm" name="ccm" value="<?= $pj['ccm'] ?>">
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
                                    <input type="email" name="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required value="<?= $pj['email'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="telefone">Telefone #1 * </label>
                                    <input type="text" data-mask="(00) 0000-0000" required class="form-control" id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]" value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="celular">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" data-mask="(00)00000-0000" class="form-control" id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]" value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone1" name="telefone1">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="recado">Telefone #3</label>
                                    <?php if (isset($arrayTelefones[2])) {
                                        ?>
                                        <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]" value="<?=  $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>

                                        <input type="text" data-mask="(00) 00000-0000" class="form-control" id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                $tel = $con->query("SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idPj'");
                                $x = 1;
                                while ($telefone = mysqli_fetch_assoc($tel)){?>
                                    <div class="form-group col-md-2">
                                        <label for="telefone[]">Telefone #<?=$x?>:</label>
                                        <input type="text" name="telefone[]" id="telefone" onkeyup="mascara( this, mtel );"  class="form-control" placeholder="Digite o telefone" required maxlength="15" value="<?= $telefone['telefone']?>">
                                    </div>
                                    <?php
                                    $x++;
                                }
                                ?>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9" placeholder="Digite o CEP" required data-mask="00000-000" value="<?= $end['cep'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite a rua" maxlength="200" readonly value="<?= $end['logradouro'] ?>">
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Ex.: 10" required value="<?= $end['numero'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento" value="<?= $end['complemento'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Digite o Bairro" maxlength="80" readonly value="<?= $end['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Digite a cidade" maxlength="50" readonly value="<?= $end['cidade'] ?>">
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2" placeholder="Ex.: SP" readonly value="<?= $end['uf'] ?>">
                                </div>
                            </div>
                            <hr/>
                            <?php
                            $atracao = $con->query("SELECT valor_individual FROM atracoes WHERE evento_id = '$idEvento'");
                            foreach($atracao as $row)
                            {
                                if($row['valor_individual'] != 0.00){
                                    $banco = recuperaDados("pj_bancos","pessoa_juridica_id",$idPj);
                                ?>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="banco">Banco:</label>
                                            <select id="banco" name="banco" class="form-control">
                                                <option value="">Selecione um banco...</option>
                                                <?php
                                                geraOpcao("bancos", $banco['banco_id']);
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="agencia">Agência:</label>
                                            <input type="text" name="agencia" class="form-control"  placeholder="Digite a Agência" maxlength="12" value="<?= $banco['agencia'] ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="conta">Conta:</label>
                                            <input type="text" name="conta" class="form-control" placeholder="Digite a Conta" maxlength="12" value="<?= $banco['conta'] ?>">
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
                                    <?php
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea id="observacao" name="observacao" rows="3" class="form-control"><?= $obs['observacao'] ?? NULL ?></textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" name="edita" value="<?= $pj['id'] ?>" class="btn btn-info pull-right">Atualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <button type="submit" name="edita" value="<?= $pj['id'] ?>" class="btn btn-info btn-block">Demais Anexos</button>
                            </div>
                            <div class="form-group col-md-3">
                                <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                                    <button type="submit" name="edita" value="<?= $pj['id'] ?>" class="btn btn-info btn-block">Representante 01</button>
                                </form>
                            </div>
                            <div class="form-group col-md-3">
                                <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                                    <button type="submit" name="edita" value="<?= $pj['id'] ?>" class="btn btn-info btn-block">Representante 02</button>
                                </form>
                            </div>
                            <div class="form-group col-md-3">
                                <form method="POST" action="?perfil=evento&p=pj_edita" role="form">
                                    <button type="submit" name="edita" value="<?= $pj['id'] ?>" class="btn btn-info btn-block">Ir ao pedido de contratação</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /. box-body -->
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

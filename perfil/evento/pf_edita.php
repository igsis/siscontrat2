<?php
$idPf = $_SESSION['idPf_pedido'] ?? NULL;
function recuperaTelefones($id,$tabela,$campo,$campoWhere){

    $con = bancoMysqli();

    $sql = "SELECT '$campo' FROM '$tabela'
            WHERE '$campoWhere' = '$id'";

    $query = mysqli_query($con,$sql);
//    $resultado = mysqli_fetch_array($query);

//    return $resultado;
}

$con = bancoMysqli();


if (isset($_POST['cadastra']) || isset($_POST['edita'])){

    $nome = $_POST['nome'];
    $nomeArtistico = $_POST['nomeArtistico'];
    $rg = $_POST['rg'] ?? NULL;
    $cpf = $_POST['cpf'] ?? NULL;
    $passaporte = $_POST['passaporte'] ?? NULL;
    $ccm = $_POST['ccm'] ?? NULL;
    $dtNascimento = $_POST['dtNascimento'] ?? NULL;
    $nacionalidade = $_POST['nacionalidade'];
    $cep = $_POST['cep'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'] ?? NULL;
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $drt = $_POST['drt'] ?? NULL;
    $incricoes = $_POST['inscricaoPissInss'] ?? NULL;
    $observacao = $_POST['observacao'] ?? NULL;
    $banco = $_POST['banco'];
    $agencia = $_POST['agencia'];
    $conta = $_POST['conta'];


}
if (isset($_POST['cadastra'])){
    //PEGA DATA ATUAL
    date_default_timezone_set('America/Sao_Paulo');
    $data = date("y-m-d h:i:s");

    $sql = "INSERT INTO siscontrat.`pessoa_fisicas`
                  (nome, nome_artistico, rg, passaporte, cpf, ccm, data_nascimento, nacionalidade_id, email, ultima_atualizacao)
            VALUES('$nome','$nomeArtistico','$rg','$passaporte','$cpf','$ccm','$dtNascimento','$nacionalidade','$email','$data')";

    if (mysqli_query($con,$sql)){

        $idPf = recuperaUltimo("pessoa_fisicas");

        //$_SESSION['idPf_pedido'] =  $idPf;

        $sqlEnd = "INSERT INTO siscontrat.pf_enderecos
                          (pessoa_fisica_id, logradouro, numero,complemento, bairro, cidade, uf, cep)
                   VALUES ('$idPf','$rua','$numero','$complemento','$bairro','$cidade','$estado','$cep')";
        if (mysqli_query($con,$sqlEnd)){
            foreach ($telefone AS $telefones){
                if (!empty($telefones)){
                    $sqlTel = "INSERT INTO siscontrat.`pf_telefones`
                               (pessoa_fisica_id, telefone, publicado)
                               VALUES ('$idPf','$telefones',1)";
                    mysqli_query($con,$sqlTel);
                }
            }
            $sqlDRT = "INSERT INTO siscontrat.`drts`
                       (pessoa_fisica_id, drt, publicado) 
                       VALUES ('$idPf','$drt',1)";

            if (mysqli_query($con,$sqlDRT)) {

                    $sqlObservacao = "INSERT INTO siscontrat.`pf_observacoes`
                              (pessoa_fisica_id,observacao,publicado)
                              VALUES ('$idPf','$observacao',1)";
                    if (mysqli_query($con, $sqlObservacao)){

                        $sqlBanco = "INSERT INTO siscontrat.`pf_bancos`
                             (pessoa_fisica_id, banco_id, agencia, conta)
                             VALUES ('$idPf','$banco','$agencia','$conta')";
                        if (mysqli_query($con, $sqlBanco)){
                            $idEvento = $_SESSION['idEvento'];
                            $sql_pedido = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id,pessoa_fisica_id) VALUES ('1', '$idEvento','1','$idPf')";
                            if(mysqli_query($con,$sql_pedido)){
                                $idPedido = recuperaUltimo("pedidos");
                                $_SESSION['idPedido'] = $idPedido;
                                $mensagem = mensagem("success","Cadastro realizado com sucesso.");
                                echo "<meta http-equiv='refresh' content='0.5, url=?perfil=evento&p=pedido_edita'>";
                            }
                            else{
                                $mensagem = mensagem("danger","Erro ao inserir banco: ". die(mysqli_error($con)));
                            }

                        }else{
                            $mensagem = mensagem("danger","Erro ao inserir banco: ". die(mysqli_error($con)));
                        }
                    }else{
                        $mensagem = mensagem("danger", "Error ao inserir a observação: ".die(mysqli_error($con)));
                    }
            }else{
                $mensagem =  mensagem("danger","Erro ao inserir DRT ". die(mysqli_error($con)));
            }
        }else{
            $mensagem =  mensagem("danger","Erro inserir endereço: ".die(mysqli_error($con)));
        }
    }else{
        $mensagem = mensagem("danger","Erro inserir pessoa fisica: ".die(mysqli_error($con)));
    }
}

if (isset($_POST['edita'])){

    $idPf = $_POST['idPf'];
    date_default_timezone_set('America/Sao_Paulo');
    $data = date("y-m-d h:i:s");
    $sql = "UPDATE siscontrat.`pessoa_fisicas` SET 
                   nome = '$nome',
                   nome_artistico = '$nomeArtistico',
                   rg = '$rg',
                   passaporte = '$passaporte',
                   cpf = '$cpf',
                   ccm = '$ccm',
                   data_nascimento = '$dtNascimento',
                   nacionalidade_id = '$nacionalidade',
                   email = '$email',
                   ultima_atualizacao = '$data'
                   WHERE id = '$idPf'";

    if (mysqli_query($con, $sql)){
        $mensagem = mensagem("success","Dados atualizado");
    }else{
        $mensagem = mensagem("danger","Erro: ".die(mysqli_error($con)));
    }

}

if(isset($_POST['carregar'])){
    $idPf = $_POST['idPf'];
    $_SESSION['idPf_pedido'] =  $idPf;
}

include "includes/menu_pf.php";

$pessoaFisica = recuperaDados("pessoa_fisicas","id",$idPf);
$endereco = recuperaDados("pf_enderecos","pessoa_fisica_id",$idPf);
$telefones = recuperaTelefones($pessoaFisica['id'],"pf_telefones","telefone","pessoa_fisica_id");
$drts = recuperaDados("drts","pessoa_fisica_id",$idPf);
$observacao = recuperaDados("pf_observacoes","pessoa_fisica_id",$idPf);
$banco = recuperaDados("pf_bancos","pessoa_fisica_id", $idPf);

?>

<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
        $("#telefone").mask('(00) 0000-00009', {reverse: true});
    });

</script>
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
        <?php if (isset($mensagem)) {
            echo $mensagem;
        }; ?>
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
                                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome" maxlength="70" required value="<?= $pessoaFisica['nome']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="nomeArtistico">Nome Artistico: *</label>
                                    <input type="text" class="form-control" name="nomeArtistico" placeholder="Digite o nome artistico" maxlength="70" required value="<?= $pessoaFisica['nome_artistico']?>">
                                </div>
                            </div>
                            <?php if ($pessoaFisica['rg'] != NULL){?>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="rg">RG: </label>
                                    <input type="text" class="form-control" name="rg" placeholder="Digite o documento" maxlength="20" value="<?= $pessoaFisica['rg']?>">
                                </div>
                            </div>
                            <?php } ?>

                            <div class="row">
                                <?php if (!empty($pessoaFisica['cpf'])){?>
                                <div class="form-group col-md-6">
                                    <label for="cpf" id="cpf">CPF:</label>
                                    <input type="text" name="cpf" class="form-control" value="<?= $pessoaFisica['cpf']?>" disabled>
                                </div>
                                <?php }else{?>
                                <div class="form-group col-md-6">
                                    <label for="passaporte" >Passaporte:</label>
                                    <input type="text" name="passaporte" class="form-control" value="<?= $pessoaFisica['passaporte']?>" disabled>
                                </div>
                                <?php }?>
                                <div class="form-group col-md-6">
                                    <label for="ccm">CCM: *</label>
                                    <input type="text" name="ccm" class="form-control" placeholder="Digite o CCM" maxlength="11" required value="<?= $pessoaFisica['ccm']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="dataNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" name="dataNascimento" onkeyup="barraData(this);" value="<?= $pessoaFisica['data_nascimento']?>"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" name="nacionalidade">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                            geraOpcao("nacionalidades",$pessoaFisica['nacionalidade_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9" placeholder="Digite o CEP" required value="<?=$endereco['cep']?>" data-mask="00000-000">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite o endereço" maxlength="200" readonly value="<?= $endereco['logradouro']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Digite o número" required value="<?= $endereco['numero']?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento" value="<?= $endereco['complemento']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Digite o Bairro" maxlength="80" readonly value="<?= $endereco['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Digite a cidade" maxlength="50" readonly value="<?= $endereco['cidade']?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2" placeholder="Digite o estado ex: (SP)" readonly value="<?= $endereco['uf']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required value="<?= $pessoaFisica['email']?>">
                                </div>
                            </div>
                            <?php
                            $id = $pessoaFisica["id"];
                            $sql = "SELECT * FROM pf_telefones
                                    WHERE pessoa_fisica_id = '$id'";

                            $query = mysqli_query($con,$sql);
                            //                                $resultado = mysqli_fetch_array($query);
                            $x = 1;
                            while ($telefone = mysqli_fetch_assoc($query)){?>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="telefone[]">Telefone #<?=$x?>:</label>
                                        <input type="text" name="telefone[]" id="telefone" class="form-control" placeholder="Digite o telefone" required maxlength="15" value="<?= $telefone['telefone']?>" data-mask="(00) 0000-00009">
                                    </div>
                                </div>
                                <?php
                                $x++;
                            }
                            ?>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="drt">DRT: </label>
                                    <input type="text" name="drt" class="form-control" maxlength="15" placeholder="Digite o DRT" value="<?= $drts['drt']?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="5" class="form-control"><?= $observacao['observacao'] ?></textarea>
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
                                          geraOpcao("bancos",$banco['banco_id']);
                                        ?>
                                   </select>
                                 </div>
                               </div>
                               <div class="row">
                                   <div class="form-group col-md-6">
                                       <label for="agencia">Agência: *</label>
                                       <input type="text" name="agencia" class="form-control" placeholder="Digite a Agência" maxlength="12" required value="<?= $banco['agencia']?>">
                                   </div>
                                   <div class="form-group col-md-6">
                                       <label for="conta">Conta: *</label>
                                       <input type="text" name="conta" class="form-control" placeholder="Digite a Conta" maxlength="12" required value="<?= $banco['conta']?>">
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


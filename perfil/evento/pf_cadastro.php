<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

/*
 * pf_edita
 */

$con = bancoMysqli();
$conn = bancoPDO();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";
$link_facc = $http . "rlt_fac_pf.php";
$tipoPessoa = 1;


if (isset($_POST['idPf']) || isset($_POST['idProponente'])) {
    $idPf = $_POST['idPf'] ?? $_POST['idProponente'];
}

if (isset($_POST['editProponente'])) {
    $idPedido = $_SESSION['idPedido'];
    $voltar = "<form action='?perfil=evento&p=pedido_edita' method='post'>
                    <input type='hidden' name='idProponente' value='$idPf'>
                    <input type='hidden' name='tipoPessoa' value='$tipoPessoa'>
                        <button type='submit' name='idPedido' id='idPedido' value='$idPedido' class='btn btn-default'>Voltar</button>
                    </form>";
} else {
    $voltar = "<form action='?perfil=evento&p=pf_pesquisa' method='post'>
                        <button type='submit' class='btn btn-default'>Voltar</button>
                    </form>";
}

if (isset($_POST['cadastra']) || isset($_POST['edita']) || isset($_POST['cadastraComLider']) || isset($_POST['atualizaPf'])) {
    $nome = addslashes($_POST['nome']);
    $nomeArtistico = addslashes($_POST['nomeArtistico']);
    $rg = $_POST['rg'] ?? NULL;
    $cpf = $_POST['cpf'] ?? NULL;
    $passaporte = $_POST['passaporte'] ?? NULL;
    $ccm = $_POST['ccm'] ?? NULL;
    $dtNascimento = $_POST['dtNascimento'] ?? NULL;
    $nacionalidade = $_POST['nacionalidade'];
    $cep = $_POST['cep'];
    $logradouro = addslashes($_POST['rua']);
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'] ?? NULL;
    $bairro = addslashes($_POST['bairro']);
    $cidade = addslashes($_POST['cidade']);
    $uf = $_POST['estado'];
    $email = $_POST['email'];
    $telefones = $_POST['telefone'];
    $drt = $_POST['drt'] ?? NULL;
    $nit = $_POST['nit'] ?? NULL;
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $banco = $_POST['banco'] ?? NULL;
    $agencia = $_POST['agencia'] ?? NULL;
    $conta = $_POST['conta'] ?? NULL;
    $data = date("y-m-d h:i:s", strtotime("-3 hours"));
}
if (isset($_POST['cadastra']) || isset($_POST['cadastraComLider']) || isset($_POST['atualizaPf'])) {
    $mensagem = "";
    $sql = "INSERT INTO siscontrat.`pessoa_fisicas` (nome, nome_artistico, rg, passaporte, cpf, ccm, data_nascimento, nacionalidade_id, email, ultima_atualizacao) VALUES('$nome','$nomeArtistico','$rg','$passaporte','$cpf','$ccm','$dtNascimento','$nacionalidade','$email','$data')";
    if (mysqli_query($con, $sql)) {
        $idPf = recuperaUltimo("pessoa_fisicas");
// cadastrar o telefone de pf
        foreach ($telefones AS $telefone) {
            if (!empty($telefone)) {
                $sqlTel = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone, publicado) VALUES ('$idPf','$telefone',1)";
                mysqli_query($con, $sqlTel);
            }
        }
// cadastrar endereco de pf
        $sqlEndereco = "INSERT INTO pf_enderecos (pessoa_fisica_id, logradouro, numero, complemento, bairro, cidade, uf, cep) VALUES ('$idPf','$logradouro','$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep')";
        if (!mysqli_query($con, $sqlEndereco)) {
            $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlEndereco;
        }

        if ($banco != NULL) {
            $sqlBanco = "INSERT INTO pf_bancos (pessoa_fisica_id, banco_id, agencia, conta) VALUES ('$idPf', '$banco', '$agencia', '$conta')";
            if (!mysqli_query($con, $sqlBanco)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlBanco;
            }
        }

        if ($observacao != NULL) {
            $sqlObs = "INSERT INTO pf_observacoes (pessoa_fisica_id, observacao) VALUES ('$idPf','$observacao')";
            if (!mysqli_query($con, $sqlObs)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlObs;
            }
        }

        if ($drt != NULL) {
            $sqlDRT = "INSERT INTO siscontrat.`drts` (pessoa_fisica_id, drt, publicado)  VALUES ('$idPf','$drt',1)";
            if (!mysqli_query($con, $sqlDRT)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlDRT;
            }
        }
        if ($nit != NULL) {
            $sqlNit = "INSERT INTO siscontrat.`nits` (pessoa_fisica_id, nit, publicado)  VALUES ('$idPf','$nit',1)";
            if (!mysqli_query($con, $sqlNit)) {
                $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlNit;
            }
        }

        if (isset($_POST['cadastraComLider'])) {
            $idPedido = $_POST['idPedido'];
            $idAtracao = $_POST['idAtracao'];

            $sqlDeleteLider = "DELETE FROM lideres WHERE atracao_id = '$idAtracao'";

            if (mysqli_query($con, $sqlDeleteLider)) {
                $sqLider = "INSERT INTO lideres (pedido_id, atracao_id, pessoa_fisica_id) 
                                VALUES ('$idPedido', '$idAtracao', '$idPf')";
                if (mysqli_query($con, $sqLider)) {
                    echo "<script>swal('Líder selecionado com sucesso!', '', 'success') </script>";
                }
            }
        }

        if (isset($_POST['atualizaPf'])) {
            $idPedido = $_POST['idPedido'];
            $sqlTestaTroca = "SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 1";
            $queryTestaTroca = mysqli_query($con, $sqlTestaTroca);
            $row = mysqli_num_rows($queryTestaTroca);
            if ($row != 0) {
                $trocaPf = "<div class='form-group col-md-3 pull-right'>
                            <form method='POST' action='?perfil=evento&p=pedido_edita' role='form'>
                                <input type='hidden' name='idPedido' value='$idPedido'>
                                <input type='hidden' name='idPf' value='$idPf'>
                                <button type='submit' name='trocaPf' class='btn btn-info btn-block'> Ir ao pedido de contratação </button>
                            </form>
                        </div>";
            }
        }


        $mensagem .= mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sql;
        //gravarLog($sql);
    }
}

if (isset($_POST['edita'])) {
    $mensagem = "";
    $idPf = $_POST['idPf'];
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

    if (mysqli_query($con, $sql)) {
        //edita telefone
        if (isset($_POST['telefone2'])) {
            $telefone2 = $_POST['telefone2'];
            $sqlTelefone2 = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone) VALUES ('$idPf', '$telefone2')";
            $query = mysqli_query($con, $sqlTelefone2);
            gravarLog($sqlTelefone2);
        }

        if (isset($_POST['telefone3'])) {
            $telefone3 = $_POST['telefone3'];
            $sqlTelefone3 = "INSERT INTO pf_telefones (pessoa_fisica_id, telefone) VALUES ('$idPf', '$telefone3')";
            $query = mysqli_query($con, $sqlTelefone3);
            gravarLog($sqlTelefone3);
        }
        foreach ($telefones as $idTelefone => $telefone) {
            if (!strlen($telefone)) {
                // Deletar telefone do banco se for apagado.
                $sqlDelete = "DELETE FROM pf_telefones WHERE id = '$idTelefone'";
                mysqli_query($con, $sqlDelete);
                gravarLog($sqlDelete);
            }

            if ($telefone != '') {
                // editar o telefone de pf
                $sqlTelefone = "UPDATE  pf_telefones SET telefone = '$telefone' WHERE id = '$idTelefone'";
                mysqli_query($con, $sqlTelefone);
                gravarLog($sqlTelefone);
            }
        }
        //edita endereço
        if ($logradouro != NULL) {
            $endereco_existe = verificaExiste("pf_enderecos", "pessoa_fisica_id", $idPf, 0);
            if ($endereco_existe['numero'] > 0) {
                $sqlEndereco = "UPDATE pf_enderecos SET logradouro = '$logradouro', numero = '$numero', complemento = '$complemento', bairro = '$bairro', cidade = '$cidade', uf = '$uf', cep = '$cep' WHERE pessoa_fisica_id = '$idPf'";
                if (!mysqli_query($con, $sqlEndereco)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[E]") . $sqlEndereco;
                }
            } else {
                $sqlEndereco = "INSERT INTO pf_enderecos (pessoa_fisica_id, logradouro, numero, complemento, bairro, cidade, uf, cep) VALUES ('$idPf','$logradouro','$numero', '$complemento', '$bairro', '$cidade', '$uf', '$cep')";
                if (!mysqli_query($con, $sqlEndereco)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlEndereco;
                }
            }
        }
        //edita banco
        if ($banco != NULL) {
            $banco_existe = verificaExiste("pf_bancos", "pessoa_fisica_id", $idPf, 0);
            if ($banco_existe['numero'] > 0) {
                $sqlBanco = "UPDATE pf_bancos SET banco_id = '$banco', agencia = '$agencia', conta = '$conta' WHERE pessoa_fisica_id = '$idPf'";
                if (!mysqli_query($con, $sqlBanco)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlBanco;
                }
            } else {
                $sqlBanco = "INSERT INTO pf_bancos (pessoa_fisica_id, banco_id, agencia, conta) VALUES ('$idPf', '$banco', '$agencia', '$conta')";
                if (!mysqli_query($con, $sqlBanco)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Primeiro registre uma atracao, para entao fazer seu pedido.") . $sqlBanco;
                }
            }
        }
        //edita nit
        if ($nit != NULL) {
            $nit_existe = verificaExiste("nits", "pessoa_fisica_id", $idPf, 0);
            if ($nit_existe['numero'] > 0) {
                $sqlNit = "UPDATE nits SET nit = '$nit' WHERE pessoa_fisica_id = '$idPf'";
                if (!mysqli_query($con, $sqlNit)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlNit;
                }
            } else {
                $sqlNit = "INSERT INTO siscontrat.`nits` (pessoa_fisica_id, nit, publicado)  VALUES ('$idPf','$nit',1)";
                if (!mysqli_query($con, $sqlNit)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Primeiro registre uma atracao, para entao fazer seu pedido.") . $sqlNit;
                }
            }
        }
        //edita drt
        if ($drt != NULL) {
            $drt_existe = verificaExiste("drts", "pessoa_fisica_id", $idPf, 0);
            if ($drt_existe['numero'] > 0) {
                $sqlNit = "UPDATE drts SET drt = '$drt' WHERE pessoa_fisica_id = '$idPf'";
                if (!mysqli_query($con, $sqlNit)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlNit;
                }
            } else {
                $sqlNit = "INSERT INTO siscontrat.`drts` (pessoa_fisica_id, drt, publicado)  VALUES ('$idPf','$drt',1)";
                if (!mysqli_query($con, $sqlNit)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Primeiro registre uma atracao, para entao fazer seu pedido.") . $sqlNit;
                }
            }
        }
        //edita observação
        if ($observacao != NULL) {
            $obs_existe = verificaExiste("pf_observacoes", "pessoa_fisica_id", $idPf, 0);
            if ($obs_existe['numero'] > 0) {
                $sqlObs = "UPDATE pf_observacoes SET observacao = '$observacao' WHERE pessoa_fisica_id = '$idPf'";
                if (!mysqli_query($con, $sqlObs)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.[B]") . $sqlObs;
                }
            } else {
                $sqlObs = "INSERT INTO pf_observacoes (pessoa_fisica_id, observacao) VALUES ('$idPf','$observacao')";
                if (!mysqli_query($con, $sqlObs)) {
                    $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.") . $sqlObs;
                }
            }
        }

        $mensagem .= mensagem("success", "Gravado com sucesso!");
    } else {
        $mensagem .= mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

if (isset($_POST['carregar'])) {
    $idPf = $_POST['idPf'];
}
/*
 * ./pf_edita
 */

if (isset($_POST['adicionar']) || isset($_POST['adicionarLider']) || isset($_POST['adicionaPf']) || isset($_POST['atualizaPf'])) {
    $documento = $_POST['documentacao'];
    $tipoDocumento = $_POST['tipoDocumento'];

    if (isset($_POST['adicionarLider'])) {
        $idAtracao = $_POST['idAtracao'];
        $idPedido = $_POST['idPedido'];
        $botoesFooter = "<input type='hidden' name='idPedido' value='$idPedido'>
                            <input type='hidden' name='idAtracao' value='$idAtracao'>
                            <button type='submit' name='cadastraComLider' class='btn btn-info pull-right'>Salvar</button>";

    } else if(isset($_POST['adicionaPf'])) {
        $idPedido = $_POST['idPedido'];
        $botoesFooter = "<input type='hidden' name='idPedido' value='$idPedido'>
                            <button type='submit' name='atualizaPf' class='btn btn-info pull-right'>Salvar</button>";
    }else{
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

$sqlOficina = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
$queryOficina = mysqli_query($con, $sqlOficina);
//$atracoes = mysqli_fetch_array($queryAtracao);

while ($atracoes = mysqli_fetch_array($queryOficina)) {
    $valores [] = $atracoes['valor_individual'];
}

if (isset($valores) && $valores > 0) {
    $valorTotal = 0;
    foreach ($valores as $valor) {
        $valorTotal += $valor;
    }
} else {
    $valorTotal = 0;
}

$idPf = $idPf ?? NULL;
$pf = recuperaDados("pessoa_fisicas", "id", $idPf);

$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$endereco = recuperaDados("pf_enderecos", "pessoa_fisica_id", $idPf);
$drts = recuperaDados("drts", "pessoa_fisica_id", $idPf);
$nits = recuperaDados("nits", "pessoa_fisica_id", $idPf);
$observacao = recuperaDados("pf_observacoes", "pessoa_fisica_id", $idPf);
$banco = recuperaDados("pf_bancos", "pessoa_fisica_id", $idPf);

if(isset($idPf)){
    $documento = $pf['cpf'] ?? $pf['passaporte'];
    if(!empty($pf['cpf'])){
        $tipoDocumento = 1;
    }
    else{
        $tipoDocumento = 2;
    }
}

$atracao = $con->query("SELECT valor_individual FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1")->fetch_array();
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
                        <form action="?perfil=evento&p=pf_cadastro" method="post">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite o nome" maxlength="70" required  value="<?= $pf['nome'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nomeArtistico">Nome Artistico:</label>
                                    <input type="text" class="form-control" name="nomeArtistico" id="nomeArtistico" placeholder="Digite o nome artistico" maxlength="70" value="<?= $pf['nome_artistico'] ?>">
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                if ($tipoDocumento == 1) {
                                    ?>
                                    <div class="form-group col-md-2">
                                        <label for="rg">RG: *</label>
                                        <input type="text" class="form-control" name="rg" id="rg" placeholder="Digite o RG" maxlength="20" required value="<?= $pf['rg'] ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cpf">CPF: </label>
                                        <input type="text" name="cpf" class="form-control" id="cpf" value="<?= $documento ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="ccm">CCM:</label>
                                        <input type="text" name="ccm" id="ccm" class="form-control" placeholder="Digite o CCM" maxlength="11" value="<?= $pf['ccm'] ?>">
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="passaporte" id="documento">Passaporte: </label>
                                        <input type="text" id="passaporte" name="passaporte" class="form-control" value="<?= $documento ?>" readonly>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-3">
                                    <label for="dataNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" id="dataNascimento" name="dtNascimento" onkeyup="barraData(this);" required value="<?= $pf['data_nascimento'] ?>"/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" id="nacionalidade" name="nacionalidade" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("nacionalidades", $pf['nacionalidade_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9" placeholder="Digite o CEP" required data-mask="00000-000" value="<?= $endereco['cep'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua" placeholder="Digite a rua" maxlength="200" readonly value="<?= $endereco['logradouro'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label> <i>(se não houver, marcar 0)</i>
                                    <input type="number" name="numero" id="numero" class="form-control" placeholder="Ex.: 10" min="0" required value="<?= $endereco['numero'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" maxlength="20" placeholder="Digite o complemento" value="<?= $endereco['complemento'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro" placeholder="Digite o Bairro" maxlength="80" readonly value="<?= $endereco['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Digite a cidade" maxlength="50" readonly value="<?= $endereco['cidade'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2" placeholder="Ex.: SP" readonly value="<?= $endereco['uf'] ?>">
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" id="email" class="form-control" maxlength="60" placeholder="Digite o E-mail" required value="<?= $pf['email'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #1: *</label>
                                    <?php
                                    if(isset($arrayTelefones[0])){
                                       ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" required class="form-control" id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]" value="<?= $arrayTelefones[0]['telefone']; ?>">
                                        <?php
                                    }else{
                                        ?>
                                        <input type="text" id="telefone" name="telefone[0]" onkeyup="mascara( this, mtel );" class="form-control" placeholder="Digite o telefone" required maxlength="15">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #2:</label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" class="form-control" id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]" value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" class="form-control" id="telefone1" name="telefone1">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #3:</label>
                                    <?php
                                    if (isset($arrayTelefones[2])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" class="form-control" id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]" value="<?= $arrayTelefones[2]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" class="form-control" id="telefone2" name="telefone1">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                if ($mostraDRT){
                                ?>
                                    <div class="form-group col-md-6">
                                        <label for="drt">DRT: </label>
                                        <input type="text" name="drt" id="drt" class="form-control" maxlength="15" placeholder="Digite o DRT" value="<?= $drts['drt'] ?>">
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
                                        <input type="text" name="nit" id="nit" class="form-control" maxlength="45" placeholder="Digite o NIT" value="<?= $nits['nit'] ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="observacao">Observação: </label>
                                        <textarea id="observacao" name="observacao" rows="3" class="form-control"><?= $observacao['observacao'] ?></textarea>
                                    </div>
                                </div>
                                <?php
                                if($atracao['valor_individual'] > 0 || $evento['tipo_evento_id'] == 2) {
                                    ?>

                                    <hr/>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="banco">Banco:</label>
                                            <select required id="banco" name="banco" class="form-control">
                                                <option value="">Selecione um banco...</option>
                                                <?php
                                                geraOpcao("bancos", "");
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="agencia">Agência: *</label>
                                            <input type="text" name="agencia" id="agencia" class="form-control" placeholder="Digite a Agência" maxlength="12" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="conta">Conta: *</label>
                                            <input type="text" name="conta" id="conta" class="form-control" placeholder="Digite a Conta" maxlength="12" required>
                                        </div>
                                    </div>
                                    <?php
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
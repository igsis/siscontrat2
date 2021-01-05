<?php
$con = bancoMysqli();
$conn = bancoPDO();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";
$linkResumo = $http . "rlt_formacao_pf.php";
$link_facc = $http . "rlt_fac_pf.php";

if(isset($_POST['carregar'])){
    $idPf = $_POST['idPf'];
}

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
    $nome = trim(addslashes($_POST['nome']));
    $nomeArtistico = isset($_POST['nomeArtistico']) ? trim(addslashes($_POST['nomeArtistico'])) : "";
    $rg =  isset($_POST['rg']) ? trim($_POST['rg']) : NULL;
    $cpf = $_POST['cpf'] ?? NULL;
    $passaporte = $_POST['passaporte'] ?? NULL;
    $ccm = isset($_POST['ccm']) ? trim($_POST['ccm']) : NULL;
    $dtNascimento = $_POST['dtNascimento'] ?? NULL;
    $nacionalidade = $_POST['nacionalidade'];
    $cep = $_POST['cep'];
    $logradouro = trim(addslashes($_POST['rua']));
    $numero = $_POST['numero'];
    $complemento = trim($_POST['complemento']) ?? NULL;
    $bairro = trim(addslashes($_POST['bairro']));
    $cidade = trim(addslashes($_POST['cidade']));
    $uf = trim($_POST['estado']);
    $email = trim($_POST['email']);
    $telefones = $_POST['telefone'];
    $drt = isset($_POST['drt']) ? trim($_POST['drt']) : NULL;
    $nit = trim($_POST['nit']) ?? NULL;
    $observacao = trim(addslashes($_POST['observacao'])) ?? NULL;
    $banco = $_POST['banco'] ?? NULL;
    $agencia = isset($_POST['agencia']) ? trim($_POST['agencia']) : NULL;
    $conta = isset($_POST['conta']) ? trim($_POST['conta']) : NULL;
    $data = date("y-m-d h:i:s", strtotime("-3 hours"));
}
if (isset($_POST['cadastra'])) {
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

if (isset($_POST['selecionar']) || isset($_POST['Voltar'])) {
    $idPf = $_POST['idPf'];
}

$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$pf = recuperaDados("pessoa_fisicas", "id", $idPf);
$testaEnderecos = $con->query("SELECT * FROM pf_enderecos WHERE pessoa_fisica_id = $idPf");

if ($testaEnderecos->num_rows > 0) {
    while ($enderecoArray = mysqli_fetch_array($testaEnderecos)) {
        $cep = $enderecoArray['cep'];
        $logradouro = $enderecoArray['logradouro'];
        $numero = $enderecoArray['numero'];
        $complemento = $enderecoArray['complemento'];
        $cidade = $enderecoArray['cidade'];
        $bairro = $enderecoArray['bairro'];
        $uf = $enderecoArray['uf'];
    }
} else {
    $cep = NULL;
    $logradouro = NULL;
    $numero = NULL;
    $complemento = NULL;
    $cidade = NULL;
    $bairro = NULL;
    $uf = NULL;
}

$testaNit = $con->query("SELECT nit FROM nits WHERE pessoa_fisica_id = $idPf");

if ($testaNit->num_rows > 0) {
    while ($nitArray = mysqli_fetch_array($testaNit)) {
        $nit = $nitArray['nit'];
    }
} else {
    $nit = NULL;
}

$testaDRT = $con->query("SELECT drt FROM drts WHERE pessoa_fisica_id = $idPf");

if ($testaDRT->num_rows > 0) {
    while ($drtArray = mysqli_fetch_array($testaDRT)) {
        $drt = $drtArray['drt'];
    }
} else {
    $drt = NULL;
}

$testaObs = $con->query("SELECT * FROM pf_observacoes WHERE pessoa_fisica_id = $idPf");

if ($testaObs->num_rows > 0) {
    while ($obsArray = mysqli_fetch_array($testaObs)) {
        $obs = $obsArray['observacao'];
    }
} else {
    $obs = NULL;
}

$testaBanco = $con->query("SELECT * FROM pf_bancos WHERE publicado = 1 AND pessoa_fisica_id = $idPf");
if ($testaBanco->num_rows > 0) {
    while ($bancoArray = mysqli_fetch_array($testaBanco)) {
        $agencia = $bancoArray['agencia'];
        $conta = $bancoArray['conta'];
        $banco = $bancoArray['banco_id'];
    }
} else {
    $agencia = NULL;
    $conta = NULL;
    $banco = NULL;
}
?>

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
        <h2 class="page-header">Edição de pessoa física</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="row" align="center">
                        <?= $mensagem ?? NULL; ?>
                    </div>
                    <div class="box-header">
                        <div class="row">
                            <h3 class="box-title col-sm-12 col-md-6">Pessoa física</h3>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="?perfil=formacao&p=pessoa_fisica&sp=edita" method="post">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome"
                                           pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,70}"
                                           maxlength="70" required value="<?= $pf['nome'] ?>">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="nomeArtistico">Nome Artistico:</label>
                                    <input type="text" class="form-control" name="nomeArtistico"
                                           placeholder="Digite o nome artistico" maxlength="70"
                                           value="<?= $pf['nome_artistico'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if (empty($pf['cpf'])) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="passaporte">Passaporte: </label>
                                        <input type="text" name="passaporte" readonly value="<?= $pf['passaporte'] ?>"
                                               class="form-control">
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="form-group col-md-2">
                                        <label for="rg">RG: *</label>
                                        <input type="text" class="form-control" name="rg" placeholder="Digite o RG"
                                               maxlength="20" required value="<?= $pf['rg'] ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cpf">CPF: </label>
                                        <input type="text" id="cpf" name="cpf" class="form-control"
                                               value="<?= $pf['cpf'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="ccm">CCM:</label>
                                        <input type="text" name="ccm" class="form-control" placeholder="Digite o CCM"
                                               maxlength="11" value="<?= $pf['ccm'] ?>">
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-3">
                                    <label for="dtNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" id="dtNascimento" name="dtNascimento"
                                           onkeyup="barraData(this);" value="<?= $pf['data_nascimento'] ?>"/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" id="nacionalidade" name="nacionalidade">
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
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000"
                                           value="<?= $cep ?>">
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
                                           placeholder="Digite o endereço" maxlength="200" readonly
                                           value="<?= $logradouro ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <i>(se não houver, marcar 0)</i>
                                    <input type="number" name="numero" class="form-control"
                                           placeholder="Digite o número" min="0" required
                                           value="<?= $numero ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento" value="<?= $complemento ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly
                                           value="<?= $bairro ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly
                                           value="<?= $cidade ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Digite o estado ex: (SP)" readonly
                                           value="<?= $uf ?>">
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60"
                                           placeholder="Digite o E-mail" required value="<?= $pf['email'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="telefone">Telefone #1 * </label>
                                    <input type="text" onkeyup="mascara( this, mtel );" maxlength="15" required
                                           class="form-control" data-mask="(00) 00000-0000"
                                           id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="celular">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                               pattern=".{14,15}" title="14 a 15 caracteres"
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               pattern=".{14,15}" title="14 a 15 caracteres"
                                               id="telefone1" name="telefone1">
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="recado">Telefone #3</label>
                                    <?php if (isset($arrayTelefones[2])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               pattern=".{14,15}" title="14 a 15 caracteres"
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>

                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
                                               pattern=".{14,15}"  title="14 a 15 caracteres"
                                               id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nit">NIT: </label>
                                    <input type="text" name="nit" class="form-control telefone" maxlength="45"
                                           placeholder="Digite o NIT" value="<?= $nit ?>">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="drt">DRT: </label>
                                    <input type="text" name="drt" class="form-control telefone" maxlength="15"
                                           placeholder="Digite o DRT" value="<?= $drt ?? "" ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="3"
                                              class="form-control"><?= $obs ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="banco">Banco</label>
                                    <select name="banco" id="banco" class="form-control">
                                        <option value="">Selecione um banco...</option>
                                        <?php
                                        geraOpcao('bancos', $banco);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="agencia">Agência</label>
                                    <input type="text" id="agencia" name="agencia" class="form-control"
                                           value="<?= $agencia ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="conta">Conta</label>
                                    <input type="text" id="conta" name="conta" class="form-control"
                                           value="<?= $conta ?>">
                                </div>
                            </div>

                            <div class="box-footer">
                                <input type="hidden" name="idPf" value="<?= $idPf ?>">

                                <div class="row">
                                    <button type="submit" name="edita" class="btn btn-primary pull-right">Gravar</button>

                                    <a href="?perfil=formacao&p=pessoa_fisica&sp=lista">
                                        <button type="button" class="btn btn-default pull-left">Voltar</button>
                                    </a>
                                </div>
                        </form>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <form method="POST" action='?perfil=formacao&p=pessoa_fisica&sp=pf_demais_anexos'
                                  role="form">
                                <input type="hidden" name="idPf" value="<?= $idPf ?>">
                                <button type="submit" class="btn btn-info pull-right btn-block">Demais
                                    Anexos
                                </button>
                            </form>
                        </div>

                        <div class="form-group col-md-4">
                            <form method="post" action="<?= $linkResumo ?> " role="form" target="_blank">
                                <input type="hidden" name="idPf" value="<?= $idPf ?>">
                                <button type="submit" class="btn btn-info btn-block">Imprimir resumo
                                </button>
                            </form>
                        </div>

                        <div class="form-group col-md-4">
                            <form method="POST" action="<?= $link_facc ?>" role="form" target="_blank">
                                <input type="hidden" name="idPf" value="<?= $idPf ?>">
                                <button type="submit" class="btn btn-info pull-right btn-block">Clique aqui para
                                    gerar a FACC
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

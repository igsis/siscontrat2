<?php
date_default_timezone_set('America/Sao_Paulo');
$con = bancoMysqli();
$conn = bancoPDO();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";
$linkResumo = $http . "rlt_contrato_pf.php";
$link_facc = $http . "rlt_fac_pf.php";

if (isset($_POST['idPf']) || isset($_POST['idProponente'])) {
    $idPf = $_POST['idPf'] ?? $_POST['idProponente'];
}

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {
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
    $nit = $_POST['nit'] ?? NULL;
    $incricoes = $_POST['inscricaoPissInss'] ?? NULL;
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $banco = $_POST['banco'] ?? NULL;
    $agencia = $_POST['agencia'] ?? NULL;
    $conta = $_POST['conta'] ?? NULL;
    $data = date("y-m-d h:i:s");
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

if (isset($_POST['carregar'])) {
    $idPf = $_POST['idPf'];
}

if (isset($_POST["enviar"])) {
    $idPf = $_POST['idPessoa'];
    $tipoPessoa = $_POST['tipoPessoa'];

    $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1";

    $query_arquivos = mysqli_query($con, $sql_arquivos);

    while ($arq = mysqli_fetch_array($query_arquivos)) {
        $y = $arq['id'];
        $x = $arq['sigla'];
        $nome_arquivo = isset($_FILES['arquivo']['name'][$x]) ? $_FILES['arquivo']['name'][$x] : null;
        $f_size = isset($_FILES['arquivo']['size'][$x]) ? $_FILES['arquivo']['size'][$x] : null;

        if ($f_size > 5242880) {
            $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 05 MB.</strong>");

        } else {
            if ($nome_arquivo != "") {
                $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
                $new_name = date("YmdHis") . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                $hoje = date("Y-m-d H:i:s");
                $dir = '../uploadsdocs/'; //Diretório para uploads
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                if ($y == 59) {
                    $allowedExts = array(".png", ".PNG", ".JPG", ".jpg"); //Extensões permitidas
                }
                $ext = strtolower(substr($nome_arquivo, -4));

                if (in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {
                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPf', '$y', '$new_name', '$hoje', '1'); ";
                        $query = mysqli_query($con, $sql_insere_arquivo);

                        if ($query) {
                            $mensagem = mensagem("success", "Arquivo recebido com sucesso");
                            echo "<script>
                                swal('Clique nos arquivos após efetuar o upload e confira a exibição do documento!', '', 'warning');                             
                            </script>";
                            gravarLog($sql_insere_arquivo);
                        } else {
                            $mensagem = mensagem("danger", "Erro ao gravar no banco");
                        }
                    } else {
                        $mensagem = mensagem("danger", "Erro no upload");
                    }
                } else {
                    echo "<script>
                            swal(\"Erro no upload! Anexar documentos somente no formato PDF.\", \"\", \"error\");                             
                        </script>";
                }
            }
        }
    }
}

if (isset($_POST['apagar'])) {
    $idArquivo = $_POST['idArquivo'];
    $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
    if (mysqli_query($con, $sql_apagar_arquivo)) {
        $arq = recuperaDados("arquivos", $idArquivo, "id");
        $mensagem = mensagem("success", "Arquivo " . $arq['arquivo'] . "apagado com sucesso!");
        gravarLog($sql_apagar_arquivo);
    } else {
        $mensagem = mensagem("danger", "Erro ao apagar o arquivo. Tente novamente!");
    }
}

$_SESSION['idPf'] = $idPf;

$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$pf = recuperaDados("pessoa_fisicas", "id", $idPf);
$endereco = recuperaDados("pf_enderecos", "pessoa_fisica_id", $idPf);
$nits = recuperaDados("nits", "pessoa_fisica_id", $idPf);
$observacao = recuperaDados("pf_observacoes", "pessoa_fisica_id", $idPf);
$banco = recuperaDados("pf_bancos", "pessoa_fisica_id", $idPf);

$foto = "SELECT arquivo FROM arquivos WHERE lista_documento_id = 59 AND publicado = 1 AND origem_id = '$idPf'";
$foto = $con->query($foto)->fetch_assoc()['arquivo'];
if ($foto == null) {
    $foto = "avatar_default.png";
    $fotoImg = "../visual/images/$foto";
} else {
    $fotoImg = "../uploadsdocs/$foto";
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

                        <h3 class="box-title col-md-6">Pessoa física</h3>
                        <img src="<?= $fotoImg ?>" alt="<?= $foto ?>" class="img-circle img-responsive col-md-6"
                             style="width: 9%; height: 12%">

                    </div>
                    <div class="box-body">
                        <form action="?perfil=contrato&p=pf&sp=edita" method="post">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome"
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
                                if (empty($pf['cpf'])) {
                                    anexosNaPagina(62, $idPf, "modal-passaporte", "Passaporte");
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
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <?php
                                    if (!empty($pf['cpf'])){
                                    anexosNaPagina(2, $idPf, "modal-rg", "RG");
                                    ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php
                                    anexosNaPagina(3, $idPf, "modal-cpf", "CPF");
                                    ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php
                                    anexosNaPagina(31, $idPf, "modal-ccm", "FDC - CCM");
                                    }
                                    ?>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000"
                                           value="<?= $endereco['cep'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>

                                <div class="form-group col-md-6">
                                    <?php
                                    anexosNaPagina(59, $idPf, "modal-foto", "Foto 3x4");
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua"
                                           placeholder="Digite o endereço" maxlength="200" readonly
                                           value="<?= $endereco['logradouro'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control"
                                           placeholder="Digite o número" min="1" required value="<?= $endereco['numero'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento" value="<?= $endereco['complemento'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly
                                           value="<?= $endereco['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly
                                           value="<?= $endereco['cidade'] ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Digite o estado ex: (SP)" readonly
                                           value="<?= $endereco['uf'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <?php
                                    anexosNaPagina(4, $idPf, "modal-endereco", "Comprovante de endereço");
                                    ?>
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
                                           class="form-control"
                                           id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="celular">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
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
                                               class="form-control"
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>

                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control"
                                               id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="nit">NIT: </label>
                                    <input type="text" name="nit" class="form-control telefone" maxlength="45"
                                           placeholder="Digite o NIT" value="<?= $nits['nit'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <?php
                                    anexosNaPagina(25, $idPf, "modal-nit", "NIT");
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="3"
                                              class="form-control"><?= $observacao['observacao'] ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="banco">Banco</label>
                                    <select name="banco" id="banco" class="form-control" required>
                                        <?php
                                        geraOpcao('bancos', $banco['banco_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="agencia">Agência</label>
                                    <input type="text" id="agencia" name="agencia" class="form-control"
                                           value="<?= $banco['agencia'] ?>" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="conta">Conta</label>
                                    <input type="text" id="conta" name="conta" class="form-control"
                                           value="<?= $banco['conta'] ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <?php
                                    $sqlFACC = "SELECT * FROM arquivos WHERE lista_documento_id = 51 AND origem_id = '$idPf' AND publicado = 1";
                                    $queryFACC = mysqli_query($con, $sqlFACC);
                                    ?>

                                    <label>Gerar FACC</label><br>
                                    <a href="<?= $link_facc . "?id=" . $idPf ?>" target="_blank" type="button"
                                       class="btn btn-primary btn-block">Clique aqui para
                                        gerar a FACC
                                    </a>
                                </div>

                                <div class="form-group col-md-5">
                                    <label>&nbsp;</label><br>
                                    <p>A FACC deve ser impressa, datada e assinada nos campos indicados no
                                        documento. Logo após, deve-se digitaliza-la e então anexa-la ao sistema
                                        no campo correspondente.</p>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php
                                    anexosNaPagina(51, $idPf, "modal-facc", "FACC");
                                    ?>
                                </div>
                            </div>

                            <div class="box-footer">
                                <input type="hidden" name="idPf" value="<?= $idPf ?>">

                                <div class="row">
                                    <button type="submit" name="edita" class="btn btn-info pull-right">Gravar</button>

                                    <a href="?perfil=contrato">
                                        <button type="button" class="btn btn-default pull-left">Voltar</button>
                                    </a>

                                    <a href="<?= $linkResumo ?>" target="_blank">
                                        <button type="button" name="pdf" id="pdf" class="btn btn-primary center-block"
                                                style="align-items: center;">Imprimir resumo
                                        </button>
                                    </a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        modalUploadArquivoUnico("modal-rg", "?perfil=contrato&p=pf&sp=edita", "RG", "rg", $idPf, "1");
        modalUploadArquivoUnico("modal-foto", "?perfil=contrato&p=pf&sp=edita", "Foto 3x4", "foto", $idPf, "1");
        modalUploadArquivoUnico("modal-cpf", "?perfil=contrato&p=pf&sp=edita", "CPF", "cpf", $idPf, "1");
        modalUploadArquivoUnico("modal-ccm", "?perfil=contrato&p=pf&sp=edita", "FDC - CCM", "ccm", $idPf, "1");
        modalUploadArquivoUnico("modal-nit", "?perfil=contrato&p=pf&sp=edita", "NIT", "pis_pasep_", $idPf, "1");
        modalUploadArquivoUnico("modal-facc", "?perfil=contrato&p=pf&sp=edita", "FACC", "faq", $idPf, "1");
        modalUploadArquivoUnico("modal-endereco", "?perfil=contrato&p=pf&sp=edita", "Comprovante de endereço", "residencia", $idPf, "1");
        ?>

    </section>
</div>

<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body text-center">
                <p>Tem certeza que deseja excluir este arquivo?</p>
            </div>
            <div class="modal-footer">
                <form action="?perfil=contrato&p=pf&sp=edita" method="post">
                    <input type="hidden" name="idArquivo" id="idArquivo" value="">
                    <input type="hidden" name="idPf" id="idPf" value="<?= $idPf ?>">
                    <input type="hidden" name="apagar" id="apagar">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);
    })
</script>

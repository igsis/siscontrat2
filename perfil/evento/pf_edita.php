<?php
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
}

if (isset($_POST['cadastraLider'])) {
    $idPedido = $_POST['idPedido'];
    $idAtracao = $_POST['idAtracao'];

    $sqlDeleteLider = "DELETE FROM lideres WHERE atracao_id = '$idAtracao'";

    if (mysqli_query($con, $sqlDeleteLider)) {
        $sqLider = "INSERT INTO lideres (pedido_id, atracao_id, pessoa_fisica_id) 
                                VALUES ('$idPedido', '$idAtracao', '$idPf')";
        if (mysqli_query($con, $sqLider)) {
            $mensagem = mensagem("success", "Líder selecionado com sucesso!");
            echo "<script> swal('Lembre-se de conferir os dados', '', 'warning') </script>";
        }
    }
}



if (isset($_POST['cadastra']) || isset($_POST['edita']) || isset($_POST['cadastraComLider']) || isset($_POST['atualizaPf'])) {
    $nome = trim(addslashes($_POST['nome']));
    $nomeArtistico = trim(addslashes($_POST['nomeArtistico'])) ?? NULL;
    $rg = isset($_POST['rg']) ? trim($_POST['rg']) : NULL;
    $cpf = $_POST['cpf'] ?? NULL;
    $passaporte = $_POST['passaporte'] ?? NULL;
    $pis_nit = isset($_POST['pis_nit']) ? trim($_POST['pis_nit']) : NULL;
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
if (isset($_POST['cadastra']) || isset($_POST['cadastraComLider']) || isset($_POST['atualizaPf'])) {
    $mensagem = "";
    $sql = "INSERT INTO siscontrat.`pessoa_fisicas` (nome, nome_artistico, rg, passaporte, cpf, pis_nit, data_nascimento, nacionalidade_id, email, ultima_atualizacao) VALUES('$nome','$nomeArtistico','$rg','$passaporte','$cpf','$pis_nit','$dtNascimento','$nacionalidade','$email','$data')";
    if (mysqli_query($con, $sql)) {
        $idPf = recuperaUltimo("pessoa_fisicas");
// cadastrar o telefone de pf
        foreach ($telefones as $telefone) {
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

        $mensagem .= mensagem("success", "Cadastrado com sucesso!");

        if (isset($_POST['atualizaPf'])) {
            $idPedido = $_POST['idPedido'];
            $sqlTestaTroca = "SELECT * FROM pedidos WHERE id = $idPedido AND origem_tipo_id = 1";
            $queryTestaTroca = mysqli_query($con, $sqlTestaTroca);
            $row = mysqli_num_rows($queryTestaTroca);
            if ($row != 0) {
                $null = "NULL";
                $sqlTrocaProponente = "UPDATE pedidos SET
                    pessoa_tipo_id = '1',
                    pessoa_juridica_id = $null,
                    pessoa_fisica_id = '$idPf'
                    WHERE id = '$idPedido'";
                if ($con->query($sqlTrocaProponente)) {
                    $mensagem = mensagem("success", "Cadastrado com sucesso! Proponente inserido no Pedido");
                }
                $trocaPf = "<div class='form-group col-md-3 pull-right'>
                                <form method='POST' action='?perfil=evento&p=pedido_edita&label=proponente' role='form'>
                                    <input type='hidden' name='idPedido' value='$idPedido'>
                                    <input type='hidden' name='idProponente' value='$idPf'>
                                    <input type='hidden' name='tipoPessoa' value='1'>
                                    <button type='submit' name='carregar' class='btn btn-primary btn-block'> Ir ao pedido de contratação </button>
                                </form>
                            </div>";;
            }
        }
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
    pis_nit = '$pis_nit',
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

$idEvento = $_SESSION['idEvento'];
$evento = recuperaDados('eventos', 'id', $idEvento);
if ($evento['tipo_evento_id'] == 1) {

    $atracoesTipo = array('3', '7', '10', '11');

    $atracoes = recuperaDados('atracoes', 'evento_id', $idEvento);
    $atracaoId = $atracoes['id'];
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

if (isset($_POST['selecionar'])) {
    $tipoPessoa = 1;
    $idPessoa = $_POST['idPf'];
    $tipoEvento = $evento['tipo_evento_id'];
    $campo = "pessoa_fisica_id";

    $sqlFirst = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, $campo, valor_total, publicado) 
                                  VALUES (1, $idEvento, $tipoPessoa, $idPessoa, $valorTotal, 1)";
    if (mysqli_query($con, $sqlFirst)) {
        $_SESSION['idPedido'] = recuperaUltimo("pedidos");
        $idPedido = $_SESSION['idPedido'];
        $sqlContratado = "INSERT INTO contratos (pedido_id) VALUES ('$idPedido')";
        if (mysqli_query($con, $sqlContratado)) {
            $mensagem = mensagem("success", "Pedido Criado com sucesso.");
        }
    } else {
        echo $sqlFirst;
    }
}

if (isset($_POST['cadastra'])) {
    $tipoPessoa = 1;
    $tipoEvento = $evento['tipo_evento_id'];
    $campo = "pessoa_fisica_id";

    $sqlFirst = "INSERT INTO pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, $campo, valor_total, publicado) 
                                  VALUES (1, $idEvento, $tipoPessoa, $idPf, $valorTotal, 1)";
    if (mysqli_query($con, $sqlFirst)) {
        $_SESSION['idPedido'] = recuperaUltimo("pedidos");
        $idPedido = $_SESSION['idPedido'];
        $sqlContratado = "INSERT INTO contratos (pedido_id) VALUES ('$idPedido')";
        if (mysqli_query($con, $sqlContratado)) {
            $mensagem = mensagem("success", "Proponente cadastrado. Pedido Criado com sucesso.");
        }
    } else {
        echo $sqlFirst;
    }
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
            $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 05
    MB.</strong>");

        } else {
            if ($nome_arquivo != "") {
                $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
                $new_name = date("YmdHis", strtotime("-3 hours")) . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                $hoje = date("Y-m-d H:i:s", strtotime("-3 hours"));
                $dir = '../uploadsdocs/'; //Diretório para uploads
                $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                $ext = strtolower(substr($nome_arquivo, -4));

                if (in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                {
                    if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                        $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPf', '$y', '$new_name', '$hoje', '1'); ";
                        $query = mysqli_query($con, $sql_insere_arquivo);

                        if ($query) {
                            $mensagem = mensagem("success", "Arquivo recebido com sucesso");
                            echo "
    <script>
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
                    echo "
    <script>
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


$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$pf = recuperaDados("pessoa_fisicas", "id", $idPf);
$endereco = recuperaDados("pf_enderecos", "pessoa_fisica_id", $idPf);
$drts = recuperaDados("drts", "pessoa_fisica_id", $idPf);
$nits = recuperaDados("nits", "pessoa_fisica_id", $idPf);
$observacao = recuperaDados("pf_observacoes", "pessoa_fisica_id", $idPf);
$banco = recuperaDados("pf_bancos", "pessoa_fisica_id", $idPf);

if ($evento['tipo_evento_id'] == 1){
$atracao = $con->query("SELECT valor_individual FROM atracoes WHERE evento_id = '$idEvento'")->fetch_array();
}else{
    $atracao = null;
}

include "includes/menu_interno.php";
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
        <h2 class="page-header">Evento</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="row" align="center">
                        <?= $mensagem ?? NULL; ?>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title">Edição de pessoa física</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pf_edita" method="post">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome"
                                           pattern="[a-zA-ZàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇáéíóúýÁÉÍÓÚÝ ]{1,70}"
                                           title="Apenas letras"
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
                                    <div class="form-group col-md-12">
                                        <label for="passaporte">Passaporte:</label>
                                        <input type="text" class="form-control" name="passaporte" maxlength="70"
                                               value="<?= $pf['passaporte'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <?php
                                        anexosNaPagina(1, $idPf, "modal-passaporte", "Passaporte");
                                        ?>
                                    </div>
                                <?php } else {
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
                                        <label for="pis_nit">PIS / NIT: *</label>
                                        <input type="text" name="pis_nit" id="pis_nit" class="form-control"
                                               placeholder="Digite PIS ou NIT" maxlength="11" required value="<?= $pf['pis_nit'] ?>">
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
                                           value="<?= $endereco['cep'] ?>">
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
                                           value="<?= $endereco['logradouro'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <i>(se não houver, marcar 0)</i>
                                    <input type="number" name="numero" class="form-control" min="0"
                                           placeholder="(se não houver número marcar 0)" required
                                           value="<?= $endereco['numero'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento"
                                           value="<?= $endereco['complemento'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly
                                           value="<?= $endereco['bairro'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly
                                           value="<?= $endereco['cidade'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Digite o estado ex: (SP)" readonly
                                           value="<?= $endereco['uf'] ?>">
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
                                           class="form-control" pattern=".{14,15}" title="14 a 15 caracteres"
                                           data-mask="(00) 00000-0000"
                                           id="telefone" name="telefone[<?= $arrayTelefones[0]['id'] ?>]"
                                           value="<?= $arrayTelefones[0]['telefone']; ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="celular">Telefone #2 </label>
                                    <?php
                                    if (isset($arrayTelefones[1])) {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" pattern=".{14,15}" title="14 a 15 caracteres"
                                               data-mask="(00) 00000-0000"
                                               id="telefone1" name="telefone[<?= $arrayTelefones[1]['id'] ?>]"
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" pattern=".{14,15}" title="14 a 15 caracteres"
                                               data-mask="(00) 00000-0000"
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
                                               class="form-control" pattern=".{14,15}" title="14 a 15 caracteres"
                                               data-mask="(00) 00000-0000"
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>

                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               data-mask="(00) 00000-0000"
                                               class="form-control" pattern=".{14,15}" title="14 a 15 caracteres"
                                               id="telefone2" name="telefone2">

                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                if ($mostraDRT) {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="drt">DRT: </label>
                                        <input type="text" name="drt" class="form-control telefone" maxlength="15"
                                               placeholder="Digite o DRT" value="<?= $drts['drt'] ?? "" ?>">
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-12">
                                    <label for="nit">NIT: </label>
                                    <input type="text" name="nit" class="form-control telefone" maxlength="45"
                                           placeholder="Digite o NIT" value="<?= $nits['nit'] ?? "" ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="3"
                                              class="form-control"><?= $observacao['observacao'] ?? "" ?></textarea>
                                </div>
                            </div>
                            <?php
                            if ($atracao != null) {
                                if ($atracao['valor_individual'] > 0) {
                                    ?>
                                    <hr/>
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
                                            <input type="text" name="agencia" class="form-control"
                                                   placeholder="Digite a Agência" maxlength="12"
                                                   value="<?= $banco['agencia'] ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="conta">Conta:</label>
                                            <input type="text" name="conta" class="form-control"
                                                   placeholder="Digite a Conta" maxlength="12"
                                                   value="<?= $banco['conta'] ?>">
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
                                            anexosNaPagina(42, $idPf, "modal-facc", "FACC");
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }else{
                                ?>
                                <hr/>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="banco">Banco:</label>
                                        <select id="banco" name="banco" class="form-control">
                                            <option value="">Selecione um banco...</option>
                                            <?php
                                            if (isset($banco)){
                                                geraOpcao("bancos", $banco['banco_id']);
                                            }else{
                                                geraOpcao("bancos");
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="agencia">Agência:</label>
                                        <input type="text" name="agencia" class="form-control"
                                               placeholder="Digite a Agência" maxlength="12"
                                               value="<?= isset($banco)? $banco['agencia'] : '' ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="conta">Conta:</label>
                                        <input type="text" name="conta" class="form-control"
                                               placeholder="Digite a Conta" maxlength="12"
                                               value="<?= isset($banco) ? $banco['conta'] : '' ?>">
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
                                        anexosNaPagina(42, $idPf, "modal-facc", "FACC");
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="box-footer">
                                <input type="hidden" name="idPf" value="<?= $idPf ?>">
                                <button type="submit" name="edita" class="btn btn-info pull-right">Alterar</button>

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
<div class="row">
    <div class="col-md-12">
        <div class="box box-default">
            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-3">
                        <form method="POST" action="?perfil=evento&p=pf_demais_anexos" role="form">
                            <button type="submit" name="idPf" value="<?= $pf['id'] ?>"
                                    class="btn btn-warning btn-block">Demais Anexos
                            </button>
                        </form>
                    </div>
                    <?php if (isset($trocaPf)) {

                        echo $trocaPf;

                    } else { ?>

                        <div class="form-group col-md-3 pull-right">
                            <?php
                            $sqlPedidos = "SELECT * FROM pedidos WHERE publicado = 1 AND origem_tipo_id = 1 AND origem_id = '$idEvento'";
                            $queryPedidos = mysqli_query($con, $sqlPedidos);
                            $pedidos = mysqli_fetch_array($queryPedidos);

                            if (($pedidos['pessoa_tipo_id'] == 1) && ($pedidos['pessoa_fisica_id'] == $idPf)) {

                                ?>
                                <a href="?perfil=evento&p=pedido_proponente" class="btn btn-info btn-block">
                                    Ir ao pedido de contratação
                                </a>

                                <?php
                            } else {
                                ?>
                                <form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
                                    <input type="hidden" name="pessoa_tipo_id" value="1">
                                    <input type="hidden" name="pessoa_id" value="<?= $pf['id'] ?>">
                                    <input type="hidden" name="valor" value="<?= $valorTotal ?>">
                                    <input type="hidden" name="tipoEvento" value="<?= $evento['tipo_evento_id'] ?>">
                                    <button type="submit" name="cadastra" class="btn btn-info btn-block">Ir ao pedido de
                                        contratação
                                    </button>
                                </form>
                                <?php
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- /. box-body -->
        </div>
    </div>
</div>

<?php
modalUploadArquivoUnico("modal-rg", "?perfil=evento&p=pf_edita", "RG", "rg", $idPf, "1");
modalUploadArquivoUnico("modal-cpf", "?perfil=evento&p=pf_edita", "CPF", "cpf", $idPf, "1");
modalUploadArquivoUnico("modal-ccm", "?perfil=evento&p=pf_edita", "FDC - CCM", "ccm", $idPf, "1");
modalUploadArquivoUnico("modal-nit", "?perfil=evento&p=pf_edita", "NIT", "pis_pasep_", $idPf, "1");
modalUploadArquivoUnico("modal-facc", "?perfil=evento&p=pf_edita", "FACC", "faq", $idPf, "1");
modalUploadArquivoUnico("modal-drt", "?perfil=evento&p=pf_edita", "DRT", "drt", $idPf, "1");
modalUploadArquivoUnico("modal-passaporte", "?perfil=evento&p=pf_edita", "Passaporte", "rg", $idPf, "1");
modalUploadArquivoUnico("modal-endereco", "?perfil=evento&p=pf_edita", "Comprovante de endereço", "residencia", $idPf, "1");
?>

</section>
<!-- /.content -->
</div>

<!--.modal-->
<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body text-center">
                <p>Tem certeza que deseja excluir este arquivo?</p>
            </div>
            <div class="modal-footer">
                <form action="?perfil=evento&p=pf_edita" method="post">
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
<!--  Fim Modal de Upload de arquivo  -->

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);

    })
</script>





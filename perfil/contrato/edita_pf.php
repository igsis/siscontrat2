<?php
$con = bancoMysqli();
$conn = bancoPDO();

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";
$link_facc = $http . "rlt_fac_pf.php";
$tipoPessoa = 1;

if (isset($_POST['selecionar']) || isset($_POST['idPf'])) {
    $idPf = $_POST['idPf'];
    $editaOnly = "<input type='hidden' name='editOnly' value= '1'>";
}



if (isset($_POST['cadastra']) || isset($_POST['edita']) || isset($_POST['cadastraComLider'])) {
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
    $data = date("y-m-d h:i:s");
    $idPedido = $_POST['idPedido'];
}

if (isset($_POST['cadastra']) || isset($_POST['cadastraComLider'])) {
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

$sqlOficina = "SELECT a.valor_individual, aa.acao_id FROM eventos e
                INNER JOIN atracoes a on e.id = a.evento_id
                INNER JOIN acao_atracao aa on a.id = aa.atracao_id
                WHERE e.id = '$idEvento' and a.publicado = 1";
$queryOficina = mysqli_query($con, $sqlOficina);
//$atracoes = mysqli_fetch_array($queryAtracao);

while ($atracoes = mysqli_fetch_array($queryOficina)) {
    $valores [] = $atracoes['valor_individual'];
    if ($atracoes['acao_id'] == 8) {
        $oficina = 1;
    }
}

if (isset($valores) && $valores > 0) {
    $valorTotal = 0;
    foreach ($valores as $valor) {
        $valorTotal += $valor;
    }
} else {
    $valorTotal = 0;
}

$sqlTelefones = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$arrayTelefones = $conn->query($sqlTelefones)->fetchAll();

$pf = recuperaDados("pessoa_fisicas", "id", $idPf);
$endereco = recuperaDados("pf_enderecos", "pessoa_fisica_id", $idPf);
$drts = recuperaDados("drts", "pessoa_fisica_id", $idPf);
$nits = recuperaDados("nits", "pessoa_fisica_id", $idPf);
$observacao = recuperaDados("pf_observacoes", "pessoa_fisica_id", $idPf);
$banco = recuperaDados("pf_bancos", "pessoa_fisica_id", $idPf);

$sql = "SELECT valor_individual FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
$atracao = mysqli_query($con, $sql);


$sqlPedidos = "SELECT * FROM pedidos WHERE publicado = 1 AND origem_tipo_id = 1 AND origem_id = '$idEvento'";
$queryPedidos = mysqli_query($con, $sqlPedidos);
$pedidos = mysqli_fetch_array($queryPedidos);


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
        <h2 class="page-header">Contrato</h2>
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
                        <form action="?perfil=contrato&p=edita_pf" method="post">
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
                                    echo "<div class='form-group col-md-6'>";
                                    anexosNaPagina(62, $idPf, "modal-passaporte", "Passaporte");
                                    $md = 3;
                                    $row = "</div>";
                                } else {
                                    ?>
                                    <div class='form-group col-md-4'>
                                        <label for="rg">RG: *</label>
                                        <input type="text" class="form-control" name="rg" placeholder="Digite o RG"
                                               maxlength="20" required value="<?= $pf['rg'] ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="cpf">CPF: </label>
                                        <input type="text" id="cpf" name="cpf" class="form-control"
                                               value="<?= $pf['cpf'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="ccm">CCM:</label>
                                        <input type="text" name="ccm" class="form-control" placeholder="Digite o CCM"
                                               maxlength="11" value="<?= $pf['ccm'] ?>">
                                    </div>
                                    <?php
                                    $md = 6;
                                    $row = "";
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-<?= $md ?>">
                                    <label for="dtNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" id="dtNascimento" name="dtNascimento"
                                           onkeyup="barraData(this);" value="<?= $pf['data_nascimento'] ?>"/>
                                </div>
                                <div class="form-group col-md-<?= $md ?>">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" id="nacionalidade" name="nacionalidade">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("nacionalidades", $pf['nacionalidade_id']);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?= $row ?>
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
                                           required
                                           value="<?= $endereco['numero'] ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento: </label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento" value="<?= $endereco['complemento'] ?>">
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
                                               value="<?= $arrayTelefones[1]['telefone']; ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
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
                                               id="telefone2" name="telefone[<?= $arrayTelefones[2]['id'] ?>]"
                                               value="<?= $arrayTelefones[2]['telefone']; ?>">

                                        <?php
                                    } else {
                                        ?>

                                        <input type="text" onkeyup="mascara( this, mtel );" maxlength="15"
                                               class="form-control" data-mask="(00) 00000-0000"
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
                                    <div class="form-group col-md-3">
                                        <label for="drt">DRT: </label>
                                        <input type="text" name="drt" class="form-control telefone" maxlength="15"
                                               placeholder="Digite o DRT" value="<?= $drts['drt'] ?? NULL ?>">
                                    </div>

                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-6">
                                    <label for="nit">NIT: </label>
                                    <input type="text" name="nit" class="form-control telefone" maxlength="45"
                                           placeholder="Digite o NIT" value="<?= $nits['nit'] ?? NULL ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea name="observacao" rows="3"
                                              class="form-control"><?= $observacao['observacao'] ?? NULL ?></textarea>
                                </div>
                            </div>
                            <hr/>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="banco">Banco:</label>
                                    <select id="banco" name="banco" class="form-control" required>
                                        <option value="">Selecione um banco...</option>
                                        <?php
                                        geraOpcao("bancos", $banco['banco_id']);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="agencia">Agência: *</label>
                                    <input type="text" name="agencia" class="form-control"
                                           placeholder="Digite a Agência" maxlength="12" required
                                           value="<?= $banco['agencia'] ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="conta">Conta: *</label>
                                    <input type="text" name="conta" class="form-control"
                                           placeholder="Digite a Conta" maxlength="12" required
                                           value="<?= $banco['conta'] ?>">
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <input type="hidden" name="idPf" value="<?= $idPf ?>">
                            <input type="hidden" name="idPedido" value="<?= $pedidos['id'] ?>">
                            <button type="submit" name="edita" class="btn btn-info pull-left" style="margin:  0 5px;">Salvar</button>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-3 pull-right">
                                <form method="POST" action="?perfil=contrato&p=demais_anexos_pf"
                                      role="form">
                                    <button type="submit" name="idPf" value="<?= $pf['id'] ?>"
                                            class="btn btn-info btn-block">Demais Anexos
                                    </button>
                                </form>
                            </div>
                            <div class="form-group col-md-3">
                                <?php
                                $sqlPedidos = "SELECT * FROM pedidos WHERE publicado = 1 AND origem_tipo_id = 1 AND origem_id = '$idEvento'";
                                $queryPedidos = mysqli_query($con, $sqlPedidos);
                                $pedidos = mysqli_fetch_array($queryPedidos);
                                ?>
                                <form method="POST" action="?perfil=contrato&p=resumo" role="form">
                                    <input type="hidden" name="idPedido" value="<?= $pedidos['id']; ?>">
                                    <input type="hidden" name="idPf" value="<?= $pf['id'] ?>">
                                    <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                                    <?= $editaOnly ?? NULL ?>
                                    <button type="submit" name="Voltar" class="btn btn-right pull-right">
                                        Voltar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /. box-body -->
                </div>
            </div>

        </div>

    </section>
    <!-- /.content -->
</div>




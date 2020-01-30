<?php
include "includes/menu_principal.php";

$idUser = $_SESSION['idUser'];
$idCapac = $_POST['idCapac'];
unset($_POST['idCapac']);

$eventoImportado = false;
$dataAtual = date("Y-m-d H:i:s");

$conSis = bancoMysqli();
$conCpc = bancoCapac();

if (isset($_POST['importarEventoCpc'])) {
    $nomeEvento = addslashes($_POST['nomeEvento']);
    $relacao_juridica_id = $_POST['relacaoJuridica'];
    $projeto_especial_id = $_POST['projetoEspecial'];
    $sinopse = addslashes($_POST['sinopse']);
    $tipo = $_POST['tipo'];
    $nomeResponsavel = trim($_POST['nomeResponsavel']);
    $telResponsavel = $_POST['telResponsavel'];
    $fiscal_id = $_POST['fiscal'];
    $suplente_id = $_POST['suplente'];
    $usuario = $_SESSION['idUser'];
    $contratacao = $_POST['contratacao'];
    $eventoStatus = "1";
    $fomento = $_POST['fomento'];
    $tipoLugar = $_POST['tipoLugar'];
    $idFomento = $_POST['tipoFomento'] ?? null;

    /** INSERE EVENTO NO SISCONTRAT */
    $sqlInsertSis = "INSERT INTO siscontrat.eventos (nome_evento,
                                 relacao_juridica_id,
                                 projeto_especial_id,
                                 tipo_evento_id,
                                 sinopse,
                                 nome_responsavel,
                                 tel_responsavel,
                                 fiscal_id,
                                 suplente_id,
                                 usuario_id,
                                 contratacao,
                                 evento_status_id,
                                 fomento,
                                 espaco_publico)
                          VALUES ('$nomeEvento',
                                  '$relacao_juridica_id',
                                  '$projeto_especial_id',
                                  '$tipo',
                                  '$sinopse',
                                  '$nomeResponsavel',
                                  '$telResponsavel',
                                  '$fiscal_id',
                                  '$suplente_id',
                                  '$usuario',
                                  '$contratacao',
                                  '$eventoStatus',
                                  '$fomento',
                                  '$tipoLugar')";

    if(mysqli_query($conSis, $sqlInsertSis)) {
        $idEvento = $conSis->insert_id;

        /** VERIFICA SE O EVENTO É FOMENTO */
        if ($idFomento != null) {
            $sqlFomentoCpc = "INSERT INTO siscontrat.evento_fomento  (evento_id, fomento_id) VALUES ('$idEvento', '$idFomento')";
            mysqli_query($conSis, $sqlFomentoCpc);
        }

        /** INSERE OS PUBLICOS SELECIONADOS */
        if (isset($_POST['publico'])) {
            atualizaDadosRelacionamento('siscontrat.evento_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
        }

        /** INSERE NA TABELA "evento_importados" PARA REGISTRAR QUE O EVENTO X VEIO DO EVENTO DO CAPAC Y */
        $sqlInsertImportado = "INSERT INTO siscontrat.evento_importados (evento_id, evento_capac_id) VALUES ('$idEvento', '$idCapac')";
        if (mysqli_query($conSis, $sqlInsertImportado)) {
            $eventoImportado = true;
            $mensagem = mensagem('success', 'Evento importado.');
        }
    } else {
        echo "<script>window.location.href = 'index.php?perfil=evento&p=importar_evento_capac&error=1&idCpc=".$idCapac."</script>";
    }
}

/** NESTA PARTE, INICIA A COMPARAÇÃO E INSERÇÃO DE PROPONENTE */
$sqlConsultaPedido = "SELECT * FROM capac_new.pedidos WHERE origem_tipo_id = 1 AND origem_id = '$idCapac'";
$pedidoCpc = $conCpc->query($sqlConsultaPedido)->fetch_assoc();

/** RECUPERA OS DADOS DO PROPONENTE SE PF */
if ($pedidoCpc['pessoa_tipo_id'] == 1) {
    $idProponenteCpc = $pedidoCpc['pessoa_fisica_id'];
    $sqlConsultaProponente = "SELECT * FROM capac_new.pessoa_fisicas WHERE id = '$idProponenteCpc'";

    $proponenteCpc = $conCpc->query($sqlConsultaProponente)->fetch_assoc();

    $sqlComparaProponente = "SELECT * FROM siscontrat.pessoa_fisicas WHERE cpf = '{$proponenteCpc['cpf']}'";

}
/** RECUPERA OS DADOS DO PROPONENTE SE PJ */
elseif ($pedidoCpc['pessoa_tipo_id'] == 2) {
    $idProponenteCpc = $pedidoCpc['pessoa_juridica_id'];
    $sqlConsultaProponente = "SELECT * FROM capac_new.pessoa_juridicas WHERE id = '$idProponenteCpc'";

    $proponenteCpc = $conCpc->query($sqlConsultaProponente)->fetch_assoc();

    $sqlComparaProponente = "SELECT * FROM siscontrat.pessoa_juridicas WHERE cnpj = '{$proponenteCpc['cnpj']}'";
}

/** EXECUTA CONSULTA PARA TESTAR SE O PROPONENTE JÁ EXISTE NO SISCONTRAT */
$queryProponenteSis = $conSis->query($sqlComparaProponente);

/** CASO EXISTA O MESMO CNPJ CADASTRADO, PUXA OS DADOS DESTE PARA COMPARAÇÃO COM OS DADOS DO CAPAC */
if ($queryProponenteSis->num_rows > 0) {
    $existeProponente = true;
    $proponenteSis = $queryProponenteSis->fetch_assoc();

    if ($pedidoCpc['pessoa_tipo_id'] == 1) {
        $documento = $proponenteCpc['cpf'];
    } else {
        $documento = $proponenteCpc['cnpj'];
    }

    $proponentes = comparaProponenteCapac($pedidoCpc['pessoa_tipo_id'], $documento);
}
/** CASO NÃO EXISTA O MESMO PROPONENTE, JÁ ENTRA NA ÁREA DE INSERÇÃO */
else {
    $existeProponente = false;
    $_POST['importarProponenteCpc'] = true;
}

/** EXECUTA A IMPORTAÇÃO DO PROPONENTE */
if (isset($_POST['importarProponenteCpc'])) {
    if (isset($_POST['idEvento'])) {
        $idEvento = $_POST['idEvento'];
    }
    /** PESSOA FISICA */
    if ($pedidoCpc['pessoa_tipo_id'] == 1) {
        $idProponentePj = "null";

        /** CASO NÃO EXISTA O CPF CADASTRADO NO SISCONTRAT, IMPORTA OS DADOS SEM QUESTIONAMENTO */
        if (!$existeProponente) {
            $sqlInsertProponente = "INSERT INTO siscontrat.pessoa_fisicas (nome, nome_artistico, rg, passaporte, cpf, ccm, data_nascimento, nacionalidade_id, email, ultima_atualizacao) 
                                        SELECT nome, nome_artistico, rg, passaporte, cpf, ccm, data_nascimento, nacionalidade_id, email, '$dataAtual' FROM capac_new.pessoa_fisicas WHERE id = '$idProponenteCpc'";

            if (mysqli_query($conSis, $sqlInsertProponente)) {
                $idProponentePf = "'".$conSis->insert_id."'";

                $sqlInsertBanco = "INSERT INTO siscontrat.pf_bancos (pessoa_fisica_id, banco_id, agencia, conta)
                               SELECT $idProponentePf, banco_id, agencia, conta FROM capac_new.pf_bancos WHERE pessoa_fisica_id = '$idProponenteCpc'";
                $conSis->query($sqlInsertBanco);

                $sqlInsertEndereco = "INSERT INTO siscontrat.pf_enderecos (pessoa_fisica_id, logradouro, numero, complemento, bairro, cidade, uf, cep)
                                  SELECT $idProponentePf, logradouro, numero, complemento, bairro, cidade, uf, cep FROM capac_new.pf_enderecos WHERE pessoa_fisica_id = '$idProponenteCpc'";
                $conSis->query($sqlInsertEndereco);

                $telefones = $conCpc->query("SELECT telefone FROM capac_new.pf_telefones WHERE pessoa_fisica_id = '$idProponenteCpc'")->fetch_all(MYSQLI_ASSOC);
                foreach ($telefones as $telefone) {
                    $sqlInsertTelefone = "INSERT INTO siscontrat.pf_telefones (pessoa_fisica_id, telefone) VALUES ($idProponentePf, '{$telefone['telefone']}')";
                    $conSis->query($sqlInsertTelefone);
                }
                $mensagem = mensagem('success', 'Proponente importado.');
            }

        }
        /** CASO EXISTA CPF CADASTRADO, OS DADOS VEM DO FORMULARIO VIA INCLUDE */
        else {
            $idProponenteSis = $_POST['idProponenteSis'];
            unset($_POST['idProponenteSis']);
            unset($_POST['importarProponenteCpc']);
            foreach ($_POST as $key => $post) {
                $dadosUpdate[] = $key." = '".$post."'";
            }
            $dadosUpdate[] = "ultima_atualizacao = '$dataAtual'";

            $sqlUpdateProponente = "UPDATE siscontrat.pessoa_fisicas SET ".implode(", ", $dadosUpdate)." WHERE id = '$idProponenteSis'";
            if (mysqli_query($conSis, $sqlUpdateProponente)) {
                $idProponentePf = "'" . $idProponenteSis . "'";
                $mensagem = mensagem('success', 'Proponente Importado. Dados atualizados com sucesso!');
            }
        }
    }
    /** PESSOA JURIDICA */
    elseif ($pedidoCpc['pessoa_tipo_id'] == 2) {
        $idProponentePf = "null";

        /** CASO NÃO EXISTA O CNPJ CADASTRADO NO SISCONTRAT, IMPORTA OS DADOS SEM QUESTIONAMENTO */
        if (!$existeProponente) {
            $sqlInsertRepresentante1 = "INSERT INTO siscontrat.representante_legais (nome, rg, cpf)
                                        SELECT nome, rg, cpf FROM capac_new.representante_legais WHERE id = '{$proponenteCpc['representante_legal1_id']}'";
            if (mysqli_query($conSis, $sqlInsertRepresentante1)) {
                $idRepresentante1 = $conSis->insert_id;

                if ($proponenteCpc['representante_legal2_id'] != NULL) {
                    $sqlInsertRepresentante2 = "INSERT INTO siscontrat.representante_legais (nome, rg, cpf)
                                                SELECT nome, rg, cpf FROM capac_new.representante_legais WHERE id = '{$proponenteCpc['representante_legal2_id']}'";
                    mysqli_query($conSis, $sqlInsertRepresentante2);
                    $idRepresentante2 = "'".$conSis->insert_id."'";
                } else {
                    $idRepresentante2 = "NULL";
                }

                $sqlInsertProponente = "INSERT INTO siscontrat.pessoa_juridicas (razao_social, cnpj, ccm, email, representante_legal1_id, representante_legal2_id, ultima_atualizacao)
                                        SELECT razao_social, cnpj, ccm, email, '$idRepresentante1', $idRepresentante2, '$dataAtual' FROM capac_new.pessoa_juridicas WHERE id = '$idProponenteCpc'";

                if (mysqli_query($conSis, $sqlInsertProponente)) {
                    $idProponentePj = "'".$conSis->insert_id."'";

                    $sqlInsertBanco = "INSERT INTO siscontrat.pj_bancos (pessoa_juridica_id, banco_id, agencia, conta)
                               SELECT $idProponentePj, banco_id, agencia, conta FROM capac_new.pj_bancos WHERE pessoa_juridica_id = '$idProponenteCpc'";
                    $conSis->query($sqlInsertBanco);

                    $sqlInsertEndereco = "INSERT INTO siscontrat.pj_enderecos (pessoa_juridica_id, logradouro, numero, complemento, bairro, cidade, uf, cep)
                                  SELECT $idProponentePj, logradouro, numero, complemento, bairro, cidade, uf, cep FROM capac_new.pj_enderecos WHERE pessoa_juridica_id = '$idProponenteCpc'";
                    $conSis->query($sqlInsertEndereco);

                    $telefones = $conCpc->query("SELECT telefone FROM capac_new.pj_telefones WHERE pessoa_juridica_id = '$idProponenteCpc'")->fetch_all(MYSQLI_ASSOC);
                    foreach ($telefones as $telefone) {
                        $sqlInsertTelefone = "INSERT INTO siscontrat.pj_telefones (pessoa_juridica_id, telefone) VALUES ($idProponentePj, '{$telefone['telefone']}')";
                        $conSis->query($sqlInsertTelefone);
                    }
                    $mensagem .= mensagem('success', 'Proponente importado.');
                }
            }
        }
        /** CASO EXISTA CNPJ CADASTRADO, OS DADOS VEM DO FORMULARIO VIA INCLUDE */
        else {
            $idProponenteSis = $_POST['idProponenteSis'];
            unset($_POST['idProponenteSis']);
            unset($_POST['importarProponenteCpc']);
            foreach ($_POST as $key => $post) {
                $dadosUpdate[] = $key." = '".$post."'";
            }
            $dadosUpdate[] = "ultima_atualizacao = '$dataAtual'";

            $sqlUpdateProponente = "UPDATE siscontrat.pessoa_juridicas SET ".implode(", ", $dadosUpdate)." WHERE id = '$idProponenteSis'";
            if (mysqli_query($conSis, $sqlUpdateProponente)) {
                $idProponentePj = "'" . $idProponenteSis . "'";
                $mensagem = mensagem('success', 'Proponente Importado. Dados atualizados com sucesso!');
            }
        }
    }

    if ($eventoImportado) {
        /** INSERINDO PEDIDO */
        $sqlInsertPedido = "INSERT INTO siscontrat.pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, pessoa_juridica_id, pessoa_fisica_id) VALUES 
                        (1, '$idEvento', {$pedidoCpc['pessoa_tipo_id']}, $idProponentePj, $idProponentePf)";
        $conSis->query($sqlInsertPedido);
        $idPedido = $conSis->insert_id;

        /** IMPORTANDO AS ATRAÇÕES */
        $sqlAtracoesCpc = "SELECT * FROM capac_new.atracoes WHERE evento_id = '$idCapac' AND publicado = 1";
        $atracoes = $conCpc->query($sqlAtracoesCpc)->fetch_all(MYSQLI_ASSOC);

        foreach ($atracoes as $atracao) {
            $sqlAcoesCpc = "SELECT acao_id FROM capac_new.acao_atracao WHERE atracao_id = {$atracao['id']}";
            $acoes = $conCpc->query($sqlAcoesCpc)->fetch_all(MYSQLI_ASSOC);

            /** INSERINDO PRODUTOR */
            $sqlInsertProdutor = "INSERT INTO siscontrat.produtores (nome, email, telefone1, telefone2, observacao)
                              SELECT nome, email, telefone1, telefone2, observacao FROM capac_new.produtores WHERE id = {$atracao['produtor_id']}";
            if(mysqli_query($conSis, $sqlInsertProdutor)) {
                $idProdutor = $conSis->insert_id;
                $sqlInsertAtracao = "INSERT INTO siscontrat.atracoes (evento_id, nome_atracao, ficha_tecnica, integrantes, classificacao_indicativa_id, release_comunicacao, links, quantidade_apresentacao, valor_individual, produtor_id)
                                 SELECT '$idEvento', cpca.nome_atracao, cpca.ficha_tecnica, cpca.integrantes, cpca.classificacao_indicativa_id, cpca.release_comunicacao, cpca.links, cpca.quantidade_apresentacao, cpca.valor_individual, '$idProdutor' FROM capac_new.atracoes AS cpca WHERE id = {$atracao['id']}";

                if(mysqli_query($conSis, $sqlInsertAtracao)) {
                    $idAtracao = $conSis->insert_id;
                    foreach ($acoes as $acao) {
                        $sqlInsertAcao = "INSERT INTO siscontrat.acao_atracao (acao_id, atracao_id) VALUES ('{$acao['acao_id']}', '$idAtracao')";
                        $conSis->query($sqlInsertAcao);
                    }
                }
            }

            /** CASO PROPONENTE PJ, TESTA A INSERÇÃO DOS LÍDERES */
            if ($pedidoCpc['pessoa_tipo_id'] == 2) {
                $lider = $conCpc->query("SELECT pf.* FROM capac_new.pessoa_fisicas AS pf
                                INNER JOIN capac_new.lideres AS l ON pf.id = l.pessoa_fisica_id
                                WHERE l.atracao_id = '{$atracao['id']}'")->fetch_assoc();

                $queryConsultaLider = $conSis->query("SELECT * FROM siscontrat.pessoa_fisicas WHERE cpf = '{$lider['cpf']}'");
                if ($queryConsultaLider->num_rows > 0) {
                    echo "PF já Existe";
                    $idLider = "";
                } else {
                    $sqlInsertPfLider = "INSERT INTO siscontrat.pessoa_fisicas (nome, nome_artistico, rg, passaporte, cpf, ccm, data_nascimento, nacionalidade_id, email, ultima_atualizacao)
                                       SELECT nome, nome_artistico, rg, passaporte, cpf, ccm, data_nascimento, nacionalidade_id, email, '$dataAtual' FROM capac_new.pessoa_fisicas WHERE id = '{$lider['id']}'";
                    $conSis->query($sqlInsertPfLider);
                    $idLider = $conSis->insert_id;

                    $telefones = $conCpc->query("SELECT telefone FROM capac_new.pf_telefones WHERE pessoa_fisica_id = '{$lider['id']}'")->fetch_all(MYSQLI_ASSOC);
                    foreach ($telefones as $telefone) {
                        $sqlInsertTelefone = "INSERT INTO siscontrat.pf_telefones (pessoa_fisica_id, telefone) VALUES ($idLider, '{$telefone['telefone']}')";
                        $conSis->query($sqlInsertTelefone);
                    }
                }

                $sqlInsertLider = "INSERT INTO siscontrat.lideres (pedido_id, atracao_id, pessoa_fisica_id)
                                   VALUES ('$idPedido', '$idAtracao', '$idLider')";
                $conSis->query($sqlInsertLider);
            }
        }
    }
}

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Importação de Evento / Proponente</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados do Proponente</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <div class="box-body">
                        <?php
                        if ($existeProponente) {
                            if ($proponentes) {
                                include_once "includes/include_import_proponente.php";
                            } else {
                                if ($pedidoCpc['pessoa_tipo_id'] == 1){
                                    $idProponente = $proponenteSis['id'];
                                    $sql_pf = "SELECT * FROM pessoa_fisicas AS pf
                                    LEFT JOIN pf_enderecos pe on pf.id = pe.pessoa_fisica_id
                                    LEFT JOIN pf_bancos pb on pf.id = pb.pessoa_fisica_id
                                    LEFT JOIN drts d on pf.id = d.pessoa_fisica_id
                                    LEFT JOIN nits n on pf.id = n.pessoa_fisica_id
                                    LEFT JOIN nacionalidades n2 on pf.nacionalidade_id = n2.id
                                    LEFT JOIN bancos b on pb.banco_id = b.id
                                    LEFT JOIN pf_detalhes pd on pf.id = pd.pessoa_fisica_id
                                    WHERE pf.id = '$idProponente'";
                                    $query_pf = mysqli_query($conSis,$sql_pf);
                                    $pf = mysqli_fetch_assoc($query_pf);

                                    $sql_tel = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idProponente'";
                                    $query_tel = mysqli_query($conSis,$sql_tel);
                                    ?>
                                    <div class="alert alert-warning">
                                        <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
                                        O proponente cadastrado no <strong>CAPAC</strong> já existe no <strong>SisContrat</strong>,
                                        porém, não existe divergencia nos dados. Clique no botão ao final da página para completar
                                        a importação.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><b> Nome:</b> <?= $pf['nome'] ?></div>
                                        <div class="col-md-6"><b>Nome Artístico:</b> <?= $pf['nome_artistico'] ?></div>
                                    </div>
                                    <div class="row">
                                        <?php
                                        if(!empty($pf['cpf'])){
                                            ?>
                                            <div class="col-md-2"><b>RG:</b> <?= $pf['rg'] ?></div>
                                            <div class="col-md-2"><b>CPF:</b> <?= $pf['cpf'] ?></div>
                                            <div class="col-md-2"><b>CCM:</b> <?= $pf['ccm'] ?></div>
                                            <?php
                                        }
                                        else{
                                            ?>
                                            <div class="col-md-6"><b>Passaporte:</b> <?= $pf['passaporte'] ?></div>
                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-3"><b>Data de Nascimento:</b> <?= date("d/m/Y", strtotime($pf['data_nascimento'])) ?></div>
                                        <div class="col-md-3"><b>Naconalidade:</b> <?= $pf['nacionalidade'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><b>E-mail:</b> <?= $pf['email'] ?></div>
                                        <div class="col-md-6">
                                            <b>Telefones:</b>
                                            <?php
                                            while ($telefones = mysqli_fetch_array($query_tel)){
                                                echo $telefones['telefone']." | ";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><b>NIT:</b> <?= $pf['nit'] ?></div>
                                        <div class="col-md-6"><b>DRT:</b> <?= $pf['drt'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>Endereço:</b> <?= $pf['logradouro'] . ", " . $pf['numero'] . " " . $pf['complemento'] . " " . $pf['bairro'] . " - " . $pf['cidade'] . "-" . $pf['uf'] . " CEP: " . $pf['cep'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><b>Banco:</b> <?= $pf['banco'] ?></div>
                                        <div class="col-md-4"><b>Agência:</b> <?= $pf['agencia'] ?></div>
                                        <div class="col-md-4"><b>Conta:</b> <?= $pf['conta'] ?></div>
                                    </div>
                                    <?php
                                } else {
                                    $idProponente = $proponenteSis['id'];
                                    $sql_pj = "SELECT pj.*, pe.*, pb.*, bc.banco, bc.codigo
                                FROM pessoa_juridicas AS pj
                                LEFT JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id
                                LEFT JOIN pj_bancos pb on pj.id = pb.pessoa_juridica_id
                                LEFT JOIN bancos bc on pb.banco_id = bc.id
                                WHERE pj.id = '$idProponente'";
                                    $query_pj = mysqli_query($conSis,$sql_pj);
                                    $pj = mysqli_fetch_assoc($query_pj);
                                    $idRep1 = $pj['representante_legal1_id'];
                                    $sql_rep1 = "SELECT * FROM representante_legais WHERE id = '$idRep1'";
                                    $query_rep1 = mysqli_query($conSis,$sql_rep1);
                                    $rep1 = mysqli_fetch_array($query_rep1);

                                    $sql_tel = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$idProponente'";
                                    $query_tel = mysqli_query($conSis,$sql_tel);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-7"><b>Razão Social:</b> <?= $pj['razao_social'] ?></div>
                                        <div class="col-md-3"><b>CNPJ:</b> <?= $pj['cnpj'] ?></div>
                                        <div class="col-md-2"><b>CCM:</b> <?= $pj['ccm'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><b>E-mail:</b> <?= $pj['email'] ?></div>
                                        <div class="col-md-6"><b>Telefones:</b>
                                            <?php
                                            while ($telefones = mysqli_fetch_array($query_tel)){
                                                echo $telefones['telefone']." | ";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>Endereço:</b> <?= $pj['logradouro'] . ", " . $pj['numero'] . " " . $pj['complemento'] . " " . $pj['bairro'] . " - " . $pj['cidade'] . "-" . $pj['uf'] . " CEP: " . $pj['cep'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4"><b>Banco:</b> <?= $pj['banco'] ?></div>
                                        <div class="col-md-4"><b>Agência:</b> <?= $pj['agencia'] ?></div>
                                        <div class="col-md-4"><b>Conta:</b> <?= $pj['conta'] ?></div>
                                    </div>
                                    <br/>
                                    <h5><b>Representante Legal</b></h5>
                                    <div class="row">
                                        <div class="col-md-6"><b>Nome:</b> <?= $rep1['nome'] ?></div>
                                        <div class="col-md-3"><b>RG:</b> <?= $rep1['rg'] ?></div>
                                        <div class="col-md-3"><b>CFP:</b> <?= $rep1['cpf'] ?></div>
                                    </div>
                                    <br>
                                    <?php
                                    if ($pj['representante_legal2_id']){
                                        $idRep2 = $pj['representante_legal2_id'];
                                        $sql_rep2 = "SELECT * FROM representante_legais WHERE id = '$idRep2'";
                                        $query_rep2 = mysqli_query($conSis,$sql_rep2);
                                        $rep2 = mysqli_fetch_array($query_rep2);
                                        ?>
                                        <div class="row">
                                            <div class="col-md-6"><b>Nome:</b> <?= $rep2['nome'] ?></div>
                                            <div class="col-md-3"><b>RG:</b> <?= $rep2['rg'] ?></div>
                                            <div class="col-md-3"><b>CFP:</b> <?= $rep2['cpf'] ?></div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <form>
                                    <div class="box-footer">
                                        <div class="pull-left">
                                            <a href="../downloads/arquivos_capac.php?tipo_pessoa=<?= $pedidoCpc['pessoa_tipo_id'] ?>&idProponente=<?= $idProponente ?>"
                                               class="btn btn-warning btn-block float-right" target="_blank">Baixar
                                                Arquivos do Proponente</a><br/>
                                        </div>
                                        <button type="submit" name="importarProponenteCpc"
                                                class="btn btn-info pull-right">Gravar
                                        </button>
                                    </div>
                                </form>
                            <?php
                            }
                        } else {
                            echo mensagem('success', "Retornando a listagem de Eventos.");
                            echo "<meta http-equiv='refresh' content='3;url=?perfil=evento&p=evento_lista'/>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
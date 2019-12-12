<?php
include "includes/menu_principal.php";

$idUser = $_SESSION['idUser'];
$idCapac = $_POST['idCapac'];
unset($_POST['idCapac']);

$eventoImportado = false;
$dataAtual = date("Y-m-d H:i:s");

$conSis = bancoMysqli();
$conCpc = bancoCapac();

$sqlConsultaPedido = "SELECT * FROM capac_new.pedidos WHERE origem_tipo_id = 1 AND origem_id = '$idCapac'";
$pedido = $conCpc->query($sqlConsultaPedido)->fetch_assoc();

//if (isset($_POST['importarEventoCpc'])) {
//    $nomeEvento = addslashes($_POST['nomeEvento']);
//    $relacao_juridica_id = $_POST['relacaoJuridica'];
//    $projeto_especial_id = $_POST['projetoEspecial'];
//    $sinopse = addslashes($_POST['sinopse']);
//    $tipo = $_POST['tipo'];
//    $fiscal_id = $_POST['fiscal'];
//    $suplente_id = $_POST['suplente'];
//    $usuario = $_SESSION['idUser'];
//    $contratacao = $_POST['contratacao'];
//    $eventoStatus = "1";
//    $fomento = $_POST['fomento'];
//    $tipoLugar = $_POST['tipoLugar'];
//    $idFomento = $_POST['tipoFomento'] ?? null;
//
//    /* INSERE EVENTO NO SISCONTRAT */
//    $sqlInsertSis = "INSERT INTO siscontrat.eventos (nome_evento,
//                                 relacao_juridica_id,
//                                 projeto_especial_id,
//                                 tipo_evento_id,
//                                 sinopse,
//                                 fiscal_id,
//                                 suplente_id,
//                                 usuario_id,
//                                 contratacao,
//                                 evento_status_id,
//                                 fomento,
//                                 espaco_publico)
//                          VALUES ('$nomeEvento',
//                                  '$relacao_juridica_id',
//                                  '$projeto_especial_id',
//                                  '$tipo',
//                                  '$sinopse',
//                                  '$fiscal_id',
//                                  '$suplente_id',
//                                  '$usuario',
//                                  '$contratacao',
//                                  '$eventoStatus',
//                                  '$fomento',
//                                  '$tipoLugar')";
//
//    if(mysqli_query($conSis, $sqlInsertSis)) {
//        $idEvento = $conSis->insert_id;
//
//        /* VERIFICA SE O EVENTO É FOMENTO */
//        if ($idFomento != null) {
//            $sqlFomentoCpc = "INSERT INTO siscontrat.evento_fomento  (evento_id, fomento_id) VALUES ('$idEvento', '$idFomento')";
//            mysqli_query($conSis, $sqlFomentoCpc);
//        }
//
//        if (isset($_POST['publico'])) {
//            atualizaDadosRelacionamento('siscontrat.evento_publico', $idEvento, $_POST['publico'], 'evento_id', 'publico_id');
//        }
//
//        $sqlInsertImportado = "INSERT INTO siscontrat.evento_importados (evento_id, evento_capac_id) VALUES ('$idEvento', '$idCapac')";
//        if (mysqli_query($conSis, $sqlInsertImportado)) {
//            $eventoImportado = true;
//            $mensagem = mensagem('success', 'Evento importado. Abaixo, verifique os dados do Proponente para efetuar a importação');
//        }
//    } else {
//        echo "<script>window.location.href = 'index.php?perfil=evento&p=importar_evento_capac&error=1&idCpc=".$idCapac."</script>";
//    }
//}

if ($pedido['pessoa_tipo_id'] == 1) {
    $idProponenteCpc = $pedido['pessoa_fisica_id'];
    $sqlConsultaProponente = "SELECT * FROM capac_new.pessoa_fisicas WHERE id = '$idProponenteCpc'";

    $proponenteCpc = $conCpc->query($sqlConsultaProponente)->fetch_assoc();

    $sqlComparaProponente = "SELECT * FROM siscontrat.pessoa_fisicas WHERE cpf = '{$proponenteCpc['cpf']}'";

} elseif ($pedido['pessoa_tipo_id'] == 2) {
    $idProponenteCpc = $pedido['pessoa_juridica_id'];
    $sqlConsultaProponente = "SELECT * FROM capac_new.pessoa_juridicas WHERE id = '$idProponenteCpc'";

    $proponenteCpc = $conCpc->query($sqlConsultaProponente)->fetch_assoc();

    $sqlComparaProponente = "SELECT * FROM siscontrat.pessoa_juridicas WHERE cnpj = '{$proponenteCpc['cnpj']}'";
}

$queryProponenteSis = $conSis->query($sqlComparaProponente);

if ($queryProponenteSis->num_rows > 0) {
    $existeProponente = true;
    $proponenteSis = $queryProponenteSis->fetch_assoc();
} else {
    $existeProponente = false;
    $_POST['importarProponenteCpc'] = true;
}

if (isset($_POST['importarProponenteCpc'])) {
    if ($pedido['pessoa_tipo_id'] == 1) {
        $idProponentePj = "null";

        $idProponentePf = "aeoo";
    } elseif ($pedido['pessoa_tipo_id'] == 2) {
        $idProponentePf = "null";
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
            }
        } else {
            $idProponenteSis = $_POST['idProponenteSis'];
            unset($_POST['idProponenteSis']);
            unset($_POST['importarProponenteCpc']);
            foreach ($_POST as $key => $post) {
                $update[] = $key." = '".$post."'";
            }

            $sqlUpdateProponente = "UPDATE siscontrat.pessoa_juridicas SET ".implode(", ", $update)." WHERE id = '$idProponenteSis'";
        }
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
        }
    }

    /* INSERINDO PEDIDO */
    $sqlInsertPedido = "INSERT INTO siscontrat.pedidos (origem_tipo_id, origem_id, pessoa_tipo_id, pessoa_juridica_id, pessoa_fisica_id) VALUES 
                        (1, '$idEvento', {$pedido['pessoa_tipo_id']}, $idProponentePj, $idProponentePf)";
    $conSis->query($sqlInsertPedido);
    $idPedido = $conSis->insert_id;

    if ($eventoImportado) {
        /* IMPORTANDO AS ATRAÇÕES */
        $sqlAtracoesCpc = "SELECT * FROM capac_new.atracoes WHERE evento_id = '$idCapac' AND publicado = 1";
        $atracoes = $conCpc->query($sqlAtracoesCpc)->fetch_all(MYSQLI_ASSOC);

        foreach ($atracoes as $atracao) {
            $sqlAcoesCpc = "SELECT acao_id FROM capac_new.acao_atracao WHERE atracao_id = {$atracao['id']}";
            $acoes = $conCpc->query($sqlAcoesCpc)->fetch_all(MYSQLI_ASSOC);

            /* INSERINDO PRODUTOR */
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

            if ($pedido['pessoa_tipo_id'] == 2) {
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
                    <?php
                    if($existeProponente) {
                        if ($pedido['pessoa_tipo_id'] == 1) {
                            echo "compara PF";
                        } else {
                            include_once "includes/include_import_proponentePj.php";
                        }
                    } else {
                        echo "importado";
                    }
                    ?>
                </div>
            </div>
        </div>

    </section>
</div>
<div class="modal fade" id="modalPublico" role="dialog" aria-labelledby="lblmodalPublico" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Público (Representatividade e Visibilidade Sócio-cultural)</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Público</th>
                        <th>Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sqlConsultaPublico = "SELECT publico, descricao FROM publicos WHERE publicado = '1' ORDER BY 1";
                    foreach ($conSis->query($sqlConsultaPublico)->fetch_all(MYSQLI_ASSOC) as $publico) {
                        ?>
                        <tr>
                            <td><?= $publico['publico'] ?></td>
                            <td><?= $publico['descricao'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
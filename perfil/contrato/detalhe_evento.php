<?php

$con = bancoMysqli();
if(isset($_POST['detalheEvento'])){
    $idEvento = $_POST['idEvento'];
    $idPedido = $_POST['idPedido'];
}


// para inserir a informação em Dotação //
$sql = "SELECT * FROM juridicos where pedido_id = '$idEvento'";
$query = mysqli_query($con,$sql);
$num = mysqli_num_rows($query);


// dados //
$evento = recuperaDados('eventos', 'id', $idEvento);
$tipo_evento = recuperaDados('tipo_eventos', 'id', $idEvento);
$projeto_especiais = recuperaDados('projeto_especiais', 'id', $idEvento);
$relacao_juridica = recuperaDados('relacao_juridicas', 'id', $idEvento);
$linguagens = recuperaDados('linguagens', 'id', $idEvento);
$atracao = recuperaDados('atracoes', 'id', $idEvento);
$classificacao = recuperaDados('classificacao_indicativas', 'id', $idEvento);
$suplente = recuperaDados('usuarios', 'id', $evento['suplente_id']);
$ocorrencia = recuperaDados('ocorrencias', 'id', $idEvento);
$retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']);
$pedidos = recuperaDados('pedidos', 'id', $idEvento);
$pagamento = recuperaDados('pagamentos', 'pedido_id', $idEvento);
$statusPedido = recuperaDados('pedido_status', 'id', $idEvento);
$produtor = recuperaDados('produtores', 'id', $idEvento);
$usuarios = recuperaDados('usuarios', 'id', $evento['usuario_id']);
$dataEvento = recuperaDados('evento_envios','id',$idEvento);
$dotacao = $con->query("SELECT * FROM juridicos WHERE pedido_id = 1")->fetch_array();
?>


<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title"><?= $evento['nome_evento'] ?></h1>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">ID do evento:</th>
                        <td><?= $evento['id'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Evento enviado em:</th>
                        <td><?= $dataEvento['data_envio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Tipo de evento:</th>
                        <td><?= $tipo_evento['tipo_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Projeto especial:</th>
                        <td><?= $projeto_especiais['projeto_especial'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Relação jurídica:</th>
                        <td><?= $relacao_juridica['relacao_juridica'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou o evento:</th>
                        <td><?= $usuarios['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $usuarios['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $usuarios['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td><?= $usuarios['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $usuarios['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $usuarios['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Suplente:</th>
                        <td><?= $suplente['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $suplente['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $suplente['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Ficha técnica:</th>
                        <td><?= $atracao['ficha_tecnica'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $classificacao['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $linguagens['linguagem'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $sqlPublico = "SELECT * 
                        FROM publicos where id = $idEvento";
                        $pub = $con->query($sqlPublico)->fetch_assoc();
                        ?>
                        <th width="30%">Público / Representatividade social:</th>
                        <td><?= $pub['publico'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Sinopse:</th>
                        <td><?= $evento['sinopse'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Release</th>
                        <td><?= $atracao['release_comunicacao'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                </table>
                <h1>Especificidades</h1>
                <h3>Ocorrências</h3>
                De <?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?>
                <br>
                <?php
                $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id']);
                ?>
                <tr>
                    <td><?= $instituicao['nome'] ?> (<?= $instituicao['sigla'] ?>)</td>
                </tr>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Evento de temporada</th>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Horário</th>
                        <td><?= $ocorrencia['horario_inicio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $instituicao['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td><?= $retirada_ingresso['retirada_ingresso'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td><?= $ocorrencia['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td><?= $produtor['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $produtor['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $produtor['telefone1'] ?></td>
                    </tr>
                </table>
                <h1>Arquivos Comunicação/Produção anexos</h1>
                <h3>Pedidos de contratação</h3>
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $evento['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do processo:</th>
                        <td><?= $pedidos['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $tipo_pessoa = "SELECT pt.pessoa FROM
                        pedidos as p 
                        INNER JOIN pessoa_tipos pt on p.pessoa_tipo_id = pt.id
                        WHERE p.publicado = 1";
                        $tpQuerry = $con->query($tipo_pessoa)->fetch_assoc();
                        ?>
                        <th width="30%">Tipo de pessoa</th>
                        <td><?= $tpQuerry['pessoa'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $pedido = "SELECT * FROM PEDIDOS WHERE id = $idEvento AND publicado = 1";
                        $query = mysqli_query($con,$pedido);
                        $pessoa = mysqli_num_rows($query);

                        if($pessoa['pessoa_tipo_id'] == 2){
                            $pj = recuperaDados("pessoa_juridicas","id",$pessoa['pessoa_juridica_id']);
                            echo "<td>".$pj['razao_social']."</td>";
                        }
                        else{
                            $pf = recuperaDados("pessoa_fisicas","id",$pessoa['pessoa_fisica_id']);
                            echo "<td>".$pf['nome']."</td>";
                        }
                        ?>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td><?= $evento['nome_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $instituicao['nome'] ?> (<?= $instituicao['sigla'] ?>)</td>
                    </tr>
                    <tr>
                        <th width="30%">Valor</th>
                        <td><?= $pedidos['valor_total'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Forma de Pagamento</th>
                        <td><?= $pedidos['forma_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoNovo($idEvento, 'ocorrencias') ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Emissão da N.E:</th>
                        <td><?= $pagamento['emissao_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Entrega da N.E</th>
                        <td><?= $pagamento['entrega_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Dotação Orçamentária:</th>
                        <td><?=$dotacao['dotacao']?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observação:</th>
                        <td><?= $pedidos['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Último status:</th>
                        <td><?= $statusPedido['status'] ?></td>
                    </tr>
                </table>
                <br/>
                <div class="pull-left">
                    <form action="?perfil=contrato&p=resumo" method="post">
                        <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                        <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                        <button type="submit" name="Voltar" class="btn btn-default pull-left">Voltar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblFormacao').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>
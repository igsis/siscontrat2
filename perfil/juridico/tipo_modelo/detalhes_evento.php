<?php

$idFormacao = $_SESSION['formacaoId'];
$con = bancoMysqli();

if (isset($_POST['mdlPadrao'])) {
    $modelo = $_POST['idPadrao'];
}
if (isset($_POST['mdlVoca'])) {
    $modelo = $_POST['idVoca'];
}
if (isset($_POST['mdlPia'])) {
    $modelo = $_POST['idPia'];
}
if (isset($_POST['mdlOficina'])) {
    $modelo = $_POST['idOficina'];
}

$sql = "SELECT 
    p.numero_processo,
    p.justificativa,
    fc.protocolo,
    pf.nome,
    p.valor_total,
    p.forma_pagamento,
    fc.data_envio,
    e.tipo_evento_id,
    e.id,
    e.nome_evento,
    e.sinopse,
    te.tipo_evento,
    pe.projeto_especial,
    rj.relacao_juridica,
    pf.email,
    pft.telefone,
    a.ficha_tecnica,
    ci.classificacao_indicativa,
    li.linguagem,
    pub.publico,
    a.release_comunicacao,
    o.data_inicio,
    o.data_fim,
    o.observacao,
    loc.local,
    o.horario_inicio,
    ri.retirada_ingresso,
    pt.pessoa,
    p2.nota_empenho,
    p2.emissao_nota_empenho,
    p2.entrega_nota_empenho
    
    FROM pedidos as p
    INNER JOIN formacao_contratacoes fc on p.origem_id = fc.id
    INNER JOIN pessoa_fisicas pf on fc.pessoa_fisica_id = pf.id
    INNER JOIN eventos e on fc.usuario_id = e.usuario_id
    INNER JOIN tipo_eventos te on te.id = e.tipo_evento_id
    INNER JOIN projeto_especiais pe on e.projeto_especial_id = pe.id
    INNER JOIN relacao_juridicas rj on rj.id = e.relacao_juridica_id
    INNER JOIN usuarios u on e.usuario_id = u.id
    INNER JOIN pf_telefones pft on pf.id = pft.pessoa_fisica_id
    INNER JOIN atracoes a on a.evento_id = e.id
    INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id
    INNER JOIN linguagens li on fc.linguagem_id = li.id
    INNER JOIN publicos pub on e.tipo_evento_id = pub.id
    INNER JOIN ocorrencias o on a.id = o.atracao_id
    INNER JOIN retirada_ingressos ri on o.retirada_ingresso_id = ri.id
    INNER JOIN locais loc on o.local_id = loc.id
    INNER JOIN pessoa_tipos pt on p.pessoa_tipo_id = pt.id
    INNER JOIN pagamentos p2 on p.id = p2.pedido_id
    WHERE fc.publicado = 1 AND p.origem_tipo_id AND p.origem_id = $idFormacao";
$evento = $con->query($sql)->fetch_array();
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
                        <td><?= $evento['data_envio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Tipo de evento:</th>
                        <td><?= $evento['tipo_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Projeto especial:</th>
                        <td><?= $evento['projeto_especial'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Relação jurídica:</th>
                        <td><?= $evento['relacao_juridica'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou o evento:</th>
                        <td><?= $evento['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $evento['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $evento['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td><?= $evento['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $evento['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $evento['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <?php
                        $sqlSuplente = "SELECT * 
                        FROM usuarios where id = $idFormacao";
                        $mdl = $con->query($sqlSuplente)->fetch_assoc();
                        ?>
                        <th width="30%">Suplente:</th>
                        <td><?= $mdl['nome_completo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $mdl['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $mdl['email'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Ficha técnica:</th>
                        <td><?= $evento['ficha_tecnica'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $evento['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $evento['linguagem'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Público / Representatividade social:</th>
                        <td><?= $evento['publico'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Sinopse:</th>
                        <td><?= $evento['sinopse'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Release</th>
                        <td><?= $evento['release_comunicacao'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                </table>
                <h1>Especificidades</h1>
                <h3>Ocorrências</h3>
                De <?= $evento['data_inicio'] ?> a <?= $evento['data_fim'] ?>
                <br>
                <?= $evento['local'] ?>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Evento de temporada</th>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td>De <?= $evento['data_inicio'] ?> a <?= $evento['data_fim'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Horário</th>
                        <td><?= $evento['horario_inicio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $evento['local'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td><?= $evento['retirada_ingresso'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td><?= $evento['observacao'] ?></td>
                    </tr>
                    <?php
                    $sqlProdutor = "SELECT pro.nome,pro.telefone1,pro.email
                        FROM produtores as pro
                        inner join eventos e on e.id = pro.id ";
                    $mdl = $con->query($sqlProdutor)->fetch_assoc();
                    ?>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td><?= $mdl['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $mdl['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $mdl['telefone1'] ?></td>
                    </tr>
                </table>
                <h1>Arquivos Comunicação/Produção anexos</h1>
                <h3>Pedidos de contratação</h3>
                <table class="table">
                    <?php
                    $sqlContratacao = "SELECT i.nome FROM 
                    ocorrencias as o 
                    INNER JOIN instituicoes i on o.instituicao_id = i.id";
                    $contratacao = $con->query($sqlContratacao)->fetch_assoc();
                    ?>
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $evento['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do processo:</th>
                        <td><?= $evento['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Setor</th>
                        <td><?= $contratacao['nome'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $sqlTipo = "SELECT pro.nome,pro.telefone1,pro.email
                        FROM produtores as pro
                        inner join eventos e on e.id = pro.id ";
                        $mdl = $con->query($sqlProdutor)->fetch_assoc();
                        ?>
                        <th width="30%">Tipo de pessoa</th>
                        <td><?= $evento['pessoa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Proponente</th>
                        <td><?= $evento['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td><?= $evento['nome_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $evento['local'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Valor</th>
                        <td><?= $evento['valor_total'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Forma de Pagamento</th>
                        <td><?= $evento['forma_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td>De <?= $evento['data_inicio'] ?> a <?= $evento['data_fim'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Parecer</th>
                    </tr>
                    <tr>
                        <th width="30%">Data de Emissão da N.E:</th>
                        <td><?= $evento['emissao_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Entrega da N.E</th>
                        <td><?= $evento['entrega_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Dotação Orçamentária:</th>
                        <td><?= $evento['justificativa'] ?></td>
                    </tr>
                </table>
                <br/>
                <div class="pull-left">
                    <a href="?perfil=juridico&p=filtrar_formacao&sp=pesquisa_formacao">
                        <button type="button" class="btn btn-default">Voltar a pesquisa</button>
                    </a>
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
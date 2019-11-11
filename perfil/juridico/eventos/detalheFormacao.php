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
    ri.retirada_ingresso
    
    
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
    WHERE fc.publicado = 1 AND p.origem_tipo_id AND p.origem_id = $idFormacao";
$formacao = $con->query($sql)->fetch_array();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $formacao['nome_evento'] ?></h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">ID do evento:</th>
                        <td><?= $formacao['id'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Evento enviado em:</th>
                        <td><?= $formacao['data_envio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Tipo de evento:</th>
                        <td><?= $formacao['tipo_evento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Projeto especial:</th>
                        <td><?= $formacao['projeto_especial'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Relação jurídica:</th>
                        <td>FALTA INSERIR</td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou o evento:</th>
                        <td><?= $formacao['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $formacao['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $formacao['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td><?= $formacao['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $formacao['telefone'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $formacao['email'] ?></td>
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
                        <th width="30%">Ficha técnica:</th>
                        <td><?= $formacao['ficha_tecnica'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $formacao['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $formacao['linguagem'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Público / Representatividade social:</th>
                        <td><?= $formacao['publico'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Sinopse:</th>
                        <td><?= $formacao['sinopse'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Release</th>
                        <td><?= $formacao['release_comunicacao'] ?></td>
                    </tr>
                </table>
                <h1>Especificidades</h1>
                <h3>Ocorrências</h3>
                De <?= $formacao['data_inicio'] ?> a <?= $formacao['data_fim'] ?>
                <br>
                <?= $formacao['local'] ?>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Evento de temporada</th>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td>De <?= $formacao['data_inicio'] ?> a <?= $formacao['data_fim'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Horário</th>
                        <td><?= $formacao['horario_inicio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $formacao['local'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td><?= $formacao['retirada_ingresso'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td><?= $formacao['observacao'] ?></td>
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
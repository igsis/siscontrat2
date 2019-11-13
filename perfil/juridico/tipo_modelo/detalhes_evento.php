<?php

$con = bancoMysqli();
$idEvento = $_SESSION['eventoId'];

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
    p.forma_pagamento,
    p.observacao,
    p.valor_total,
    u.nome_completo,
    u.telefone,
    u.email,
    p.origem_tipo_id,
    p.origem_id,
    e.protocolo,
    e.id,
    e.sinopse,
    e.nome_evento,
    p.observacao,
    pe.data,
    te.tipo_evento,
    proe.projeto_especial,
    rj.relacao_juridica,
    pf.nome,
    a.ficha_tecnica,
    a.release_comunicacao,
    ci.classificacao_indicativa,
    oc.data_inicio,
    oc.data_fim,
    oc.horario_inicio,
    oc.data_fim,
    i.nome,
    i.sigla,
    ri.retirada_ingresso,
    pt.pessoa,
    pa.entrega_nota_empenho,
    pa.nota_empenho,
    pa.emissao_nota_empenho
    
    
    
    FROM pedidos as p
    INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
    INNER JOIN eventos e on e.id = p.origem_id
    INNER JOIN producao_eventos pe on e.id = pe.evento_id
    INNER JOIN tipo_eventos te on e.tipo_evento_id = te.id
    INNER JOIN projeto_especiais proe on e.projeto_especial_id = proe.id
    INNER JOIN relacao_juridicas rj on e.relacao_juridica_id = rj.id
    INNER JOIN usuarios u on e.usuario_id = u.id
    INNER JOIN atracoes a on e.id = a.evento_id
    INNER JOIN ocorrencias oc on a.id = oc.atracao_id
    INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id
    INNER JOIN instituicoes i on oc.instituicao_id = i.id
    INNER JOIN retirada_ingressos ri on oc.retirada_ingresso_id = ri.id
    INNER JOIN pessoa_tipos pt on p.pessoa_tipo_id = pt.id
    INNER JOIN pagamentos pa on p.id = pa.pedido_id
    
    WHERE p.publicado = 1 AND p.origem_tipo_id AND p.origem_id = $idEvento";
$evento = $con->query($sql)->fetch_array();
echo $sql
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
                        <td><?= $evento['data'] ?></td>
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
                        <td><?= $evento['nome_completo'] ?></td>
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
                        <td><?= $evento['nome_completo'] ?></td>
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
                        FROM usuarios where id = $idEvento";
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
                        <?php
                        $sqlLinguagem = "SELECT * 
                        FROM linguagens where id = $idEvento";
                        $lingua = $con->query($sqlLinguagem)->fetch_assoc();
                        ?>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $lingua['linguagem'] ?></td>
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
                <?php
                $sqlLocal = "SELECT 
                    l.local
                    from locais l 
                    INNER JOIN ocorrencias o on l.id = o.local_id";
                $local = $con->query($sqlLocal)->fetch_assoc();
                ?>
                <br>
                <?= $local['local'] ?>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Evento de temporada</th>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoNovo($idEvento,'ocorrencias' )?></td>
                    </tr>
                    <tr>
                        <th width="30%">Horário</th>
                        <td><?= $evento['horario_inicio'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td><?= $evento['nome'] ?> (<?= $evento['sigla'] ?>)</td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td><?= $evento['retirada_ingresso'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td><?= $evento['observacao'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $sqlProdutor = "SELECT 
                        pro.nome,
                        pro.telefone1,
                        pro.email
                        FROM produtores as pro
                        INNER JOIN eventos e on e.id = pro.id ";
                        $mdl = $con->query($sqlProdutor)->fetch_assoc();
                        ?>
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
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $evento['id'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do processo:</th>
                        <td><?= $evento['numero_processo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Setor</th>
                        <td><?= $evento['nome'] ?></td>
                    </tr>
                    <tr>
                        <?php
                        $sqlTipo = "SELECT pro.nome,pro.telefone1,pro.email
                        FROM produtores as pro
                        inner join eventos e on e.id = pro.$idEvento ";
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
                        <td><?= $evento['nome'] ?> (<?= $evento['sigla'] ?>)</td>
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
                        <td><?= retornaPeriodoNovo($idEvento,'ocorrencias' )?></td>
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
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Observação:</th>
                        <td><?= $evento['observacao']?></td>
                    </tr>
                    <tr>
                        <th width="30%">Último status:</th>
                        <td></td>
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
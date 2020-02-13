<?php

$con = bancoMysqli();

isset($_POST['idFormacao']);
$idFormacao = $_POST['idFormacao'];

$sql = "SELECT p.numero_processo,
            p.forma_pagamento,
            p.valor_total,
            p.data_kit_pagamento,
            p.origem_id,
            pf.nome,
            pf.email,
            ci.classificacao_indicativa,
            l.linguagem,
            fc.protocolo,
            fc.observacao,
            pro.programa,
            pro.edital,
            fc.observacao,
            pag.emissao_nota_empenho,
            pag.entrega_nota_empenho,
            fs.status,
            fc.data_envio
            

        FROM pedidos as p
        INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
        INNER JOIN formacao_contratacoes fc on p.id = fc.pedido_id
        INNER JOIN classificacao_indicativas ci on fc.classificacao = ci.id
        INNER JOIN linguagens l on fc.linguagem_id = l.id
        INNER JOIN programas pro on fc.programa_id = pro.id
        INNER JOIN pagamentos pag on pag.pedido_id = p.id
        INNER JOIN formacao_status fs on fs.id = fc.form_status_id
        WHERE p.publicado = 1 AND p.origem_tipo_id = 2 AND fc.id = $idFormacao";
$query = $con->query($sql)->fetch_assoc();



//  local //
$sqlLocal = "SELECT l.local FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

// pegando a vigencia
$f = recuperaDados('formacao_contratacoes', 'id', $idFormacao);
$idVigencia = $f['form_vigencia_id'];

// usuarios //
$usuarios = recuperaDados('usuarios', 'id', $f['usuario_id']);

// fiscal , suplente //
$suplente = recuperaDados('usuarios', 'id', $f['suplente_id']);
$fiscal = recuperaDados('usuarios', 'id', $f['fiscal_id']);


// pegando telefone de pf_telefones //
$idPf = $f ['pessoa_fisica_id'];
$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}
$tel = substr($tel, 0, -3);
////

$fcHora = recuperaDados('formacao_parcelas','id',$idFormacao);

?>


<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">ID do evento:</th>
                        <td><?= $idFormacao ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Enviado em:</th>
                        <td><?= $query['data_envio'] ?></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Usuário que cadastrou a formação:</th>
                        <td><?= $query['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $tel ?> </td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $query['email'] ?></td>
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
                        <td><?= $query ['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Faixa ou indicação etária:</th>
                        <td><?= $query['classificacao_indicativa'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Linguagem / Expressão artística:</th>
                        <td><?= $query['linguagem'] ?></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                </table>
                <h1>Especificidades</h1>
                <h3>Ocorrências</h3>
                <br>
                <tr>
                    <td></td>
                </tr>
                <br>
                <br>
                <table class="table">
                    <tr>
                        <th width="30%">Evento de temporada</th>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= retornaPeriodoFormacao($idVigencia) ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td>
                            <?php
                            while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                $local = $local . $linhaLocal['local'] . ' - ';
                            }

                            $local = substr($local, 0, -3);
                            echo $local;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td><?= $query['nome'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td><?= $query['email'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td><?= $tel ?></td>
                    </tr>
                </table>
                <h1>Arquivos Comunicação/Produção anexos</h1>
                <h3>Pedidos de contratação</h3>
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $query['protocolo'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do processo:</th>
                        <td><?= $query['numero_processo'] ?></td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td><?= $query['programa'] ?>-<?= $query['linguagem'] ?>-<?= $query['edital'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td>
                            <?php
                            while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
                                $local = $local . $linhaLocal['local'] . '-';
                            }

                            $local = substr($local, 0, -3);
                            echo $local;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="30%">Valor</th>
                        <td><?= $query['valor_total'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Forma de Pagamento</th>
                        <td><?= $query['forma_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data</th>
                        <td><?= $query['data_kit_pagamento'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Emissão da N.E:</th>
                        <td><?= $query['emissao_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Data de Entrega da N.E</th>
                        <td><?= $query['entrega_nota_empenho'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Dotação Orçamentária:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Observação:</th>
                        <td><?= $query['observacao'] ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Último status:</th>
                        <td><?= $query['status'] ?></td>
                    </tr>
                </table>
                <br/>
                <div class="pull-left">
                    <a href="?perfil=juridico">
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

<?php

$con = bancoMysqli();
isset($_POST['idFormacao']);
$idFormacao = $_POST['idFormacao'];


$sql = "SELECT p.numero_processo,
            p.forma_pagamento,
            p.valor_total,
            p.data_kit_pagamento,
            p.origem_id,
            fc.data_envio,
            fc.protocolo,
            fc.observacao,
            pf.nome, 
            fs.status,
            fc.id,
            ci.classificacao_indicativa,
            pt.pessoa,
            l.linguagem,
            pag.nota_empenho,
            pag.emissao_nota_empenho,
            pag.entrega.nota_empenho
            

        FROM pedidos as p
        INNER JOIN pagamentos pag on p.id = pag.pedido_id
        INNER JOIN formacao_status fs on p.status_pedido_id = fs.id 
        INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
        INNER JOIN pessoa_tipos pt on pt.id = p.pessoa_tipo_id
        INNER JOIN formacao_contratacoes fc on p.origem_id = fc.id
        INNER JOIN linguagens l on l.id = fc.linguagem_id
        INNER JOIN classificacao_indicativas ci on ci.id = fc.classificacao
        WHERE p.publicado = 1 AND p.origem_tipo_id = 2 AND fc.publicado = 1 AND p.id = $idFormacao";
$query = $con->query($sql)->fetch_assoc();

$sqlLocal = "SELECT l.local FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";

$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

?>


<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="box-title"></h1>
            </div>
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
                        <th width="30%">Usuário que cadastrou o evento:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Reponsável pelo evento:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th><br/></th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Suplente:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td></td>
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
                        <th width="30%">Público / Representatividade social:</th>
                        <td></td>
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
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Horário</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Local</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Retirada de ingressos:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Observações:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Produtor responsavel:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Email:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th width="30%">Telefone:</th>
                        <td></td>
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
                        <th width="30%">Tipo de pessoa</th>
                        <td><?= $query['pessoa'] ?></td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th width="30%">Objeto</th>
                        <td></td>
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

<?php
$con = bancoMysqli();

$idPedido = $_POST['idPedido'];

$sql = "SELECT p.id, 
               ec.protocolo,
               p.numero_processo,
               pf.nome,
               l.local,
               s.status 
        FROM pedidos AS p 
        INNER JOIN emia_contratacao AS ec ON ec.id = p.origem_id
        INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
        INNER JOIN emia_status AS s ON ec.emia_status_id = s.id
        INNER JOIN locais AS l ON ec.local_id = l.id
        WHERE p.origem_tipo_id = 3 AND ec.publicado = 1 AND p.publicado = 1 AND p.id = '$idPedido'";

$pedido = $con->query($sql)->fetch_array();

?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">
           Resumo do Pedido
        </h2>
        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    Dados
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th width="30%">Protocolo:</th>
                            <td><?= $pedido['protocolo'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Número do Processo:</th>
                            <td><?= $pedido['numero_processo'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Proponente:</th>
                            <td><?= $pedido['nome'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Local:</th>
                            <td><?= $pedido['local'] ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Status:</th>
                            <td><?= $pedido['status'] ?></td>
                        </tr>
                </table>
                </div>
            </div>
            <div class="box-footer">
                <form action="?perfil=emia&p=conclusao&sp=finaliza" role="form" method="post">
                    <input type="hidden" id="idPedido" value="<?=$idPedido?>" name="idPedido">
                    <button type="submit" id="atualizar" name="atualizar"  class="btn btn-success pull-right">Concluir Pedido</button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php
$con = bancoMysqli();

if(isset($_POST['carrega']) || isset($_POST['atualizar'])){
    $idPedido = $_POST['idPedido'];
}

$btns_footer = "<input type='hidden' name='idPedido' value='$idPedido'>
                <button class='btn btn-primary pull-right' type='submit' name='atualizar'>Concluir o pedido</button>

                <a href='?perfil=emia&p=conclusao&sp=pesquisa'>
                    <button type='button' class='btn btn-default'>Nova Pesquisa</button>
                </a>";

if(isset($_POST['carrega'])){
    $testa = $con->query("SELECT status_pedido_id FROM pedidos WHERE origem_tipo_id = 3 AND id = $idPedido")->fetch_array();
    if($testa['status_pedido_id'] == 21){
        $btns_footer = "<a href='?perfil=emia&p=conclusao&sp=pesquisa'>
                            <button type='button' class='btn btn-default btn-block center-block' style='width:35%'>Nova Pesquisa</button>
                        </a>";
        $mensagem = mensagem('warning', 'O pedido escolhido já está concluído!');
    }
}


if(isset($_POST['atualizar'])){
    $sql = "UPDATE pedidos SET status_pedido_id = 21 WHERE id = $idPedido";
    if(mysqli_query($con,$sql)){
        $mensagem = mensagem('success', 'Pedido concluído com sucesso!');
        $btns_footer = "<a href='?perfil=emia&p=conclusao&sp=pesquisa'>
                            <button type='button' class='btn btn-default btn-block center-block' style='width:35%'>Nova Pesquisa</button>
                        </a>";
    }else{
        $mensagem = mensagem('danger','Erro ao concluir processo!');
    }
}

$sql = "SELECT p.id, 
               ec.protocolo,
               p.numero_processo,
               pf.nome,
               l.local,
               s.status 
        FROM pedidos AS p 
        INNER JOIN emia_contratacao AS ec ON ec.id = p.origem_id
        INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
        INNER JOIN pedido_status AS s ON p.status_pedido_id = s.id
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
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-header">
                <div class="box-title">
                    Dados
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                <form action="?perfil=emia&p=conclusao&sp=finaliza" role="form" method="post">
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
                    <?=$btns_footer?>
                </form>
            </div>
        </div>
    </section>
</div>

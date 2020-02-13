<?php
$con = bancoMysqli();

if(isset($_POST['carregar']) || isset($_POST['conclui'])){
    $idPedido = $_POST['idPedido'];
}

$btns_footer = "<input type='hidden' name='idPedido' value='$idPedido'>
                <button class='btn btn-primary pull-right' type='submit' name='conclui'>Concluir o pedido</button>

                <a href='?perfil=formacao&p=conclusao&sp=pesquisa'>
                    <button type='button' class='btn btn-default'>Nova Pesquisa</button>
                </a>";

if(isset($_POST['carregar'])){
    $testa = $con->query("SELECT status_pedido_id FROM pedidos WHERE origem_tipo_id = 2 AND id = $idPedido")->fetch_array();
    if($testa['status_pedido_id'] == 21){
        $btns_footer = "<a href='?perfil=formacao&p=conclusao&sp=pesquisa'>
                            <button type='button' class='btn btn-default btn-block center-block' style='width:35%'>Nova Pesquisa</button>
                        </a>";
        $mensagem = mensagem('warning', 'O pedido escolhido já está concluído!');
    }
}

if(isset($_POST['conclui'])){
    $sql = "UPDATE pedidos SET status_pedido_id = 21 WHERE id = $idPedido AND origem_tipo_id = 2";
    if(mysqli_query($con,$sql)){
        $mensagem = mensagem("success", "Pedido concluído com sucesso!");
        $btns_footer = "<a href='?perfil=formacao&p=conclusao&sp=pesquisa'>
                            <button type='button' class='btn btn-default btn-block center-block' style='width:35%'>Nova Pesquisa</button>
                        </a>";
    }else{
        $mensagem = mensagem("danger", "Erro ao concluir o pedido");     
    }
}

$pedido = $con->query("SELECT p.id, p.numero_processo, 
                               pf.nome, s.status, c.protocolo
                        FROM pedidos AS p
                        INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
                        INNER JOIN pedido_status AS s ON p.status_pedido_id = s.id
                        INNER JOIN formacao_contratacoes AS c ON p.origem_id = c.id
                        WHERE p.publicado = 1 AND p.origem_tipo_id = 2 AND p.id = $idPedido")->fetch_array();

?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3 class="page-title">Formação - Conclusão</h3>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Concluir o pedido</h3>
            </div>
            <div class="row" align="center">
                <?php if (isset($mensagem)) {
                    echo $mensagem;
                }; ?>
            </div>
            <div class="box-body">
                <div class="col-md-12">
                <form action="?perfil=formacao&p=conclusao&sp=concluir" method="post">
                    <table class="table">
                        <tr>
                            <th width="30%">Numero do Processo: </th>
                            <td><?=$pedido['numero_processo']?></td>
                        </tr>
                        
                        <tr>
                            <th width="30%">Protocolo: </th>
                            <td><?=$pedido['protocolo']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Proponente: </th>
                            <td><?=$pedido['nome']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Status do Pedido: </th>
                            <td><?=$pedido['status']?></td>
                        </tr>
                    </table>
                </div>
                <div class="box-footer">
                    <?=$btns_footer?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
$idFormacao = $_POST['idFormacao'];
$con = bancoMysqli();


$sql = "SELECT 
    p.numero_processo,
    fc.protocolo,
    pf.nome,
    p.valor_total,
    fc.data_envio
    
    
    
    FROM pedidos as p
    INNER JOIN formacao_contratacoes fc on p.origem_id = fc.id
    INNER JOIN pessoa_fisicas pf on fc.pessoa_fisica_id = pf.id
    WHERE fc.publicado = 1 AND p.origem_tipo_id AND p.origem_id = $idFormacao";
    $formacao = $con->query($sql)->fetch_assoc();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhes do pedido selecionado</h3>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th width="30%">Protocolo:</th>
                        <td><?= $formacao['protocolo']?></td>
                    </tr>
                    <tr>
                        <th width="30%">Número do Processo:</th>
                        <td><?= $formacao['numero_processo']?></td>
                    </tr>
                    <tr>
                        <th width="30%">Contratado</th>
                        <td><?= $formacao['nome']?></td>
                    </tr>
                    <?php
                    $sqlLocal = "SELECT 
                    l.local
                    FROM formacao_locais as fl
                    INNER JOIN locais l on fl.local_id = l.id
                    WHERE l.publicado = 1 ";
                    $local = $con->query($sqlLocal)->fetch_assoc();
                    ?>

                    <tr>
                        <th width="30%">Local</th>
                        <td><?=$local['local']?></td>
                    </tr>
                    <tr>
                        <th width="30%">Contratado</th>
                        <td><?= $formacao['valor_total']?></td>
                    </tr>

                    <tr>
                        <th width="30%">Forma de pagamento:</th>
                        <td>Conforme item 4.4 do Edital. Entrega de Entrega de documentos a partir de 01/06/2017. O pagamento da 1ª parcela se dará a partir do primeiro dia útil subsequente à comprovação da execução dos 2 primeiros meses de projeto. Entrega de Entrega de documentos a partir de 01/07/2017. O pagamento da 2ª parcela se dará a partir do primeiro dia útil subsequente à comprovação da execução dos terceiro e quarto meses de projeto. Entrega de Entrega de documentos a partir de 01/08/2017. O pagamento da 3ª parcela se dará a partir do primeiro dia útil subsequente à comprovação do encerramento do projeto.</td>
                    </tr>

                    <tr>
                        <th width="30%">Amparo:</th>
                        <td> I – À vista dos elementos constantes do presente, em especial o Parecer da Comissão de Atividades Artísticas e Culturais n° , diante da competência a mim delegada pela Portaria nº 17/2018-SMC/G, AUTORIZO, com fundamento no artigo 25, inciso III, da Lei Federal nº 8.666/93, a contratação nas condições abaixo estipuladas, observada a legislação vigente e demais cautelas legais:</td>
                    </tr>
                </table>
            </div>

        </div>
</div>
</section>
</div>
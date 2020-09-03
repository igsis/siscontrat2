<?php
$con = bancoMysqli();
$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2";


if (isset($_POST['tipoModelo'])) {
    $modelo = $_POST['tipoModelo'];
    $dotacao = "";
}
if(isset($_POST['idFormacao'])){
    $idFormacao = $_POST['idFormacao'];
}
if(isset($_POST['voltar'])){
    $dotacao = $_POST['dotacao'];
}



$sqlModelo = "SELECT * FROM modelo_juridicos where id = $modelo";
$mdl = $con->query($sqlModelo)->fetch_assoc();


$sql = "
SELECT p.numero_processo,fc.protocolo,pf.nome,p.origem_id,p.forma_pagamento,p.valor_total from pedidos AS p
INNER JOIN formacao_contratacoes AS fc ON p.origem_id = fc.id
INNER JOIN pessoa_fisicas AS pf ON p.pessoa_fisica_id = pf.id
WHERE fc.id = '$idFormacao' AND p.origem_tipo_id = 2";
$query = $con->query($sql)->fetch_assoc();

// pegar periodo da formação ( atraves do id da vigencia )
$fc = recuperaDados('formacao_contratacoes', 'id', $query['origem_id']);
$periodo = retornaPeriodoFormacao($fc['form_vigencia_id']);
// pegar o local
$sqlLocal = "SELECT l.local FROM formacao_locais fl 
INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFormacao'";
/// insere o nome fiscal no texto que apresenta na finalização
///
$usuarios = recuperaDados('usuarios','id',$idFormacao);
$fiscal= $usuarios["nome_completo"];
$suplente= $usuarios["nome_completo"];
$rfSuplente= $usuarios["rf_rg"];
$rfFiscal= $usuarios["rf_rg"];

$mdl = str_replace("nomeFiscal", $fiscal, $mdl);
$mdl = str_replace("rfFiscal", $rfFiscal, $mdl);
$mdl = str_replace("nomeSuplente", $suplente, $mdl);
$mdl = str_replace("rfSuplente", $rfSuplente, $mdl);

$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Jurídico</h2>
        </div>
            <form action="?perfil=juridico&p=filtrar_formacao&sp=detalhe_formacao" role="form" method="post">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Detalhes da formação selecionada</h3>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Protocolo:</th>
                            <td><?= $query['protocolo'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Número do Processo:</th>
                            <td><?= $query['numero_processo'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Contratado:</th>
                            <td><?= $query['nome'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Local:</th>
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
                            <th width="30%">Valor:</th>
                            <td><?= $query['valor_total'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Período:</th>
                            <td><?= $periodo ?></td>

                        </tr>
                        <tr>
                            <th width="30%">Forma de pagamento:</th>
                            <td><?= $query['forma_pagamento'] ?></td>
                        </tr>
                        <tr>
                            <th width="30%">Amparo:</th>
                            <td><textarea name="amparo" rows="6"
                                          cols="85" class="form-control"><?= $mdl['amparo'] ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="30%">Dotação Orçamentária</th>
                            <td><textarea name="dotacao" rows="1"
                                          cols="85" class="form-control"><?$dotacao?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th width="30%">Finalização:</th>
                            <td><textarea name="finalizacao" rows="8"
                                          cols="85" class="form-control"><?= $mdl['finalizacao'] ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="idFormacao" value="<?= $idFormacao ?>">
                    <input type="hidden" name="tipoModelo" value="<?= $modelo ?>">
                    <button type="submit" name="detalhe" class="btn btn-info pull-left">Detalhe Formação
                    </button>
        </form>
        <form action="?perfil=juridico&p=filtrar_formacao&sp=modelo_final_formacao" method="post">
            <input type="hidden" name="idFormacao" value="<?= $idFormacao ?>">
            <input type="hidden" name="dotacao" value="<?= $dotacao ?>">
            <button type="submit" name="enviar" class="btn btn-info pull-right">Gravar
            </button>
        </form>
</div>
</div>

</section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEvento').DataTable({
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
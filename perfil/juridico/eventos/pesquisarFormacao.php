<?php
$con = bancoMysqli();
$codigopedido = "";
$numprocesso = "";
$statuspedido = "";

if (isset($_POST['codigopedido']) && $_POST['codigopedido'] != null) {
    $codigopedido = $_POST['codigopedido'];
    $codigopedido = " AND protocolo='$codigopedido'";
}
if (isset($_POST['numprocesso']) && $_POST['numprocesso'] != null) {
    $numprocesso = $_POST['numprocesso'];
    $numprocesso = "AND numero_processo='$numprocesso'";
}
if (isset($_POST['statuspedido']) && $_POST['statuspedido'] != null) {
    $statuspedido = $_POST['statuspedido'];
    $statuspedido = "AND formacao_status =$statuspedido'";
}


$sql = "SELECT p.numero_processo,
            fc.protocolo, 
            pf.nome, 
            fs.status,
            fc.id
            

        FROM pedidos as p INNER JOIN formacao_status fs on p.id = fs.id 
        INNER JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id 
        INNER JOIN formacao_contratacoes fc on p.origem_id = fc.id 
        WHERE p.publicado = 1 AND p.origem_tipo_id = 2 AND fc.publicado = 1 $numprocesso $codigopedido $statuspedido";


?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3>Buscar por Formação</h3>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Resultado da pesquisa</h3>
            </div>
            <div class="box-body">
                <table id="tblEventos" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Processo</th>
                        <th>Protocolo</th>
                        <th>Proponente</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($query = mysqli_query($con,$sql)){
                        while ($formacao = mysqli_fetch_array($query)){
                            $_SESSION['formacaoId'] = $formacao['id'];
                            ?>
                            <tr>
                                <?php
                                if(isset($formacao['numero_processo'])){
                                    ?>
                                    <td>
                                        <form action="?perfil=juridico&p=tipo_modelo&sp=seleciona_modelo" role="form"  method="POST">
                                            <button type="submit" class="btn btn-primary"><?= $formacao['numero_processo'] ?></button>
                                        </form>
                                    </td>
                                    <?php
                                } else {
                                    echo "<td> Não possui </td>";
                                }
                                ?>
                                <td><?=$formacao['protocolo']?></td>
                                <td><?=$formacao['nome']?></td>
                                <td><?=$formacao['status']?></td>

                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <a href="?perfil=juridico&p=filtrar_formacao&sp=pesquisa_formacao">
                    <button type="button" class="btn btn-default">Voltar a pesquisa</button>
                </a>
            </div>
        </div>
    </section>
</div>
<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblEventos').DataTable({
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
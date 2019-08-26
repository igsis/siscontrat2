<?php
$con = bancoMysqli();
$sqlPedidos = "SELECT
       c.id AS 'id',
       c.protocolo AS 'protocolo',
		pf.nome AS 'pessoa',
		c.ano AS 'ano',
        p.programa AS 'programa',
        l.linguagem AS 'linguagem',
		fc.cargo AS 'cargo'
        FROM formacao_contratacoes AS c
        INNER JOIN pessoa_fisicas AS pf ON pf.id = c.pessoa_fisica_id
        INNER JOIN programas AS p ON p.id = c.programa_id
        INNER JOIN linguagens AS l ON l.id = c.linguagem_id
        INNER JOIN formacao_cargos AS fc ON fc.id = c.form_cargo_id
        WHERE c.publicado = 1";
$queryPedidos = mysqli_query($con,$sqlPedidos);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Lista de Pedidos de Contratação</h2>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Listagem</h3>
            </div>
            <div class="box-body">
                    <table id="tblPedidos" class="table table-striped table-responsive">
                        <thead>
                        <tr>
                           <th> Protocolo </th>
                           <th> Pessoa </th>
                           <th> Ano </th>
                           <th> Programa </th>
                           <th> Linguagem </th>
                           <th> Cargo </th>
                           <th> Visualizar </th>
                        </tr>
                        </thead>
                        <?php
                            echo"<tbody>";
                            while($pedidos = mysqli_fetch_array($queryPedidos)){
                                echo"<td>" . $pedidos['protocolo'] . "</td>";
                                echo"<td>" . $pedidos['pessoa'] . "</td>";
                                echo"<td>" . $pedidos['ano'] . "</td>";
                                echo"<td>" . $pedidos['programa'] . "</td>";
                                echo"<td>" . $pedidos['linguagem'] . "</td>";
                                echo"<td>" . $pedidos['cargo'] . "</td>";
                                echo"<td>
                                
                            <form method='POST' action='?perfil=formacao&p=pedido_contratacao&sp=detalhes' role='form'>
                            <input type='hidden' name='idPC' value='" . $pedidos['id'] . "'>
                            <button type='submit' name='carregar' class='btn btn-block btn-primary'><span class='glyphicon glyphicon-eye-open'> </span></button>
                            </form>
                                </td>";
                                echo "</tbody>";
                            }?>
                            <tfoot>
                                <tr>
                                    <th> Protocolo </th>
                                    <th> Pessoa </th>
                                    <th> Ano </th>
                                    <th> Programa </th>
                                    <th> Linguagem </th>
                                    <th> Cargo </th>
                                    <th> Visualizar </th>
                                </tr>
                            </tfoot>
                    </table>
                <div class="box-footer">
                    <a href="?perfil=formacao">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <a href="?perfil=formacao&p=pedido_contratacao&sp=cadastro">
                        <button type="button" class="btn btn-primary pull-right"> Cadastrar </button>
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
        $('#tblPedidos').DataTable({
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
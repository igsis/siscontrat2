<?php
    unset($_SESSION['idEvento']);
    unset($_SESSION['idPj']);
    unset($_SESSION['idPf']);
    
    $con = bancoMysqli();
    $conn = bancoPDO();
    
    if(isset($_POST['excluir'])){
        $evento = $_POST['idEvent'];
        $stmt = $conn->prepare("UPDATE agendoes SET publicado = 0 WHERE id =:id");
        $stmt->execute(['id' => $evento]);
        $mensagem = mensagem("success", "Evento excluido com sucesso!");
    }
    
    $idUser = $_SESSION['idUser'];
    $sql = "SELECT ev.id AS idEvento, ev.nome_evento FROM agendoes AS ev
            WHERE publicado = 1 AND usuario_id = '$idUser' AND evento_status_id = 1";
    
    $query = mysqli_query($con, $sql);
    

?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
        <div class="row" align="center">
                <?php if (isset($mensagem)) {
                        echo $mensagem;
                       } ?>
            </div>
            <div class="box-header">
                <h4>Lista de Agendões</h4>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="tblAgendoes">
                    <thead>
                        <tr>
                            <th>Nome do Evento</th>
                            <th>Local</th>
                            <th>Período</th>
                            <th width="10%">Editar</th>
                            <th width="10%">Apagar</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php while($evento = mysqli_fetch_array($query)) { 
                                $sqlLocal = "SELECT l.local FROM locais l INNER JOIN agendao_ocorrencias ao ON ao.local_id = l.id WHERE ao.origem_ocorrencia_id = ". $evento['idEvento']. " AND ao.publicado = 1";
                                $queryLocal = mysqli_query($con, $sqlLocal);
                                $local = '';
                                while ($locais = mysqli_fetch_array($queryLocal)) {
                                    $local = $local . '; ' . $locais['local'];
                                }
                                $local = substr($local, 1);
                                ?>
                                <tr>
                                    <td><?=$evento['nome_evento']?></td>
                                    <td><?=$local?></td>
                                    <td><?=retornaPeriodoNovo($evento['idEvento'], 'agendao_ocorrencias')?></td>
                                    <td>
                                        <form action="?perfil=agendao&p=evento_edita" method="POST">
                                            <input type="hidden" name="idEvento" value="<?=$evento['idEvento']?>">
                                            <button type="submit" name="carregar" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-eye-open"></span></button>
                                        </form>
                                    </td>
                                    
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" name="idEvento" value="<?=$evento['idEvento']?>">
                                            <button type="button" name="carregar" class="btn btn-danger btn-block"
                                            id="excluiAgendao" data-toggle="modal" data-target="#exclusao" name="excluiAgendao"
                                            data-id="<?= $evento['idEvento'] ?>" data-name="<?=$evento['nome_evento']?>"><span class="glyphicon glyphicon-trash"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nome do Evento</th>
                            <th>Local</th>
                            <th>Período</th>
                            <th width="10%">Editar</th>
                            <th width="10%">Apagar</th>
                        </tr>    
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
            <div class="modal-dialog">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Confirmação de Exclusão</h4>
                </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este evento?</p>
                        </div>
                        <div class="modal-footer">
                            <form action="?perfil=agendao&p=listagem" method="post">
                                <input type="hidden" name="idEvent" id="idEvent" value="">
                                <input type="hidden" name="apagar" id="apagar">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                            </form>
                </div>
            </div>
        </div>
    </div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAgendoes').DataTable({
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

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal',function (e){
        let evento = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o evento ${evento}?`);
        $(this).find('#idEvent').attr('value',`${id}`);
    })
</script>
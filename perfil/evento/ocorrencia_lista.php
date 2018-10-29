<?php
include "includes/menu_interno.php";

$con = bancoMysqli();

$evento = recuperaDados('eventos', 'id', $_SESSION['idEvento']);

$tipo_ocorrencia_id = $evento['tipo_evento_id'];

$idOrigem = $_POST['idOrigem'];

$sql = "SELECT o.id, o.origem_ocorrencia_id, l.local, o.data_inicio, o.horario_inicio, o.horario_fim FROM ocorrencias as o
        INNER JOIN  locais as l ON o.local_id = l.id
        WHERE o.origem_ocorrencia_id = '$idOrigem' AND o.tipo_ocorrencia_id = '$tipo_ocorrencia_id'";

$query = mysqli_query($con,$sql);
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Ocorrências</h2>
        <div class="row">
            <div class="col-md-2">
                <form method="POST" action="?perfil=evento&p=ocorrencia_cadastro">
                    <input type="hidden" name="idOrigem" value="<?= $idOrigem ?>">
                    <button type="submit" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Adiciona</button>
                </form>
            </div>

        </div>
        <br/>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>

                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Data início</th>
                                <th>Horario início</th>
                                <th>Horario final</th>
                                <th>Local</th>
                                <th colspan="3" width="10%">Ação</th>
                            </tr>
                            </thead>

                            <?php

                            echo "<tbody>";
                            while ($ocorrencia = mysqli_fetch_array($query)){

                                echo "<tr>";
                                echo "<td>".exibirDataBr($ocorrencia['data_inicio'])."</td>";
                                echo "<td>".exibirHora($ocorrencia['horario_inicio'])."</td>";
                                echo "<td>".exibirHora($ocorrencia['horario_fim'])."</td>";
                                echo "<td>".$ocorrencia['local']."</td>";

                                echo "<td>
                                    <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_edita\" role=\"form\">
                                    <input type='hidden' name='idOcorrencia' value='".$ocorrencia['id']."'>
                                    <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\">Carregar</button>
                                    </form>
                                </td>";
                                
                                echo "<td>
                                    <input type='hidden' name='idOcorrencia'>
                                    <buttonn class='btn btn-info' data-toggle='modal' data-target='#duplica' data-ocorrencia-id='".$ocorrencia['id']."' data-tittle='Duplicando ocorrência' data-message='Digite o número de vezes que deseja duplicar a ocorrência: '>Duplicar</buttonn>
                                </td>";

                                echo "<td>
                                    <button type=\"button\" class=\"btn btn-block btn-danger\">Apagar</button>
                                  </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Data início</th>
                                <th>Horario início</th>
                                <th>Horario final</th>
                                <th>Local</th>
                                <th colspan="3" width="10%">Ação</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
            </div>
            </div>
        </div>
    </section>
</div>

<!-- Duplicando o correncias -->
<div class="modal fade" id="duplica" role="dialog" aria-labelledby="duplicar" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='cancelarProjeto' action="?perfil=smc_detalhes_projeto" class="form-horizontal" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><p> Duplicando ocorrência</p></h4>
                </div>
                <div class="modal-body">
                    <p>Digite o número de vezes que deseja duplicar a ocorrência: </p>
                    <input type="number" min="1" max="10" name="numeroDuplica" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <input type='hidden' name='idProjeto' value='<?php echo "sasd" ?>'>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type='submit' class='btn btn-info btn-sm' style="border-radius: 10px;" name="duplicar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#duplicar').on('show.bs.modal', function (e)
    {
        let id = $(e.relatedTarget).attr('data-ocorrencia-id');
        console.log(id);
    });

</script>

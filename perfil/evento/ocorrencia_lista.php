<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$conn = bancoPDO();

if(isset($_POST['apagar'])){

    $idOcorrencia = $_POST['idOcorrenciaApaga'];

    $sql ="UPDATE siscontrat.ocorrencias SET publicado = 0 WHERE id = '$idOcorrencia'";

    if (mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Ocorrência apagada com sucesso");
    }else{
        $mensagem = mensagem("danger","Erro ao apagar a ocorrência. Tente novamente!");
    }

}

if(isset($_POST['duplicar'])){
    $idProjeto = $_POST['idProjeto'];
    $numeroDuplica = $_POST['numeroDuplica'];
    
    $sqlProjeto = "SELECT * FROM siscontrat.ocorrencias WHERE id = :id";

    $stmt = $conn->prepare($sqlProjeto);
    $stmt->bindValue(':id', $idProjeto);
    $stmt->execute();
    $cloneOcorrencia = $stmt->fetch();

    array_shift($cloneOcorrencia);
    $inserir = "INSERT INTO siscontrat.ocorrencias 
            (" . implode(',', array_keys($cloneOcorrencia)). ") VALUES 
            (" . sprintf( "'%s'", implode( "','", $cloneOcorrencia )).")";

    for ($i=0; $i < $numeroDuplica; $i++) { 
        if($conn->exec($inserir)){
            $sucesso = true;
        }else{
            $sucesso = false;
        }
    }

    if($sucesso){
        $mensagem = mensagem("success","$numeroDuplica Ocorrência(s) replicada(s) com sucesso!");
    
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
    }

}

$evento = recuperaDados('eventos', 'id', $_SESSION['idEvento']);

$tipo_ocorrencia_id = $evento['tipo_evento_id'];

$idOrigem = $_POST['idOrigem'] ?? $_POST['idOrigemModal'];

$sql = "SELECT o.id, o.origem_ocorrencia_id, l.local, o.data_inicio, o.horario_inicio, o.horario_fim 
        FROM ocorrencias as o
        INNER JOIN  locais as l ON o.local_id = l.id
        WHERE o.origem_ocorrencia_id = '$idOrigem' AND o.tipo_ocorrencia_id = '$tipo_ocorrencia_id' AND o.publicado = 1";

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
                    <div class="row" align="center">
                        <?php if(isset($mensagem)){echo $mensagem;};?>
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
                                    <buttonn class='btn btn-info' data-toggle='modal' data-target='#duplicar' data-ocorrencia-id='".$ocorrencia['id']."' data-tittle='Replicando ocorrência' data-message='Digite o número de vezes que deseja replicar a ocorrência: '>Replicar</buttonn>
                                </td>";

                                echo "<td>
                                    <button class='btn btn-block btn-danger' data-toggle='modal' data-target='#apagar' data-id='".$ocorrencia['id']."' data-tittle='Apagar ocorrência' data-message='Deseja mesmo pagar está ocorrências' onClick='setarIdOcorrencia(".$ocorrencia['id'].")'>Apagar</button>
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

<!-- Duplicando ocorrencias -->
<div class="modal fade" id="duplicar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formDuplicar' action="?perfil=evento&p=ocorrencia_lista" class="form-horizontal" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Replicando ocorrência</h4>
                </div>
                <div class="modal-body">
                    <p>Digite o número de vezes que deseja replicar a ocorrência: </p>
                    <input type="number" min="1" max="10" name="numeroDuplica" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <input type='hidden' name='idProjeto'> <!-- vem pelo js -->
                    <input type='hidden' name='idOrigemModal' value="<?=$idOrigem?>">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type='submit' class='btn btn-info btn-sm' name="duplicar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Apagar ocorrência-->
<div class="modal fade" id="apagar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formApagar' action="?perfil=evento&p=ocorrencia_lista" class="form-horizontal" role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Apagar ocorrência</h4>
                </div>
                <div class="modal-body">
                    <p>Deseja mesmo apagar esta ocorrência?</p>
                </div>
                <div class="modal-footer">
                        <input type="hidden" name="idOcorrenciaApaga">
                        <input type='hidden' name='idOrigemModal' value="<?=$idOrigem?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type='submit' class='btn btn-info btn-sm' name="apagar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#duplicar').on('show.bs.modal', (e) =>
    {
        document.querySelector('#formDuplicar input[name="idProjeto"]').value = e.relatedTarget.dataset.ocorrenciaId

    });

    function setarIdOcorrencia(valor) {
        document.querySelector('#formApagar input[name="idOcorrenciaApaga"]').value = valor;
    }


</script>

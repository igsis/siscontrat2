<?php
/**
 * Conteúdo da label "#produtor" do arquivo "finalizar.php"
 */

$idEvento = $_SESSION['idEvento'];
$agendao = $con->query("SELECT produtor_id FROM agendoes WHERE id  = $idEvento")->fetch_array();
$idProdutor = $agendao['produtor_id'];
if($idProdutor != NULL){
    $produtor = $con->query("SELECT * FROM produtores WHERE id = $idProdutor")->fetch_array();
}
?>

<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionOcorrencia">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        Produtor
                    </h4>
                </div>
                <div id="produtor" class="panel-collapse collapse in">
                    <div class="box-body">
                        <?php
                        if($idProdutor != NULL) {
                            ?>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Nome do produtor:</label> <?= $produtor['nome'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Email:</label> <?= $produtor['email'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Telefone:</label> <?= $produtor['telefone1'] . " " . $produtor['telefone1'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Observador:</label> <?= $produtor['observacao'] ?>
                                </div>
                            </div>
                            <?php
                        }
                        else{
                        ?>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="row text-center bg-danger">Não há produtor inserido.</div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
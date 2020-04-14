<?php

/**
 * Conteúdo da label "#produtor" do arquivo "producao/agendao/verificados.php"
 */

$idEvento = $_SESSION['idEvento'];
$agendao = $con->query("SELECT produtor_id FROM agendoes WHERE id = '$idEvento'")->fetch_array();
$idProdutor = $agendao['produtor_id'];
if ($idProdutor != null) {
    $produtor = $con->query("SELECT * FROM produtores WHERE id = '$idProdutor'")->fetch_array();
}
?>
<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionOcorrencia">
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title"> Produtor </h4>
                </div>
                <div class="panel-collapse collapse in" id="produtor">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">
                                <?php
                                if ($idProdutor != null) {
                                    ?>
                                    <tr>
                                        <th width="30%">Nome do produtor:</th>
                                        <td><?= $produtor['nome'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Email:</th>
                                        <td><?= $produtor['email'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Telefone(s):</th>
                                        <td><?= $produtor['telefone1'] . " | " . $produtor['telefone2'] ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Observação:</th>
                                        <td><?= $produtor['observacao'] == null ? "Não cadastrado" : $produtor['observacao'] ?></td>
                                    </tr>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <div class="row text-center bg-danger">Não há produtor inserido.</div>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

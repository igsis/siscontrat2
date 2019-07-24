<?php
/**
 * Conteúdo da label "#atracao" do arquivo "finalizar.php"
 */

?>

<div class="box box-solid">
    <div class="box-body">
        <div class="box-group" id="accordionAtracao">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <?php foreach ($filmes as $filme) {
            ?>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordionAtracao" href="#collapse<?= $filme['id'] ?>">
                                Resumo do Filme: <?= $filme['titulo'] ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?= $filme['id'] ?>" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th width="30%">Nome do Filme:</th>
                                        <td><?=$filme['titulo']?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Ano de Produção:</th>
                                        <td><?=$filme['ano_producao']?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Gênero:</th>
                                        <td><?=$filme['genero']?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Sinopse:</th>
                                        <td><?=$filme['sinopse']?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Duração:</th>
                                        <td><?=$filme['duracao']?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- /.box-body -->
</div>

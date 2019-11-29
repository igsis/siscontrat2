<?php

include "includes/menu.php";

$con = bancoMysqli();

$sql = "SELECT e.id, e.protocolo, e.nome_evento,  er.data_envio, e.usuario_id
        FROM eventos e INNER JOIN evento_envios ee ON e.id = ee.evento_id INNER JOIN evento_reaberturas er on e.id = er.eventos_id
        WHERE er.data_envio > ee.data_envio and e.publicado = 1";

?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h3 class="box-title">Lista </h3>
        <div class="row" align="center">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
<!--                        <h3 class="box-title">Listagem</h3-->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Proponente</th>
                                    <th>Nome do evento</th>
                                    <th>Valor</th>
                                    <th>Local</th>
                                    <th>Período</th>
                                    <th>Data reabertura</th>
                                    <th>Reaberto por</th>
                                    <th>StatusPrazo (Dias)</th>
                                    <th>Operador</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th colspan="10"><p align="center">Não foram encontrados registros</p></th>
                                </tr>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Proponente</th>
                                    <th>Nome do evento</th>
                                    <th>Valor</th>
                                    <th>Local</th>
                                    <th>Período</th>
                                    <th>Data reabertura</th>
                                    <th>Reaberto por</th>
                                    <th>StatusPrazo (Dias)</th>
                                    <th>Operador</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="?perfil=contrato">
                            <button type="button" class="btn btn-default">Voltar</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



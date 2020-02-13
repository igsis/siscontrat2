<?php

$server = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2"; //mudar para pasta do igsis
$http = $server . "/pdf/";

$link_rlt = $http . "exporta_excel_curadoria.php";

$link_rlt_word = $http . "exporta_word_curadoria.php";

?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="page-header"> Área de Impressão </h3>
        <div class="box">
            <div class="box-header">
                <h4 align="center">
                    Qual modelo de documento deseja imprimir?
                </h4>
            </div>
            <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="<?= $link_rlt ?>" target="_blank" method="post">
                                <button type="submit" class="btn btn-primary btn-block center-block" style="width:35%">
                                <span class="glyphicon glyphicon-list-alt">
                                </span>
                                    Excel
                                </button>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <a href="<?= $link_rlt_word ?>" target="_blank">
                                <button type="button" class="btn btn-primary btn-block center-block" style="width:35%">
                                Word
                                <span class="glyphicon glyphicon-paperclip"></span>
                                </button>
                            </a>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=curadoria&p=index">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
            </div>
        </div>
    </section>
</div>


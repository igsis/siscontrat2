<?php
include "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="box">
            <ul class="nav nav-tabs">
                <li class="nav active"><a href="#carregar" data-toggle="tab">Carregar evento gravado</a></li>
                <li class="nav"><a href="#acompanhar" data-toggle="tab">Acompanhar evento enviado</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="carregar">
                    <?php
                    include "evento_lista.php"; 
                    ?>
                </div>

                <div class="tab-pane" id="acompanhar">
                    <?php
                    include "lista_eventos_enviados.php";
                    ?>
                </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=agendao">
                    <button type="button" class="btn btn-default" id="voltar" name="voltar">Voltar</button>
                </a>
            </div>
        </div>
    </section>
</div>

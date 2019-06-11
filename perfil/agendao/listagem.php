<?php
include "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content-header">
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

            <div class="tab-pane fade in active" id="acompanhar">
                <?php
                    include "lista_eventos_enviados.php";
                ?>
            </div>
        </div>

    </section>
</div>

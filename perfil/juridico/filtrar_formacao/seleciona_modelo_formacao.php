<?php
isset($_POST['idFormacao']);
    $idFormacao = $_POST['idFormacao'];

?>
<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center">Escolha um modelo</h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <form action="?perfil=juridico&p=filtrar_formacao&sp=resumo_formacao" method="POST">
                            <button name="mdlVoca" type="submit" class="btn btn-primary btn-lg btn-block">VOCACIONAL
                            </button>
                            <input type="hidden" value="1" name="tipoModelo">
                            <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                        </form>
                        <form action="?perfil=juridico&p=filtrar_formacao&sp=resumo_formacao" method="POST">
                            <button name="mdlPia" type="submit" class="btn btn-primary btn-lg btn-block">PIÁ</button>
                            <input type="hidden" value="2" name="tipoModelo">
                            <input type="hidden" value="<?= $idFormacao ?>" name="idFormacao">
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </section>

</div>

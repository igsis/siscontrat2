<?php


isset($_POST['idEvento']);
$idEvento = $_POST['idEvento'];

?>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <h2 align="center">Escolha um modelo</h2>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-offset-4 col-md-4" align="center">
                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST" target="_blank">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">PADRÃO
                        </button>
                        <input type="hidden" value="1" name="tipoModelo">
                        <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                        </form>

                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST" target="_blank">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">VOCACIONAL
                            </button>
                            <input type="hidden" value="2" name="tipoModelo">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                        </form>

                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST" target="_blank">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">PIÁ</button>
                            <input type="hidden" value="3" name="tipoModelo">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                        </form>

                        <form action="?perfil=juridico&p=tipo_modelo&sp=resultado" method="POST" target="_blank">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Oficinas
                            </button>
                            <input type="hidden" value="4" name="tipoModelo">
                            <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>
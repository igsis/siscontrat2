<?php
if(isset($_POST['reabertura'])){
    $con = bancoMysqli();
    $idEvento = $_SESSION['idEvento'];
    $now = date('Y-m-d H:i:s',strtotime("-3 Hours"));
    $idUsuario = $_SESSION['idUser'];
    $sql = "INSERT INTO evento_reaberturas (evento_id, data_reabertura, usuario_reabertura_id) VALUES ('$idEvento', '$now', '$idUsuario')";

    if (mysqli_query($con, $sql)) 
        $mensagem = mensagem("success", "Reabertura do evento realizada com sucesso!");
    else 
        $mensagem = mensagem("danger", "Erro ao efetuar a reabertura do evento! Tente novamente.");
}

unset($_SESSION['idEvento']);
unset($_SESSION['idPedido']);
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Busca</h2>
        <div class="row" align="center">
            <?php if (isset($mensagem)) {
                echo $mensagem;
            }; ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Busca de contratos</h3>
                    </div>
                    <form method="POST" action="?perfil=contrato&p=filtrar_contratos&sp=resultado"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="protocolo">Protocolo</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="num_processo">Número de processo</label>
                                    <input type="text" class="form-control" name="num_processo" id="num_processo">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="evento">Nome do evento</label>
                                    <input type="text" class="form-control" name="evento" id="evento">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="projeto">Projeto especial</label>
                                    <select name="projeto" id="projeto" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('projeto_especiais');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="usuario">Fiscal, suplente ou usuário que cadastrou o evento</label>
                                    <select name="usuario" id="usuario" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('usuarios');
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="status">Pedido status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('pedido_status');
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="busca" id="busca" class="btn btn-primary pull-right">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

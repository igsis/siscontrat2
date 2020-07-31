<script src="../visual/plugins/sweetalert2/sweetalert2.min.js"></script>
<link rel="stylesheet" href="../visual/plugins/sweetalert2/sweetalert2.css">
<?php
if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}

if (isset($_POST['reabertura'])) {
    $now = date('Y-m-d H:i:s', strtotime("-3 Hours"));
    $idUsuario = $_SESSION['usuario_id_s'];
    $sql = "INSERT INTO evento_reaberturas (evento_id, data_reabertura, usuario_reabertura_id) VALUES ('$idEvento', '$now', '$idUsuario')";
    $sqlStatus = "UPDATE eventos SET evento_status_id = 1 WHERE id = '$idEvento'";

    //post para o chamado
    $tipoChamado = $_POST['chamado'];
    $nomeEvento = $_POST['nome_evento'];
    $titulo = "Reabertura do Evento: " . $nomeEvento;
    $justificativa = trim(addslashes($_POST['justificativa']));

    if ((mysqli_query($con, $sql)) && (mysqli_query($con, $sqlStatus))) {
       $insertChamado = $con->query("INSERT INTO chamados (evento_id, chamado_tipo_id, titulo, justificativa, usuario_id, data) VALUES
                                                        ('$idEvento', '$tipoChamado', '$titulo', '$justificativa', '$idUsuario', '$now')");

        $mensagem = "<script>
                    Swal.fire({
                        title: 'Reabertura',
                        html: 'Reabertura do evento realizada com sucesso!',
                        type: 'success',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCancelButton: false,
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location.href = 'index.php';
                    });
                </script>";
        echo "A";
        gravarLog($sql);
        unset($_SESSION['idEvento']);
        unset($_SESSION['idPedido']);
    } else {
        $mensagem = mensagem("danger", "Erro ao efetuar a reabertura do evento! Tente novamente.");
    }
}

$dadosEvento = $con->query("SELECT e.nome_evento, p.numero_processo FROM eventos AS e INNER JOIN pedidos AS p ON p.origem_id = e.id WHERE e.id = $idEvento AND p.origem_tipo_id = 1 AND p.publicado = 1 AND e.publicado = 1")->fetch_array();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h3 class="page-title">Reabrir Evento e Pedido</h3>
        </div>
        <div class="box">
            <div>
                <div class="row" align="center">
                    <?php if (isset($mensagem)) {
                        echo $mensagem;
                    }; ?>
                </div>
            </div>
            <div class="box-header with-border">
                <h4 class="box-title">Dados</h4>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="?perfil=contrato&p=reabertura" method="POST" role="form">
                        <div class="col-md-6">
                            <label for="nome_evento">Nome do Evento:</label>
                            <input type="text" class="form-control" name="nome_evento" readonly
                                   value="<?= $dadosEvento['nome_evento'] ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="numProcesso">Número do Processo:</label>
                            <input type="text" class="form-control" name="numProcesso" readonly
                                   value="<?= $dadosEvento['numero_processo'] ?? NULL ?>">
                        </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label for="chamado">Tipo de Chamado: *</label>
                        <select name="chamado" class="form-control" required>
                            <option value="">Selecione uma opção...</option>
                            <?php
                            $sql = "SELECT * FROM chamado_tipos WHERE id IN (7, 8, 9, 10, 5, 11, 12, 13)";
                            $query = mysqli_query($con, $sql);
                            while ($chamado = mysqli_fetch_array($query)) { ?>
                                <option value="<?= $chamado['id'] ?>"><?= $chamado['tipo'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label for="justificariva">Justificativa: *</label>
                        <textarea class="form-control" required placeholder="Informe aqui o que será alterado" name="justificativa" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <input type="hidden" name="idEvento" value="<?= $idEvento ?>">
                <button type="submit" name="reabertura" class="btn btn-primary pull-right">Reabrir</button>
                </form>
                <form action="?perfil=contrato&p=resumo" method="post">
                    <input type="hidden" name="idEvento" value="<?=$idEvento?>">
                    <button type="submit" class="btn btn-default" name="load">Voltar</button>
                </form>
            </div>
        </div>
    </section>
</div>

<?php
include "includes/menu.php";
include "includes/funcoesAuxiliares1.php";

$con = bancoMysqli();
$conn = bancoPDO();
$tipo = '';



if (isset($_POST['salvar'])) {
    $tipo = $_POST['tipo'];
    $idEvento = $_POST['id'];
    $projetoEspecial = $_POST['projetoEspecial'];
    $sinopse = $_POST['sinopse'];
    $editado = isset($_POST['Editado']) ? $_POST['Editado'] : NULL ;
    $revisado = isset($_POST['Revisado']) ? $_POST['Revisado'] : NULL;
    $site = isset($_POST['Site']) ? $_POST['Site'] : NULL;
    $impresso = isset($_POST['Impresso']) ? $_POST['Impresso'] : NULL;
    $foto = isset($_POST['Foto']) ? $_POST['Foto'] : NULL;
    $lista = array($editado,$revisado ,$site ,$impresso,$foto);
    switch ($tipo) {
        case 1:
            $saveEvento = "UPDATE eventos SET projeto_especial_id = '$projetoEspecial', sinopse = '$sinopse' WHERE id = '$idEvento'";
            if (mysqli_query($con, $saveEvento)) {
                 $valida = gravaStatus($lista, 'comunicacoes', $idEvento);
            } else {
                $valida = false;
            }
            break;
        case 2:
            $saveAgendao = "UPDATE agendoes SET projeto_especial_id = '$projetoEspecial', sinopse = '$sinopse' WHERE id = '$idEvento'";
            if (mysqli_query($con, $saveAgendao)) {
                $valida = gravaStatus($lista, 'comunicacao_agendoes', $idEvento);
            } else {
                $valida = false;
            }
            break;

    }

    if ($valida){
        $mensagem = mensagem("success","Gravado com sucesso!");
    }else{
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }
}

if (isset($_POST['evento']) || $tipo == 1) {
    if (isset($_POST['evento'])) {
        $idEvento = $_POST['evento'];
    }
    $tipo = '1';
// Evento
    $sqlEvento = "SELECT * FROM eventos
              WHERE id = '{$idEvento}' AND publicado = 1";
    $query = mysqli_query($con, $sqlEvento);
    $evento = mysqli_fetch_array($query, MYSQLI_ASSOC);

// Status do evento
    $sqlStatus = "SELECT * FROM comunicacao_status";
    $query2 = mysqli_query($con, $sqlStatus);
    $status = mysqli_fetch_all($query2, MYSQLI_ASSOC);

    $sqlComu = "SELECT comunicacao_status_id FROM comunicacoes WHERE eventos_id ='{$evento['id']}' AND publicado = 1";
    $query3 = mysqli_query($con, $sqlComu);
    $comu = mysqli_fetch_all($query3, MYSQLI_ASSOC);
}

if (isset($_POST['agendao']) || $tipo == 2) {
    if (isset($_POST['agendao'])) {
        $idEvento = $_POST['agendao'];
    }
    $tipo = '2';

    // Evento
    $sqlEvento = "SELECT * FROM agendoes
              WHERE id = '{$idEvento}' AND publicado = 1";
    $query = mysqli_query($con, $sqlEvento);
    $evento = mysqli_fetch_array($query, MYSQLI_ASSOC);

    // Status do evento
    $sqlStatus = "SELECT * FROM comunicacao_status";
    $query2 = mysqli_query($con, $sqlStatus);
    $status = mysqli_fetch_all($query2, MYSQLI_ASSOC);

    $sqlComu = "SELECT comunicacao_status_id FROM comunicacao_agendoes WHERE eventos_id ='{$evento['id']}' AND publicado = 1";
    $query3 = mysqli_query($con, $sqlComu);
    $comu = mysqli_fetch_all($query3, MYSQLI_ASSOC);

}

?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <h2 class="page-header">Comunicação</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Comunicação:</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <!-- /.box-header -->
                    <form method="post" action="?perfil=comunicacao&p=comunicacao">
                        <div class="box-body">
                            <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                            <label>Status:</label>
                            <div class="row">
                                <div class="col-md-12 checkbox">
                                    <?php
                                    foreach ($status as $st) {
                                        ?>
                                        <label>
                                            <input type="checkbox" name="<?= $st['status'] ?>" value="<?= $st['id'] ?>"
                                                   <?= in_array_r($st['id'], $comu) ? 'checked' : '' ?>>
                                            <?= $st['status'] ?>
                                        </label>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nomeEvento">Nome do Evento:</label>
                                        <input class="form-control" type="text" name="nomeEvento"
                                               value="<?= $evento['nome_evento'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="projetoEspecial">Projeto Especial:</label>
                                        <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                            <option value="">Selecione um Projeto especial</option>
                                            <?php
                                            geraOpcao('projeto_especiais', $evento['projeto_especial_id']);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="sinopse">Sinopse:</label>
                                        <textarea name="sinopse" id="" cols="30" rows="10"
                                                  class="form-control"><?= $evento['sinopse'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <a class="btn btn-default" href="?perfil=comunicacao&p=filtro">Voltar</a>
                            <a class="btn btn-success" href="<?= "?perfil=comunicacao&p=visualizar_ocorrencias&id={$evento['id']}" ?>">Ver ocorrencias</a>
                            <input type="hidden" name="tipo" value="<?= $tipo ?>">
                            <button type="submit" name="salvar" class="btn btn-info pull-right">Salvar</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
</div>

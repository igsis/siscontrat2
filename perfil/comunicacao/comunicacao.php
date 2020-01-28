<?php
include "includes/menu.php";

$con = bancoMysqli();
$conn = bancoPDO();

$idEvento = $_POST['evento'];

$sqlEvento = "SELECT * FROM eventos
              WHERE id = '{$idEvento}' AND publicado = 1";

$query = mysqli_query($con,$sqlEvento);

$evento = mysqli_fetch_array($query,MYSQLI_ASSOC);

?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php
        var_dump($evento);

        ?>
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
                    <div class="box-body">
                        <form action="">
                            <label>Status:</label>
                            <div class="row">
                                <div class="col-md-12 checkbox">
                                    <label>
                                        <input type="checkbox" name="editado">
                                        Editdo
                                    </label>
                                    <label class="margin-left-20">
                                        <input type="checkbox" name="revisado">
                                        Revisado
                                    </label>
                                    <label class="margin-left-20">
                                        <input type="checkbox" name="site">
                                        Site
                                    </label>
                                    <label class="margin-left-20">
                                        <input type="checkbox" name="impresso">
                                        Impresso
                                    </label>
                                    <label class="margin-left-20">
                                        <input type="checkbox" name="foto">
                                        Foto para divulgação
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nomeEvento">Nome do Evento:</label>
                                        <input class="form-control" type="text" name="nomeEvento" value="<?= $evento['nome_evento'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Projeto Especial:</label>
                                        <select class="form-control" name="" id="">
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
                                        <label for="">Ficha tecnica:</label>
                                        <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Sinopse:</label>
                                        <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Release:</label>
                                        <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-default">Voltar</button>
                        <button type="submit" class="btn btn-info pull-right">Salvar</button>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- /.content -->
</div>

<?php
include "includes/menu_interno.php";

$idPedido = $_SESSION['idPedido'];

$sql_atracao = "SELECT a.id, a.nome_atracao, pf.nome, l.pessoa_fisica_id FROM atracoes AS a                                              
                                            LEFT JOIN lideres l on a.id = l.atracao_id
                                            left join pessoa_fisicas pf on l.pessoa_fisica_id = pf.id
                                            WHERE a.publicado = 1 AND a.evento_id = '" . $_SESSION['idEvento'] . "'";
$query_atracao = mysqli_query($con, $sql_atracao);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START FORM-->
        <h2 class="page-header">Pedido de Contratação</h2>
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Líderes</h3>
                    </div>

                    <div class="row" align="center">
                        <?= $mensagem ?? "" ?>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Atração</th>
                                        <th>Proponente</th>
                                        <th width="10%">Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($atracao = mysqli_fetch_array($query_atracao)): ?>
                                            <tr>
                                                <td><?= $atracao['nome_atracao']?></td>
                                                <?php if ($atracao['pessoa_fisica_id'] > 0): ?>
                                                    <td>
                                                        <div class='col-md-7'>
                                                            <form method="POST" action="?perfil=evento&p=lider_edita" role="form">
                                                                <input type='hidden' name='idPedido' value='<?=$idPedido?>'>
                                                                <input type='hidden' name='idAtracao' value='<?= $atracao['id'] ?>'>
                                                                <input type='hidden' name='idLider' value='<?=$atracao['pessoa_fisica_id'] ?>'>
                                                                <button type="submit" name='carregar' class="btn btn-primary">
                                                                    <i class="fa fa-edit"></i> <?= $atracao['nome'] ?>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class='col-md-5'>
                                                            <form method="POST" action="?perfil=evento&p=lider_anexos" role="form">
                                                                <input type='hidden' name='idPedido' value='<?=$idPedido?>'>
                                                                <input type='hidden' name='idAtracao' value='<?=$atracao['id']?>'>
                                                                <input type='hidden' name='idPf' value='<?=$atracao['pessoa_fisica_id']?>'>
                                                                <button type="submit" name='carregar' class="btn btn-info">
                                                                    <i class="fa fa-file"></i> Anexos do Líder
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="?perfil=evento&p=pesquisa_lider" role="form">
                                                            <input type='hidden' name='atracao' value='<?=$atracao['id']?>'>
                                                            <input type='hidden' name='lider' value='<?=$idPedido?>'>
                                                            <button type="submit" name='pesquisar' class="btn btn-warning">
                                                                <i class='fa fa-refresh'></i> Trocar
                                                            </button>
                                                        </form>
                                                    </td>
                                                <?php else: ?>
                                                    <td>
                                                        <form method="POST" action="?perfil=evento&p=pesquisa_lider" role="form">
                                                        <input type='hidden' name='atracao' value='<?=$atracao['id']?>'>
                                                        <input type='hidden' name='lider' value='<?=$idPedido?>'>
                                                        <button type="submit" name='pesquisar' class="btn btn-primary">
                                                            <i class='fa fa-plus'></i> Adicionar
                                                        </button>
                                                        </form>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

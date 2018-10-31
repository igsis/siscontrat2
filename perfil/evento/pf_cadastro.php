<?php

$con = bancoMysqli();
include "includes/menu_interno.php";

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Cadastro de pessoa física</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="?perfil=evento&p=pf_edita" method="post">
                            <div class="col-md-12 form-group">
                                <label for="nome">Nome: *</label>
                                <input type="text" class="form-control" name="nome" placeholder="Digite o nome" maxlength="70" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="nomeArtistico">Nome Artistico: *</label>
                                <input type="text" class="form-control" name="nomeArtistico" placeholder="Digite o nome artistico" maxlength="70" required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>


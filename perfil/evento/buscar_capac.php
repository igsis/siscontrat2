<?php
include "includes/menu_principal.php";
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Busca</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buscar no CAPAC</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=resultado_capac"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 form-group">
                                    <label for="protocolo">Protocolo de Cadastro no CAPAC:</label>
                                    <input type="text" name="protocolo" id="protocolo" class="form-control" data-mask="00000000.00000-E">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-12 form-group">
                                    <label for="nome">Nome do Evento:</label>
                                    <input type="text" name="nome" id="nome" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="publico">Público:</label>
                                    <select name="publico" id="publico" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('publicos');
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


<script>
    $(document).ready(function (){
        $('#protocolo').mask('99999999.99999-E');
    });
</script>
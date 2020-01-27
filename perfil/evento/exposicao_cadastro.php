<?php
$con = bancoMysqli();
include "includes/menu_interno.php";

//$_SESSION['idAtracao'] = $_POST['idAtracao'];

if (isset($_POST['carregar'])) {
    $idAtracao = $_POST['idAtracao'];
}

?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Especificidade</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=exposicao_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="contratados">Quantidade de contratados *</label><br/>
                                    <input class="form-control" type="number" min="1" name="contratados"
                                                  id="contratados" required>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="tipo_exposicao">Tipo de Exposição *</label> <br>
                                    <select class="form-control" id="tipo_exposicao" name="tipo_exposicao" required>
                                            <option value="">Selecione uma opção...</option>
                                            <?php geraOpcao('tipo_exposicao') ?>
                                        </select>
                                </div>


                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="painel">Confecção de painéis</label> <br>
                                    <label><input type="radio" name="painel" value="1" checked> Sim </label>
                                    <label><input type="radio" name="painel" value="0"> Não </label>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="legenda">Confecção de legendas</label> <br>
                                    <label><input type="radio" name="legenda" value="1" checked> Sim </label>
                                    <label><input type="radio" name="legenda" value="0"> Não </label>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="identidade">Criação de Identidade Visual</label> <br>
                                    <label><input type="radio" name="identidade" value="1" checked> Sim </label>
                                    <label><input type="radio" name="identidade" value="0"> Não </label>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="suporte">Suporte extra (exposição)</label> <br>
                                    <label><input type="radio" name="suporte" value="1" checked> Sim </label>
                                    <label><input type="radio" name="suporte" value="0"> Não </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="documentacao">Pedido de documentação</label> <br>
                                    <label><input type="radio" name="documentacao" id="fotografia" value="2"
                                                  checked> Fotografia </label>
                                    <label><input type="radio" name="documentacao" id="audio" value="1"> Áudio
                                    </label>
                                    <label><input type="radio" name="documentacao" id="video" value="0"> Vídeo
                                    </label>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="acervo">Acervo </label><br>
                                    <select class="form-control" id="acervo" name="acervo">
                                        <option value="1">A exposição NÃO possui peças que fazem parte da coleção da
                                            instituição.
                                        </option>
                                        <option value="2">A exposição POSSUI peças que fazem parte da coleção da
                                            instituição.
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="box-footer">
                                <a href="?perfil=evento&p=atracoes_lista">
                                    <button type="button" class="btn btn-default">Voltar</button>
                                </a>
                                <input type="hidden" name="idAtracao" value="<?= $idAtracao ?>">
                                <button type="submit" name="cadastra" class="btn btn-info pull-right">Salvar</button>
                            </div>
                        </div>

                </div>
                </form>
            </div>
        </div>
   </section>
</div>


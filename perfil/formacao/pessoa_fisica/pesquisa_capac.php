<?php

if (isset($_GET['erro'])){
    $mensagem = mensagem('danger','Use o filtro para acessar Listagem Pessoa Fisica Capac');
}

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
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=formacao&p=pessoa_fisica&sp=resultado_pesquisa_capac" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="cod_capac">Código do cadastro no CAPAC</label>
                                    <input type="number" name="cod_capac" id="cod_capac" class="form-control">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="inscricao">Ano de inscrição</label>
                                    <input type="number" class="form-control" name="inscricao" id="inscricao"
                                           minlength="4" min="2018">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="proponente">Nome do Proponente</label>
                                    <input type="text" class="form-control" name="proponente" id="proponente">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label for="programa">Programa</label>
                                    <select name="programa" id="programa" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('programas');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="funcao">Função</label>
                                    <select name="funcao" id="funcao" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('formacao_cargos');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="linguagem">Linguagem</label>
                                    <select name="linguagem" id="linguagem" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('linguagens');
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="regiao">Região preferencial</label>
                                    <select name="regiao" id="regiao" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao('regiao_preferencias');
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

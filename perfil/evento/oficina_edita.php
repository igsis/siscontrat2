<?php
$con = bancoMysqli();
include "includes/menu_interno.php";
?>
<script language="JavaScript" >
    function barraData(n){
        if(n.value.length==2)
            c.value += '/';
        if(n.value.length==5)
            c.value += '/';
    }
</script>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=evento_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="certificado">Certficado?</label><br/>
                                    <label><input type="radio" name="certificado" value="1" checked> Sim </label>
                                    <label><input type="radio" name="certificado" value="0"> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Vagas">Vagas</label> <br>
                                    <input type="number" name="vagas">
                                </div>
                            </div>
                            <div class="row">
                                <label for="publico_alvo">Público-alvo *</label>
                                <textarea name="publico_alvo" id="publico-alvo" rows="5"></textarea>
                            </div>
                            <div class="row">
                                <label for="material">Material Requisitado: </label>
                                <textarea name="publico_alvo" id="publico-alvo" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <select class="form-control" id="tipoDocumento" name="forma_inscricao">
                                        <option value="1">Sem necessidade</option>
                                        <option value="2">Pelo site - ficha de inscrição</option>
                                        <option value="3">Pelo site - por email</option>
                                        <option value="4">Pessoalmente</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="divulgacao">Divulgação de inscrição: </label>
                                    <input type="date" name="divulgacao" onkeyup="barraData(this);"/>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="inicio_inscricao">Início de inscrição: </label>
                                        <input type="date" name="inicio_inscricao" onkeyup="barraData(this);"/>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="encerramento_inscricao">Encerramento de inscrição: </label>
                                        <input type="date" name="encerramento_inscricao" onkeyup="barraData(this);"/>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="inicio_inscricao">Início de inscrição: </label>
                                            <input type="date" name="inicio_inscricao" onkeyup="barraData(this);"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="encerramento_inscricao">Encerramento de inscrição: </label>
                                            <input type="date" name="encerramento_inscricao" onkeyup="barraData(this);"/>
                                        </div>





                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="Descrição">Descrição</label><br/>
                                            <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                            <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i></p>
                                            <textarea name="sinopse" id="sinopse" class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-default">Cancelar</button>
                                        <button type="submit" name="salvar" class="btn btn-info pull-right">Salvar</button>
                                    </div>

                                </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

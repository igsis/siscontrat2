<?php
include "includes/menu_principal.php";

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_verifica_cep.php';
?>
<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Adição de Local</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Local</h3>
                    </div>
                    <form method="POST" action="?perfil=agendao&p=inicio"
                          role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cep">Instituição: *</label>
                                    <select name="instituicao" id="instituicao" class="form-control" required>
                                        <option value="6">Espaços Abertos</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="cep">Local: *</label>
                                    <input type="text" class="form-control" name="local" id="local" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" id="tabelaEsconde">
                                        <thead>
                                        <tr>
                                            <th>Local</th>
                                            <th>Cep</th>
                                            <th>Rua</th>
                                            <th>Bairro</th>
                                        </tr>
                                        </thead>

                                        <tbody id="tabelaLocais">
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th>Local</th>
                                            <th>Cep</th>
                                            <th>Rua</th>
                                            <th>Bairro</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua"
                                           placeholder="Digite a rua" maxlength="200" readonly>
                                </div>
                                <div class="form-group col-md-1">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" placeholder="Ex.: 10"
                                           required min="0">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="cep">Zona: *</label>
                                    <select class="form-control" id="zona" name="zona">
                                        <?php
                                        geraOpcao('zonas');
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Ex.: SP" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="cadastraLocal" id="cadastraLocal"
                                    class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
    const url = `<?=$url?>`;
    let cep = $('#cep');
    let tabela = $('#tabelaLocais');
    let tabelaEsconde = $('#tabelaEsconde').hide();

    cep.blur(function () {
        if (cep.val() == '') {
            tabelaEsconde.hide();
        } else {
            $.ajax({
                url: url,
                type: 'POST',
                data: {"cep": cep.val()},

                success: function (data) {
                    tabela.html('');

                    if (data.length > 0) {
                        tabelaEsconde.show();
                        for (let dados of data) {
                            tabela.append("<tr>" +
                                " <td>" + dados.local + "</td> " +
                                "<td>" + dados.cep + "</td>" +
                                "<td>" + dados.logradouro + "</td>" +
                                "<td>" + dados.bairro + "</td>" +
                                "</tr>")
                        }
                    } else if (!data.ok)
                        tabelaEsconde.hide();
                }
            })
        }
    })
</script>
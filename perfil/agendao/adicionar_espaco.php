<?php
include "includes/menu_principal.php";
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';
?>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Adição de Espaço</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Espaços</h3>
                    </div>
                    <form method="POST" action="?perfil=agendao&p=inicio"
                          role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="instituicao">Instituição: *</label>
                                    <select name="instituicao" id="instituicao" class="form-control" required>
                                        <option value="6">Espaços Abertos</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="local">Local: </label>
                                    <select name="local" id="local" class="form-control" required>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="sigla">Espaço: </label>
                                    <input type="text" class="form-control" id="espaco" name="espaco" required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="cadastraEspaco" id="cadastraEspaco" class="btn btn-primary pull-right">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

    const url = `<?=$url?>`;

    getLocais(6, 1);

    function getLocais(idInstituicao, selectedId) {
        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    if (selectedId == local.id) {
                        $('#local').append(`<option value='${local.id}' selected>${local.local}</option>`).focus();
                    } else {
                        $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                    }
                }
            })
    }
</script>



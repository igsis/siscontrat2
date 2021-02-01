<footer class="main-footer" id="footerPrincipal">
    <div class="pull-right hidden-xs" align="right">
        <strong><?= date('Y') ?> &copy; SISCONTRAT</strong><br/>
        STI - Sistemas de Informação - <b>Version</b> 2.0
    </div>
    <img src="images/logo_cultura.png"/>
    <div class="box-body">
        <div class="box-group" id="accordion">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseRodape">
                            Configurações
                        </a>
                    </h4>
                </div>
                <div id="collapseRodape" class="panel-collapse collapse">
                    <div class="box-body">
                        <?php
                        echo "<strong>SESSION</strong><pre>", var_dump($_SESSION), "</pre>";
                        echo "<strong>POST</strong><pre>", var_dump($_POST), "</pre>";
                        echo "<strong>GET</strong><pre>", var_dump($_GET), "</pre>";
                        echo "<strong>SERVER</strong><pre>", var_dump($_SERVER), "</pre>";
                        echo "<strong>FILES</strong><pre>", var_dump($_FILES), "</pre>";
                        echo ini_get('session.gc_maxlifetime') / 60; // em minutos
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>

<!--  MODAL PARA LISTAGEM DE EVENTOS E INSTITUIÇÕES EM TABELAS -->
<div id="modalLocais_Inst" class="modal modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalTitulo"></h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tbody id="conteudoModal">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--  MODAL PARA LISTAGEM DE CHAMADOS DE EVENTOS -->
<div id="modalChamadosEventos" class="modal modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modalTitulo">Lista de Chamados</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Datas</th>
                            <th>Tipo</th>
                            <th>Titulo</th>
                            <th>Justificativo</th>
                        </tr>
                    </thead>
                    <tbody id="conteudoModal">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<a href="https://forms.gle/ktjaMbEHmANLuFXi8" class="btn btn-warning rounded-circle" target="_blank"
   style="position:fixed;bottom:40px;right:40px;text-align:center;
   box-shadow: 1px 1px 2px #888;z-index:1000;">
    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
    Deixe sua opnião</a>

<!--  Mask-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- DataTables -->
<!-- <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> -->
<!-- API Consulta CEP -->
<script src="./dist/js/cep_api.js"></script>
<script src="./dist/js/scripts.js"></script>

<script type="text/javascript" src="js/autocomplete.js"></script>
<script src="js/jquery-ui.js"></script>

<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="plugins/select2/js/i18n/pt-BR.js" type="text/javascript"></script>

<script>
    $(document).ready(function (){
        //Initialize Select2 Elements
        $('.select2').select2();

        $('.select2bs4').select2({
            theme: 'bootstrap4',
            language: 'pt-BR'
        });
    });
</script>

<script>
    function exibirChamados(id){
        const modalId= '#modalChamadosEventos';
        const conteudo = '#conteudoModal';
        $.ajax({
            method: "GET",
            url: "<?= 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_chamado_evento.php' ?>?idEvento="+id,
        })
            .done(function (content) {
                $(modalId).find(conteudo).empty();
                content = JSON.parse(content);

                content.forEach((item) =>{
                    let linha = document.createElement('tr');

                    let data = new Date(`${item.data}`);

                    linha.appendChild(criaColuna(`${data.toLocaleDateString()}`));
                    linha.appendChild(criaColuna(`${item.tipo}`));
                    linha.appendChild(criaColuna(`${item.titulo}`));
                    linha.appendChild(criaColuna(`${item.justificativa}`));

                    document.querySelector(`${modalId} ${conteudo}`).appendChild(linha);

                });

                // $(modalId).find(conteudo).append(`<tr><td>${content}</td></tr>`);
                $(modalId).modal();
            });
    }

    function criaColuna(text){
        let coluna = document.createElement("td");
        coluna.innerHTML = `${text}`;
        return coluna;
    }
</script>

<!-- page script -->
<script>
    // $(function () {
    //     $('#example1').DataTable()
    //     $('#example2').DataTable({
    //         'paging'      : true,
    //         'lengthChange': false,
    //         'searching'   : false,
    //         'ordering'    : true,
    //         'info'        : true,
    //         'autoWidth'   : false
    //     })
    // })
    /*Downloaded from https://www.codeseek.co/shantikumarsingh/bootstrap-3-stepper-OOgGzG */
    /*jslint browser: true*/
    /*global $, jQuery, alert*/
    (function ($) {
        'use strict';

        $(function () {

            $(document).ready(function () {
                function triggerClick(elem) {
                    $(elem).click();
                }

                var $progressWizard = $('.stepper'),
                    $tab_active,
                    $tab_prev,
                    $tab_next,
                    $btn_prev = $progressWizard.find('.prev-step'),
                    $btn_next = $progressWizard.find('.next-step'),
                    $tab_toggle = $progressWizard.find('[data-toggle="tab"]'),
                    $tooltips = $progressWizard.find('[data-toggle="tab"][title]');

                // To do:
                // Disable User select drop-down after first step.
                // Add support for payment type switching.

                //Initialize tooltips
                $tooltips.tooltip();

                //Wizard
                $tab_toggle.on('show.bs.tab', function (e) {
                    var $target = $(e.target);

                    if (!$target.parent().hasClass('active, disabled')) {
                        $target.parent().prev().addClass('completed');
                    }
                    if ($target.parent().hasClass('disabled')) {
                        return false;
                    }
                });

                // $tab_toggle.on('click', function(event) {
                //     event.preventDefault();
                //     event.stopPropagation();
                //     return false;
                // });

                $btn_next.on('click', function () {
                    $tab_active = $progressWizard.find('.active');

                    $tab_active.next().removeClass('disabled');

                    $tab_next = $tab_active.next().find('a[data-toggle="tab"]');
                    triggerClick($tab_next);

                });
                $btn_prev.click(function () {
                    $tab_active = $progressWizard.find('.active');
                    $tab_prev = $tab_active.prev().find('a[data-toggle="tab"]');
                    triggerClick($tab_prev);
                });

                $('.opcArtista').on('click', function () {
                    let valor = $(this).val();
                    let htm = '';
                    if (valor == 1) {
                        htm = '<div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>1º Tópico</strong></h4>\n' +
                            '                                                    <label for="topico1">Neste tópico deve conter o posicionamento da\n' +
                            '                                                        comissão e as informações gerais do evento (nome do artista,\n' +
                            '                                                        evento,\n' +
                            '                                                        datas, valor, tempo, etc).</label><br />\n' +
                            '                                                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br />\n' +
                            '                                                        <i>Esta comissão ratifica o pedido de contratação de Nome do\n' +
                            '                                                            artista\n' +
                            '                                                            ou grupo (nome artístico) por intermédio da Nome da empresa\n' +
                            '                                                            representante, para apresentação artística no evento “Nome\n' +
                            '                                                            do\n' +
                            '                                                            evento ou atividade especial”, que ocorrerá no dia datas ou\n' +
                            '                                                            período quando for temporada no valor de R$ XXX (valor por\n' +
                            '                                                            extenso).</i>\n' +
                            '                                                    </span>\n' +
                            '                                                    <textarea name="topico1" id="topico1" class="form-control"\n' +
                            '                                                        rows="3"></textarea>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                        <div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>2º Tópico (mínimo de 500 caracteres)</strong></h4>\n' +
                            '                                                    <label for="topico2">Neste tópico deve-se falar sobre o evento ou\n' +
                            '                                                        atividade especial da qual o artista/grupo irá participar. Se\n' +
                            '                                                        for\n' +
                            '                                                        programação geral do equipamento, sem estar vinculada a nenhum\n' +
                            '                                                        evento ou projeto específico, falar sobre o equipamento,\n' +
                            '                                                        histórico,\n' +
                            '                                                        tipo de atividades desenvolvidas, etc, demonstrando a\n' +
                            '                                                        importância\n' +
                            '                                                        desse tipo de programação dentro do equipamento.<br />Se for\n' +
                            '                                                        programação\n' +
                            '                                                        geral do equipamento, sem estar vinculada a nenhum evento ou\n' +
                            '                                                        projeto\n' +
                            '                                                        específico, falar sobre o equipamento, histórico, tipo de\n' +
                            '                                                        atividades\n' +
                            '                                                        desenvolvidas, etc, demonstrando a importância desse tipo de\n' +
                            '                                                        programação\n' +
                            '                                                        dentro do equipamento.</label><br />\n' +
                            '                                                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br />\n' +
                            '                                                        <i>Em sua nona edição, o projeto Virada Cultural, da Secretaria\n' +
                            '                                                            Municipal de Cultura, consolida a Cidade de São Paulo como o\n' +
                            '                                                            principal pólo gerador de arte e cultura do País\n' +
                            '                                                            proporcionando,\n' +
                            '                                                            não só aos munícipes como também aos visitantes de outros\n' +
                            '                                                            Estados e de outras nacionalidades, o acesso gratuito ao que\n' +
                            '                                                            há\n' +
                            '                                                            de melhor na produção cultural atual existente no Brasil e\n' +
                            '                                                            no\n' +
                            '                                                            exterior. A Virada Cultural da Cidade de São Paulo, através\n' +
                            '                                                            de\n' +
                            '                                                            apresentações artísticas em logradouros públicos e\n' +
                            '                                                            equipamentos\n' +
                            '                                                            oficiais dentre outros espaços culturais conquistou, nesses\n' +
                            '                                                            nove\n' +
                            '                                                            anos de existência, o reconhecimento da mídia e do público,\n' +
                            '                                                            solidificando-se como um dos eventos nacionais mais\n' +
                            '                                                            conhecidos e\n' +
                            '                                                            divulgados do Brasil, assim como no exterior.</i></span>\n' +
                            '                                                    <textarea id="topico2" name="topico2" class="form-control" rows="5"\n' +
                            '                                                        onkeyup="mostrarResultado(this.value,500,\'spcontando\');contarCaracteres(this.value,500,\'sprestante\')"></textarea>\n' +
                            '                                                    <span id="spcontando" style="font-family:Georgia;">Comece a digitar\n' +
                            '                                                        para\n' +
                            '                                                        ativar a contagem de caracteres.</span><br />\n' +
                            '                                                    <span id="sprestante" style="font-family:Georgia;"></span>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                        <div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>3º Tópico (mínimo de 700 caracteres)</strong></h4>\n' +
                            '                                                    <label>Neste tópico deve-se falar sobre o currículo/biografia do\n' +
                            '                                                        artista\n' +
                            '                                                        ou grupo (na 3ª pessoa), escrever um breve release. Deve ficar\n' +
                            '                                                        claro\n' +
                            '                                                        que o artista contribuirá positivamente para a programação e\n' +
                            '                                                        porque\n' +
                            '                                                        essa é a melhor escolha de artista para o evento.</label>\n' +
                            '                                                    <textarea id="topico3" name="topico3" class="form-control" rows="5"\n' +
                            '                                                        onkeyup="mostrarResultado3(this.value,700,\'spcontando3\');contarCaracteres3(this.value,700,\'sprestante3\')"></textarea>\n' +
                            '                                                    <span id="spcontando3" style="font-family:Georgia;">Comece a digitar\n' +
                            '                                                        para\n' +
                            '                                                        ativar a contagem de caracteres.</span><br />\n' +
                            '                                                    <span id="sprestante3" style="font-family:Georgia;"></span>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                        <div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>4º Tópico</strong></h4>\n' +
                            '                                                    <label for="topico4">Neste tópico deve-se falar que o contratado tem\n' +
                            '                                                        o\n' +
                            '                                                        necessário para a contratação e que as exigências legais foram\n' +
                            '                                                        observadas, apresentando a comprovação documental (mínimo três\n' +
                            '                                                        comprovações diferentes) do valor proposto para o cachê.\n' +
                            '                                                        Encerrar\n' +
                            '                                                        com a manifestação favorável da comissão quanto à\n' +
                            '                                                        contratação.</label><br />\n' +
                            '                                                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br />\n' +
                            '                                                        <i>Os artistas reúnem as condições necessárias para integrar a\n' +
                            '                                                            programação Secretaria Municipal de Cultura, possuem\n' +
                            '                                                            consagração, reconhecimento e aceitação do público, conforme\n' +
                            '                                                            documentos juntados ao presente, SEI ( link do clipping,\n' +
                            '                                                            curriculo e release ). Ainda, avaliamos que o cachê proposto\n' +
                            '                                                            encontra-se compatível com os valores praticados no mercado\n' +
                            '                                                            e\n' +
                            '                                                            pagos por esta Secretaria, conforme pode ser comprovado\n' +
                            '                                                            pelos\n' +
                            '                                                            processos/notas fiscais ( link de 3 números de processos SEI\n' +
                            '                                                            que\n' +
                            '                                                            constem notas fiscais ), em cumprimento ao Acórdão TC\n' +
                            '                                                            2.393/15-37.\n' +
                            '                                                            Sendo os serviços indubitavelmente de natureza artística,\n' +
                            '                                                            manifestamo-nos favoravelmente à contratação, endossando a\n' +
                            '                                                            proposta inicial.</i> </span>\n' +
                            '                                                    <textarea id="topico4" name="topico4" class="form-control" rows="5">\n' +
                            '                                                                    </textarea>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>';
                        $('#caixa').html(htm);
                    } else if (valor == 2) {
                        htm = '<div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>1º Tópico</strong></h4>\n' +
                            '                                                    <label for="topico1">Neste tópico deve conter o posicionamento da\n' +
                            '                                                        comissão e as informações gerais do evento (nome do artista,\n' +
                            '                                                        evento, datas, valor, tempo, etc).</label><br />\n' +
                            '                                                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br />\n' +
                            '                                                        <i>Esta comissão ratifica o pedido de contratação de Nome do\n' +
                            '                                                            artista ou grupo (nome artístico) por intermédio da Nome da\n' +
                            '                                                            empresa representante, para apresentação artística no evento\n' +
                            '                                                            “Nome do evento ou atividade especial”, que ocorrerá no dia\n' +
                            '                                                            datas ou período quando for temporada no valor de R$ XXX\n' +
                            '                                                            (valor por extenso).</i>\n' +
                            '                                                    </span>\n' +
                            '                                                    <textarea name="topico1" id="topico1" class="form-control"\n' +
                            '                                                        rows="3"></textarea>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                        <div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>2º Tópico (mínimo de 500 caracteres)</strong></h4>\n' +
                            '                                                    <label for="topico2">Neste tópico deve-se falar sobre o evento ou\n' +
                            '                                                        atividade especial da qual o artista/grupo irá participar. Se\n' +
                            '                                                        for programação geral do equipamento, sem estar vinculada a\n' +
                            '                                                        nenhum evento ou projeto específico, falar sobre o equipamento,\n' +
                            '                                                        histórico, tipo de atividades desenvolvidas, etc, demonstrando a\n' +
                            '                                                        importância desse tipo de programação dentro do\n' +
                            '                                                        equipamento.</label><br />\n' +
                            '                                                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br />\n' +
                            '                                                        <i>Em sua nona edição, o projeto Virada Cultural, da Secretaria\n' +
                            '                                                            Municipal de Cultura, consolida a Cidade de São Paulo como o\n' +
                            '                                                            principal pólo gerador de arte e cultura do País\n' +
                            '                                                            proporcionando, não só aos munícipes como também aos\n' +
                            '                                                            visitantes de outros Estados e de outras nacionalidades, o\n' +
                            '                                                            acesso gratuito ao que há de melhor na produção cultural\n' +
                            '                                                            atual existente no Brasil e no exterior. A Virada Cultural\n' +
                            '                                                            da Cidade de São Paulo, através de apresentações artísticas\n' +
                            '                                                            em logradouros públicos e equipamentos oficiais dentre\n' +
                            '                                                            outros espaços culturais conquistou, nesses nove anos de\n' +
                            '                                                            existência, o reconhecimento da mídia e do público,\n' +
                            '                                                            solidificando-se como um dos eventos nacionais mais\n' +
                            '                                                            conhecidos e divulgados do Brasil, assim como no\n' +
                            '                                                            exterior.</i></span>\n' +
                            '                                                    <textarea id="topico2" name="topico2" class="form-control" rows="5"\n' +
                            '                                                        onkeyup="mostrarResultado(this.value,500,\'spcontando\');contarCaracteres(this.value,500,\'sprestante\')"></textarea>\n' +
                            '                                                    <span id="spcontando" style="font-family:Georgia;">Comece a digitar\n' +
                            '                                                        para\n' +
                            '                                                        ativar a contagem de caracteres.</span><br />\n' +
                            '                                                    <span id="sprestante" style="font-family:Georgia;"></span>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                        <div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>3º Tópico (mínimo de 700 caracteres)</strong></h4>\n' +
                            '                                                    <label>Neste tópico deve-se falar sobre o currículo/biografia do\n' +
                            '                                                        artista ou grupo (na 3ª pessoa), escrever um breve release. Deve\n' +
                            '                                                        ficar claro que o artista contribuirá positivamente para a\n' +
                            '                                                        programação e porque essa é a melhor escolha de artista para o\n' +
                            '                                                        evento.</label>\n' +
                            '                                                    <textarea id="topico3" name="topico3" class="form-control" rows="5"\n' +
                            '                                                        onkeyup="mostrarResultado3(this.value,700,\'spcontando3\');contarCaracteres3(this.value,700,\'sprestante3\')"></textarea>\n' +
                            '                                                    <span id="spcontando3" style="font-family:Georgia;">Comece a digitar\n' +
                            '                                                        para\n' +
                            '                                                        ativar a contagem de caracteres.</span><br />\n' +
                            '                                                    <span id="sprestante3" style="font-family:Georgia;"></span>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                        <div class="row">\n' +
                            '                                            <div class="col-md-10">\n' +
                            '                                                <div class="form-group">\n' +
                            '                                                    <h4><strong>4º Tópico</strong></h4>\n' +
                            '                                                    <label for="topico4">Neste tópico deve-se falar que o contratado tem\n' +
                            '                                                        o necessário para a contratação e que as exigências legais foram\n' +
                            '                                                        observadas, apresentando a comprovação documental (mínimo três\n' +
                            '                                                        notas fiscais de eventos que não foram contratados pela\n' +
                            '                                                        prefeitura) do valor proposto para o cachê. Encerrar com a\n' +
                            '                                                        manifestação favorável da comissão quanto à\n' +
                            '                                                        contratação.</label><br />\n' +
                            '                                                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br />\n' +
                            '                                                        <i>O espetáculo é composto por profissionais consagrados pelo\n' +
                            '                                                            público e pela crítica especializada, estando o cachê\n' +
                            '                                                            proposto de acordo com os valores praticados no mercado,\n' +
                            '                                                            conforme pode ser comprovado pelos documentos x, y e z, em\n' +
                            '                                                            cumprimento ao Acórdão TCM 2.393/15-37.\n' +
                            '                                                            Sendo os serviços indubitavelmente de natureza artística,\n' +
                            '                                                            manifestamo-nos favoravelmente à contratação, endossando a\n' +
                            '                                                            proposta inicial.</i> </span>\n' +
                            '                                                    <textarea id="topico4" name="topico4" class="form-control" rows="5">\n' +
                            '                                                                            </textarea>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                        </div>';
                        $('#caixa').html(htm);
                    } else {
                        $('#caixa').html('');
                    }
                });
            });
        });

    }(jQuery, this));

    //população de modal para locais e instituições em tabelas
    function exibirLocal_Instituicao(link, modalId, tituloModal){
        $(modalId).on('show.bs.modal', function (e) {
            var conteudo = $(e.relatedTarget).attr('data-name');
            var id = $(e.relatedTarget).attr('data-id');
            if(conteudo == "local"){
                $(tituloModal).html('<strong>Lista de Local(ais)</strong>');
            }else{
                $(tituloModal).html('<strong>Lista de Instituição(ões)</strong>');
            }
            $.ajax({
                method: "GET",
                url: link + "?idEvento=" + id + "&conteudo=" + conteudo
            })
                .done(function (content) {
                    $(modalId).find('#conteudoModal').empty();
                    $(modalId).find('#conteudoModal').append(`<tr><td>${content}</td></tr>`);
                });
        })
    }
</script>
</body>
</html>
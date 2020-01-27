<?php
include "includes/menu.php";
$con = bancoMysqli();
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';
$urlEvento = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_full_calendar.php';
?>
<script>
const URL = `<?=$urlEvento?>`;
let events = [];

axios.get(URL)
    .then(response => {
        const contador = response.data.length;

        for (let i = 0; i < contador; i++) {
            let title = response.data[i].nomeEvento;
            let start = response.data[i].dataInicio + "T" + response.data[i].horaInicio;
            let end = response.data[i].dataFim + "T" + response.data[i].horaFim;

            events[i] = {
                title,
                start,
                end
            }
        }

        console.log(events);
        carregaCalendario();
    })
    .catch(error => {
        console.warn(error)
    })
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="row ">
            <div class="col-md-6">
                <label for="data_inicio">Data Início*</label> <br>
                <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                       required placeholder="DD/MM/AAAA" onblur="mudaData()">
            </div>
            <div class="col-md-6">
                <label for="data_fim">Data Encerramento</label> <br>
                <input type="date" name="data_fim" class="form-control" id="datepicker11"
                       placeholder="DD/MM/AAAA" onblur="validate()">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="instituicao">Instituição</label>
                <select class="form-control" name="instituicao" id="instituicao">
                    <option value="">Selecione uma opção...</option>

                    <?php
                    geraOpcao("instituicoes");
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="local">Local</label>
                <select class="form-control" id="local" name="local">
                    <!-- Populando pelo js -->
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="espaco">Espaço</label>
                <select class="form-control" id="espaco" name="espaco">
                    <!-- Populando pelo js -->
                </select>
            </div>
        </div>

        <div class="row">
            <div id='calendar' class="col-md-8 col-md-offset-2"></div>
        </div>
    </section>
</div>

<script>
    const url = `<?=$url?>`;

    let instituicao = document.querySelector('#instituicao');

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();

        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                }
            })

    });

    let local = document.querySelector('#local');

    local.addEventListener('change', async e => {
        let idLocal = $('#local option:checked').val();

        fetch(`${url}?espaco_id=${idLocal}`)
            .then(response => response.json())
            .then(espacos => {
                $('#espaco option').remove();
                if (espacos.length < 1) {
                    $('#espaco').append('<option value="">Não há espaço para esse local</option>')
                        .attr('required', false)
                        .focus();
                } else {
                    $('#espaco').append('<option value="">Selecione uma opção...</option>')
                        .attr('required', true)
                        .focus();
                }

                for (const espaco of espacos) {
                    $('#espaco').append(`<option value='${espaco.id}'>${espaco.espaco}</option>`)
                }

            })
    });
</script>

<script>
    // document.addEventListener('DOMContentLoaded', carregaCalendario);
    // $(document).ready(carregaCalendario());

    function carregaCalendario(){
        let data = new Date();
        let dia = data.getDate().toString();
        let mes = (data.getMonth() + 1).toString();
        let ano = data.getFullYear().toString();

        if (dia.length == 1) dia = "0" + dia;
        if (mes.length == 1) mes = "0" + mes;


        const diaAtual = ano + '-' + mes + '-' + dia;

        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pt-br',
            plugins: ['dayGrid', 'timeGrid'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },

            defaultDate: diaAtual,
            navLinks: true, // can click day/week names to navigate views
            businessHours: true, // display business hours
            editable: false,
            events
        });

        calendar.setOption('locale', 'pt-br');
        calendar.render();
    }
</script>

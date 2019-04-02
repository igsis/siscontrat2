<?php
include "includes/menu.php";
$con = bancoMysqli();
$url = 'http://'.$_SERVER['HTTP_HOST'].'/siscontrat2/funcoes/api_locais_espacos.php';
?>

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

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

      locale: 'pt-br',
      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      defaultDate: '2019-03-12',
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      events: [
        {
          title: 'Business Lunch',
          start: '2019-03-03T13:00:00',
          constraint: 'businessHours'
        },
        {
          title: 'Meeting',
          start: '2019-03-13T11:00:00',
          constraint: 'availableForMeeting', // defined below
          color: '#257e4a'
        },
        {
          title: 'Conference',
          start: '2019-03-18',
          end: '2019-03-20'
        },
        {
          title: 'Party',
          start: '2019-03-29T20:00:00'
        },

        // areas where "Meeting" must be dropped
        {
          groupId: 'availableForMeeting',
          start: '2019-03-11T10:00:00',
          end: '2019-03-11T16:00:00',
          rendering: 'background'
        },
        {
          groupId: 'availableForMeeting',
          start: '2019-03-13T10:00:00',
          end: '2019-03-13T16:00:00',
          rendering: 'background'
        },

        // red areas where no events can be dropped
        {
          start: '2019-03-24',
          end: '2019-03-28',
          overlap: false,
          rendering: 'background',
          color: '#ff9f89'
        },
        {
          start: '2019-03-06',
          end: '2019-03-08',
          overlap: false,
          rendering: 'background',
          color: '#ff9f89'
        }
      ]
    });

    calendar.setOption('locale', 'pt-br');
    calendar.render();
  });

</script>

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
                    $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();;
                }
            })

    })

    let local = document.querySelector('#local');

    local.addEventListener('change', async e => {
        let idLocal = $('#local option:checked').val();

        fetch(`${url}?espaco_id=${idLocal}`)
            .then(response => response.json() )
            .then(espacos => {
                $('#espaco option').remove();
                if(espacos.length < 1){
                    $('#espaco').append('<option value="">Não há espaço para esse local</option>')
                        .attr('required',false)
                        .focus();
                }else{
                    $('#espaco').append('<option value="">Selecione uma opção...</option>')
                        .attr('required',true)
                        .focus();
                }

                for (const espaco of espacos) {
                    $('#espaco').append(`<option value='${espaco.id}'>${espaco.espaco}</option>`)
                }

            })
    })

</script>




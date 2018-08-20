
<footer>
	<div class="container">
        <div class="row">
            <div class="col-md-2">
                <img src="../visual/images/logo_cultura_q.png">
            </div>
            <div class="col-md-offset-2 col-md-4" style="padding: 10px">
                <span style="color: #ccc; "><?= date("Y") ?> @ IGSIS - CAPAC<br>Secretaria Municipal de Cultura<br>Prefeitura de São Paulo</span>
            </div>
            <div class="col-md-offset-2 col-md-2">
                <img src="../visual/images/logo_igsis_azul.png">
            </div>
        </div>
		<div class="row">
			<div class="col-md-12">
			<?php
				$usr = $_SESSION['idUser'];
				if($usr < 11)
				{
					echo "<strong>SESSION</strong><pre>", var_dump($_SESSION), "</pre>";
					echo "<strong>POST</strong><pre>", var_dump($_POST), "</pre>";
					echo "<strong>GET</strong><pre>", var_dump($_GET), "</pre>";
					echo "<strong>FILES</strong><pre>", var_dump($_FILES), "</pre>";
					echo ini_get('session.gc_maxlifetime')/60; // em minutos
				}
			?>
			</div>
		</div>
	</div>
</footer>

<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.smooth-scroll.min.js"></script>
<script src="js/jquery.dlmenu.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
  //Script para confirmação de exclusão de arquivo
        $('#confirmApagar').on('show.bs.modal', function (e)
        {
            $message = $(e.relatedTarget).attr('data-message');
            $(this).find('.modal-body p').text($message);
             
            // Pass form reference to modal for submission on yes/ok
            var form = $(e.relatedTarget).closest('form');
            $(this).find('.modal-footer #confirm').data('form', form);
        });
         
        // Form confirm (yes/ok) handler, submits form
        $('#confirmApagar').find('.modal-footer #confirm').on('click', function()
        {
            $(this).data('form').submit();
        });
    </script>
</body>

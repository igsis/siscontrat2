<?php
/*TODO: Ocultar opções do menu*/
$con = bancoMysqli();
$idPj = isset($_SESSION['idPj']) ? $_SESSION['idPj'] : '';
$urlPj = array(
	'/igsiscapac/visual/index.php?perfil=proponente_pj_resultado', // 00
	'/igsiscapac/visual/index.php?perfil=informacoes_iniciais_pj', // 01 info iniciais
	'/igsiscapac/visual/index.php?perfil=arquivos_pj',//02  // arq pj
	'/igsiscapac/visual/index.php?perfil=endereco_pj', // 03 enderco
	'/igsiscapac/visual/index.php?perfil=representante1_pj', // 04 representante 1
	'/igsiscapac/visual/index.php?perfil=representante1_pj_resultado_busca', // 05 representante 1
	'/igsiscapac/visual/index.php?perfil=representante1_pj_cadastro', // 06 representante 1 
	'/igsiscapac/visual/index.php?perfil=arquivos_representante1', // 07 arquivo representante 1
	'/igsiscapac/visual/index.php?perfil=representante2_pj', // 08 ---------------- representante 2
	'/igsiscapac/visual/index.php?perfil=representante2_pj_resultado_busca', // 09  representante 2
	'/igsiscapac/visual/index.php?perfil=representante2_pj_cadastro', // 10 representante 2
	'/igsiscapac/visual/index.php?perfil=arquivos_representante2', // 11 ARQ representante 2
	'/igsiscapac/visual/index.php?perfil=dados_bancarios_pj', // 12 dados bancarios
	'/igsiscapac/visual/index.php?perfil=arquivos_dados_bancarios_pj', //13 Arquivo dos Dados Bancários
	'/igsiscapac/visual/index.php?perfil=anexos_pj', // 14 anexos
	'/igsiscapac/visual/index.php?perfil=final_pj', // finalizar
    '/igsiscapac/visual/index.php?perfil=representante1_pj_cadastro&id_pj='.$idPj,// 16 representante 1
    '/igsiscapac/visual/index.php?perfil=representante2_pj_cadastro&id_pj='.$idPj // 17 representante 2

);
for ($i = 0; $i < count($urlPj); $i++) {
    if ($uri == $urlPj[$i]) {
    	if ($i == 0 || $i == 1){
            $ativ1 = 'active loading';
        }elseif ($i == 2){ // arq pj
        	$ativ2 = 'active loading';
        }elseif ($i == 3){ // enderco
        	$ativ3 = 'active loading';
        }elseif ($i == 4 || $i == 5 || $i == 6 || $i == 16){ // representante
        	$ativ4 = 'active loading';
        }elseif ($i == 7){ // ARQ representante
        	$ativ5 = 'active loading';
        }elseif ($i == 8 || $i == 9 || $i == 10 || $i == 17){ // representante 2 
        	$ativ6 = 'active loading';
        }elseif ($i == 11){ // arq representante 2 
        	$ativ7 = 'active loading';
        }elseif ($i == 12){ //  Dados Bancários
        	$ativ8 = 'active loading';
        }elseif ($i == 13){ // Arquivo dos Dados Bancários
        	$ativ9 = 'active loading';
        }elseif ($i == 14){ // Anexos
        	$ativ10 = 'active loading';
        }elseif ($i == 15){ // Arquivo dos Dados Bancários
        	$ativ11 = 'active loading';
        }
    	if(!(isset($_SESSION['idEvento']))){
?>
 <!-- Pessoa Jurídica id evento -->
            <div id="smartwizard">
                <ul>
                    <li class="hidden">
                        <a href=""><br /></a>
                    </li>
                    <li class="<?php echo isset($ativ1) ? $ativ1 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=informacoes_iniciais_pj'" href=""><br /><small>Informações Iniciais</small></a>
                    </li> <!-- Ok -->
                    <li class="<?php echo isset($ativ2) ? $ativ2 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_pj'" href=""><br /><small>Arquivos da Empresa</small></a>
                    </li>
                    <li class="<?php echo isset($ativ3) ? $ativ3 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=endereco_pj'" href=""><br /><small>Endereço</small></a>
                    </li>
                    <li class="<?php echo isset($ativ4) ? $ativ4 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=representante1_pj'" href=""><br /><small>Representante Legal 1</small></a>
                    </li>
                    <li class="<?php echo isset($ativ5) ? $ativ5 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_representante1'" href=""><br /><small>Arquivos Representante Legal 1</small></a>
                    </li>
                </ul>
                <ul>                     
                    <li class="<?php echo isset($ativ6) ? $ativ6 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=representante2_pj'" href=""><br /><small>Representante Legal 2</small></a>
                    </li>
                    <li class="<?php echo isset($ativ7) ? $ativ7 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_representante2'" href=""><br /><small>Arquivos Representante Legal 2</small></a>
                    </li>                                                  
                    <li class="<?php echo isset($ativ8) ? $ativ8 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=dados_bancarios_pj'" href=""><br />Dados Bancários</a>
                    </li>
                    <li class="<?php echo isset($ativ9) ? $ativ9 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=arquivos_dados_bancarios_pj'" href=""><br /><small>Arquivos Dados Bancários</small></a>
                    </li>          
                    <li class="<?php echo isset($ativ10) ? $ativ10 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=anexos_pj'" href=""><br /><small>Demais Anexos</small></a>
                    </li> 
                    <li class="<?php echo isset($ativ11) ? $ativ11 : 'clickable'; ?>">
                        <a onclick="location.href='index.php?perfil=final_pj'" href=""><br /><small>Finalizar</small></a>
                    </li> 
                </ul>
            </div>
<?php
		} 
	}
}
?>            
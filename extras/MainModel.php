<?php
require_once "DbModel.php";

class MainModel extends DbModel
{
    /**
     * <p>Encripta a mensagem usando o "openssl_encrypt"</p>
     * @param string $string
     * <p>Mensagem a ser encriptada</p>
     * @return string
     * <p>Retorna o valor já encriptado</p>
     */
    public function encryption($string)
    {
        $output = false;
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    /**
     * <p>Decripta uma mensagem encriptada com a função "encryption"</p>
     * @param string $string
     * <p>Mensagem a ser decriptada</p>
     * @return string
     * <p>Retorna a mensagem decriptada</p>
     */
    protected function decryption($string)
    {
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
        return $output;
    }

    public function dinheiroBr($valor)
    {
        $valor = number_format($valor, 2, ',', '.');
        return $valor;
    }

    function valorPorExtenso($valor = 0)
    {
        //retorna um valor por extenso
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];
        $rt = "";

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : " ";
            if ($valor == "000") $z++; elseif ($z > 0) $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : "") . $r;
        }
        return ($rt ? $rt : "zero");
    }

    //retorna sim para campos valo = 1 e não para campos valor=0
    public function simNao($campo):string
    {
        if ($campo == 1 ) {
            return "Sim";
        } else {
            return "Não";
        }
    }

    //checa se o campo do parâmetro possuí algum dado, caso não possua, ele retorna "Não cadastrado"
    public function checaCampo($campo):string
    {
        if ($campo == NULL || $campo == '') {
            return "Não cadastrado";
        } else {
            return $campo;
        }
    }
}
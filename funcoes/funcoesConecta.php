<?php
// Conexão de Banco MySQLi
function bancoMysqli()
{
	$servidor = 'localhost';
	$usuario = 'root';
	$senha = '';
	$banco = 'siscontrat';
	$con = mysqli_connect($servidor,$usuario,$senha,$banco);
	mysqli_set_charset($con,"utf8");
	return $con;
}
// Conexão de Banco com PDO
function bancoPDO()
{
	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'siscontrat';
	$charset = 'utf8';
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset;";

	try {
		$conn = new PDO($dsn, $user, $pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $conn;
	}
	catch(PDOException $e)	{
		echo "Erro " . $e->getMessage();
	}
}
// Conexão com banco do CAPAC
function bancoCapac()
{
    $servidor = 'localhost';
    $usuario = 'root';
    $senha = '';
    $banco = 'capac_new';
    $con = mysqli_connect($servidor,$usuario,$senha,$banco);
    mysqli_set_charset($con,"utf8");
    return $con;
}

// Conexão com banco do CAPAC
function bancoCapacAntigo()
{
    $servidor = 'localhost';
    $usuario = 'root';
    $senha = '';
    $banco = 'capac';
    $con = mysqli_connect($servidor,$usuario,$senha,$banco);
    mysqli_set_charset($con,"utf8");
    return $con;
}

// Cria conexao ao banco de CEPs.
function bancoMysqliCep()
{
	$servidor = 'localhost';
	$usuario = 'root';
	$senha = '';
	$banco = 'cep';
	$con = mysqli_connect($servidor,$usuario,$senha,$banco);
	mysqli_set_charset($con,"utf8");
	return $con;
}

define('SERVER', "127.0.0.1");

define('DB1', "siscontrat");
define('USER1', "root");
define('PASS1', "");

define('DB2', "capac_new");
define('USER2', "root");
define('PASS2', "");

define('SGDB1', "mysql:host=".SERVER.";dbname=".DB1);
define('SGDB2', "mysql:host=".SERVER.";dbname=".DB2);

define('METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'S3cr3t');
define('SECRET_IV', '123456');
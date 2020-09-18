<?php

$funcao = $_REQUEST["action"];

if (function_exists($funcao)) {
	call_user_func($funcao);
}

function valida_cpf_postgre($cpf){


    $con_string = "host=ecospb.ddns.net port=5432 dbname=persona21062018 user=postgres password=postgres";
	if(!$dbcon = pg_connect($con_string)) die ("Erro ao conectar ao banco<br>".pg_last_error($dbcon));


	$sql = "SELECT tr.cpf FROM persona.trabalhadores tr";
	$query = pg_query($dbcon, $sql);
	pg_close($dbcon);

	
	while ($linha = pg_fetch_array($query) ) {
		if($linha['cpf'] === $cpf){
			return true;
		} 
    }
    return false;
}

function usuario_existe($cpf){

	$conecta = mysql_connect("localhost", "root", "mericunofoide") or print (mysql_error()); 
	mysql_select_db("sistema_login", $conecta) or print(mysql_error()); 
	$sql = "SELECT cpf FROM usuarios"; 
	$result = mysql_query($sql, $conecta);

 
	while($linha = mysql_fetch_array($result)) { 

		if($linha['cpf'] === $cpf){
			
			return true;
		}
	}  
	mysql_free_result($result); 
	mysql_close($conecta);
	return false;
}


function cadastrar(){

	$cpf = trim($_POST["cpf"]); // trim limpa espaços da dir e esq
	$senha = trim($_POST["senha"]);
	$email = trim($_POST["email"]);

	if($email == ""){
		$email = NULL;
	}

	if((strlen($cpf) != 11) || (count(spliti (" ", $cpf)) > 1)){

		echo "<script>alert('CPF invalido...');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if( !is_null($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))){

		echo "<script>alert('E-mail invalido!');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if((strlen($senha) < 6) || (count(spliti (" ", $senha)) > 1)){

		echo "<script>alert('O Campo senha deve conter no minimo 6 caracteres (sem espacos em branco)');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if(!valida_cpf_postgre($cpf)){

		echo "<script>alert('CPF nao cadastrado na ECOS, entre em contato com o Departamento Pessoal...');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if(usuario_existe($cpf)){

		echo "<script>alert('CPF ja possui cadastro no sistema...');</script>";
		echo "<script>window.history.back();</script>";
		

	}else{

		$strcon = mysqli_connect('localhost','root','mericunofoide','sistema_login') or die('Erro ao conectar ao banco de dados');

		if($email == NULL){
			$sql = "INSERT INTO usuarios (`user_id`, `cpf`, `email`, `user_password`) VALUES (0,'{$cpf}',NULL,'{$senha}')";
		}else{
			$sql = "INSERT INTO usuarios (`user_id`, `cpf`, `email`, `user_password`) VALUES (0,'{$cpf}','{$email}','{$senha}')";
		}

		
		
		if(mysqli_query($strcon,$sql)){

			mysqli_close($strcon);
			echo "<script>alert('Usuario cadastrado com sucesso...');</script>";
			echo "<script>window.history.back();window.history.back();</script>";

		}else{

			mysqli_close($strcon);
			echo "<script>alert('Erro ao tentar cadastrar usuario...');</script>";
			echo "<script>window.history.back();</script>";
		}	
	}
}

function getCPF($id){

	$conecta = mysql_connect("localhost", "root", "mericunofoide") or print (mysql_error()); 
	mysql_select_db("sistema_login", $conecta) or print(mysql_error()); 
	$sql = "SELECT * FROM usuarios where user_id = '{$id}'"; 
	$result = mysql_query($sql, $conecta);
	mysql_close($conecta);

	$linha = mysql_fetch_array($result);
	return $linha['cpf'];
}

function editar(){

	$cpf = trim($_POST["cpf"]); // trim limpa espaços da dir e esq
	$senha = trim($_POST["senha"]);
	$email = trim($_POST["email"]);
	$id = trim($_POST["id"]);

	if($email == ""){
		$email = NULL;
	}

	if((strlen($cpf) != 11) || (count(spliti (" ", $cpf)) > 1)){

		echo "<script>alert('CPF invalido...');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if( !is_null($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL))){

		echo "<script>alert('E-mail invalido!');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if((strlen($senha) < 6) || (count(spliti (" ", $senha)) > 1)){

		echo "<script>alert('O Campo senha deve conter no minimo 6 caracteres (sem espacos em branco)');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if(!valida_cpf_postgre($cpf)){

		echo "<script>alert('CPF nao cadastrado na ECOS, entre em contato com o Departamento Pessoal...');</script>";
		echo "<script>window.history.back();</script>";
		

	}else if(usuario_existe($cpf) && (getCPF($id) != $cpf )     ){

		echo "<script>alert('CPF ja possui cadastro no sistema...');</script>";
		echo "<script>window.history.back();</script>";
		

	}else{

		$strcon = mysqli_connect('localhost','root','mericunofoide','sistema_login') or die('Erro ao conectar ao banco de dados');

		if($email == NULL){
			
			$sql = "UPDATE usuarios SET email = NULL, user_password = '{$senha}' WHERE user_id = '{$id}'";

		}else{
			$sql = "UPDATE usuarios SET email = '{$email}', user_password = '{$senha}' WHERE user_id = '{$id}'";
		}

		
		
		if( $strcon->query($sql) ){ 

			mysqli_close($strcon);
			echo "<script>alert('Usuario editado com sucesso...');</script>";
			echo "<script>window.history.back();window.history.back();</script>";

		}else{

			mysqli_close($strcon);
			echo "<script>alert('Erro ao tentar editar usuario...');</script>";
			echo "<script>window.history.back();</script>";
		}	
	}
}

?>

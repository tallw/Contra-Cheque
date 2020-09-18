<?php 

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

function get_email($cpf){

	$conecta = mysql_connect("localhost", "root", "mericunofoide") or print (mysql_error()); 
	mysql_select_db("sistema_login", $conecta) or print(mysql_error()); 
	$sql = "SELECT * FROM usuarios where cpf = '{$cpf}'"; 
	$result = mysql_query($sql, $conecta);
	mysql_close($conecta);

	$linha = mysql_fetch_array($result);
	return $linha['email'];
}



$cpf = $_GET["cpf"];

if((strlen($cpf) != 11) || (count(spliti (" ", $cpf)) > 1)){

	echo "<script>alert('CPF invalido...');</script>";
	echo "<script>window.history.back();</script>";

}else if(!valida_cpf_postgre($cpf)){

	echo "<script>alert('CPF nao cadastrado na ECOS, entre em contato com o Departamento Pessoal...');</script>";
	echo "<script>window.history.back();</script>";
		

}else if(!usuario_existe($cpf)){

	echo "<script>alert('CPF nao cadastrado no sistema, faca seu cadastro na opcao ao lado...')";
	echo "<script>window.history.back();</script>";
		

}else{

	$email = get_email($cpf);

	if(is_null($email) || $email == ""){

		echo "<script>alert('Voce nao possui E-mail de recuperacao de senha em seu cadastro, entre em contato com o Departamento Pessoal...');</script>;";
		echo "<script>window.history.back();</script>";

	}else{

		$pedaco = spliti ("@", $email)[0];

		echo "<script> if(confirm('Confirma envio da sua senha para o email ".$pedaco."...?')) { location.href= 'envia_email.php?cpf=".$cpf."' ;}else{window.history.back();}</script>";

	}
	
}


?>
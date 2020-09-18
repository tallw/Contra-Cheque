<?php


session_cache_expire(1);
session_start();
include('../login/config.php');
include('../login/verifica_login.php');
include('../login/redirect.php');

$cpf= $_GET['cpf'];	

$_SESSION['usuario'] = $cpf;


$conecta = mysql_connect("localhost", "root", "mericunofoide") or print (mysql_error()); 
mysql_select_db("sistema_login", $conecta) or print(mysql_error()); 
$sql = "SELECT * FROM usuarios where cpf = '{$cpf}'"; 
$result = mysql_query($sql, $conecta);
mysql_close($conecta);

$linha = mysql_fetch_array($result);

$email = $linha['email'];
$senha = $linha['user_password'];

$id = $linha['user_id'];

?>



<!doctype html>
<html>

	<head>
		<meta charset="UTF-8">	
		<title>EDICAO DE USU&Aacute;RIO ECOSPB</title>
		<link rel="shortcut icon" type="image/x-icon" href="../fotos/icon.png" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/custom.css">
		<script src="funcoes.js" type="text/javascript"></script>
	</head>

	<script type="text/javascript">

		function validateForm() {
    		var x = document.forms["cpf"]["cpf"].value;
    		if (x == "") {
        		alert("Preencher o Campo");
        		return false;
    		}
		}

	</script>

	<body style="background-image:  url('../images/background.jpg');">
	

		<div id="demo">
			<center><legend style="color: white"><h1><b>EDI&Ccedil;&Atilde;O DE USU&Aacute;RIO ECOSPB</b></h1></legend></center>
			
			
		</div>

		<br/>	
		<form id="formulario" method="post" action="action.php" name="formulario">

			<input type="hidden" id="action" name="action" />

			<input name="id" id="id" type="hidden" value= <?php echo $id; ?>  />

			<input name="cpf" id="cpf" type="hidden" value= <?php echo $cpf; ?>  />

			
 				
			<center><label style="color: white"> <?php echo "CPF: ".$cpf; ?> </label></center><br/>

			<center>E-mail: <input type="text" id="email" name="email" onsubmit="return validateForm()" maxlength="100" value = <?php echo $email; ?> placeholder="Infome um E-mail para recuperação de senha:" style="width: 300px;"> _</center><br/>
			<center>Senha: <input type="password"  id="senha" name="senha" placeholder="Infome a sua Senha:" value = <?php echo $senha; ?> style="width: 300px;"> *</center><br/>

			<center><input type="button" value="SALVAR" onclick="javascript:doPost('formulario', 'editar');"></center><br/><br/>
			
		</form>

			<center><input type="button" value="VOLTAR" onclick="window.history.back(); "></center>	

	</body>
</html>
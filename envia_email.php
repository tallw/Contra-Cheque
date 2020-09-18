<?php 

$cpf = $_GET['cpf'];

$conecta = mysql_connect("localhost", "root", "mericunofoide") or print (mysql_error()); 
mysql_select_db("sistema_login", $conecta) or print(mysql_error()); 
$sql = "SELECT * FROM usuarios where cpf = '{$cpf}'"; 
$result = mysql_query($sql, $conecta);
mysql_close($conecta);

$linha = mysql_fetch_array($result);
$email = $linha['email'];
$senha = $linha['user_password'];

if(is_null($email) || $email == ""){

		echo "<script>alert('Voce nao possui E-mail de recuperacao de senha em seu cadastro, entre em contato com o Departamento Pessoal...');</script>;";
		echo "<script>window.history.back();window.history.back();</script>";

}else{


	$txtNome	= 'ECOS Contra-cheque...';
	$txtAssunto	= 'Envio de recuperacao de senha para acesso ao contra-cheque...';
	$txtEmail	= $email;
	$txtMensagem = 'A senha referente ao CPF: '.$cpf.' é: '.$senha;

	/* Montar o corpo do email*/
	$corpoMensagem 		= "<b>Remetente: </b> ".$txtNome." <br><b>Assunto: </b> ".$txtAssunto."<br><b>Mensagem: </b> ".$txtMensagem;

	/* Extender a classe do phpmailer para envio do email*/
	require_once("../site_ecospb/phpmailer/class.phpmailer.php");

	/* Definir Usuário e Senha do Gmail de onde partirá os emails*/
	define('GUSER', 'ecos.avisos'); 
	define('GPWD', 'EcosAvisos1');

	function smtpmailer($para, $de, $nomeDestinatario, $assunto, $corpo) { 
		global $error;
		$mail = new PHPMailer();
		/* Montando o Email*/
		$mail->IsSMTP();		    /* Ativar SMTP*/
		$mail->SMTPDebug = 0;		/* Debugar: 1 = erros e mensagens, 2 = mensagens apenas*/
		$mail->SMTPAuth = true;		/* Autenticação ativada	*/
		$mail->SMTPSecure = 'tls';	/* TLS REQUERIDO pelo GMail*/
		$mail->Host = 'smtp.gmail.com';	/* SMTP utilizado*/
		$mail->Port = 587;  		   /* A porta 587 deverá estar aberta em seu servidor*/
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->SetFrom($de, $nomeDestinatario);
		$mail->Subject = $assunto;
		$mail->Body = $corpo;
		$mail->AddAddress($para);
		$mail->IsHTML(true);

		/* Função Responsável por Enviar o Email*/
		if(!$mail->Send()) {
			$error = "<font color='red'><b>Mail error: </b></font>".$mail->ErrorInfo; 
			return false;
		} else {
			$error = "<font color='blue'><b>Mensagem enviada com Sucesso!</b></font>";
			return true;
		}
	}

	/* Passagem dos parametros: email do Destinatário, email do remetende, nome do remetente, assunto, mensagem do email.*/
 	if (smtpmailer($txtEmail, 'ecos.avisos@gmail.com', $txtNome, $txtAssunto, $corpoMensagem)) {
 		//echo "<script>window.history.back();window.history.back();</script>";
	 	Header("location: sucesso.php"); // Redireciona para uma página de Sucesso.
	}
	if (!empty($error)) echo $error;
}

?>
<?php
// Inicia a sessão
session_start();
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		
		<title>ECOS PB Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="fotos/icon.png" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<link rel="stylesheet" type="text/css" href="css/pag.css">
	</head>
	<body>
		<div class= 'legend'>
			<legend><h1><b>CONTRA-CHEQUE ECOS-PB</b></h1></legend>
		</div>
		<div class='jumbotron'>
		<form action="index.php" method="post" class="form-horizontal col-md-7">
			  <table>
				<div class='col-md-5'>
					<input class="form-control" type="text" name="usuario" maxlength="11" required placeholder="Infome o seu CPF">	
				</div>
				<tr>
					<td><input type="submit" class="btn btn-primary" value="Entrar"></td>
				</tr>		
			</table>
			<table>
				<div class='col-md-5'>
			    	<input class="form-control" type="password" name="senha"  required placeholder="Infome a sua Senha">
				</div>
				<tr>
					<td><a class="black_text" href="cria-usuarios/nova_senha.html"><b>PRIMEIRO ACESSO</b></a></td>				
				</tr>
				<?php if ( ! empty( $_SESSION['login_erro'] ) ) :?>
					<tr>
						<td style="color: red;"><?php echo $_SESSION['login_erro'];?></td>
						<?php $_SESSION['login_erro'] = ''; ?>
					</tr>
				<?php endif; ?>
			</table>
		</form>
	</div>
		<div class="rodape">
  			<p>Av.General Justo, 275 - sala 318 B - Torre - João Pessoa - CEP: 20021-130<br />
    		Tel:  (21) 2517-3314 - E-mail: <a href="netoantonio@ecospb.com.br">netoantonio@ecospb.com.br</a><br />
  			</p>
  			<h5><em>Copyright &copy; 2013 - Espaço, Cidadania e Oportunidades Sociais - ECOS</em></h5>
		</div>
	</body>
</html>
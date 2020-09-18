<?php
// Inicia a sessão
session_start();

?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		
		<title>ECOS Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="fotos/icon.png" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
	<link rel="stylesheet" type="text/css" href="css/pag.css">
	</head>
	<style type="text/css">

		.campos_e{
			position: absolute; 
			left: 40px; 	
			width: 300px;
		}
		.campos_d{
			position: absolute; 
			left: 680px; 	
			width: 300px;
		}

		.btn_e{
			position: absolute; 
			left: 40px; 	
			
		}

		.btn_d{
			position: absolute; 
			left: 900px; 	
			
		}

		#teste{
    
    		background-image: url('images/background.jpg');
		}

	</style>
	<script type="text/javascript">

		function checkNumber(valor) {
  			var regra = /^[0-9]+$/;
  			if (valor.match(regra)) {
    			return true;
  			}else{
    			return false;
  			}
		}  
		
		function ChamarLink() {

        	var cpf = document.getElementById("cpf").value;	
        	
        	if((cpf.length < 11) || (cpf.split(" ").length > 1) || (!checkNumber(cpf))){
        		alert("CPF Invalido...");
        	}else{
        		location.href = "valida_email.php?cpf=" + cpf;
        		//document.getElementById('gerar').innerHTML = "<a class='black_text' href='valida_email.php?cpf="+cpf+"'><font size='2'>CLIQUE AQUI</font></a>";
        	}
        	
    	}

	</script>
	
	<body  id="teste">
		<div>
		<div class= 'legend'>
			<legend style="color: white"><h1><b>CONTRA-CHEQUE ECOS</b></h1></legend>
		</div>
		<div class='jumbotron'>

		<form action="index.php" method="post" class="form-horizontal col-md-7">
			  <table>
				<div class='campos_e' style="top: 10px;">
					<input class="form-control" type="text" id="cpf" name="usuario" maxlength="11"  placeholder="Infome o seu CPF">	
				</div>

				<div class="btn_e" style="top: 110px;">
					<input type="submit" class="btn btn-primary" value="Entrar">
				</div>		
			</table>

			<table>
				<div class='campos_e' style="top: 60px;">
			    	<input class="form-control" type="password" name="senha"  required placeholder="Infome a sua Senha">
				</div>
				
				<div class='campos_d' style="top: 120px;">
					<a class="black_text" href="cria-usuarios/nova_senha.html" style="color: white"><b>CLIQUE AQUI</b></a><b> PARA FAZER SEU CADASTRO</b>
				</div>				
				
				<?php if ( ! empty( $_SESSION['login_erro'] ) ) :?>
					<tr>
						<td style="color: red;"><?php echo $_SESSION['login_erro'];?></td>
						<?php $_SESSION['login_erro'] = ''; ?>
					</tr>
				<?php endif; ?>
			</table>

			<div class="btn_d" style="top: -30px;">
				<input class="btn btn-primary" type="button" onclick="window.history.back();" value="VOLTAR">
			</div>

			<div style="top: 115px; left: 150px; position: absolute; ">

				<input style="font-size: 12px; background-color: transparent; color: red; border: 0" class="btn btn-primary" type="button" onclick="ChamarLink()" value="Esqueceu sua senha?">
	
			</div>
			
			
		</form>

		 
			
	</div>
</div>
		
	</body>
</html>
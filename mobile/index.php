
<?php
session_start();
include('../login/config.php');
include('../login/verifica_login.php');
include('../login/redirect.php');

	$cpf= $_SESSION['usuario'];	
	
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
	<title>Cliente ECO</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>

<script language="javascript">

    function ChamarLink() {
        var ano = document.getElementById("select_ano").value;
		var mes = document.getElementById("select_mes").value;
		var cpf = "<?php print $cpf; ?>";

        document.getElementById("gerar").innerHTML = "<a href='../index2.php?ano="+ano+"&mes="+mes+"&cpf="+cpf+"'>Click</a>";
    }

</script>

<body>
	<div class='container'>
		<fieldset>
			<!-- Cabecalho da Listagem -->
			<legend><h1>Olá, <b><?php echo $nome = $_SESSION['nome_usuario']?></b>, <a href="../login/sair.php">clique aqui</a> para sair.</h1></legend>
			<!-- Link para página de cadastro -->
			<div class='clearfix'></div>
			
			<select class="form-control" style="width: 150px" id="select_ano" name="select_ano" ">
				<option value="">Escolha o Ano</option>
				<?php for ($i=2017; $i < date('Y')+1; $i++) { 
						echo '<option value='.$i.'>'.$i.'</option>';
				}?>
			</select>

			<br/>
				
			<select class="form-control" style="width: 150px" id="select_mes" name="select_mes" onchange="ChamarLink();">
				<option value="">Escolha o M&ecirc;s</option>
				<?php $meses = array('Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'); ?>	
				<?php for ($i=0; $i < 12; $i++) { 
					echo '<option value='.($i+1).'>'.$meses[$i].'</option>';
				}?>	
			</select>

			<br>
			<div id="gerar"></div>							 
					
		</fieldset>
	</div>
</body>
</html>
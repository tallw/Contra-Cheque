<?php
session_cache_expire(1);

// #################################### evita erro miss confirmar pagina

header('Cache-Control: no cache');
session_cache_limiter('private_no_expire'); // Cliente não vai receber o header expirado.
session_start();

//#########################################


include('login/config.php');
include('login/verifica_login.php');
include('login/redirect.php');

	$cpf= $_SESSION['usuario'];	

	$con_string = "host=ecospb.ddns.net port=5432 dbname=persona21062018 user=postgres password=postgres";
	if(!$dbcon = pg_connect($con_string)) die ("Erro ao conectar ao banco<br>".pg_last_error($dbcon));

	$sql = "SELECT tr.nome from persona.trabalhadores tr where tr.cpf = '{$cpf}'";
	$query = pg_query($dbcon, $sql);
	pg_close($dbcon);

	while ($linha = pg_fetch_array($query) ) {

        $nome_pessoa = $linha['nome'];
           
    }

    $DT_atual = new DateTime( 'now', new DateTimeZone( 'America/Fortaleza') );
    $DT_atual = $DT_atual->format('Y-m-d');

    $partes = explode("-", $DT_atual);
	$dia = $partes[2];
	$mes = $partes[1];
	$ano = $partes[0];

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
	<title>Cliente ECO</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="fotos/icon.png" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/custom.css">
</head>

<script language="javascript">

    function verifica_decimo() {
        var dia = "<?php print $dia; ?>";
		var mes = "<?php print $mes; ?>";
		var ano = "<?php print $ano; ?>";

		if (mes === '12' && dia < 26 ) {

			var cpf = "<?php print $cpf; ?>";
			document.getElementById("gerar_decimo").innerHTML = "<a href='index3.php?ano="+ano+"&mes="+mes+"&cpf="+cpf+"'>Gerar PDF do seu Contra-cheque (Décimo)</a>";

		} 
    }

    function ChamarLink(){

    	var ano = document.getElementById("select_ano").value;
		var mes = document.getElementById("select_mes").value;
		var cpf = "<?php print $cpf; ?>";

        document.getElementById("gerar").innerHTML = "<a style='color: red' href='index2.php?ano="+ano+"&mes="+mes+"&cpf="+cpf+"'>Gerar PDF do seu Contra-cheque</a>";

    }

    function edita_user(){
    	var cpf = "<?php print $cpf; ?>";
    	location.href = "cria-usuarios/edita_user.php?cpf=" + cpf;
    }

</script>

<body onload="return verifica_decimo()" style="background-image:  url('images/background.jpg');">
	<div class='container'>
		<fieldset>
			<!-- Cabecalho da Listagem -->
			<legend style="color: white"><h1>Olá, <b><?php echo $nome_pessoa ?></b>, <a style="color: red" href="login/sair.php">clique aqui</a> para sair.</h1></legend>
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

			<div style="top: 150px; left: 1000px; position: absolute; ">

				<input class="btn btn-primary" type="button" onclick="edita_user()" value="EDITAR USUARIO">
	
			</div>

			<br>
			<div id="gerar"></div>
			<br>
			<br>
			<div id="gerar_decimo"></div>							 
					
		</fieldset>
	</div>

	<div style="top: 150px; left: 600px; position: absolute; ">
  			<center><p style="color: white"><b>Escolha o Ano e M&ecirc;s da movimenta&ccedil;&acirc;o...
  			<b></p></center>
  			
		</div>
</body>
</html>
<?php
// Pagina de Cadastro de cliente.
// Editada e adaptada  para ECOS PB


// Inclui o arquivo de configuracao
include('../login/config.php');

// Variavel para preencher o erro (se existir)
$erro = false;

// Apaga usuários
if ( isset( $_GET['del'] ) ) {
	// Delete de cara (sem confirmacao)
	$pdo_insere = $conexao_pdo->prepare('DELETE FROM usuarios WHERE user_id=?');
	$pdo_insere->execute( array( (int)$_GET['del'] ) );
	
	// Redireciona para o index.php
	header('location: index.php');
}

// Verifica se algo foi postado para publicar ou editar
if ( isset( $_POST ) && ! empty( $_POST ) ) {
	// Cria as variáveis
	foreach ( $_POST as $chave => $valor ) {
		$$chave = $valor;
		
		// Verifica se existe algum campo em branco
		if ( empty ( $valor ) ) {
			// Preenche o erro
			$erro = 'Existem campos em branco.';
		}
	}
	
	// Verifica se as variaveis foram configuradas
	if ( empty( $form_usuario ) || empty( $form_senha )) {
		$erro = 'Existem campos em branco.';
	}
	
	// Verifica se o usuario existe
	$pdo_verifica = $conexao_pdo->prepare('SELECT * FROM usuarios WHERE cpf = ? LIMIT 1');
	$pdo_verifica->execute( array( $form_usuario ) );

	
	// Captura os dados da linha
	$user_id = $pdo_verifica->fetch();
	$user_id = $user_id['user_id'];
	
		
	// Verifica se tem algum erro
	if ( ! $erro ) {
		// Se o usuario existir, atualiza
		if ( ! empty( $user_id )) {
			$pdo_insere = $conexao_pdo->prepare('UPDATE usuarios SET cpf=?, user_password=? WHERE user_id=?');
			$pdo_insere->execute( array( $form_usuario,  crypt( $form_senha ),  $user_id ) );
			echo "Cadastro feito com sucesso!", $user_id['user_password'];   //Sucesso
			
		// Se o usuario nao existir, cadastra novo
		} else {
			//$pdo_insere = $conexao_pdo->prepare('INSERT INTO usuarios (cpf, user_password, user_name) VALUES (?, ?, ?)');
			//$pdo_insere->execute( array( $form_usuario, crypt( $form_senha ), $form_nome ) );
			echo "Nao faz parte do Quadro de Funcionarios ECOS PB!";
			
		}
	}
}
?>

<html>
	<head>
		<meta charset="UTF-8">
		
		<title>Cadastro</title>
	</head>
	<body>
		<p>Para editar, apenas digite o nome de usuário que deseja editar.</p>
		<p><a href="../login.php">Clique aqui</a> para testar.</p>
		<form action="" method="post">
			<table>
				<tr>
					<td><b>CPF:</b></td>
				</tr>
				<tr>
					<td><input type="text"  name="form_usuario" maxlength="11" required></td>
				</tr>
				<tr>
					<td><b>Senha:</b></td>
				</tr>
				<tr>
					<td><input type="password" name="form_senha" required></td>
				</tr>
				<tr>
					<td><b>Nome:</b></td>
				</tr>
				
				
				<?php if ( ! empty ( $erro ) ) :?>
					<tr>
						<td style="color: red;"><?php echo $erro;?></td>
					</tr>
				<?php endif; ?>
				
				<tr>
					<td><input type="submit" value="Cadastrar"></td>
				</tr>
			</table>
		</form>
		<!-- <?php 
		// Mostra os usuarios
		$pdo_verifica = $conexao_pdo->prepare('SELECT * FROM usuarios ORDER BY user_id DESC');
		$pdo_verifica->execute();
		?>
		
		<table border="1" cellpadding="5">
		<tr>
			<th>ID</th>
			<th>Nome</th>
			<th>Usuário</th>
			<th>Senha Criptografada</th>
			<th>Ação</th>
		</tr>
		<?php
		while( $fetch = $pdo_verifica->fetch() ) {
			echo '<tr>';
			echo '<td>' . $fetch['user_id'] . '</td>';
			echo '<td>' . $fetch['cpf'] . '</td>';
			echo '<td>' . $fetch['user_password'] . '</td>';
			echo '<td> <a style="color:red;" href="?del=' . $fetch['user_id'] . '">Apagar</a> </td>';
			echo '</tr>';
		}
		?> !-->
		
		</table>
	</body>
</html>

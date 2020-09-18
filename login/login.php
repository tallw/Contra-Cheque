<?php
// Inicia a sessão
session_start();
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		
		<title>Login</title>
	</head>
	<body>
		<form action="index.php" method="post">
			<table>
				<tr>
					<td>Usuário</td>
				</tr>
				<tr>
					<td><input type="text" name="usuario" required></td>
				</tr>
				<tr>
					<td>Senha</td>
				</tr>
				<tr>
					<td><input type="password" name="senha" required></td>
				</tr>
				
				<tr>
					<td><a href="cria-usuarios/">Criar usuário</a></td>
				</tr>
				
				<?php if ( ! empty( $_SESSION['login_erro'] ) ) :?>
					<tr>
						<td style="color: red;"><?php echo $_SESSION['login_erro'];?></td>
						<?php $_SESSION['login_erro'] = ''; ?>
					</tr>
				<?php endif; ?>
				
				<tr>
					<td><input type="submit" value="Entrar"></td>
				</tr>
			</table>
		</form>
	</body>
</html>
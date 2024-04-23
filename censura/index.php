<?php
require_once 'conf/bd.php';

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de bloqueio de domínios.</title>
    <link rel="stylesheet" type="text/css" href="conf/style.css">

</head>

<body>

    <div class="overlay"></div>
    <div class="container">
        <h1>Gerenciador de Domínios</h1>
        <a href="ver.php">Visualizar Domínios Cadastrados</a>
        <br><br>
        <h2>Adicionar Domínio</h2>
        <form action="adicionar_dominio.php" method="post">
            <label for="dominios">Digite os domínios a serem bloqueados (um por linha):</label><br>
            <textarea name="dominios" id="dominios" rows="20" cols="40"></textarea><br>
            <input type="submit" value="Salvar"><br>
        </form>
		<form action="logout.php" method="post">
			<input type="submit" value="Sair">
		</form>
    </div>
</body>
</html>

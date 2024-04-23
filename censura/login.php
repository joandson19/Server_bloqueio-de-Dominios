<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="conf/style1.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="autenticar.php" method="post">
            <label for="nome_usuario">Nome de usuário:</label>
            <input type="text" id="nome_usuario" name="nome_usuario" autocomplete="off">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" autocomplete="off">
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>

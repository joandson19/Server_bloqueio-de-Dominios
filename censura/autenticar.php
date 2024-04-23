<?php
session_start();
require('conf/bd.php');

$conn = new mysqli($host, $usuario, $senha, $banco_de_dados);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST["nome_usuario"];
    $senha = $_POST["senha"];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ? AND senha = ?");
    $stmt->bind_param("ss", $nome_usuario, $senha);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Autenticação bem-sucedida, redireciona para a página principal
        $_SESSION['nome_usuario'] = $nome_usuario;
        header("Location: index.php");
    } else {
        // Autenticação falhou, exibe mensagem de erro
        echo "Nome de usuário ou senha inválidos.";
    }

    $stmt->close();
}

$conn->close();
?>

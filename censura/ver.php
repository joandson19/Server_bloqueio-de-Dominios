<?php
require_once 'conf/bd.php';

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($host, $usuario, $senha, $banco_de_dados);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir'])) {
    $dominio_id = $_POST['excluir'];
    
    // Obtém o domínio a ser excluído
    $stmt = $conn->prepare("SELECT dominio FROM dominios WHERE id = ?");
    $stmt->bind_param("i", $dominio_id);
    $stmt->execute();
    $stmt->bind_result($dominio);
    $stmt->fetch();
    $stmt->close();
    
    // Exclui o domínio do banco de dados
    $stmt = $conn->prepare("DELETE FROM dominios WHERE id = ?");
    $stmt->bind_param("i", $dominio_id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT id, dominio FROM dominios";
$resultado = $conn->query($sql);

// Contador de domínios cadastrados
$num_dominios = $resultado->num_rows;

if ($num_dominios > 0) {
    echo "<h1>Domínios Cadastrados ($num_dominios)</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Domínio</th><th>Ações</th></tr>";
    while($row = $resultado->fetch_assoc()) {
        echo "<tr><td>" . $row["dominio"]. "</td><td><form action='' method='post'><input type='hidden' name='excluir' value='" . $row["id"] . "'><input type='submit' value='Excluir'></form></td></tr>";
    }
    echo "</table>";
} else {
    echo "<h1>Nenhum domínio cadastrado</h1>";
}

$conn->close();
?>

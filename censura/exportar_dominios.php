<?php
require_once 'conf/bd.php';

$conn = new mysqli($host, $usuario, $senha, $banco_de_dados);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para selecionar todos os domínios cadastrados
//$sql = "SELECT dominio FROM dominios";
$sql = "SELECT dominio FROM dominios ORDER BY dominio ASC";

$resultado = $conn->query($sql);

if ($resultado->num_rows > 0) {
    // Define o tipo de conteúdo como texto simples
    header('Content-Type: text/plain');
    
    // Loop através dos resultados e imprime os domínios
    while($row = $resultado->fetch_assoc()) {
        echo $row['dominio'] . "\n";
    }
} else {
    echo "Nenhum domínio cadastrado.";
}

$conn->close();
?>

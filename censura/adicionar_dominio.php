<?php

require('conf/bd.php');

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($host, $usuario, $senha, $banco_de_dados);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o campo de domínios foi preenchido
    if (isset($_POST["dominios"]) && !empty($_POST["dominios"])) {
        // Obtém os domínios do formulário e separa-os em um array
        $dominios = explode("\n", $_POST["dominios"]);
        
        // Remove qualquer espaçamento extra em cada domínio
        $dominios = array_map('trim', $dominios);
        
        // Prepara a consulta SQL para inserção
        $stmt = $conn->prepare("INSERT INTO dominios (dominio) VALUES (?)");
        
        // Inicializa uma variável para controlar se pelo menos um domínio foi adicionado
        $dominio_adicionado = false;
        
        // Loop pelos domínios
        foreach ($dominios as $dominio) {
            // Verifica se o domínio já existe no banco de dados
            $verificar_dominio = $conn->prepare("SELECT id FROM dominios WHERE dominio = ?");
            $verificar_dominio->bind_param("s", $dominio);
            $verificar_dominio->execute();
            $verificar_dominio->store_result();
            if ($verificar_dominio->num_rows == 0) {
                // Insere o domínio no banco de dados se não existir
                $stmt->bind_param("s", $dominio);
                $stmt->execute();
                
                // Define a variável como verdadeira se pelo menos um domínio for adicionado
                $dominio_adicionado = true;
            }
            $verificar_dominio->close();
        }
        
        // Exibe a mensagem apenas se pelo menos um domínio foi adicionado
        if ($dominio_adicionado) {
            echo "Domínios adicionados com sucesso!";
        } else {
            echo "Todos os domínios já estão cadastrados.";
        }
        
        // Fecha a conexão
        $stmt->close();
    } else {
        echo "Por favor, insira pelo menos um domínio.";
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
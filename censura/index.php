<?php
require_once 'conf/bd.php';

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

// Buscar a quantidade de domínios que expiram nos próximos 7 dias
$sql_alerta = "SELECT COUNT(*) AS total FROM dominios WHERE data_expiracao BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$result_alerta = $conn->query($sql_alerta);
$dominios_proximos_expiracao = $result_alerta->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de bloqueio de domínios</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <h1>Gerenciador de Domínios</h1>
        <nav>
            <a href="ver.php" class="btn" target="_blank">Visualizar Domínios Cadastrados</a>
        </nav>
        <nav>
            <a href="inserir/" class="btn" target="_blank">Extrair Domínios de PDF</a>
        </nav>
        <br>
        <?php if ($dominios_proximos_expiracao > 0): ?>
            <div class="alert">
                <strong>Atenção!</strong> Existem <?php echo $dominios_proximos_expiracao; ?> domínios que expiram nos próximos 7 dias.
            </div>
        <?php endif; ?>
        <h2>Adicionar Domínio</h2>
        <form action="adicionar_dominio.php" method="post" onsubmit="return validarFormulario();">
            <label for="dominios">Digite os domínios a serem bloqueados (um por linha):</label><br>
            <textarea name="dominios" id="dominios" rows="10" cols="50" oninput="validarDominioLive()"></textarea><br>
            
            <label for="temporario">Domínio Temporário:</label>
            <input type="checkbox" id="temporario" name="temporario" onchange="toggleDataExpiracao()">
            
            <div id="data-expiracao-container" style="display: none;">
                <label for="data_expiracao">Data de Remoção:</label>
                <input type="date" name="data_expiracao" id="data_expiracao">
            </div>
            
            <p id="erro" style="color: red;"></p>
            <input type="submit" value="Salvar" class="btn btn-primary"><br>
        </form>
        <form action="logout.php" method="post">
            <input type="submit" value="Sair" class="btn btn-danger">
        </form>
    </div>
    <script>
        function toggleDataExpiracao() {
            let checkbox = document.getElementById("temporario");
            let dataContainer = document.getElementById("data-expiracao-container");
            dataContainer.style.display = checkbox.checked ? "block" : "none";
        }

        function validarDominioLive() {
            let textarea = document.getElementById("dominios");
            let erro = document.getElementById("erro");
            let linhas = textarea.value.split("\n");
            let regex = /^(?!-)([a-zA-Z0-9-]{1,63}\.)+[a-zA-Z]{2,}$/;
            
            for (let linha of linhas) {
                if (linha.includes(" ") || !regex.test(linha.trim())) {
                    erro.textContent = "Domínio inválido detectado: " + linha;
                    return;
                }
            }
            erro.textContent = "";
        }

		function validarFormulario() {
			let entrada = document.getElementById("dominios").value.trim();
			if (entrada === "") {
				alert("Por favor, insira pelo menos um domínio.");
				return false;
			}

			let linhas = entrada.split("\n");
			let regexASCII = /^[\x00-\x7F]+$/;  // Somente ASCII
			let regexDOMINIO = /^(?!-)([a-zA-Z0-9-]{1,63}\.)+[a-zA-Z]{2,63}$/;

			let validos = [];
			let descartados = [];

			for (let linha of linhas) {
				let dom = linha.trim();
				if (dom === "") continue;

				// Se contém acentuação → descartar
				if (!regexASCII.test(dom)) {
					descartados.push(dom);
					continue;
				}

				// Se não é domínio válido ASCII → descartar
				if (!regexDOMINIO.test(dom)) {
					descartados.push(dom);
					continue;
				}

				// Domínio OK
				validos.push(dom);
			}

			if (validos.length === 0) {
				alert("Nenhum domínio válido encontrado.");
				return false;
			}

			// Atualiza textarea apenas com os válidos
			document.getElementById("dominios").value = validos.join("\n");

			// Se houver descartados, apenas avisa, mas deixa enviar
			if (descartados.length > 0) {
				alert(
					"Os seguintes domínios foram descartados automaticamente (IDN/acentuação ou inválidos):\n\n" +
					descartados.join("\n") +
					"\n\nSomente os válidos serão enviados."
				);
			}

			return true;
		}

    </script>
</body>
</html>

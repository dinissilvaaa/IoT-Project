<!DOCTYPE html> <!-- EU SEI QUE NÃO DEVERIA ESTAR ASSIM MAS O VALIDATOR DAVA ERRO CASO COLOCASSE LÁ EM BAIXO ANTES DO HEAD -->
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Acesso Restrito</title>
    <!-- Link para o estilo da página -->
    <link rel="stylesheet" href="styleH3.css">
</head>

<?php
session_start();

// Verifica se a sessão não contém a chave 'David', indicando que o acesso é restrito
if (!isset($_SESSION['David'])) {
    // Redireciona para 'dashboard.php' após 5 segundos
    header("refresh:5;url=dashboard.php");

    // Exibe mensagem de acesso restrito
    echo <<<HTML
<body>
    <div class="container">
        <h1>Aqui Não</h1>
        <p>Não tens acesso ao histórico. Deixa de ser curioso! Vais voltar para o dashboard em <span class="timer" id="contador">5</span> segundos.</p>
    </div>

    <!-- Script para contar os segundos até o redirecionamento -->
    <script>
        let tempo = 5;  // Tempo inicial de 5 segundos
        const contador = document.getElementById('contador');  // Elemento onde o contador será exibido
        const interval = setInterval(() => {
            tempo--;  // Decrementa o tempo a cada segundo
            contador.textContent = tempo;  // Atualiza o contador na página
            if (tempo <= 0) clearInterval(interval);  // Para o contador quando chega a zero
        }, 1000);  // Intervalo de 1 segundo
    </script>
</body>
HTML;

    exit;  // Impede que o restante da execução continue
}
?>

</html>


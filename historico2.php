<?php
// Inicia a sessão, para acessar ou criar variáveis de sessão
session_start();

// Verifica se o usuário está logado, se não estiver, redireciona para o login
if (!isset($_SESSION['username'])) {
    // Se o usuário não estiver logado, após 5 segundos, será redirecionado para a página de login (index.php)
    header("refresh:5;url=index.php");

    // Exibe uma mensagem de "Acesso Restrito" e interrompe a execução do código
    die("Acesso Restrito");
}

// Carrega os valores dos sensores de arquivos de texto no servidor
// Os dados são lidos e armazenados em variáveis para posterior uso no site

// Leitura dos dados do sensor de temperatura
$valor_temperatura = file_get_contents("api/files/Temperatura/valor.txt"); // Valor da temperatura
$hora_temperatura = file_get_contents("api/files/Temperatura/hora.txt");   // Hora da última leitura
$nome_temperatura = file_get_contents("api/files/Temperatura/nome.txt");   // Nome do sensor de temperatura

// Leitura dos dados do sensor de umidade
$valor_humidade = file_get_contents("api/files/Humidade/valor.txt"); // Valor da humidade
$hora_humidade = file_get_contents("api/files/Humidade/hora.txt");   // Hora da última leitura
$nome_humidade = file_get_contents("api/files/Humidade/nome.txt");   // Nome do sensor de umidade

// Leitura dos dados do sensor de estacionamento
$valor_estacionamento = file_get_contents("api/files/Estacionamento/valor.txt"); // Valor do sensor de estacionamento
$hora_estacionamento = file_get_contents("api/files/Estacionamento/hora.txt");   // Hora da última leitura
$nome_estacionamento = file_get_contents("api/files/Estacionamento/nome.txt");   // Nome do sensor de estacionamento

// Leitura dos dados do sensor de Ar Condicionado
$valor_ArCondicionado = file_get_contents("api/files/ArCondicionado/valor.txt"); // Valor do Ar Condicionado
$hora_ArCondicionado = file_get_contents("api/files/ArCondicionado/hora.txt");   // Hora da última leitura
$nome_ArCondicionado = file_get_contents("api/files/ArCondicionado/nome.txt");   // Nome do sensor de Ar Condicionado

// Leitura dos dados do sensor de Ventoinha
$valor_Ventoinha = file_get_contents("api/files/Ventoinha/valor.txt"); // Valor da Ventoinha
$hora_Ventoinha = file_get_contents("api/files/Ventoinha/hora.txt");   // Hora da última leitura
$nome_Ventoinha = file_get_contents("api/files/Ventoinha/nome.txt");   // Nome do sensor de Ventoinha

// Leitura dos dados do sensor de Sinal
$valor_Sinal = file_get_contents("api/files/Sinal/valor.txt"); // Valor do Sinal
$hora_Sinal = file_get_contents("api/files/Sinal/hora.txt");   // Hora da última leitura
$nome_Sinal = file_get_contents("api/files/Sinal/nome.txt");   // Nome do sensor de Sinal
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Dashboard</title>
    <!-- Definir a meta tag para o controle de layout responsivo -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Inclusão do Bootstrap para facilitar a criação de layout e estilo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Inclusão do arquivo CSS para o estilo personalizado -->
    <link rel="stylesheet" href="styleH2.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-sm bg-light">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><strong>Smart Campus 2024/25</strong></a>
                    </li>
                    <li>
                        <a class="nav-link" href="historico2.php">Histórico</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn container text-black" href="logout.php"><strong>Logout</strong></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

<!-- Título da página -->
<h1>Históricos</h1>

<!-- Menu com as opções de histórico de dados, exibidas com base no nome do usuário -->
<div class="menu">
    <?php
    // Verifica se o usuário logado é o "David", exibindo mais opções
    if ($_SESSION['username'] == "David") {
    ?>
        <!-- Links para o histórico de dados de cada sensor -->
        <a class="opcao" href="historico3.php">Histórico de Temperatura</a>
        <a class="opcao" href="historico3.php">Histórico de Humidade</a>
        <a class="opcao" href="historico3.php">Histórico de Estacionamento</a>
        <a class="opcao" href="historico3.php">Histórico de Ar Condicionado</a>
        <a class="opcao" href="historico3.php">Histórico de Ventoinha</a>
        <a class="opcao" href="historico3.php">Histórico de Sinal</a>
    <?php
    }

    // Verifica se o usuário logado é o "Dinis", exibindo outras opções
    if ($_SESSION['username'] == "Dinis") {
    ?>
        <!-- Links para o histórico de dados de cada sensor, com o nome do sensor passado como parâmetro -->
        <a class="opcao" href="historico1.php?nome=Temperatura">Histórico de Temperatura</a>
        <a class="opcao" href="historico1.php?nome=Humidade">Histórico de Humidade</a>
        <a class="opcao" href="historico1.php?nome=Estacionamento">Histórico de Estacionamento</a>
        <a class="opcao" href="historico1.php?nome=ArCondicionado">Histórico de Ar Condicionado</a>
        <a class="opcao" href="historico1.php?nome=Ventoinha">Histórico de Ventoinha</a>
        <a class="opcao" href="historico1.php?nome=Sinal">Histórico de Sinal</a>
    <?php
    }
    ?>
</div>

<!-- Inclusão dos scripts necessários para o funcionamento do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

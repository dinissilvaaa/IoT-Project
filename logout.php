<!doctype html>
<html lang="en">
<head>
    <!-- Título da página -->
    <title>Logout</title>
</head>

<?php 
// Inicia a sessão para manipulação de dados de sessão
session_start();  

// Remove todas as variáveis da sessão
session_unset();   

// Destroi a sessão
session_destroy();  

// Redireciona o usuário para a página inicial (index.php) após o logout
header("refresh:0;url=index.php"); 

// Encerra o script para evitar que o código PHP continue executando
exit;
?>

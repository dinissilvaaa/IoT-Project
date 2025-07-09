<?php
// Inicia a sessão para manipulação de dados de sessão
session_start();

// Variável para armazenar mensagens de erro
$erro = "";

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Recupera o nome de usuário enviado pelo formulário
    $searchfor = $_POST['username'];

    // Verifica se o nome de usuário foi preenchido
    if (!empty($searchfor)) {
        // Abre o arquivo 'users.txt' para leitura
        $file = fopen('users/users.txt', 'r');
        
        // Verifica se o arquivo foi aberto com sucesso
        if ($file) {
            // Inicializa uma variável para verificar se o usuário foi encontrado
            $userFound = false;

            // Lê o arquivo linha por linha
            while (($line = fgets($file)) !== false) {
                // Divide a linha em duas partes: nome de usuário e senha (separados por ':')
                $contentFile = explode(':', trim($line));

                // Verifica se o nome de usuário corresponde ao enviado no formulário
                if ($contentFile[0] == $searchfor) {
                    $userFound = true;

                    // Verifica se a senha fornecida corresponde à senha armazenada no arquivo
                    if (password_verify($_POST['password'], $contentFile[1])) {
                        // Inicia a sessão com o nome de usuário
                        $_SESSION["username"] = $_POST['username'];
                        // Fecha o arquivo
                        fclose($file);
                        // Redireciona para o dashboard
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        // Senha incorreta
                        $erro = "Senha incorreta.";
                    }
                }
            }

            // Fecha o arquivo após a leitura
            fclose($file);

            // Se o usuário não foi encontrado
            if (!$userFound) {
                $erro = "Usuário não encontrado.";
            }
        } else {
            // Se o arquivo de usuários não foi encontrado
            $erro = "Arquivo de usuários não encontrado.";
        }
    } else {
        // Se o nome de usuário não foi preenchido
        $erro = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Definição do tipo de conteúdo da página -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <!-- Responsividade da página -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Título da página -->
    <title>Login</title>
    
    <!-- Inclusão do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Inclusão do estilo personalizado -->
    <link rel="stylesheet" href="styleI.css">
    
    <!-- Inclusão dos ícones FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Inclusão da fonte Montserrat do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <!-- Formulário de login -->
            <form method="POST" class="AulaForm">
                
                <!-- Logo no topo -->
                <a href="index.php">
                    <img id="logo" src="Imagens/logodash.png" alt="logo" style="width: 300px; height: 150px; margin-bottom: 20px;">
                </a>

                <!-- Campo para o nome de usuário -->
                <div class="mb-3">
                    <label for="exampleInputUser" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" id="exampleInputUser" name="username" placeholder="ID" required>
                    </div>
                </div>

                <!-- Campo para a senha -->
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="PASSWORD" required>
                    </div>
                </div>

                <!-- Exibição de mensagens de erro (se houver) -->
                <?php if (!empty($erro)) { ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php } ?>

                <!-- Botão de login -->
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
        </div>
    </div>

    <!-- Inclusão do script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

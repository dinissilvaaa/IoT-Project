<?php
// Ajuste o caminho conforme o seu webroot
$dest = __DIR__ . '/api/files/Camara/webcam.jpg';
if (isset($_FILES['webcam']) && $_FILES['webcam']['error'] === UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['webcam']['tmp_name'], $dest);
    http_response_code(200);
    echo 'OK';
} 
?>

<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Carrega o Bootstrap para facilitar o layout e estilo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="styleH1.css"> <!-- Carrega o arquivo de estilos personalizados -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Carrega o Chart.js para gráficos -->
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
    
<!-- Câmara centralizada -->
<div class="row justify-content-center mt-5">
  <div class="col-sm-6 col-md-4">
    <div class="card px-0">
      <div class="card-header sensor text-center"><b>Câmera de Vigilância</b></div>
      <div class="card-body p-0 d-flex justify-content-center align-items-start">
        <?php
          // Timestamp para busting de cache do navegador
          $ts = time();
          echo "<img src='api/files/Camara/webcam.jpg?ts={$ts}' alt='Imagem da Webcam' class='img-fluid' style='max-width:100%;'>";
        ?>
      </div>
      <div class="card-footer">
      </div>
    </div>
  </div>
</div>

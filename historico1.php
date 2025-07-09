<?php 
session_start(); // Inicia a sessão para acessar dados do usuário

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['username'])) {
    // Se o utilizador não estiver autenticado, redireciona para a página de login
    header("refresh:5;url=index.php");
    die("Acesso Restrito"); // Exibe uma mensagem de "Acesso Restrito" e interrompe a execução do código
}

// Se o utilizador for 'David', redireciona-o para a dashboard
if ($_SESSION['username'] === "David") {
    header("Location: dashboard.php"); // Redireciona para a dashboard
    exit; // Interrompe a execução do código após o redirecionamento
}

// Lê os valores dos arquivos de temperatura
  $valor_temperatura = file_get_contents("api/files/Temperatura/valor.txt");
  $hora_temperatura = file_get_contents("api/files/Temperatura/hora.txt");
  $nome_temperatura = file_get_contents("api/files/Temperatura/nome.txt");

  // Lê os valores dos arquivos de humidade
  $valor_humidade = file_get_contents("api/files/Humidade/valor.txt");
  $hora_humidade = file_get_contents("api/files/Humidade/hora.txt");
  $nome_humidade = file_get_contents("api/files/Humidade/nome.txt");

  // Lê os valores dos arquivos de estacionamento
  $valor_estacionamento = file_get_contents("api/files/Estacionamento/valor.txt");
  $hora_estacionamento = file_get_contents("api/files/Estacionamento/hora.txt");
  $nome_estacionamento = file_get_contents("api/files/Estacionamento/nome.txt");

  // Lê os valores dos arquivos de ar condicionado
  $valor_ArCondicionado = file_get_contents("api/files/ArCondicionado/valor.txt");
  $hora_ArCondicionado = file_get_contents("api/files/ArCondicionado/hora.txt");
  $nome_ArCondicionado = file_get_contents("api/files/ArCondicionado/nome.txt");

  // Lê os valores dos arquivos de ventoinha
  $valor_Ventoinha = file_get_contents("api/files/Ventoinha/valor.txt");
  $hora_Ventoinha = file_get_contents("api/files/Ventoinha/hora.txt");
  $nome_Ventoinha = file_get_contents("api/files/Ventoinha/nome.txt");

  // Lê os valores dos arquivos de sinal de estacionamento
  $valor_Sinal = file_get_contents("api/files/Sinal/valor.txt");
  $hora_Sinal = file_get_contents("api/files/Sinal/hora.txt");
  $nome_Sinal = file_get_contents("api/files/Sinal/nome.txt");
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

    <main>
        <div class="container d-flex justify-content-around align-items-center">
            <!-- Exibição do gráfico e histórico dos sensores -->
            <?php
            // Verifica se a requisição é do tipo GET e se o parâmetro 'nome' foi passado
           if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if (isset($_GET['nome'])) {
                    $nome = $_GET['nome'];
                    $caminho = "api/files/$nome/log.txt";
            
                    if (file_exists($caminho)) {
                        // Exibe o título e a área para o gráfico
                        echo "<div class='container'>";
                        echo "<div class='my-5'>";
                        echo "<h2>Gráfico de Valores</h2>";
                        echo "<canvas id='graficoSensor'></canvas>";
                        echo "</div>";
            
                        // Exibe a tabela de histórico
                        echo "<div class='content mb-5'>";
                        echo "<h2>Histórico de " . htmlspecialchars($nome) . "</h2>";
                        echo "<table class='table'>";
                        echo "<thead>";
                        echo "  <tr>";
                        echo "      <th>Data/Hora</th>";
                        echo "      <th>Valor</th>";
                        echo "      <th>Estado</th>";
                        echo "  </tr>";
                        echo "</thead>";
                        echo "<tbody>"; 
            
                        $conteudo = file_get_contents($caminho);
                        $linhas = explode(PHP_EOL, $conteudo);
            
                        $horas = [];
                        $valores = [];
                        $estados = [];
                        $cores = [];
            
                        foreach ($linhas as $linha) {
                            if (trim($linha) !== '') {
                                $partes = explode(';', $linha);
                        
                                // Verifica se a linha possui pelo menos dois elementos (data e valor)
                                if (count($partes) >= 2) {
                                    $hora = trim($partes[0]);
                                    $valor = trim($partes[1]);
                                    $horas[] = $hora;
                        
                                    $estado = 'Desconhecido';
                                    $cor = '';
                        
                                    // Lógica para sensores
                                    if ($nome === 'Temperatura') {
                                        $valor = (float) $valor;
                                        $valores[] = $valor;
                        
                                        if ($valor < 20) {
                                            $estado = 'Baixa'; $cor = 'success';
                                        } elseif ($valor < 30) {
                                            $estado = 'Amena'; $cor = 'primary';
                                        } else {
                                            $estado = 'Elevada'; $cor = 'danger';
                                        }
                        
                                    } elseif ($nome === 'Humidade') {
                                        $valor = (float) $valor;
                                        $valores[] = $valor;
                        
                                        if ($valor < 30) {
                                            $estado = 'Baixa'; $cor = 'success';
                                        } elseif ($valor < 70) {
                                            $estado = 'Normal'; $cor = 'primary';
                                        } else {
                                            $estado = 'Elevada'; $cor = 'danger';
                                        }
                        
                                    } elseif ($nome === 'Estacionamento') {
                                        $valor = (int) $valor;
                                        $valores[] = $valor;
                        
                                        $estado = ($valor > 0) ? 'Parque Livre' : 'Parque Cheio';
                                        $cor = ($valor > 0) ? 'success' : 'danger';
                        
                                    } elseif ($nome === 'ArCondicionado') {
                                        $valor = (int) $valor;
                                        $valores[] = $valor;
                        
                                        switch ($valor) {
                                            case 0: $estado = 'Desligado'; $cor = 'danger'; break;
                                            case 1: $estado = 'Aquecimento'; $cor = 'success'; break;
                                            case 2: $estado = 'Arrefecimento'; $cor = 'primary'; break;
                                            default: $estado = 'Desconhecido'; $cor = 'secondary'; break;
                                        }
                        
                                    } elseif ($nome === 'Ventoinha') {
                                        $valor = (int) $valor;
                                        $valores[] = $valor;
                        
                                        switch ($valor) {
                                            case 0: $estado = 'Desligada'; $cor = 'danger'; break;
                                            case 1: $estado = 'Potência Média'; $cor = 'info'; break;
                                            case 2: $estado = 'Potência Máxima'; $cor = 'success'; break;
                                            default: $estado = 'Desconhecido'; $cor = 'secondary'; break;
                                        }
                        
                                    } elseif ($nome === 'Sinal') {
                                        $valor = (int) $valor;
                                        $valores[] = $valor;
                        
                                        switch ($valor) {
                                            case 0: $estado = 'Parque Cheio'; $cor = 'danger'; break;
                                            case 1: $estado = 'Parque Disponível'; $cor = 'success'; break;
                                            default: $estado = 'Desconhecido'; $cor = 'secondary'; break;
                                        }
                                    }
                        
                                    // Exibe a linha na tabela, escapando o conteúdo por segurança
                                    echo "<tr>
                                            <td>" . htmlspecialchars($hora) . "</td>
                                            <td>" . htmlspecialchars($valor) . "</td>
                                            <td><span class='badge rounded-pill text-bg-{$cor}'>" . htmlspecialchars($estado) . "</span></td>
                                          </tr>";
                        
                                    $cores[] = $cor;
                                }
                            }
                        }                        
            
                        // Fim da tabela
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>"; // Fecha content
                        echo "</div>"; // Fecha container
            
                    } else {
                        echo "<div class='alert alert-danger'>Ficheiro log para '{$nome}' não existe.</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning'>Parâmetro 'nome' não especificado ou está vazio na URL.</div>";
                }
            } else {
                echo "<div class='alert alert-secondary'>Este ficheiro aceita apenas requisições GET.</div>";
            }
            
            
            ?>
        </div>
    </main> 

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <script>
        // Geração do gráfico usando Chart.js
        const labels = <?php echo json_encode($horas); ?>; // Horas para o eixo X
        const data = <?php echo json_encode($valores); ?>; // Valores para o eixo Y

        const ctx = document.getElementById('graficoSensor').getContext('2d');
        new Chart(ctx, {
            type: 'line', // Tipo de gráfico (linha)
            data: {
                labels: labels,
                datasets: [{
                    label: '<?php echo $nome; ?>',
                    data: data,
                    fill: false,
                    borderColor: '#007bff', // Cor da linha
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Hora'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: '<?php echo $nome; ?>'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

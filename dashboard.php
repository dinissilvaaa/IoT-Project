<?php 
  // Inicia a sessão para permitir acesso a variáveis de sessão
  session_start();

  // Verifica se a variável de sessão 'username' está definida (usuário autenticado)
  if(!isset($_SESSION['username'])){
      // Se o usuário não estiver autenticado, pode-se redirecionar ou mostrar uma mensagem (não implementado aqui)
  }

  // Verifica se o método da requisição é POST (ou seja, se dados foram enviados para o servidor)
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // --------------------- TRATAMENTO DO LED ---------------------
    
    // Verifica se o parâmetro 'cor' foi enviado via POST
    if (isset($_POST['cor'])) {
      // Armazena o valor da cor enviada (ex: "verde", "amarelo", "vermelho") na variável $cor_LED
      $cor_LED = $_POST['cor'];

      // Escreve o valor da cor no ficheiro de texto localizado em 'api/files/LED/valor.txt'
      file_put_contents("api/files/LED/valor.txt", $cor_LED);

      // Envia uma mensagem de confirmação para o cliente com a cor gravada
      echo "Cor gravada com sucesso: $cor_LED";

      // Termina a execução do script, pois já tratou a requisição
      exit;
    }

    // ------------------ TRATAMENTO DA CAMPAINHA ------------------

    // Verifica se o parâmetro 'campainha' foi enviado via POST
    if (isset($_POST['campainha'])) {
      // Armazena o valor enviado (normalmente "0" ou "1") na variável $valorCampainha
      $valorCampainha = $_POST['campainha'];

      // Escreve o valor da campainha no ficheiro de texto em 'api/files/Campainha/valor.txt'
      file_put_contents("api/files/Campainha/valor.txt", $valorCampainha);

      // Envia uma mensagem de confirmação ao cliente com o valor gravado
      echo "Valor da campainha gravado com sucesso: $valorCampainha";

      // Termina a execução do script após tratar a campainha
      exit;
    }

    // Se chegou até aqui, significa que nenhum dos parâmetros esperados ('cor' ou 'campainha') foi enviado
    echo "Erro: nenhum parâmetro válido ('cor' ou 'campainha') foi enviado.";

    // Termina a execução do script com mensagem de erro
    exit;
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

  // Lê os valores dos arquivos de sinal de estacionamento
  $valor_Sinal = file_get_contents("api/files/Sinal/valor.txt");
  $hora_Sinal = file_get_contents("api/files/Sinal/hora.txt");
?>

<!doctype html>
<html lang="en">
<head>

 <title>Dashboard</title>

  <!-- Meta tags para compatibilidade e responsividade -->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 para estilos -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
  >

  <!-- Estilo personalizado -->
  <link rel="stylesheet" href="styleD.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm bg-light">
            <div class="container-fluid">
                <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php"><strong>Smart Campus 2024/25</strong></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="historico2.php">Histórico</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="camara.php">Câmara</a>
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
    <div class="container d-flex justify-content-around align-items-center"> 
        <div class="dashboard-header text-center">
                <h1 class="titulo-dashboard" style="color: black;">Bem Vindo, <?php echo $_SESSION['username']; ?></h1>
                <img src="Imagens/logodash.png" alt="Logo" class="logo-dashboard" style="width: 1000px;">
        </div>
    </div>
            <div class="container" style="margin-bottom: 30px;">
                <h2> Sensores </h2>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header sensor">
                                <div class="text-center">
                                    <span>
                                        <?php echo "{$nome_temperatura}: {$valor_temperatura}ºC dia {$hora_temperatura}"; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                    <img src="Imagens/temperatura.png"  class="card-img-top mx-auto d-block" style="width: 79.4%;" alt="...">
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                <span style="font-size: small;"><strong>Atualização:</strong> <?php echo $hora_temperatura; ?> 
                                    <?php if ($_SESSION['username'] !== "David") { ?>
                                        - <a href="historico1.php?nome=Temperatura">Histórico</a>
                                    <?php } ?>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header sensor">
                                <div class="text-center">
                                    <span> 
                                        <?php echo "{$nome_humidade}: {$valor_humidade}ºC dia {$hora_humidade}"; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                    <img src="Imagens/humidade.png"  class="card-img-top mx-auto d-block" style="width: 46.4%;" alt="...">
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                <span style="font-size: small;"><strong>Atualização:</strong> <?php echo $hora_humidade; ?> 
                                    <?php if ($_SESSION['username'] !== "David") { ?>
                                        - <a href="historico1.php?nome=Humidade">Histórico</a>
                                    <?php } ?>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header atuador">
                                <div class="text-center">
                                    <span>
                                    <?php echo $nome_estacionamento . ": " . $valor_estacionamento . " dia " . $hora_estacionamento; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                    <img src="Imagens/carro.png "  class="card-img-top mx-auto d-block" style="width: 40.4%;" alt="...">
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                    <span style="font-size: small;"><strong>Atualização:</strong> <?php echo $hora_estacionamento; ?> 
                                        <?php if ($_SESSION['username'] !== "David") { ?>
                                            - <a href="historico1.php?nome=Estacionamento">Histórico</a>
                                        <?php } ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="container" style="margin-bottom: 30px;">
                <h2> Atuadores </h2>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header sensor">
                                <div class="text-center">
                                    <span>
                                    <?php 
                                if ($valor_temperatura >= 30) {
                                    echo "Ligado (Arrefecimento)";
                            }   if ($valor_temperatura < 30 && $valor_temperatura >=20 ) {
                                    echo "Desligado";
                            }   if ($valor_temperatura < 20) {
                                    echo "Ligado (Aquecimento)";
                            }
                            ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                            <?php 
                                        if ($valor_temperatura >= 30) {
                                            echo "<img src='Imagens/arlig.png'  class='card-img-top mx-auto d-block' style='width: 85%;' alt='...'>";
                                    }   if ($valor_temperatura < 30 && $valor_temperatura >=20 ) {
                                            echo "<img src='Imagens/ardes.png'  class='card-img-top mx-auto d-block' style='width: 90%;' alt='...'>";
                                    }   if ($valor_temperatura < 20) {
                                            echo "<img src='Imagens/arlig.png'  class='card-img-top mx-auto d-block' style='width: 85%;' alt='...'>";
                                    }
                                ?>
                            </div>
                            <div class="card-footer">
    <div class="text-center">
        <span style="font-size: small;">
            <strong>Atualização:</strong> <?php echo $hora_temperatura; ?>
            <?php 
                if ($_SESSION['username'] !== "David") { 
                    echo ' - <a href="historico1.php?nome=ArCondicionado">Histórico</a>';
                }
            ?>
        </span>
    </div>
</div>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header sensor">
                                <div class="text-center">
                                    <span> 
                                        <?php 
                                    if ($valor_humidade >= 70) {
                                            echo "Ligada - Potência Máxima";
                                }   if ($valor_humidade < 70 && $valor_humidade >=20 ) {
                                            echo "Ligada - Potência Média";
                                }   if ($valor_humidade < 20) {
                                            echo "Desligada";
                                }
                                    
                            ?>
                            </span>
                                </div>
                            </div>
                            <div class="card-body">
                                    <?php 
                                        if ($valor_humidade >= 70) {
                                            echo "<img src='Imagens/venton.png'  class='card-img-top mx-auto d-block' style='width: 69%;' alt='...'>";
                                    }   if ($valor_humidade < 70 && $valor_humidade >=20 ) {
                                            echo "<img src='Imagens/venton.png'  class='card-img-top mx-auto d-block' style='width: 69%;' alt='...'>";
                                    }   if ($valor_humidade < 20) {
                                            echo "<img src='Imagens/ventof.png'  class='card-img-top mx-auto d-block' style='width: 72%;' alt='...'>";
                                    }
                                ?>
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                <span style="font-size: small;"><strong>Atualização:</strong> <?php echo $hora_humidade;?> 
                                    <?php if ($_SESSION['username'] !== "David") { ?>
                                        - <a href="historico1.php?nome=Ventoinha">Histórico</a>
                                    <?php } ?>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-header atuador">
                                <div class="text-center">
                                    <span>
                                        <?php
                                            if ($valor_estacionamento == 0) {
                                                echo "Parque Indisponível";
                                        }  else {
                                                echo "Parque Disponível";
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                            <?php 
                                        if ($valor_estacionamento == 0) {
                                            echo "<img src='Imagens/ocupado.png'  class='card-img-top mx-auto d-block' style='width: 40%;' alt='...'>";
                                    }   if ($valor_estacionamento > 0 ) {
                                            echo "<img src='Imagens/livre.png'  class='card-img-top mx-auto d-block' style='width: 40%;' alt='...'>";
                                    }
                                ?>
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                <span style="font-size: small;"><strong>Atualização:</strong> <?php echo $hora_estacionamento;?>
                                    <?php if ($_SESSION['username'] !== "David") { ?>
                                         - <a href="historico1.php?nome=Sinal">Histórico</a> 
                                    <?php } ?>
                                </span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="container" style="margin-top: 0px; margin-bottom: 20px;">
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipos de Sensores</th>
                        <th>Valor</th>
                        <th>Data de Atualização</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sensor de Temperatura -->
                    <tr>
                        <td><?php echo $nome_temperatura; ?></td>
                        <td><?php echo $valor_temperatura; ?>ºC</td>
                        <td><?php echo $hora_temperatura; ?></td>
                        <td>
                            <?php 
                                if ($valor_temperatura >= 30) {
                                    echo '<span class="badge rounded-pill text-bg-danger">Elevada</span>';
                                } elseif ($valor_temperatura >= 20) {
                                    echo '<span class="badge rounded-pill text-bg-primary">Amena</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-info">Baixa</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <!-- Sensor de Humidade -->
                    <tr>
                        <td><?php echo $nome_humidade; ?></td>
                        <td><?php echo $valor_humidade; ?>%</td>
                        <td><?php echo $hora_humidade; ?></td>
                        <td>
                            <?php 
                                if ($valor_humidade >= 70) {
                                    echo '<span class="badge rounded-pill text-bg-danger">Elevada</span>';
                                } elseif ($valor_humidade >= 30) {
                                    echo '<span class="badge rounded-pill text-bg-primary">Normal</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-info">Baixa</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <!-- Sensor de Estacionamento -->
                    <tr>
                        <td><?php echo $nome_estacionamento; ?></td>
                        <td><?php echo $valor_estacionamento; ?></td>
                        <td><?php echo $hora_estacionamento; ?></td>
                        <td>
                            <?php 
                                if ($valor_estacionamento == 0) {
                                    echo '<span class="badge rounded-pill text-bg-danger">Parque Cheio</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-success">Estacionamentos Disponíveis</span>';
                                }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 0px; margin-bottom: 20px;">
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipos de Atuadores</th>
                        <th></th>
                        <th>Data de Atualização</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Atuador Ar Condicionado -->
                    <tr>
                        <td><?php echo $nome_ArCondicionado; ?></td>
                        <td></td>
                        <td><?php echo $hora_temperatura; ?></td>
                        <td>
                            <?php 
                                if ($valor_temperatura >= 30) {
                                    echo '<span class="badge rounded-pill text-bg-primary">Ligado (Arrefecimento)</span>';
                                } elseif ($valor_temperatura >= 20) {
                                    echo '<span class="badge rounded-pill text-bg-danger">Desligado</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-success">Ligado (Aquecimento)</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <!-- Atuador Ventoinha -->
                    <tr>
                        <td><?php echo $nome_Ventoinha; ?></td>
                        <td></td>
                        <td><?php echo $hora_humidade; ?></td>
                        <td>
                            <?php 
                                if ($valor_humidade >= 70) {
                                    echo '<span class="badge rounded-pill text-bg-success">Ligada - Potência Máxima</span>';
                                } elseif ($valor_humidade >= 20) {
                                    echo '<span class="badge rounded-pill text-bg-info">Ligada - Potência Média</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-danger">Desligada</span>';
                                }
                            ?>
                        </td>
                    </tr>
                    <!-- Sinal do Estacionamento -->
                    <tr>
                        <td><?php echo $nome_Sinal; ?></td>
                        <td></td>
                        <td><?php echo $hora_estacionamento; ?></td>
                        <td>
                            <?php 
                                if ($valor_estacionamento == 0) {
                                    echo '<span class="badge rounded-pill text-bg-danger">Parque Cheio</span>';
                                } else {
                                    echo '<span class="badge rounded-pill text-bg-success">Parque Disponível</span>';
                                }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

 <!-- Container principal da seção "Estudantes na Sala 1" -->
<div class="container" style="margin-bottom: 0px; text-align: center;">
  <h2>Estudantes na Sala 1</h2> <!-- Título da seção -->

  <!-- Container interno com flex para centralizar -->
  <div class="container" style="margin-bottom: 30px; height: px; display: flex; justify-content: center; align-items: center; text-align: center;">
    <div class="row w-100 justify-content-center"> <!-- Linha com largura total, centralizada -->
      <div class="col-sm-4"> <!-- Coluna de tamanho pequeno -->
        <!-- Cartão Bootstrap com conteúdo centralizado -->
        <div class="card h-100 d-flex flex-column justify-content-center align-items-center">
          <div class="card-body d-flex justify-content-center align-items-center">
            <div class="roda-wrapper"> <!-- Wrapper do círculo/roda -->
              <div class="roda-completa" id="roda"> <!-- Elemento da roda com ID para estilização e JS -->
                <!-- Botão para diminuir o número -->
                <button class="botao menos" onclick="alterarContador(-1)">−</button>
                <!-- Elemento que mostra o número atual -->
                <span id="numero">0</span>
                <!-- Botão para aumentar o número -->
                <button class="botao mais" onclick="alterarContador(1)">+</button>
              </div>
            </div>
          </div> <!-- Fim do corpo do cartão -->
        </div> <!-- Fim do cartão -->
      </div> <!-- Fim da coluna -->
    </div> <!-- Fim da linha -->
  </div> <!-- Fim do container flex -->
</div> <!-- Fim do container principal -->

<!-- Segunda seção: Campainha -->
<div class="container" style="margin-bottom: 30px; text-align: center;">
  <h2>Campainha</h2> <!-- Título da seção -->

  <!-- Container interno com flex -->
  <div class="container" style="margin-bottom: 30px; display: flex; justify-content: center; align-items: center; text-align: center;">
    <div class="row w-100 justify-content-center">
      <div class="col-sm-4">
        <div class="card h-100 d-flex flex-column justify-content-center align-items-center">
          <div class="card-body d-flex justify-content-center align-items-center">
            <div class="roda-wrapper">
              <div class="roda-completa" id="roda">
                <!-- Botão da campainha com emoji -->
                <button class="botao campainha" onclick="tocarCampainha()">🔔</button>
              </div>
            </div>
          </div> <!-- Fim do corpo do cartão -->
        </div> <!-- Fim do cartão -->
      </div> <!-- Fim da coluna -->
    </div> <!-- Fim da linha -->
  </div> <!-- Fim do container flex -->
</div> <!-- Fim do container principal -->


<!-- ENVIAR VALOR 3 LEDS -->


<!-- Script que trata o contador e envia cor à API -->
<script>
let contador = localStorage.getItem('contador') ? parseInt(localStorage.getItem('contador')) : 0; // Recupera o contador do localStorage ou inicia com 0
const numero = document.getElementById("numero"); // Referência ao elemento que mostra o número
const roda = document.getElementById("roda"); // Referência ao elemento da roda para mudar cor

function atualizarContador() {
  numero.textContent = contador; // Atualiza o número na tela
  let cor; // Variável para armazenar a cor correspondente

  if (contador <= 10) {
    roda.style.backgroundColor = "green"; // Cor verde para 0–10
    cor = "verde";
  } else if (contador <= 20) {
    roda.style.backgroundColor = "gold"; // Cor amarela para 11–20
    cor = "amarelo";
  } else {
    roda.style.backgroundColor = "red"; // Cor vermelha para 21–30
    cor = "vermelho";
  }

  enviarCorParaAPI(cor); // Envia a cor para a API PHP
}

function alterarContador(valor) {
  contador += valor; // Altera o contador com o valor recebido
  if (contador < 0) contador = 0; // Limita o contador mínimo
  if (contador > 30) contador = 30; // Limita o contador máximo
  localStorage.setItem('contador', contador); // Salva o valor no localStorage
  atualizarContador(); // Atualiza a interface
}

function enviarCorParaAPI(cor) {
  fetch('dashboard.php', {
    method: 'POST', // Envia via POST
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, // Tipo de conteúdo
    body: 'cor=' + encodeURIComponent(cor) // Envia a cor no corpo da requisição
  })
  .then(response => response.text()) // Processa a resposta como texto
  .then(data => console.log("Resposta da API:", data)) // Loga a resposta da API
  .catch(error => console.error("Erro ao enviar cor:", error)); // Loga erros
}

// Atualiza a roda na primeira carga da página
atualizarContador();
</script>


<!-- ENVIAR VALOR CAMPAINHA -->


<!-- Script que trata o botão da campainha -->
<script>
// Ao carregar a página (DOMContentLoaded)
window.addEventListener('DOMContentLoaded', () => {
  fetch('dashboard.php') // Requisição GET para verificar valor atual da campainha
    .then(response => response.text()) // Processa resposta
    .then(valorAtual => {
      console.log("Valor atual no load:", valorAtual.trim()); // Loga valor atual

      if (valorAtual.trim() !== "1") {
        // Se valor não for 1, envia 0 para garantir estado inicial
        fetch('dashboard.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'campainha=0'
        })
        .then(res => res.text())
        .then(data => console.log("campainha=0 enviado no load:", data)); // Loga resposta
      }
    });
});

function tocarCampainha() {
  fetch('dashboard.php', {
    method: 'POST', // Envia via POST
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'campainha=1' // Envia valor 1 (campainha ativada)
  })
  .then(res => res.text()) // Processa resposta
  .then(data => {
    console.log("campainha=1 enviado:", data); // Loga resposta

    setTimeout(() => {
      fetch('dashboard.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'campainha=0' // Envia valor 0 após 3 segundos (desliga campainha)
      })
      .then(res => res.text())
      .then(data => console.log("campainha=0 enviado depois de 3s:", data)); // Loga resposta
    }, 3000); // Espera 3 segundos
  });
}
</script>

<!-- REFRESH AUTOMÁTICO -->
<!-- Script que trata o refresh automático da página -->
<script>
const tempoEmSegundos = 5; // Define o intervalo de atualização automática em segundos

function guardarScrollAntesDeRefresh() {
  const scrollY = window.scrollY || window.pageYOffset; // Obtém a posição Y de scroll atual
  localStorage.setItem('scrollPos', scrollY); // Guarda a posição de scroll no localStorage
}

function refreshAutomatico() {
  guardarScrollAntesDeRefresh(); // Salva o scroll antes de recarregar
  location.reload(); // Recarrega a página (sem enviar dados)
}

// Ao carregar a página
window.addEventListener('load', () => {
  const scrollPos = localStorage.getItem('scrollPos'); // Recupera posição de scroll salva
  if (scrollPos !== null) {
    window.scrollTo(0, parseInt(scrollPos)); // Rola até a posição salva
    localStorage.removeItem('scrollPos'); // Remove do localStorage
  }
});

setInterval(refreshAutomatico, tempoEmSegundos * 1000); // Atualiza a página a cada X segundos
</script>

</div> <!-- Fim de qualquer div aberta antes -->

</body>
</html>

<!-- Inclusão de bibliotecas JavaScript do Bootstrap -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
  integrity="sha384-mQ93fQ+utMwPbHkAOE+ZL6KDmCEk+cujlEMoGeA4zomcK8FESyqD2G1VA2xzF6Vj"
  crossorigin="anonymous">
</script>

</body>
</html>
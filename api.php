<?php
// Define o tipo de conteúdo da resposta como texto plano UTF-8
header('Content-Type: text/plain; charset=utf-8');

// Verifica o método da requisição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    print_r($_POST); // Mostra os dados recebidos para debug

    // Verifica se todos os parâmetros necessários foram enviados
    if (isset($_POST['valor']) && isset($_POST['hora']) && isset($_POST['nome'])) {
        
        // Extrai e valida os dados recebidos
        $nome = $_POST['nome'];
        $valorSensor = floatval($_POST['valor']);
        $hora = $_POST['hora'];

        // Guarda os valores atuais do sensor em arquivos separados
        file_put_contents("files/$nome/valor.txt", $valorSensor);
        file_put_contents("files/$nome/hora.txt", $hora);

        // Acrescenta uma linha ao ficheiro de log do sensor
        $logEntry = $hora . ";" . $valorSensor . PHP_EOL;
        file_put_contents("files/$nome/log.txt", $logEntry, FILE_APPEND);

        // Define variáveis para o atuador associado ao sensor
        $atuador = "";
        $valorNumerico = -1;

        // Define o valor do atuador com base no sensor
        if ($nome == "temperatura") {
            $atuador = "ArCondicionado";
            if ($valorSensor < 20) {
                $valorNumerico = 1; // Ligado no quente
            } elseif ($valorSensor <= 30) {
                $valorNumerico = 0; // Desligado
            } else {
                $valorNumerico = 2; // Ligado no frio
            }

        } elseif ($nome == "humidade") {
            $atuador = "Ventoinha";
            if ($valorSensor < 30) {
                $valorNumerico = 0; // Desligada
            } elseif ($valorSensor <= 70) {
                $valorNumerico = 1; // Potência média
            } else {
                $valorNumerico = 2; // Potência máxima
            }

        } elseif ($nome == "estacionamento") {
            $atuador = "Sinal";
            $valorNumerico = ($valorSensor == 0) ? 0 : 1; // 0 = Cheio, 1 = Livre
        }

        // Garante que o diretório do atuador existe
        if (!is_dir("files/$atuador")) {
            mkdir("files/$atuador", 0777, true);
        }

        // Grava os valores do atuador nos arquivos correspondentes
        file_put_contents("files/$atuador/valor.txt", $valorNumerico);
        file_put_contents("files/$atuador/hora.txt", $hora);
        file_put_contents("files/$atuador/log.txt", $hora . ";" . $valorNumerico . PHP_EOL, FILE_APPEND);

        echo "Dados do atuador gravados: $atuador = $valorNumerico\n";

    } else {
        // Falta de parâmetros obrigatórios
        http_response_code(400);
        echo "Parâmetros recebidos não são válidos\n";
    }

    exit(); // Finaliza o script após o POST

} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

    echo "Recebido um GET\n";

    // Verifica se o parâmetro 'nome' foi fornecido
    if (isset($_GET['nome'])) {
        $nome = $_GET["nome"];
        $sensorFile = "files/$nome/valor.txt";

        // Verifica se o arquivo existe e mostra o conteúdo
        if (file_exists($sensorFile)) {
            echo file_get_contents($sensorFile);
        } else {
            http_response_code(404);
            echo "Erro: Sensor '$nome' não encontrado!" . PHP_EOL;
        }
    } else {
        // Nome do sensor não especificado
        http_response_code(400);
        echo "Faltam parâmetros no GET! Erro: Sensor inválido!" . PHP_EOL;
    }

} else {
    // Método não permitido (apenas GET e POST são aceitos)
    http_response_code(403);
    echo "Método não permitido\n";
}




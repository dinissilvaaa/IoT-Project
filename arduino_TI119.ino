#include <WiFi101.h>              // Biblioteca para gerir WiFi no Arduino MKR1000
#include <ArduinoHttpClient.h>    // Biblioteca para fazer pedidos HTTP (GET/POST)
#include <DHT.h>                  // Biblioteca para sensor DHT (temperatura e humidade)
#include <NTPClient.h>            // Biblioteca para sincronizar tempo via NTP
#include <WiFiUdp.h>              // Biblioteca para usar UDP, necessária para NTP
#include <time.h>                 // Biblioteca C padrão para manipulação de tempo

// === CONFIGURAÇÕES DO SENSOR DHT11 ===
#define DHTPIN 0                  // Pino onde o sensor DHT está ligado (digital 0)
#define DHTTYPE DHT11             // Tipo do sensor é DHT11
DHT dht(DHTPIN, DHTTYPE);         // Instancia o objeto do sensor DHT

// === CONFIGURAÇÃO WiFi E SERVIDOR ===
char SSID[] = "labs";  // Nome da rede WiFi a ligar
char PASS_WIFI[] = "1nv3nt@r2023_IPLEIRIA";  // Palavra-passe da rede WiFi
char URL[] = "iot.dei.estg.ipleiria.pt"; // URL base do servidor para comunicação HTTP
int PORTO = 80;                   // Porta do servidor HTTP (normal 80)

// === OBJETOS PARA COMUNICAÇÃO ===
WiFiClient clienteWifi;           // Cliente WiFi para gerir conexão TCP
HttpClient clienteHTTP(clienteWifi, URL, PORTO); // Cliente HTTP para requisições via TCP no servidor e porta definidos

// === CONFIGURAÇÃO DO NTP (Relógio via Internet) ===
WiFiUDP clienteUDP;              // Cliente UDP para comunicação NTP
char NTP_SERVER[] = "ntp.ipleiria.pt"; // Endereço do servidor NTP para sincronizar hora
NTPClient clienteNTP(clienteUDP, NTP_SERVER, 3600); // Objeto cliente NTP com deslocamento de 3600 segundos (UTC+1)

// Função setup() executa uma vez no arranque
void setup() {
  // === INICIALIZAÇÃO DE LEDS PARA STOCK ===
  pinMode(1, OUTPUT);             // Define pino 1 como saída (LED verde)
  pinMode(2, OUTPUT);             // Define pino 2 como saída (LED amarelo)
  pinMode(3, OUTPUT);             // Define pino 3 como saída (LED vermelho)
  pinMode(4, OUTPUT);             // Define pino 4 como saída (LED estacionamento)

  // === INICIALIZAÇÃO SERIAL ===
  Serial.begin(115200);           // Inicia comunicação serial a 115200 baud
  while (!Serial);                // Espera até que a porta serial esteja pronta

  Serial.print("A ligar à rede WiFi: "); // Mensagem informativa
  Serial.println(SSID);                   // Mostra o nome da rede WiFi

  WiFi.begin(SSID, PASS_WIFI);            // Começa a conectar na rede WiFi
  while (WiFi.status() != WL_CONNECTED) {  // Espera até conexão ser estabelecida
    Serial.print(".");                     // Indica progresso com pontos
    delay(500);                           // Atraso de meio segundo entre tentativas
  }
  Serial.println("\nConectado!");          // Mensagem de sucesso na conexão

  dht.begin();                             // Inicializa o sensor DHT
  clienteNTP.begin();                      // Inicializa cliente NTP
  clienteNTP.update();                     // Atualiza a hora pela primeira vez
}

// Função para formatar timestamp Unix (epoch) em string legível (YYYY-MM-DD HH:MM)
String formatarDataHora(unsigned long epochTime) {
  time_t rawTime = epochTime;                // Converte unsigned long para time_t
  struct tm *timeInfo = gmtime(&rawTime);   // Converte para estrutura de tempo UTC (sem fuso)

  char buffer[25];                           // Buffer para armazenar string formatada
  sprintf(buffer, "%04d-%02d-%02d %02d:%02d",  // Formata a data/hora em "YYYY-MM-DD HH:MM"
          timeInfo->tm_year + 1900,          // Ano (a partir de 1900)
          timeInfo->tm_mon + 1,               // Mês (0-11, por isso soma 1)
          timeInfo->tm_mday,                  // Dia do mês
          timeInfo->tm_hour,                  // Hora
          timeInfo->tm_min);                  // Minuto

  return String(buffer);                      // Retorna como String Arduino
}

// === FUNÇÃO PARA ENVIAR DADOS PARA A API ===
void post2API(String nome, String valor, String hora) {
  String URLPath = "http://iot.dei.estg.ipleiria.pt/ti/ti119/ti119/api/api.php";  // Caminho completo da API para POST
  String contentType = "application/x-www-form-urlencoded";  // Tipo de conteúdo HTTP para POST
  String body = "nome=" + nome + "&valor=" + valor + "&hora=" + hora;  // Corpo do POST com os parâmetros

  clienteHTTP.post(URLPath, contentType, body);  // Faz o pedido POST

  while (clienteHTTP.connected()) {               // Enquanto a conexão estiver aberta
    if (clienteHTTP.available()) {                // Se houver resposta disponível
      int responseStatusCode = clienteHTTP.responseStatusCode();  // Lê o código HTTP da resposta
      String responseBody = clienteHTTP.responseBody();           // Lê o corpo da resposta
      Serial.println("Status Code: " + String(responseStatusCode) + "; Resposta: " + responseBody); // Imprime na serial
    }
  }
  clienteHTTP.stop();  // Fecha a conexão HTTP
}

// Função para ler cor do LED a partir da API e definir LEDs correspondentes
void corLed() {
  String caminhoCor = "/ti/ti119/ti119/api/files/LED/valor.txt";  // Caminho do ficheiro da cor do LED

  clienteHTTP.get(caminhoCor);               // Faz pedido GET para obter cor

  int statusCode = clienteHTTP.responseStatusCode();  // Lê código HTTP da resposta
  String resposta = clienteHTTP.responseBody();        // Lê o conteúdo da resposta

  Serial.println("Cor lida: " + resposta);  // Mostra cor recebida no monitor serial

  clienteHTTP.stop();                                // Fecha a conexão HTTP
  resposta.trim();                                  // Remove espaços e quebras de linha

  // Liga/desliga os LEDs conforme a cor recebida
  if (resposta == "verde") {
    digitalWrite(1, HIGH);
    digitalWrite(2, LOW);
    digitalWrite(3, LOW);
  } else if (resposta == "amarelo") {
    digitalWrite(1, LOW);
    digitalWrite(2, HIGH);
    digitalWrite(3, LOW);
  } else if (resposta == "vermelho") {
    digitalWrite(1, LOW);
    digitalWrite(2, LOW);
    digitalWrite(3, HIGH);
  } else {  // Se cor não reconhecida, apaga todos LEDs
    digitalWrite(1, LOW);
    digitalWrite(2, LOW);
    digitalWrite(3, LOW);
  }
}

// Função para verificar valor do estacionamento via API e acender LED correspondente
void verEstacionamento() {
  String caminho = "/ti/ti119/ti119/api/files/Estacionamento/valor.txt";  // Caminho para ficheiro estacionamento

  clienteHTTP.get(caminho);                       // Pedido GET para ler valor do estacionamento

  int statusCode = clienteHTTP.responseStatusCode();  // Código HTTP da resposta
  String resposta = clienteHTTP.responseBody();        // Conteúdo do ficheiro

  Serial.println("Valor do Estacionamento lido: " + resposta);  // Mostra valor no monitor serial

  clienteHTTP.stop();                                 // Fecha conexão HTTP
  resposta.trim();                                    // Remove espaços e quebras de linha

  if (resposta == "1") {                              // Se valor for 1, liga LED estacionamento
    digitalWrite(4, HIGH);
  } else {                                            // Senão, desliga LED estacionamento
    digitalWrite(4, LOW);
  }
}

// Função loop() roda continuamente após setup()
void loop() { 
  // === LEITURA DE TEMPERATURA E HUMIDADE ===
  float Temperatura = dht.readTemperature();  // Lê temperatura do sensor DHT11
  float Humidade = dht.readHumidity();        // Lê humidade do sensor DHT11

  Serial.println("\n================= TEMPERATURA / HUMIDADE =================");

  if (isnan(Temperatura) || isnan(Humidade)) {  // Verifica se leituras são válidas
    Serial.println("Temperatura / Humidade: Falha na leitura do sensor DHT!"); // Se inválidas, imprime erro
  } else {
    clienteNTP.update();                         // Atualiza hora via NTP
    String datahora = formatarDataHora(clienteNTP.getEpochTime());  // Formata hora atual
    post2API("Temperatura", String(Temperatura), datahora);   // Envia temperatura para API
    post2API("Humidade", String(Humidade), datahora);         // Envia humidade para API
  }
  Serial.println("\n================= COR LED =================");
  corLed();            // Atualiza cor do LED a partir do servidor

  Serial.println("\n================= ESTACIONAMENTO =================");
  verEstacionamento(); // Atualiza estado do LED estacionamento a partir do servidor

  delay(5000);         // Aguarda 5 segundos antes da próxima iteração
}

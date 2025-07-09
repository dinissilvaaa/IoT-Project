import RPi.GPIO as GPIO             # Biblioteca para controlar os pinos GPIO do Raspberry Pi
import time                         # Biblioteca para usar pausas com time.sleep()
import requests                     # Biblioteca para fazer requisições HTTP (GET, POST)
from datetime import datetime       # Para trabalhar com datas e horas
from zoneinfo import ZoneInfo       # Para lidar com fusos horários corretamente (ex: Lisboa)
import cv2                          # Biblioteca OpenCV para captura e manipulação de imagem

# =============================================
# CONFIGURAÇÃO DE PINOS
# =============================================

BUZZER_PIN = 2                      # Define o pino GPIO 2 para o buzzer
BOTAO_PIN = 4                       # Define o pino GPIO 4 para o botão
SERVO_PIN = 17                      # Define o pino GPIO 17 para o servo motor

GPIO.setmode(GPIO.BCM)             # Define o modo BCM (numeração dos pinos GPIO)

GPIO.setup(BUZZER_PIN, GPIO.OUT)   # Configura o pino do buzzer como saída
GPIO.setup(BOTAO_PIN, GPIO.IN)
GPIO.setup(SERVO_PIN, GPIO.OUT)    # Configura o pino do servo como saída (PWM)

pwm_servo = GPIO.PWM(SERVO_PIN, 50)    # Inicializa o PWM no pino do servo com frequência de 50Hz
pwm_servo.start(0)                     # Inicia o PWM com duty cycle 0 (servo parado)

# =============================================
# URLS DA API
# =============================================

URL_API = "http://iot.dei.estg.ipleiria.pt/ti/ti119/ti119/api/api.php"  # URL da API para envio de dados
URL_CAMPAINHA = "http://iot.dei.estg.ipleiria.pt/ti/ti119/ti119/api/files/Campainha/valor.txt"  # Valor do buzzer
URL_HUMIDADE = "https://iot.dei.estg.ipleiria.pt/ti/ti119/ti119/api/files/Humidade/valor.txt"   # Valor da humidade
URL_ESTACIONAMENTO = "https://iot.dei.estg.ipleiria.pt/ti/ti119/ti119/api/files/Estacionamento/valor.txt"

# =============================================
# VARIÁVEL DE ESTADO GLOBAL (servo)
# =============================================

ultimo_estado_servo = None         # Guarda o último estado do servo motor (ex: girar/parado)

# =============================================
# FUNÇÃO: Enviar dados para a API
# =============================================

def post2API(nome: str, valor: str, estado: str):
    agora = datetime.now(ZoneInfo("Europe/Lisbon"))

    payload = {
        'nome': nome,
        'valor': valor,
        'hora': agora.strftime('%Y-%m-%d %H:%M:%S'),
        'estado': estado
    }

    url = URL_API

    try:
        response = requests.post(url, data=payload)  # 🔁 CORRIGIDO
        print(f"[POST] Código: {response.status_code} - Resposta: {response.text}")
    except Exception as e:
        print(f"[ERRO] Falha ao enviar para API: {e}")
    
# =============================================
# FUNÇÃO: Verificar valor do buzzer (remoto)
# =============================================

def verificar_valor_buzzer():
    try:
        resposta = requests.get(URL_CAMPAINHA, timeout=5)  # Faz GET ao valor do buzzer
        resposta.raise_for_status()                        # Lança exceção se erro HTTP
        valor = resposta.text.strip()                      # Limpa espaços e quebras de linha

        if valor not in ['0', '1']:                        # Valida valor recebido
            print(f"[BUZZER] Valor inválido: '{valor}' → Usando '0'")
            return '0'

        return valor

    except requests.RequestException as e:
        print(f"[BUZZER] Erro na leitura: {e}")            # Erro na requisição
        return '0'

# =============================================
# FUNÇÃO: Controlar servo com base na humidade
# =============================================

def controlar_servo_pela_humidade():
    global ultimo_estado_servo

    try:
        resposta = requests.get(URL_HUMIDADE, timeout=5)   # Lê valor da humidade
        resposta.raise_for_status()
        valor = resposta.text.strip()

        try:
            humidade = float(valor)                        # Converte para número

            if humidade > 30:
                pwm_servo.ChangeDutyCycle(6.5)             # Gira o servo (ajustar para o modelo)
                ultimo_estado_servo = 'girar'
                print(f"[SERVO] Humidade: {humidade} → A GIRAR")

            elif humidade <= 30 and ultimo_estado_servo == 'girar':
                pwm_servo.ChangeDutyCycle(0)               # Para o servo
                ultimo_estado_servo = 'parado'
                print(f"[SERVO] Humidade: {humidade} → PARADO")

            else:
                print(f"[SERVO] Humidade: {humidade} - Estado mantido - ({ultimo_estado_servo})")

        except ValueError:
            print(f"[SERVO] Valor inválido: '{valor}'")    # Não é número válido

    except requests.RequestException as e:
        print(f"[SERVO] Erro na leitura da humidade: {e}") # Falha no GET

# =============================================
# LOOP PRINCIPAL
# =============================================

try:
    while True:

        # =============================================
        # CÂMARA DE VIGILÂNCIA
        # =============================================

        webcam_url = "http://10.20.229.95:4747/video"     # URL da stream da webcam (app IP Webcam)
        cap = cv2.VideoCapture(webcam_url)                 # Captura vídeo da webcam
        ret, frame = cap.read()                            # Lê um frame
        cap.release()                                      # Liberta a câmera

        if not ret:
            print("[WEBCAM] Erro: não foi possível ler o frame da webcam")  # Falha
        else:
            local_path = "webcam.jpg"                      # Caminho para guardar a imagem
            cv2.imwrite(local_path, frame)                 # Salva o frame como imagem

            upload_url = "https://iot.dei.estg.ipleiria.pt/ti/ti119/ti119/camara.php"  # URL para upload
            with open(local_path, "rb") as f:              # Abre a imagem
                files = { "webcam": ("webcam.jpg", f, "image/jpeg") }  # Prepara ficheiro para envio
                resp = requests.post(upload_url, files=files)          # Faz upload da imagem

            if resp.status_code == 200:
                print(f"[IMAGEM] Upload bem-sucedido!")  # Sucesso
            else:
                print(f"[IMAGEM] Erro no upload: {resp.status_code} – {resp.text}")  # Erro

        # =============================================
        # BUZZER
        # =============================================
        valor_buzzer = verificar_valor_buzzer()            # Lê valor remoto da campainha

        if valor_buzzer == '1':
            GPIO.output(BUZZER_PIN, GPIO.HIGH)             # Ativa buzzer
            print("[BUZZER] ATIVO")
        else:
            GPIO.output(BUZZER_PIN, GPIO.LOW)              # Desativa buzzer
            print("[BUZZER] DESATIVADO")

        # =============================================
        # BOTÃO
        # =============================================

        estado_botao = GPIO.input(BOTAO_PIN)
        valor_botao = '1' if estado_botao == GPIO.HIGH else '0'
        post2API("Estacionamento", valor_botao, valor_botao)
        print(f"[ESTACIONAMENTO] {'OCUPADO' if valor_botao == '1' else 'LIVRE'}")
    
        # =============================================
        # SERVO
        # =============================================
        controlar_servo_pela_humidade()                    # Controla servo conforme humidade

        # --- Pausa de 3 segundos ---
        print("------ A aguardar próximo ciclo ------\n")
        time.sleep(3)                                      # Aguarda 3 segundos

# =============================================
# FINALIZAÇÃO (CTRL+C)
# =============================================

except KeyboardInterrupt:
    print("\n[PROGRAMA] Interrompido pelo utilizador.")    # Mensagem ao terminar com Ctrl+C

finally:
    GPIO.output(BUZZER_PIN, GPIO.LOW)      # Garante buzzer desligado
    pwm_servo.ChangeDutyCycle(0)           # Para o servo
    pwm_servo.stop()                       # Termina PWM
    GPIO.cleanup()                         # Liberta os pinos GPIO
    print("[PROGRAMA] GPIO limpo. Encerrado com sucesso.") # Fim seguro

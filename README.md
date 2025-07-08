# 🌐 Smart Environment Monitoring Dashboard

This project is a real-time dashboard connected to an API that shows the values of different sensors and controls actuators automatically. I developed it as part of my final project for the subject **Tecnologias da Informação** (Information Technologies).

## 📌 What does it do?

The system simulates a smart environment where several sensors and actuators send and receive data through a PHP API. The dashboard shows the current values live and also has a history page with graphs to see how things changed over time.

### 🔧 Main Features

- **Live Dashboard** with:
  - 🌡️ Temperature sensor → Controls an air conditioner (off, heat, cold)
  - 💧 Humidity sensor → Controls a fan (off, medium, max)
  - 🅿️ Parking sensor → Controls a parking sign (available, full)

- **Automatic Actuator Control** based on sensor values

- **History and Graphs** using logs stored in `.txt` files

- **API**:
  - Sensors send values to `api.php`
  - Dashboard reads values from the API
  - The API also sends the correct actuator state back to devices
  - All values are saved in text files

- **Arduino and Raspberry Pi support**:
  - 📲 Arduino MKR1000 connects via WiFi, reads actuator state from API, and turns on the right LED
  - 🍓 Raspberry Pi runs Python code to react to actuator values (buzzer, button, servo)

---

## 🧩 Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (API and dashboard)
- **Storage**: Text files (`.txt`)
- **Hardware**:
  - Arduino MKR1000
  - Raspberry Pi
  - DHT11 (temperature and humidity)
  - HC-SR04 or IR sensor (parking)
  - LEDs, buzzer, servo motor

---

## 🛠️ How it works (step by step)

1. Arduino and Raspberry collect sensor data
2. They send the data to the API (POST)
3. The API saves it in `.txt` files
4. The dashboard reads and shows the latest values
5. Based on the values, the API decides what the actuator should do
6. The devices read that info and act (turn on LED, buzzer, etc)

---

## ⚙️ How to run it

### For the API + Dashboard

1. Use a PHP-compatible server (e.g., XAMPP, WAMP or online hosting)
2. Give write permissions to the folders `sensores`, `atuadores`, and `logs`
3. Open `dashboard.php` in your browser

### For Arduino

1. Open the Arduino sketch (in `arduino/sketch.ino`)
2. Add your WiFi name, password, and API URL
3. Upload it to your MKR1000 board

### For Raspberry Pi

1. Make sure Python is installed along with `requests`, `RPi.GPIO`, and `cv2`
2. Run the main script: `python3 raspberry/main.py`
3. It will read from the API and control the buzzer, button and servo

---

## 🧠 Why I did this

- To put IoT concepts into practice
- To combine real electronics with web technologies
- To learn how devices communicate with a web server
- To organize my code in a clear and commented way

---

## 📚 Author

Made by **Dinis Silva**, 18 years old, from 🇵🇹 Portugal.  
Final project for the course **Tecnologias da Informação (TI)**.


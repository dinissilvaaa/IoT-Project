# 🌐 Smart Environment Monitoring Dashboard

A **real-time interactive dashboard** integrated with an **API** to monitor environmental sensors and control actuators dynamically. This project was developed as part of my final work in the subject of **Information Technologies**.

## 📌 Project Overview

This system simulates a smart environment where multiple sensors and actuators communicate with a central API. The **dashboard** provides a live view of the current sensor readings and actuator states, while also offering a **history section with graphs** to visualize trends over time.

### 🔧 Main Features

- **Real-time Dashboard**: Displays current values from:
  - 🌡️ Temperature sensor → Controls an air conditioner (off, heating, cooling)
  - 💧 Humidity sensor → Controls a fan (off, medium, max)
  - 🅿️ Parking sensor → Controls a parking sign (available, full)

- **Actuator Visualization**: Dashboard updates based on logic tied to sensor thresholds.

- **History and Graphs**: View detailed logs and line/bar charts of past sensor and actuator values.

- **API Integration**:
  - Sensors send data to a PHP-based API (`api.php`)
  - The dashboard fetches and visualizes this data
  - The actuator states are also written/read via the API
  - Files are used to log all actions (`*.txt` format)

- **Arduino and Raspberry Pi Support**:
  - 📲 Arduino MKR1000 with WiFi: Reads actuator values from API and controls LEDs
  - 🍓 Raspberry Pi with Python: Reads online sensor/actuator data and controls buzzer, button and servo motor accordingly

---

## 🧩 Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (API & dashboard)
- **Database**: `.txt` log files (lightweight data logging)
- **Hardware**:
  - Arduino MKR1000 (WiFi)
  - Raspberry Pi (Python + GPIO)
  - DHT11 sensor (Temperature and Humidity)
  - HC-SR04 or IR sensor for parking detection
  - LEDs, Buzzer, Servo Motor

---

## 🖥️ Project Structure

/project-root
├── dashboard.php # Displays real-time sensor & actuator states
├── historico1.php # Graphs + history (text file based)
├── historico2.php # Extended history + individual sensor logs
├── api.php # Receives data from Arduino/Pi and returns actuator states
├── sensores/ # Folder with latest sensor values
├── atuadores/ # Folder with latest actuator values
├── logs/ # Historical logs for sensors & actuators (.txt)
├── js/ # Chart libraries (e.g., Chart.js)
├── arduino/ # Arduino code to send/read data
└── raspberry/ # Python code to interact with the environment


---

## 📷 Screenshots

| Dashboard | History & Graphs |
|----------|------------------|
| ![Dashboard](screenshots/dashboard.png) | ![Graphs](screenshots/historico.png) |

---

## 🛠️ How It Works

1. **Arduino/Raspberry Pi** collect sensor data.
2. Devices **POST** sensor values to the **API**.
3. PHP stores the data in `.txt` files.
4. Dashboard and historical pages **read the files** to show real-time info and history.
5. Actuator logic runs on the server, and the result is sent back to the devices (LEDs, buzzers, etc).

---

## ⚙️ Setup Instructions

### API + Dashboard

1. Host the project on a PHP-supported server (e.g., XAMPP, WAMP, or remote server).
2. Make sure folders like `sensores`, `atuadores`, and `logs` are **writable** (`chmod 777` if Linux).
3. Open `dashboard.php` to view the main interface.

### Arduino

1. Use Arduino IDE.
2. Load the sketch from `arduino/sketch.ino`.
3. Replace WiFi credentials and API URL accordingly.
4. Upload to the MKR1000 board.

### Raspberry Pi

1. Make sure Python and `requests`, `RPi.GPIO`, `cv2` (OpenCV), etc. are installed.
2. Run the script from `raspberry/main.py`.
3. The Pi reads remote sensor/actuator states and interacts with buzzer/servo accordingly.

---

## 🧠 Project Goals

- Apply concepts of IoT (Internet of Things)
- Use real-time web technologies with physical devices
- Combine software and hardware logic
- Create a readable and maintainable codebase with full comments

---

## 📚 Credits

Created by **Dinis Silva**, 18-year-old student from **Portugal** 🇵🇹  
Final project for the subject **Tecnologias da Informação (TI)**

---

## 📫 Contact

- GitHub: [@dinissilvaaa](https://github.com/dinissilvaaa)
- Email: dinissilva.dev@gmail.com (example)
- Portfolio: coming soon...

---


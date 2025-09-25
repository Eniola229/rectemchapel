# AUTOMATIC BIOMETRIC SYSTEM

An advanced biometric recognition and management system designed for automation and efficiency.  
Built using **Laravel**, **Python**, and **C**, with **MySQL** as the primary database.

---

## 🚀 Features
- **Biometric Capture & Verification** – Reliable and accurate recognition process.
- **Laravel Backend** – RESTful API, authentication, and web interface.
- **Python Integration** – Handles biometric processing and data exchange.
- **C Components** – Used for performance-critical biometric operations.
- **MySQL Database** – Secure and efficient data storage.
- **Cloud/Local Communication** – Can integrate with third-party services (e.g., Cloudinary).

---

## 🛠️ Tech Stack
- **Backend:** Laravel (PHP 8+)
- **Processing:** Python 3.x + C
- **Database:** MySQL
- **Dependencies:** cURL, Composer, Pip, GCC (for C compilation)

---

## 📦 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/automatic-biometric-system.git
cd automatic-biometric-system
```

### 2. Install PHP & Laravel Dependencies
```bash
composer install
```

### 3. Configure Environment
Copy `.env.example` to `.env` and update your database credentials:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Run Database Migrations
```bash
php artisan migrate
```

### 5. Install Python Requirements
```bash
pip install -r requirements.txt
```

### 6. Compile C Code (if required)
```bash
gcc path/to/your_c_code.c -o your_c_executable
```

### 7. Serve the Application
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser.

---

## 📖 Usage
1. Connect the biometric device.
2. Start the application.
3. Capture and verify fingerprints.
4. Data will be securely stored in the MySQL database.

---

## 🧑‍💻 Author
**Built by:**  
👨‍💻 **Joshua Adeyemi (AfricTech)**  

---

## 📜 License
This project is licensed under the MIT License – you are free to use and modify it.

---

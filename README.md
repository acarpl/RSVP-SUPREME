# 🎉 RSVP SUPREME
A modern RSVP management system built with **Laravel** and **SQLite**, designed to make guest registration, attendance confirmation, and event monitoring seamless and efficient.  
Lightweight. Fast. Scalable.

---

## 🚀 Features

### 🔹 RSVP Form
- Input tamu yang responsif  
- Validasi otomatis  
- UI clean & minimalis  

### 🔹 Admin Dashboard
- Lihat daftar tamu real-time  
- Rekap hadir / tidak hadir  
- Sorting & filtering tamu  

### 🔹 Event Management
- Manajemen tamu simpel  
- Database SQLite yang super ringan  
- Mudah dipindah-pindah environment  

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | **Laravel 10+** |
| Database | **SQLite** |
| Frontend | Blade, TailwindCSS |
| Tools | GitHub, Composer, VS Code |

---

## 📦 Installation

### 1️⃣ Clone Repository
```bash
git clone https://github.com/acarpl/RSVP-SUPREME.git
cd RSVP-SUPREME
````

### 2️⃣ Install Dependencies

```bash
composer install
```

### 3️⃣ Setup Environment

Copy file `.env.example`:

```bash
cp .env.example .env
```

### 4️⃣ Configure SQLite

Buat file database SQLite:

```bash
mkdir -p database
touch database/database.sqlite
```

Lalu pada file `.env`, ganti:

```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**Note:** Pada Windows biasanya seperti ini:

```
DB_CONNECTION=sqlite
DB_DATABASE=C:\xampp\htdocs\RSVP-SUPREME\database\database.sqlite
```

💡 *Atau luarannya bisa kosong:*

```
DB_CONNECTION=sqlite
DB_DATABASE=
```

Laravel akan otomatis menggunakan `database/database.sqlite`.

---

## 🗂️ Migrate Database

```bash
php artisan migrate
```

---

## ▶️ Run the App

```bash
php artisan serve
```

App berjalan di:

```
http://127.0.0.1:8000
```

---

## 📁 Project Structure (Simplified)

```
RSVP-SUPREME/
│── app/
│── bootstrap/
│── config/
│── database/
│     └── database.sqlite
│── public/
│── resources/
│── routes/
│── .env
│── composer.json
│── README.md
```

---

## 🎯 How it Works

1. Tamu membuka halaman RSVP
2. Mengisi nama, kontak, status kehadiran
3. Data langsung disimpan ke SQLite
4. Admin melihat daftar tamu di dashboard sederhana
5. Semua proses cepat dan ringan

---

## 🧪 Testing (Optional)

```bash
php artisan test
```

---

## 👑 Author

**KEPALACACHIPMUNK — Art Director & Full Stack Developer**
**JIDANDUN — Full Stack Developer**
**IJASKRONGS — Full Stack Developer**

Creator of *RSVP SUPREME*, crafted with care, clarity, and clean code.

---

## 📄 License

Open-source for personal and educational use.

```

---

# General Ledger System (GL)

General Ledger (GL) adalah sistem akuntansi berbasis web untuk mengelola jurnal, transaksi, dan laporan keuangan.  
Aplikasi ini dibangun secara modular dengan kontrol akses berbasis peran (role-based access control).

---

## Overview

Sistem ini dirancang untuk mengelola data keuangan dengan struktur yang rapi dan aman.  
Dilengkapi fitur login, validasi sesi otomatis, manajemen menu dinamis, dan notifikasi berbasis SweetAlert.

---

## Tech Stack

| Layer           | Technology                              |
| ---------------- | --------------------------------------- |
| Backend          | PHP (CodeIgniter 3)                     |
| Frontend         | HTML5, Bootstrap 5, jQuery, SweetAlert2 |
| Database         | MySQL / MariaDB                         |
| Authentication   | Session-based                           |
| Role Management  | Dynamic Role & Menu Access Control      |

---

## Features

- Dashboard informasi keuangan  
- Role-based access (menu tampil sesuai hak akses)  
- Sidebar dinamis dari database  
- Session expiration otomatis  
- SweetAlert flash notification  
- Master data management (Company, Department, Cost Center, dll)  
- Modular controller system (navigasi dinamis dengan `tocontroller()`)  
- Validasi login dan hak akses  

---

## System Architecture

 
Client (HTML, Bootstrap, jQuery)
        │
        ▼
CodeIgniter MVC (Controller → Model → View)
Auth System + Middleware (is_logged_in)
Session + SweetAlert + Helper
        │
        ▼
MySQL Database (users, roles, menus, role_menu_access)
 
---

## Default Login

Username: admin
Password: admin


Syarat Pembuatan Journal 
1. Company harus sudah dibuat
2. Branch harus sudah dibuat
3. Department harus sudah dibuat
4. segmen harus sudah dibuat
5. divisi harus sudah dibuat
6. Cost Center harus sudah dibuat
7. journal source harus sudah dibuat
8. trial balance harus sudah dibuat group 1,2,3 berdasarkan company
9. cost Center harus sudah di assign ke user yang akan membuat journal
10. chart of account harus sudah dibuat
11. chart of account harus sudah di assign ke cost center


 

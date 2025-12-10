# DigitalService — Website Layanan Digital

DigitalService adalah website pemesanan layanan digital berbasis PHP yang menyediakan sistem pemesanan, manajemen layanan, dan pengelolaan data oleh admin.

Gunakan akun berikut untuk masuk ke halaman admin:

Username: admin  
Password: admin123

Pastikan akun admin sudah tersedia pada tabel `users` dengan role admin.

## Fitur Website

### Fitur untuk User
- Melihat daftar layanan dan paket.
- Melakukan pemesanan lengkap dengan upload bukti pembayaran.
- Melihat status pesanan (Pending, Waiting Confirmation, In Progress, Completed).
- Mengirim pesan melalui halaman Contact.
- Mengakses dashboard user dengan informasi pesanan yang tersimpan.

### Fitur untuk Admin
- Login khusus admin.
- Mengelola data layanan (tambah, edit, hapus).
- Mengelola data paket.
- Mengelola portofolio.
- Mengelola pesanan:
  - Mengubah status pesanan.
  - Melihat bukti pembayaran.
  - Menghapus pesanan.
- Mengelola pesan dari pengguna tanpa fitur balasan langsung.
- Dashboard statistik untuk memantau jumlah layanan, pesanan, dan pesan.

## Teknologi yang Digunakan
- PHP Native (tanpa framework)
- MySQL (phpMyAdmin / XAMPP)
- HTML & CSS
- JavaScript
- SweetAlert2
- FontAwesome


## Cara Menjalankan Proyek
1. Install XAMPP atau server lokal lainnya.
2. Pindahkan folder `digital_service` ke: C:\xampp\htdocs\
3. Buat database bernama `digital_service` melalui phpMyAdmin.
4. Import file SQL yang sudah disediakan.
5. Sesuaikan konfigurasi koneksi database pada `config/db.php` jika diperlukan.
6. Akses website melalui browser: http://localhost/digital_service/public/
7. Masuk ke halaman admin menggunakan akun admin yang tersedia.


## Catatan
- Pastikan folder upload memiliki izin tulis (write permission).
- Fitur balasan pesan tidak dilakukan melalui dashboard
- Admin membalas pesan melalui email pengguna.
- Semua fitur telah disesuaikan agar berjalan pada sesi user dan admin secara terpisah.

### Bagian ini menjelaskan langkah yang harus dilakukan jika menambahkan paket baru ke sistem.

Instruksi ini penting karena paket terhubung langsung dengan layanan melalui **service_id**.

1. **Tambahkan Paket Melalui Dashboard Admin**
   Tambahkan paket baru menggunakan fitur "Tambah Paket" di menu Admin. Pastikan data paket sudah lengkap (nama paket, harga, deskripsi, thumbnail, dan memilih layanan induk).

2. **Jika terjadi error ketika user menekan tombol "Order" lalu diarahkan ke section layanan**, maka lanjutkan ke langkah berikutnya.

3. **Periksa dan Sesuaikan service_id di phpMyAdmin**
   Masuk ke phpMyAdmin → buka tabel `packages` → periksa kolom **service_id**.

   * Jika kosong
   * Jika salah ID
   * Jika tidak sesuai dengan layanan yang seharusnya

   Maka paket tidak akan dikenali oleh sistem user, dan tombol Order akan otomatis mengarah kembali ke halaman layanan.

4. **Solusi:**
   Edit `service_id` pada paket tersebut agar cocok dengan ID layanan di tabel `services`.
   Setelah diperbaiki, tombol "Order" akan berfungsi dan mengarah ke halaman pemesanan sesuai paket.

   Contoh: Jika paket yang diinginkan adalah "UI/UX DEVELOPMENT", maka pilihlah column service_id 7, karena di option sudah tertera juga nama layanan tersebut. 

   Dan yang terpenting adalah service_id harus sesuai semua dengan layanan yang ada.  

5. **Catatan Penting:**

   * `service_id` adalah penghubung antara layanan dan paket.
   * Setiap kali menambah layanan baru, otomatis ID layanan berubah sesuai urutan.
   * Saat menambah paket, pastikan memilih layanan yang benar agar `service_id` tersimpan sesuai dengan melakukan sesuatu pada langkah point 4 "Solusi".

6. Jika sistem sudah benar dan service_id sesuai, maka paket akan muncul pada halaman layanan user dan tombol Order siap digunakan tanpa error.

# LP_Beauty --- Inventory & Sales Management System

Aplikasi web berbasis Laravel untuk membantu toko makeup dan skincare
mengelola data produk, persediaan, pemasok, pembelian, penjualan, dan
pengembalian barang. Proyek ini dikembangkan sebagai proyek terintegrasi
mata kuliah **Web Programming 2** dengan fokus pada Laravel MVC,
database relasional, validasi, autentikasi, otorisasi, serta konsistensi
stok.

> **Catatan status:** README ini disusun berdasarkan fitur dan struktur
> proyek yang telah dibahas serta persyaratan pada PPT UTS. Sebelum
> dikumpulkan, cocokkan bagian akun demo, seeder, ERD, screenshot, dan
> hasil pengujian dengan isi repository yang benar-benar tersedia.

## Daftar Isi

-   [Gambaran Proyek](#gambaran-proyek)
-   [Tujuan](#tujuan)
-   [Teknologi](#teknologi)
-   [Fitur](#fitur)
-   [Peran Pengguna](#peran-pengguna)
-   [Aturan Bisnis dan Alur Stok](#aturan-bisnis-dan-alur-stok)
-   [Struktur Data dan Relasi](#struktur-data-dan-relasi)
-   [Arsitektur Aplikasi](#arsitektur-aplikasi)
-   [Persyaratan Sistem](#persyaratan-sistem)
-   [Instalasi dan Menjalankan
    Aplikasi](#instalasi-dan-menjalankan-aplikasi)
-   [Konfigurasi Environment](#konfigurasi-environment)
-   [Akun Demo](#akun-demo)
-   [Pengujian](#pengujian)
-   [Dokumentasi Tambahan](#dokumentasi-tambahan)
-   [Keamanan](#keamanan)
-   [Batasan dan Catatan](#batasan-dan-catatan)

## Gambaran Proyek

LP_Beauty merupakan aplikasi pengelolaan inventaris dan transaksi untuk
toko makeup dan skincare. Sistem menghubungkan data produk dengan
kategori, pemasok, pembelian, penjualan, dan pergerakan stok sehingga
perubahan persediaan dapat ditelusuri.

Masalah yang ingin dibantu oleh sistem ini antara lain: - Pencatatan
stok yang tidak sesuai dengan kondisi transaksi. - Risiko penjualan
ketika stok tidak mencukupi. - Kesulitan melacak riwayat transaksi dan
perubahan persediaan. - Kebutuhan pembatasan akses antara Admin dan
Staff.

## Tujuan

1.  Mengelola data produk, kategori, dan pemasok dalam satu aplikasi.
2.  Mencatat pembelian dan penjualan beserta detail itemnya.
3.  Memperbarui stok sesuai transaksi yang berhasil.
4.  Menolak transaksi penjualan apabila stok tidak mencukupi.
5.  Menyediakan riwayat pergerakan stok dan pengembalian barang.
6.  Menerapkan autentikasi serta pembatasan akses berdasarkan peran
    pengguna.
7.  Menggunakan struktur Laravel MVC dan database relasional agar kode
    lebih terorganisasi.

## Teknologi

-   **Framework:** Laravel 13
-   **Bahasa:** PHP 8.3
-   **Database:** MySQL
-   **ORM:** Eloquent ORM
-   **Tampilan:** Blade, HTML, CSS, dan JavaScript
-   **Lingkungan pengembangan:** Laragon
-   **Dependency management:** Composer dan npm (jika aset frontend
    memerlukannya)

Versi yang tertulis adalah versi lingkungan pengembangan saat proyek
diperiksa. Versi aktual dapat berbeda sesuai konfigurasi komputer yang
menjalankan aplikasi.

## Fitur

### Autentikasi dan dashboard

-   Login dan logout.
-   Halaman yang memerlukan autentikasi.
-   Dashboard ringkas untuk Admin.

### Data master

-   **Produk:** melihat daftar, detail, menambah, mengubah, dan
    menghapus sesuai hak akses.
-   **SKU:** kode produk unik; pada formulir tambah produk, SKU dibuat
    otomatis berdasarkan kategori.
-   **Kategori:** pengelolaan kategori produk.
-   **Supplier:** pengelolaan data pemasok.

### Transaksi pembelian

-   Mencatat pembelian dari pemasok.
-   Menyimpan detail produk dan kuantitas pada transaksi.
-   Stok bertambah ketika pembelian berhasil diproses.

### Transaksi penjualan

-   Mencatat penjualan dan detail barang.
-   Memeriksa ketersediaan stok.
-   Stok berkurang ketika penjualan berhasil.
-   Penjualan dengan stok yang tidak mencukupi seharusnya ditolak dengan
    pesan yang sesuai.

### Pengembalian barang

-   Pengembalian dibuka dari **detail transaksi penjualan**, bukan
    sebagai menu terpisah.
-   Pengembalian dikaitkan dengan transaksi asal.
-   Barang yang layak dijual kembali dapat menambah stok.
-   Barang rusak tidak menambah stok layak jual.
-   Pada pertukaran barang, stok produk pengganti disesuaikan sesuai
    proses transaksi.
-   Riwayat perubahan stok terkait dapat dilihat melalui menu Manajemen
    Stok.

### Manajemen stok

-   Melihat riwayat pergerakan stok.
-   Mencatat penyesuaian stok manual sesuai kebutuhan, misalnya selisih
    stok fisik atau barang rusak.
-   Riwayat membantu menelusuri alasan perubahan persediaan.

### Pencarian dan navigasi

-   Pencarian produk berdasarkan informasi yang tersedia, termasuk nama,
    merek, atau SKU.
-   Filter kategori produk.
-   Navigasi aplikasi menggunakan layout Blade yang digunakan bersama.

## Peran Pengguna

Hak akses diatur melalui middleware/role pada rute aplikasi. Gambaran
fungsi yang dirancang:

  -----------------------------------------------------------------------
  Fungsi                                  Admin                     Staff
  ------------------- ------------------------- -------------------------
  Login dan logout                           Ya                        Ya

  Melihat produk                             Ya                        Ya

  Mengelola produk                           Ya                  Terbatas

  Mengelola kategori                         Ya                     Tidak

  Mengelola supplier                         Ya                     Tidak

  Mengelola pembelian                        Ya        Sesuai konfigurasi
                                                                 aplikasi

  Melakukan penjualan                        Ya                        Ya

  Memproses              Dirancang khusus Admin                     Tidak
  pengembalian                                  

  Manajemen stok             Sesuai konfigurasi        Sesuai konfigurasi
                                       aplikasi                  aplikasi
  -----------------------------------------------------------------------

**Penting:** tabel ini menggambarkan rancangan akses yang dituju.
Pastikan implementasi akhir di `routes/web.php`, middleware, controller,
dan tampilan konsisten. Menyembunyikan tombol di tampilan saja tidak
cukup; rute yang melakukan aksi juga harus dilindungi di server.

## Aturan Bisnis dan Alur Stok

### Pembelian

1.  Pengguna memilih pemasok dan produk.
2.  Sistem memvalidasi data serta kuantitas.
3.  Sistem menyimpan transaksi beserta detail item.
4.  Setelah transaksi berhasil, stok produk bertambah.

### Penjualan

1.  Staff/Admin memilih produk dan kuantitas.
2.  Sistem memeriksa stok yang tersedia.
3.  Sistem menghitung subtotal dan total di sisi server.
4.  Sistem menyimpan transaksi beserta detail item.
5.  Setelah transaksi berhasil, stok berkurang.
6.  Jika stok tidak cukup, transaksi harus ditolak tanpa mengurangi
    stok.

### Pengembalian

1.  Pengguna yang berwenang membuka detail penjualan.
2.  Pengembalian diproses berdasarkan transaksi penjualan asal.
3.  Sistem menyesuaikan stok berdasarkan kondisi barang dan jenis proses
    pengembalian.
4.  Pergerakan stok dicatat agar dapat ditelusuri.

### Penyesuaian stok manual

Penyesuaian digunakan untuk kejadian di luar transaksi
pembelian/penjualan, seperti barang rusak, kehilangan, atau perbedaan
antara stok fisik dan sistem. Penyesuaian sebaiknya menyertakan jenis
perubahan, jumlah, dan alasan yang jelas.

## Struktur Data dan Relasi

Tabel utama yang digunakan atau diharapkan dalam sistem:

-   `users` --- akun pengguna dan peran.
-   `categories` --- kategori produk.
-   `products` --- data produk dan SKU.
-   `suppliers` --- data pemasok.
-   `purchases` --- header transaksi pembelian.
-   `purchase_items` --- detail item pembelian.
-   `sales` --- header transaksi penjualan.
-   `sale_items` --- detail item penjualan.
-   `stock_movements` --- riwayat pergerakan stok.
-   `sale_returns` --- data utama pengembalian.
-   `sale_return_items` --- detail item pengembalian.

Relasi Eloquent yang relevan meliputi: - Category `hasMany` Products;
Product `belongsTo` Category. - Supplier `hasMany` Purchases; Purchase
`belongsTo` Supplier. - Purchase `hasMany` PurchaseItems; PurchaseItem
`belongsTo` Purchase dan Product. - User `hasMany` Sales; Sale
`belongsTo` User. - Sale `hasMany` SaleItems; SaleItem `belongsTo` Sale
dan Product. - Product berelasi dengan item pembelian dan item
penjualan. - Sale return terhubung ke transaksi penjualan dan detail
item pengembalian sesuai model yang digunakan.

Foreign key ditempatkan pada tabel yang menyimpan referensi ke data
induk, misalnya `products.category_id`, `purchase_items.purchase_id`,
dan `sale_items.sale_id`.

### ERD

Tambahkan gambar ERD final pada repository, misalnya dengan nama
`docs/erd-lp-beauty.png`, lalu tampilkan di bawah ini setelah file
tersebut tersedia:

``` markdown
![ERD LP_Beauty](docs/erd-lp-beauty.png)
```

> Jangan menambahkan gambar placeholder sebagai ERD final. Pastikan
> diagram sesuai dengan migration dan relasi yang benar-benar ada di
> proyek.

## Arsitektur Aplikasi

Aplikasi mengikuti pola Laravel MVC:

1.  **Browser** mengirim request HTTP.
2.  **Route** pada `routes/web.php` menentukan endpoint.
3.  **Middleware** memeriksa autentikasi dan hak akses.
4.  **Controller** memvalidasi request dan menjalankan alur aplikasi.
5.  **Model/Eloquent** berinteraksi dengan database serta relasi.
6.  **MySQL** menyimpan data transaksi dan persediaan.
7.  **Blade View** menampilkan respons kepada pengguna.

Validasi input harus dilakukan di sisi server. Nilai penting seperti
harga, total transaksi, dan perubahan stok tidak boleh hanya dipercaya
dari input browser.

## Persyaratan Sistem

-   PHP sesuai versi yang didukung proyek (pengembangan dilakukan dengan
    PHP 8.3).
-   Composer.
-   MySQL.
-   Node.js dan npm jika aset frontend perlu dibangun.
-   Git (opsional, untuk mengambil repository).
-   Ekstensi PHP yang dibutuhkan oleh Laravel dan driver database MySQL.

## Instalasi dan Menjalankan Aplikasi

### 1. Masuk ke folder proyek

Jika proyek sudah ada di komputer:

``` bash
cd C:\laragon\www\lp-beauty
```

Jika mengambil proyek dari repository, gunakan URL repository milik
proyek:

``` bash
git clone <URL_REPOSITORY>
cd <NAMA_FOLDER_PROYEK>
```

Ganti placeholder dengan URL dan nama folder yang benar.

### 2. Instal dependency PHP

``` bash
composer install
```

### 3. Siapkan file environment

Buat file `.env` dari contoh:

**Windows Command Prompt:**

``` bat
copy .env.example .env
```

**Git Bash / Linux / macOS:**

``` bash
cp .env.example .env
```

Jika file `.env` sudah tersedia pada instalasi lokal, jangan menimpanya
secara tidak sengaja. Pastikan pengaturan database sesuai dengan MySQL
lokal.

### 4. Buat application key

``` bash
php artisan key:generate
```

### 5. Atur database

Buat database kosong melalui MySQL/phpMyAdmin, lalu sesuaikan
konfigurasi pada `.env`. Contoh:

``` dotenv
APP_NAME=LP_Beauty
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lp_beauty
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, password, dan port dengan lingkungan
lokal. Jangan menyalin kredensial pribadi ke repository.

### 6. Jalankan migration

Periksa terlebih dahulu migration yang tersedia. Untuk database baru
yang kosong:

``` bash
php artisan migrate
```

Seeder dapat dijalankan jika seeder proyek memang telah disiapkan dan
ditinjau:

``` bash
php artisan db:seed
```

Jika ada seeder yang membuat akun demo atau data contoh, periksa isi
file seeder sebelum menjalankannya. Jangan menggunakan `migrate:fresh`
pada database yang berisi data penting karena perintah itu menghapus
seluruh tabel sebelum membuatnya kembali.

### 7. Siapkan aset frontend (jika diperlukan)

``` bash
npm install
npm run build
```

Jika proyek tidak menggunakan proses build frontend, langkah ini mungkin
tidak diperlukan.

### 8. Jalankan aplikasi

``` bash
php artisan serve
```

Buka alamat berikut pada browser:

``` text
http://127.0.0.1:8000
```

Untuk masuk, buka halaman login aplikasi. Pastikan MySQL/Laragon
berjalan terlebih dahulu.

## Konfigurasi Environment

File `.env` berisi konfigurasi lokal seperti: - Nama dan URL aplikasi. -
Mode aplikasi dan pengaturan debug. - Koneksi database. - Konfigurasi
session, cache, dan layanan lain yang digunakan.

File `.env` **tidak boleh dimasukkan ke repository**. Repository
sebaiknya menyertakan `.env.example` tanpa password atau rahasia. Untuk
penggunaan production, `APP_DEBUG` harus dinonaktifkan dan konfigurasi
keamanan diperiksa.

## Akun Demo

Masukkan akun demo hanya setelah memastikan akun tersebut benar-benar
tersedia melalui seeder atau telah dibuat secara sengaja di lingkungan
demo.

  Peran   Email/Username             Password
  ------- -------------------------- --------------------------------
  Admin   Isi setelah diverifikasi   Jangan simpan password pribadi
  Staff   Isi setelah diverifikasi   Jangan simpan password pribadi

Gunakan akun demo, bukan akun pribadi. Ganti password contoh sebelum
presentasi atau penggunaan nyata. Jangan mengunggah password asli ke
GitHub.

## Pengujian

Gunakan skenario berikut untuk memeriksa fungsi aplikasi. Isi hasil
aktual setelah pengujian; jangan menandai lulus jika skenario belum
dicoba.

  --------------------------------------------------------------------------
                    No. Skenario         Hasil yang       Hasil aktual
                                         diharapkan       
  --------------------- ---------------- ---------------- ------------------
                      1 Membuka          Dialihkan ke     Belum dicatat
                        dashboard tanpa  login atau       
                        login            ditolak          

                      2 Login dengan     Pengguna         Sudah diperiksa:
                        kredensial benar berhasil masuk   berhasil

                      3 SKU otomatis     SKU sesuai       Sudah diperiksa:
                        saat memilih     prefix kategori  normal
                        kategori         dan unik         

                      4 Menambah produk  Produk tersimpan Belum dicatat pada
                        dengan data                       checklist ini
                        valid                             

                      5 Memasukkan data  Validasi menolak Belum dicatat
                        produk tidak     data dan         
                        valid            menampilkan      
                                         pesan            

                      6 Pembelian        Stok bertambah   Belum dicatat
                        berhasil         sesuai jumlah    

                      7 Penjualan dengan Transaksi        Halaman/formulir
                        stok cukup       berhasil dan     sudah diperiksa;
                                         stok berkurang   skenario perubahan
                                                          stok perlu diuji

                      8 Penjualan        Transaksi        Belum dicatat
                        melebihi stok    ditolak dan stok 
                                         tidak berubah    

                      9 Pengembalian     Stok diperbarui  Alur halaman sudah
                        barang layak     sesuai aturan    diperiksa; hasil
                        jual             pengembalian     perubahan stok
                                                          perlu dicatat

                     10 Pengembalian     Tidak menambah   Belum dicatat
                        barang rusak     stok layak jual  

                     11 Penyesuaian stok Stok dan riwayat Halaman/formulir
                        manual           tercatat sesuai  sudah diperiksa;
                                         input            penyimpanan perlu
                                                          diuji

                     12 Staff membuka    Akses ditolak    Periksa setiap
                        fungsi khusus    oleh server      rute yang dibatasi
                        Admin                             

                     13 Pencarian dan    Hasil sesuai     Sudah diperiksa:
                        filter produk    kata             normal
                                         kunci/filter     

                     14 Relasi dan       Tabel serta      Migration telah
                        migration        relasi sesuai    tercatat berjalan;
                                         skema            relasi perlu
                                                          diverifikasi
                                                          terhadap kode
  --------------------------------------------------------------------------

### Perintah pemeriksaan yang berguna

``` bash
php artisan about
php artisan migrate:status
php artisan route:list
```

Jalankan perintah pemeriksaan tersebut dari folder proyek. Hindari
perintah yang menghapus tabel saat menggunakan database berisi data.

## Dokumentasi Tambahan

Sesuai kebutuhan pengumpulan proyek, lengkapi repository dengan:

-   `docs/erd-lp-beauty.png` --- ERD final yang sesuai dengan migration.
-   `docs/screenshots/` --- screenshot login, dashboard, produk,
    pembelian, penjualan, detail pengembalian, dan manajemen stok.
-   `docs/test-cases.md` --- skenario uji beserta hasil aktual atau
    bukti pengujian.
-   Informasi akun demo yang aman, tanpa password pribadi.
-   Penjelasan singkat relasi Eloquent, aturan stok, dan pembatasan
    peran.

Pastikan screenshot tidak memperlihatkan informasi pribadi atau rahasia.

## Keamanan

-   Semua halaman dan aksi sensitif harus dilindungi autentikasi serta
    otorisasi server-side.
-   Gunakan validasi Laravel pada request yang diterima.
-   Pertahankan proteksi CSRF pada formulir web.
-   Password harus disimpan menggunakan hashing, bukan teks biasa.
-   Gunakan escaping output pada Blade untuk data pengguna.
-   Gunakan foreign key dan unique constraint yang sesuai.
-   Untuk transaksi yang mengubah beberapa tabel, pertimbangkan database
    transaction agar data tidak tersimpan sebagian ketika terjadi
    kegagalan.
-   Jangan commit `.env`, password, API key, atau data pribadi.
-   Jangan gunakan `APP_DEBUG=true` pada production.

## Batasan dan Catatan

-   README ini menjelaskan tujuan dan alur fitur LP_Beauty. Detail yang
    belum diverifikasi pada kode perlu dicocokkan sebelum pengumpulan.
-   ERD, screenshot, dan akun demo harus ditambahkan berdasarkan artefak
    yang benar-benar ada; jangan mengklaim lampiran yang belum dibuat.
-   Pastikan daftar fitur dan tabel pada README cocok dengan migration,
    model, controller, route, dan tampilan versi akhir.
-   Jika repository akan dinilai pada komputer bersih, lakukan uji
    instalasi dari clone baru dan database kosong bila waktu
    memungkinkan.
-   Mahasiswa harus dapat menjelaskan kode dan aturan bisnis yang
    dikumpulkan, khususnya relasi Eloquent, validasi stok, perhitungan
    total di server, transaksi database, serta middleware untuk
    pembatasan akses.

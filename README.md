# 📊 SPOS - Sistem Peramalan Penjualan & Optimalisasi Stok

## 📌 Deskripsi
SPOS (Sistem Peramalan Penjualan & Optimalisasi Stok) adalah aplikasi berbasis web yang dikembangkan untuk membantu bisnis dalam mengelola transaksi penjualan sekaligus melakukan **analisis data penjualan**.

Berbeda dengan sistem kasir pada umumnya, SPOS dilengkapi dengan fitur **peramalan penjualan menggunakan metode Least Square** serta **optimalisasi stok menggunakan metode Min-Max**, sehingga dapat membantu pengambilan keputusan dalam pengelolaan inventori.

---

## 🚀 Fitur Utama
- 🔐 Autentikasi & Manajemen User (Admin / Kasir)
- 🛒 Transaksi Penjualan (Point of Sale)
- 📦 Manajemen Data Barang & Stok
- 📊 Laporan Penjualan
- 📈 Peramalan Penjualan (Forecasting)
- 📉 Rekomendasi Optimalisasi Stok
- 🧾 Cetak Struk

---

## 🧠 Metode Peramalan & Optimalisasi

### 📈 Peramalan Penjualan (Least Square)
Metode **Least Square** digunakan untuk memprediksi penjualan di periode mendatang berdasarkan data historis.

Model matematis:
Y = a + bX

Dimana:
- Y = hasil peramalan penjualan
- X = periode waktu
- a = konstanta
- b = koefisien tren

Metode ini digunakan untuk mengidentifikasi tren penjualan sehingga sistem dapat memperkirakan kebutuhan stok di masa depan secara lebih akurat.

---

### 📦 Optimalisasi Stok (Min-Max)
Metode **Min-Max** digunakan untuk menentukan batas minimum dan maksimum stok barang.

- **Minimum Stock (Min):** batas minimal sebelum dilakukan restock
- **Maximum Stock (Max):** batas maksimal penyimpanan barang

Sistem akan memberikan rekomendasi:
- Waktu yang tepat untuk melakukan restock
- Jumlah stok yang perlu ditambahkan

Tujuan:
- Menghindari kehabisan stok (stockout)
- Mengurangi kelebihan stok (overstock)

---

## 📊 Contoh Hasil Analisis
Contoh:
- Hasil peramalan bulan berikutnya: **120 unit**
- Minimum stok: **50 unit**
- Maximum stok: **150 unit**

Berdasarkan hasil tersebut, sistem dapat memberikan rekomendasi pengadaan barang secara optimal.

---

## 🛠️ Teknologi yang Digunakan
- **Backend:** Laravel (PHP)
- **Frontend:** HTML, CSS, JavaScript
- **Template UI:** AdminLTE
- **Database:** MySQL

## UI Dashboard Web
<img width="1902" height="876" alt="image" src="https://github.com/user-attachments/assets/f254ce18-66f2-4a0a-aeb1-02761134040a" />

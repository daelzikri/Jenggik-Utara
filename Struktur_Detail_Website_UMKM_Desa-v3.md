# Dokumen Struktur Detail, Tampilan, dan Konten Website Portal UMKM & Desa (Versi 3 - Halaman Detail Lengkap)

Dokumen ini berfungsi sebagai panduan cetak biru (*blueprint*) final untuk pengembangan website Portal Komunitas Desa & Gerai UMKM Terpadu. Pada versi ini, struktur dirancang langsung ke produk tanpa kategori umum, lengkap dengan rancangan halaman detail spesifik untuk setiap produk UMKM.

---

## 🗺️ 1. Arsitektur Informasi & Struktur Navigasi

Website menggunakan struktur datar (*flat structure*) pada halaman katalog utama, di mana setiap kartu produk langsung terhubung secara dinamis ke halaman detailnya masing-masing.

```
[Beranda / Home]
   ├── Profil Singkat Desa
   ├── Potensi Desa
   ├── Produk UMKM Unggulan (Sorotan)
   └── Testimoni Pengunjung/Pembeli
│
└── [Gerai UMKM / Katalog Utama] 
       ├── Kartu Gula Aren ───────────────> [Halaman Detail: Gula Aren]
       ├── Kartu VCO (Minyak Kelapa) ─────> [Halaman Detail: VCO]
       ├── Kartu Keripik Talas ───────────> [Halaman Detail: Keripik Talas]
       ├── Kartu Klepon ──────────────────> [Halaman Detail: Klepon]
       ├── Kartu Jajanan Tradisional ─────> [Halaman Detail: Jajanan Tradisional]
       └── Kartu Keranjang Dasan Tinggi ──> [Halaman Detail: Keranjang Dasan Tinggi]
```

---

## 🎨 2. Panduan Visual & Tata Letak Halaman Utama

### A. Halaman Beranda (Home)
* **Bagian Hero (Slider / Carousel):** Foto *full-width* keasrian alam desa, aktivitas warga (penderes nira/pengrajin), dan produk unggulan secara bergantian otomatis. Teks overlay: *"Selamat Datang di Portal Resmi Desa Wisata & Gerai UMKM Terpadu"*.
* **Bagian Profil & Potensi:** Tata letak 2 kolom berisi narasi singkat kekayaan alam desa sebagai basis bahan baku produk lokal.
* **Bagian UMKM Unggulan:** Menampilkan 3 produk ikonik (misal: Gula Aren, VCO, Keranjang) dengan tombol cepat *"Lihat Detail Produk"*.
* **Bagian Testimoni:** Ruang khusus di bawah berbentuk slider horizontal berisi ulasan kepuasan pembeli atau kesan wisatawan.

### B. Halaman Gerai UMKM (Katalog)
* **Tampilan:** Grid kartu produk yang tersusun rapi (3-4 kolom di desktop, 1-2 kolom di HP).
* **Isi:** Menampilkan seluruh 6 produk secara sejajar langsung tanpa folder/kategori perantara (Makanan/Kerajinan).

---

## 📄 3. Spesifikasi & Konten Halaman Detail Setiap UMKM

Setiap produk memiliki satu halaman khusus yang terpisah. Struktur tampilan seragam (Kiri: Galeri Foto, Kanan: Informasi Utama & Tombol WhatsApp, Bawah: Deskripsi Lengkap).

### 1. Halaman Detail: Gula Aren
* **Nama Produk Resmi:** Gula Aren Asli & Gula Semut Alami Desa
* **Tagline:** *100% Dari Nira Murni, Diproses Tradisional Tanpa Bahan Kimia.*
* **Deskripsi Produk:** Diproduksi langsung oleh para penderes nira desa dari pohon aren pilihan. Melalui proses pemasakan tradisional yang terjaga suhunya untuk menghasilkan aroma wangi yang pekat dan rasa manis legit yang alami. Sangat sehat sebagai pengganti gula pasir harian.
* **Informasi Tambahan:** Tersedia varian Gula Cetak (Batangan) dan Gula Semut (Bubuk). Kemasan ramah lingkungan.
* **Teks WhatsApp Otomatis:** *"Halo Pengelola UMKM, saya tertarik memesan produk Gula Aren Desa. Mohon informasi harga dan cara pengirimannya."*

### 2. Halaman Detail: VCO (Virgin Coconut Oil)
* **Nama Produk Resmi:** VCO - Minyak Kelapa Murni Alami
* **Tagline:** *Minyak Kelapa Murni Metode Cold-Pressed, Kaya Manfaat untuk Kesehatan.*
* **Deskripsi Produk:** Dibuat dari kelapa segar pilihan hasil perkebunan desa tanpa melalui proses pemanasan tinggi (Cold-Pressed) dan tanpa penjernih kimia. Menghasilkan minyak VCO jernih dengan aroma kelapa segar yang lembut. Sangat baik untuk suplemen kesehatan, perawatan kulit, maupun kebutuhan diet.
* **Informasi Tambahan:** Kemasan botol higienis ukuran 100ml dan 250ml.
* **Teks WhatsApp Otomatis:** *"Halo Pengelola UMKM, saya ingin memesan produk Minyak Kelapa Murni (VCO). Boleh tahu untuk ukuran yang ready ukuran berapa saja? Terima kasih."*

### 3. Halaman Detail: Keripik Talas
* **Nama Produk Resmi:** Keripik Talas Renyah Khas Desa
* **Tagline:** *Camilan Gurih Nan Renyah dari Talas Segar Pilihan Warga.*
* **Deskripsi Produk:** Camilan renyah yang berbahan dasar talas lokal hasil panen kebun desa. Diiris tipis secara presisi dan digoreng dengan minyak berkualitas untuk menghasilkan tekstur yang renyah tanpa keras. Bebas dari pengawet buatan, sangat cocok menemani waktu santai bersama keluarga.
* **Informasi Tambahan:** Tersedia varian rasa Original (Asin Gurih) dan Pedas Manis Lokal. Kemasan ziplock kedap udara.
* **Teks WhatsApp Otomatis:** *"Halo Pengelola UMKM, saya mau memesan Keripik Talas Desa. Apakah varian rasa original dan pedas manisnya siap kirim?"*

### 4. Halaman Detail: Klepon
* **Nama Produk Resmi:** Klepon Gula Aren Lumer Segar
* **Tagline:** *Dibuat Segar Setiap Hari, Sensasi Gula Aren Asli yang Meledak di Mulut.*
* **Deskripsi Produk:** Kue basah tradisional legendaris yang terbuat dari tepung ketan premium dipadu dengan pasta daun pandan asli untuk warna hijau alaminya. Diisi dengan potongan Gula Aren asli desa kami dan dibalur parutan kelapa muda segar. 
* **Catatan Khusus:** Produk ini diproduksi segar harian berdasarkan pesanan (*Made to Order*) untuk menjaga kualitas tekstur lembutnya. Hanya melayani pengiriman instan/lokal.
* **Teks WhatsApp Otomatis:** *"Halo Pengelola UMKM, saya mau pesan Klepon Lumer untuk acara besok. Apakah bisa diproduksi dan dikirim pagi hari?"*

### 5. Halaman Detail: Jajanan Tradisional
* **Nama Produk Resmi:** Paket Tampah Aneka Jajanan Tradisional Desa
* **Tagline:** *Sajian Kue Basah Tradisional Otentik untuk Segala Acara Anda.*
* **Deskripsi Produk:** Menyediakan berbagai macam kue basah tradisional khas desa (seperti lapis, nagasari, dadar gulung, dan lainnya) yang dibuat menggunakan resep turun-temurun. Menggunakan pemanis alami dan tanpa pewarna tekstil, memberikan rasa otentik jajanan pasar yang dirindukan.
* **Informasi Tambahan:** Menerima pesanan dalam bentuk paket kotak (snack box) maupun nampan/tampah besar untuk keperluan rapat, hajatan, atau acara keluarga.
* **Teks WhatsApp Otomatis:** *"Halo Pengelola UMKM, saya ingin bertanya mengenai opsi paket Jajanan Tradisional untuk keperluan acara/hajatan. Mohon daftar menunya. Terima kasih."*

### 6. Halaman Detail: Keranjang Dasan Tinggi
* **Nama Produk Resmi:** Keranjang Anyaman Bambu Khas Dasan Tinggi
* **Tagline:** *Karya Seni Anyaman Tangan Estetik, Kuat, dan Ramah Lingkungan.*
* **Deskripsi Produk:** Produk kerajinan tangan kebanggaan dari wilayah Dasan Tinggi. Dianyam secara manual oleh para pengrajin lokal yang terampil menggunakan bahan bambu pilihan yang sudah melalui proses pengawetan alami. Memiliki struktur yang kokoh, rapi, dan desain yang estetik untuk wadah belanja, hantaran, maupun dekorasi ruangan.
* **Informasi Tambahan:** Tersedia dalam berbagai ukuran (Kecil, Sedang, Besar) dengan opsi tali pegangan kulit atau anyaman penuh.
* **Teks WhatsApp Otomatis:** *"Halo Pengelola UMKM, saya sangat tertarik dengan produk Keranjang Anyaman Dasan Tinggi. Boleh dikirimkan katalog ukuran beserta harganya?"*

---

## ⚙️ 4. Catatan Teknis untuk Pengembang (Developer)
1. **Dynamic Page Template:** Developer wajib membuat 1 templat halaman detail produk (*single-product.php* atau komponen dinamis) agar pengelolaan data teks dan gambar di atas tinggal ditarik dari database berdasarkan ID produk tanpa membuat 6 file kode terpisah.
2. **Pre-filled WhatsApp Link Generator:** Gunakan enkripsi teks otomatis URL WhatsApp (`https://wa.me/nomorhp?text=...`) untuk setiap tombol pemesanan sesuai dengan draft teks otomatis yang ada pada tiap poin di atas.

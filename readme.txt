=== Indfir ===

Contributors: Indra Firdaus
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tema majalah berita untuk indfir.com. Tanpa page builder, tanpa lisensi
berbayar, tanpa dependensi plugin.

== Deskripsi ==

Indfir menyusun halaman depan secara otomatis dari kategori yang kamu pilih,
sehingga tidak ada layout yang perlu dirakit ulang setiap hari. Susunan blok
beranda:

1. Hero      - berita utama, satu sorotan bergambar, dan kolom terpopuler.
2. Blok 1-3  - satu kategori utama (dua kolom) diapit dua kategori sempit.
3. Blok 4-5  - dua sorotan besar bergambar plus sidebar kategori.
4. Blok 6    - kategori lebar penuh: tiga sorotan dan deretan kartu.
5. Terbaru   - daftar artikel terbaru; blok ini yang membawa penomoran halaman.

Pada halaman 2 dan seterusnya hanya daftar "Terbaru" yang tampil, sehingga
navigasi halaman berperilaku seperti arsip biasa.

== Pengaturan ==

Semua diatur lewat Tampilan > Sesuaikan (Customizer):

* Identitas Situs   - logo dan tagline di samping logo.
* Warna             - warna utama (navbar) dan warna aksen.
* Tipografi & Layout- Google Fonts on/off, font stack sendiri, lebar
                      kontainer, panjang ringkasan.
* Header & Top Bar  - tampilkan top bar, teks kiri, tanggal otomatis.
* Media Sosial      - Facebook, Instagram, X, YouTube.
* Beranda: Blok     - judul dan kategori untuk tiap blok. Kosongkan judul
                      untuk menyembunyikan blok.
* Halaman Artikel   - tombol berbagi, artikel terkait, kotak penulis,
                      navigasi sebelum/berikutnya, breadcrumb.
* Footer            - judul kolom, deskripsi, teks hak cipta.

== Lokasi Menu ==

* Menu Utama (navbar) - mendukung dropdown sampai 3 tingkat.
* Menu Atas (top bar) - satu tingkat, dipakai ulang di kolom pertama footer.
* Menu Footer        - satu tingkat, tampil di atas kolom footer.

Bila belum ada menu yang dipasang di "Menu Utama", tema menampilkan Home
plus tujuh kategori teramai secara otomatis.

== Area Widget ==

* Sidebar Utama  - tampil di artikel, arsip, pencarian, dan beranda.
                   Bila kosong, konten memakai lebar penuh.
* Footer Kolom 1-4 - bila keempatnya kosong, footer memakai tampilan bawaan
                   (deskripsi, Terbaru, Populer, Jelajah).

== Populer ==

Kolom "Populer" memakai penghitung tampilan bawaan tema yang disimpan di
meta _indfir_views. Kunjungan dari pengguna yang sedang login tidak dihitung.
Angka mulai terkumpul sejak tema diaktifkan, jadi beberapa hari pertama
urutannya masih mengikuti tanggal.

== Ukuran Gambar ==

* indfir-lead  800x500  - sorotan hero dan blok lebar.
* indfir-card  520x320  - kartu standar.
* indfir-hero  700x560  - sorotan bergambar dengan overlay gelap.
* indfir-thumb 150x120  - thumbnail daftar samping.

Setelah aktivasi, jalankan plugin regenerate thumbnails agar artikel lama
punya ukuran-ukuran ini.

== Changelog ==

= 1.0.0 =
* Rilis pertama.

INSTRUKSI PEMBUATAN APLIKASI WEB INVENTARIS

TUJUAN
Buat aplikasi manajemen stok untuk bengkel Vespa bernama Mo Gerzz. Sistem harus menghitung titik pemesanan kembali secara otomatis menggunakan metode Safety Stock dan Reorder Point.

TEKNOLOGI

Gunakan Framework Laravel.

Gunakan Database MySQL.

Gunakan Bootstrap atau Tailwind CSS untuk tampilan.

STRUKTUR DATABASE

Tabel Produk: simpan nama barang, stok saat ini, waktu tunggu (lead time), rata-rata pemakaian, nilai SS, dan nilai ROP.

Tabel Transaksi: catat setiap barang masuk dan keluar beserta tanggalnya.

Tabel Supplier: simpan informasi kontak penyedia barang.

LOGIKA PERHITUNGAN

Hitung Safety Stock (SS) dengan rumus: (Pemakaian Maksimal x Lead Time Maksimal) dikurangi (Pemakaian Rata-rata x Lead Time Rata-rata).

Hitung Reorder Point (ROP) dengan rumus: (Pemakaian Rata-rata x Lead Time) ditambah SS.

Sistem harus memperbarui nilai ini secara otomatis saat ada data transaksi baru.

FITUR UTAMA

Dashboard: Tampilkan ringkasan total stok dan daftar barang yang sudah menyentuh batas ROP.

Master Barang: Fitur untuk menambah, mengubah, dan menghapus data barang.

Pencatatan Transaksi: Input barang masuk dan keluar yang langsung mengubah jumlah stok di database.

Laporan Restock: Halaman khusus yang menampilkan daftar barang yang harus segera dipesan ke supplier.

VALIDASI DAN KEAMANAN

Beri tanda peringatan merah jika stok saat ini di bawah nilai ROP.

Cegah transaksi keluar jika jumlah stok tidak mencukupi.

Gunakan sistem login agar hanya admin yang bisa mengelola data
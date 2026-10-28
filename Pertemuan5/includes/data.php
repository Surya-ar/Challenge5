<?php
/**
 * data.php
 * Pusat data statis: profil toko, kategori, dan daftar produk UMKM.
 * Ganti nomor WhatsApp di bawah dengan nomor milikmu (format 62xxxxxxxxxx).
 */

$toko = [
    'nama'    => 'Pasar Karsa',
    'tagline' => 'Karya UMKM lokal, langsung dari tangan pembuatnya',
    'wa'      => '6281234567890',
    'ongkir'  => 10000, // biaya antar tetap
];

// Warna tile dan ikon untuk tiap kategori
$kategori = [
    'Makanan'   => ['warna' => '#F7D26B', 'ikon' => '🍘'],
    'Minuman'   => ['warna' => '#9FD8CB', 'ikon' => '☕'],
    'Kriya'     => ['warna' => '#F4A896', 'ikon' => '🧺'],
    'Fesyen'    => ['warna' => '#BDBAEC', 'ikon' => '🧵'],
    'Perawatan' => ['warna' => '#C3E2A8', 'ikon' => '🌿'],
];

$produk = [
    [
        'id' => 1, 'nama' => 'Keripik Tempe Renyah', 'kategori' => 'Makanan',
        'harga' => 18000, 'stok' => 40, 'penjual' => 'Dapur Bu Sari', 'ikon' => '🍘', 'unggulan' => true,
        'deskripsi' => 'Tempe diiris tipis, digoreng garing dengan bumbu bawang dan ketumbar. Kemasan 200 gram.',
    ],
    [
        'id' => 2, 'nama' => 'Sambal Roa Pedas Manis', 'kategori' => 'Makanan',
        'harga' => 35000, 'stok' => 14, 'penjual' => 'Sambal Dermaga', 'ikon' => '🌶️', 'unggulan' => true,
        'deskripsi' => 'Ikan roa asap diolah dengan cabai rawit dan bawang merah. Tahan 3 bulan tanpa pengawet.',
    ],
    [
        'id' => 3, 'nama' => 'Kopi Robusta Sangrai 250 g', 'kategori' => 'Minuman',
        'harga' => 48000, 'stok' => 25, 'penjual' => 'Kopi Tanah Tinggi', 'ikon' => '☕', 'unggulan' => true,
        'deskripsi' => 'Biji pilihan petani lokal, sangrai medium. Tersedia dalam bentuk biji atau bubuk.',
    ],
    [
        'id' => 4, 'nama' => 'Wedang Jahe Serai', 'kategori' => 'Minuman',
        'harga' => 22000, 'stok' => 5, 'penjual' => 'Rempah Nenek', 'ikon' => '🫚', 'unggulan' => false,
        'deskripsi' => 'Campuran jahe merah, serai, dan gula aren. Seduh dengan air panas, hangat dan wangi.',
    ],
    [
        'id' => 5, 'nama' => 'Tas Anyaman Pandan', 'kategori' => 'Kriya',
        'harga' => 85000, 'stok' => 9, 'penjual' => 'Anyam Rumah', 'ikon' => '👜', 'unggulan' => true,
        'deskripsi' => 'Dianyam tangan dari daun pandan, bagian dalam berlapis kain katun. Muat laptop 13 inci.',
    ],
    [
        'id' => 6, 'nama' => 'Gelas Keramik Glasir Tangan', 'kategori' => 'Kriya',
        'harga' => 55000, 'stok' => 18, 'penjual' => 'Bengkel Tanah Liat', 'ikon' => '🏺', 'unggulan' => false,
        'deskripsi' => 'Dibentuk dan diglasir satu per satu, sehingga pola tiap gelas sedikit berbeda.',
    ],
    [
        'id' => 7, 'nama' => 'Kain Tenun Ikat 2 m', 'kategori' => 'Fesyen',
        'harga' => 275000, 'stok' => 6, 'penjual' => 'Tenun Mama Lia', 'ikon' => '🧵', 'unggulan' => false,
        'deskripsi' => 'Benang katun pewarna alami, ditenun di alat tenun gedogan. Cocok untuk kain atau selendang.',
    ],
    [
        'id' => 8, 'nama' => 'Kemeja Batik Tulis', 'kategori' => 'Fesyen',
        'harga' => 320000, 'stok' => 4, 'penjual' => 'Batik Canting Biru', 'ikon' => '👔', 'unggulan' => false,
        'deskripsi' => 'Motif ditulis dengan canting di atas katun primisima. Ukuran M, L, dan XL.',
    ],
    [
        'id' => 9, 'nama' => 'Sabun Kelapa Rempah', 'kategori' => 'Perawatan',
        'harga' => 25000, 'stok' => 32, 'penjual' => 'Sabun Pelita', 'ikon' => '🧼', 'unggulan' => false,
        'deskripsi' => 'Minyak kelapa murni dengan kunyit dan sereh. Lembut untuk kulit kering.',
    ],
    [
        'id' => 10, 'nama' => 'Lilin Aromaterapi Serai', 'kategori' => 'Perawatan',
        'harga' => 42000, 'stok' => 12, 'penjual' => 'Nyala Kecil', 'ikon' => '🕯️', 'unggulan' => false,
        'deskripsi' => 'Lilin soya dalam toples kaca, menyala sekitar 25 jam. Aroma serai dan jeruk purut.',
    ],
    [
        'id' => 11, 'nama' => 'Dodol Nanas', 'kategori' => 'Makanan',
        'harga' => 20000, 'stok' => 0, 'penjual' => 'Dapur Bu Sari', 'ikon' => '🍍', 'unggulan' => false,
        'deskripsi' => 'Dodol legit dari nanas matang dan gula kelapa. Sedang dibuat ulang, segera tersedia.',
    ],
    [
        'id' => 12, 'nama' => 'Teh Rosella Kering', 'kategori' => 'Minuman',
        'harga' => 17000, 'stok' => 27, 'penjual' => 'Kebun Rosella', 'ikon' => '🌺', 'unggulan' => false,
        'deskripsi' => 'Kelopak rosella dikeringkan alami. Seduh panas atau dingin, rasanya segar dan asam.',
    ],
];
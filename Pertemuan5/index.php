<?php
require_once __DIR__ . '/includes/fungsi.php';

/* Tambah ke pesanan (POST), lalu kembali ke katalog dengan filter yang sama */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_cek();
    $id  = (int) ($_POST['id'] ?? 0);
    $jml = max(1, (int) ($_POST['jml'] ?? 1));
    $p   = ambil_produk($id);

    if ($p && keranjang_tambah($id, $jml)) {
        flash_set('ok', $p['nama'] . ' masuk ke pesanan.');
    } else {
        flash_set('gagal', 'Produk ini sedang tidak tersedia.');
    }

    $balik = array_filter([
        'q'    => trim((string) ($_POST['q'] ?? '')),
        'kat'  => (string) ($_POST['kat'] ?? ''),
        'urut' => (string) ($_POST['urut'] ?? ''),
    ], fn($v) => $v !== '');
    header('Location: index.php' . ($balik ? '?' . http_build_query($balik) : '') . '#katalog');
    exit;
}

/* Baca filter dari URL */
$q    = trim((string) ($_GET['q'] ?? ''));
$kat  = (string) ($_GET['kat'] ?? '');
$urut = (string) ($_GET['urut'] ?? 'unggulan');
if (!isset($kategori[$kat])) { $kat = ''; }
if (!in_array($urut, ['unggulan', 'murah', 'mahal', 'nama'], true)) { $urut = 'unggulan'; }

$daftar   = filter_produk($produk, $q, $kat, $urut);
$unggulan = array_slice(array_values(array_filter($produk, fn($p) => $p['unggulan'])), 0, 4);
$hitung   = hitung_per_kategori();

function url_filter(array $ubah): string
{
    global $q, $kat, $urut;
    $param = array_filter(array_merge(['q' => $q, 'kat' => $kat, 'urut' => $urut], $ubah), fn($v) => $v !== '' && $v !== 'unggulan');
    return 'index.php' . ($param ? '?' . http_build_query($param) : '') . '#katalog';
}

$judul = 'Katalog';
$aktif = 'katalog';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div>
    <h1>Belanja langsung dari tangan yang membuatnya.</h1>
    <p>Jajanan, kopi, kriya, kain, dan perawatan tubuh dari pelaku UMKM lokal. Pilih, pesan, lalu penjual menghubungi kamu lewat WhatsApp.</p>
    <div class="aksi">
      <a class="btn btn-utama" href="#katalog">Lihat katalog</a>
      <a class="btn btn-garis" href="pesanan.php">Pesanan saya</a>
    </div>
  </div>

  <aside class="papan" aria-label="Produk unggulan">
    <h2>Papan hari ini</h2>
    <p class="sub">Yang paling sering dicari minggu ini</p>
    <ul>
      <?php foreach ($unggulan as $u): ?>
        <li>
          <a href="#produk-<?= $u['id'] ?>">
            <span class="nama"><?= e($u['nama']) ?></span>
            <span class="titik"></span>
            <span class="harga"><?= rupiah($u['harga']) ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>
</section>

<section class="jaminan" aria-label="Keunggulan">
  <div><b>Langsung dari pembuat</b><span>Tanpa perantara, penjual yang mengemas pesananmu.</span></div>
  <div><b>Konfirmasi lewat WhatsApp</b><span>Rincian pesanan terkirim rapi ke penjual.</span></div>
  <div><b>Ambil sendiri atau diantar</b><span>Ambil gratis, atau diantar dengan biaya tetap.</span></div>
</section>

<section class="katalog" id="katalog">
  <div class="kepala-seksi">
    <div>
      <h2>Semua karya</h2>
      <p><?= count($daftar) ?> produk<?= $kat !== '' ? ' di kategori ' . e($kat) : '' ?><?= $q !== '' ? ' untuk &ldquo;' . e($q) . '&rdquo;' : '' ?></p>
    </div>
  </div>

  <form class="toolbar" method="get" action="index.php#katalog">
    <?php if ($kat !== ''): ?><input type="hidden" name="kat" value="<?= e($kat) ?>"><?php endif; ?>
    <div class="cari">
      <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari nama produk atau penjual" aria-label="Cari produk">
    </div>
    <div class="urut">
      <select name="urut" aria-label="Urutkan" onchange="this.form.submit()">
        <option value="unggulan" <?= $urut === 'unggulan' ? 'selected' : '' ?>>Unggulan dulu</option>
        <option value="murah" <?= $urut === 'murah' ? 'selected' : '' ?>>Harga termurah</option>
        <option value="mahal" <?= $urut === 'mahal' ? 'selected' : '' ?>>Harga termahal</option>
        <option value="nama" <?= $urut === 'nama' ? 'selected' : '' ?>>Nama A ke Z</option>
      </select>
    </div>
    <button class="btn btn-utama" type="submit">Cari</button>
  </form>

  <div class="chips" role="navigation" aria-label="Kategori">
    <a class="chip <?= $kat === '' ? 'aktif' : '' ?>" href="<?= e(url_filter(['kat' => ''])) ?>">Semua <small><?= count($produk) ?></small></a>
    <?php foreach ($kategori as $nama => $info): ?>
      <a class="chip <?= $kat === $nama ? 'aktif' : '' ?>" href="<?= e(url_filter(['kat' => $nama])) ?>"><?= e($nama) ?> <small><?= $hitung[$nama] ?></small></a>
    <?php endforeach; ?>
  </div>

  <?php if (!$daftar): ?>
    <div class="kosong">
      <h3>Produk tidak ditemukan</h3>
      <p>Coba kata kunci lain atau tampilkan semua kategori.</p>
      <a class="btn btn-utama" href="index.php#katalog">Tampilkan semua produk</a>
    </div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($daftar as $p): ?>
        <article class="kartu" id="produk-<?= $p['id'] ?>">
          <div class="tile" style="--tile:<?= e(warna_kategori($p['kategori'])) ?>">
            <span class="kat"><?= e($p['kategori']) ?></span>
            <?php if ($p['stok'] === 0): ?>
              <span class="stok habis">Habis</span>
            <?php elseif ($p['stok'] <= 5): ?>
              <span class="stok">Sisa <?= $p['stok'] ?></span>
            <?php endif; ?>
            <span class="ikon" aria-hidden="true"><?= $p['ikon'] ?></span>
          </div>
          <div class="isi">
            <p class="penjual">oleh <?= e($p['penjual']) ?></p>
            <h3><?= e($p['nama']) ?></h3>
            <p class="desk"><?= e($p['deskripsi']) ?></p>
          </div>
          <form method="post" action="index.php">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <input type="hidden" name="q" value="<?= e($q) ?>">
            <input type="hidden" name="kat" value="<?= e($kat) ?>">
            <input type="hidden" name="urut" value="<?= e($urut === 'unggulan' ? '' : $urut) ?>">
            <span class="harga"><?= rupiah($p['harga']) ?></span>
            <div class="beli">
              <input type="number" name="jml" value="1" min="1" max="<?= max(1, $p['stok']) ?>" aria-label="Jumlah <?= e($p['nama']) ?>" <?= $p['stok'] === 0 ? 'disabled' : '' ?>>
              <button class="btn btn-kuning" type="submit" <?= $p['stok'] === 0 ? 'disabled' : '' ?>><?= $p['stok'] === 0 ? 'Stok habis' : 'Tambah ke pesanan' ?></button>
            </div>
          </form>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
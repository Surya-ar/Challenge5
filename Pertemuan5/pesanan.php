<?php
require_once __DIR__ . '/includes/fungsi.php';

$galat = [];
$lama  = ['nama' => '', 'wa' => '', 'metode' => 'ambil', 'alamat' => '', 'catatan' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_cek();
    $aksi = (string) ($_POST['aksi'] ?? '');
    $id   = (int) ($_POST['id'] ?? 0);

    if ($aksi === 'ubah') {
        keranjang_set($id, (int) ($_POST['jml'] ?? 0));
        header('Location: pesanan.php'); exit;
    }
    if ($aksi === 'hapus') {
        keranjang_set($id, 0);
        flash_set('ok', 'Produk dihapus dari pesanan.');
        header('Location: pesanan.php'); exit;
    }
    if ($aksi === 'kosongkan') {
        keranjang_kosongkan();
        flash_set('ok', 'Pesanan dikosongkan.');
        header('Location: pesanan.php'); exit;
    }

    if ($aksi === 'checkout') {
        $lama['nama']    = trim((string) ($_POST['nama'] ?? ''));
        $lama['wa']      = trim((string) ($_POST['wa'] ?? ''));
        $lama['metode']  = ($_POST['metode'] ?? '') === 'antar' ? 'antar' : 'ambil';
        $lama['alamat']  = trim((string) ($_POST['alamat'] ?? ''));
        $lama['catatan'] = trim((string) ($_POST['catatan'] ?? ''));

        $rinci = keranjang_rinci();
        $wa    = preg_replace('/[\s\-\.]/', '', $lama['wa']);

        if (!$rinci)                                        { $galat[] = 'Pesanan masih kosong. Tambahkan produk dulu.'; }
        if (mb_strlen($lama['nama']) < 3)                   { $galat[] = 'Nama lengkap minimal 3 huruf.'; }
        if (!preg_match('/^(\+62|62|0)8[0-9]{8,11}$/', $wa)) { $galat[] = 'Nomor WhatsApp tidak valid. Contoh: 081234567890.'; }
        if ($lama['metode'] === 'antar' && mb_strlen($lama['alamat']) < 10) { $galat[] = 'Alamat pengantaran minimal 10 karakter.'; }
        if (mb_strlen($lama['catatan']) > 200)              { $galat[] = 'Catatan maksimal 200 karakter.'; }

        if (!$galat) {
            $subtotal = keranjang_subtotal();
            $ongkir   = hitung_ongkir($lama['metode']);
            $_SESSION['struk'] = [
                'kode'    => buat_kode_pesanan(),
                'waktu'   => date('d/m/Y H:i'),
                'nama'    => $lama['nama'],
                'wa'      => $wa,
                'metode'  => $lama['metode'],
                'alamat'  => $lama['metode'] === 'antar' ? $lama['alamat'] : '',
                'catatan' => $lama['catatan'],
                'item'    => array_map(fn($r) => ['nama' => $r['nama'], 'jml' => $r['jml'], 'harga' => $r['harga'], 'subtotal' => $r['subtotal']], $rinci),
                'subtotal'=> $subtotal,
                'ongkir'  => $ongkir,
                'total'   => $subtotal + $ongkir,
            ];
            keranjang_kosongkan();
            header('Location: pesanan.php?selesai=1'); exit;
        }
    }
}

$struk = (isset($_GET['selesai']) && !empty($_SESSION['struk'])) ? $_SESSION['struk'] : null;
$rinci = keranjang_rinci();
$subtotal = keranjang_subtotal();
$ongkirNow = hitung_ongkir($lama['metode']);

$judul = $struk ? 'Pesanan diterima' : 'Pesanan';
$aktif = 'pesanan';
require __DIR__ . '/includes/header.php';
?>

<?php if ($struk): ?>
  <?php
    $pesanWa = "Halo, saya {$struk['nama']}. Saya ingin konfirmasi pesanan {$struk['kode']}:\n";
    foreach ($struk['item'] as $it) { $pesanWa .= "- {$it['jml']}x {$it['nama']}\n"; }
    $pesanWa .= "Total: " . rupiah($struk['total']) . "\nMetode: " . ($struk['metode'] === 'antar' ? 'Diantar' : 'Ambil sendiri');
    $linkWa = 'https://wa.me/' . $toko['wa'] . '?text=' . rawurlencode($pesanWa);
  ?>
  <section class="halaman">
    <div class="struk">
      <h1>Pesanan diterima</h1>
      <p>Terima kasih, <?= e($struk['nama']) ?>. Kirim rincian ini ke penjual lewat WhatsApp agar pesananmu diproses.</p>
      <span class="kode"><?= e($struk['kode']) ?></span>

      <dl>
        <dt>Waktu</dt><dd><?= e($struk['waktu']) ?></dd>
        <dt>WhatsApp</dt><dd><?= e($struk['wa']) ?></dd>
        <dt>Metode</dt><dd><?= $struk['metode'] === 'antar' ? 'Diantar ke alamat' : 'Ambil sendiri' ?></dd>
        <?php if ($struk['alamat'] !== ''): ?><dt>Alamat</dt><dd><?= e($struk['alamat']) ?></dd><?php endif; ?>
        <?php if ($struk['catatan'] !== ''): ?><dt>Catatan</dt><dd><?= e($struk['catatan']) ?></dd><?php endif; ?>
      </dl>

      <table>
        <?php foreach ($struk['item'] as $it): ?>
          <tr><td><?= e($it['nama']) ?> &times; <?= $it['jml'] ?></td><td><?= rupiah($it['subtotal']) ?></td></tr>
        <?php endforeach; ?>
        <tr><td>Biaya antar</td><td><?= $struk['ongkir'] ? rupiah($struk['ongkir']) : 'Gratis' ?></td></tr>
      </table>
      <div class="jml-total"><span>Total</span><span><?= rupiah($struk['total']) ?></span></div>

      <div class="aksi">
        <a class="btn btn-utama" href="<?= e($linkWa) ?>" target="_blank" rel="noopener">Kirim lewat WhatsApp</a>
        <a class="btn btn-garis" href="index.php">Belanja lagi</a>
      </div>
    </div>
  </section>

<?php elseif (!$rinci): ?>
  <section class="halaman">
    <h1>Pesanan</h1>
    <p class="ket">Produk yang kamu pilih akan muncul di sini.</p>
    <div class="kosong">
      <h3>Belum ada produk di pesanan</h3>
      <p>Pilih dulu karya favoritmu di katalog.</p>
      <a class="btn btn-utama" href="index.php#katalog">Buka katalog</a>
    </div>
  </section>

<?php else: ?>
  <section class="halaman">
    <h1>Pesanan</h1>
    <p class="ket"><?= keranjang_jumlah() ?> barang dari <?= count($rinci) ?> produk. Periksa jumlahnya, lalu isi data pemesan.</p>

    <div class="dua-kolom">
      <div>
        <?php foreach ($rinci as $r): ?>
          <div class="baris">
            <div class="mini" style="--tile:<?= e(warna_kategori($r['kategori'])) ?>" aria-hidden="true"><?= $r['ikon'] ?></div>
            <div>
              <h3><?= e($r['nama']) ?></h3>
              <p class="kecil"><?= e($r['penjual']) ?> &middot; <?= rupiah($r['harga']) ?></p>
              <form method="post" style="display:inline-flex;align-items:center;margin-top:8px">
                <?= csrf_field() ?>
                <input type="hidden" name="aksi" value="ubah">
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <span class="stepper">
                  <button type="submit" name="jml" value="<?= $r['jml'] - 1 ?>" aria-label="Kurangi">&minus;</button>
                  <span><?= $r['jml'] ?></span>
                  <button type="submit" name="jml" value="<?= $r['jml'] + 1 ?>" aria-label="Tambah" <?= $r['jml'] >= $r['stok'] ? 'disabled' : '' ?>>+</button>
                </span>
              </form>
              <form method="post" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="aksi" value="hapus">
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <button class="link-hapus" type="submit">Hapus</button>
              </form>
            </div>
            <div class="kanan"><strong><?= rupiah($r['subtotal']) ?></strong></div>
          </div>
        <?php endforeach; ?>

        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="aksi" value="kosongkan">
          <button class="link-hapus" type="submit" style="margin:6px 0 0">Kosongkan semua</button>
        </form>
      </div>

      <form class="panel" method="post" id="form-pesan" novalidate>
        <h2>Data pemesan</h2>
        <?php if ($galat): ?>
          <div class="galat" role="alert">
            <strong>Periksa kembali isian kamu</strong>
            <ul><?php foreach ($galat as $g): ?><li><?= e($g) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>

        <?= csrf_field() ?>
        <input type="hidden" name="aksi" value="checkout">

        <div class="field">
          <label for="nama">Nama lengkap</label>
          <input type="text" id="nama" name="nama" value="<?= e($lama['nama']) ?>" autocomplete="name">
        </div>
        <div class="field">
          <label for="wa">Nomor WhatsApp</label>
          <input type="tel" id="wa" name="wa" value="<?= e($lama['wa']) ?>" placeholder="081234567890" autocomplete="tel">
        </div>
        <div class="field">
          <span class="lbl">Cara menerima</span>
          <div class="pilihan">
            <label><input type="radio" name="metode" value="ambil" <?= $lama['metode'] === 'ambil' ? 'checked' : '' ?>>Ambil sendiri<small>Gratis</small></label>
            <label><input type="radio" name="metode" value="antar" <?= $lama['metode'] === 'antar' ? 'checked' : '' ?>>Diantar<small><?= rupiah($toko['ongkir']) ?></small></label>
          </div>
        </div>
        <div class="field" id="grup-alamat" <?= $lama['metode'] === 'antar' ? '' : 'hidden' ?>>
          <label for="alamat">Alamat pengantaran</label>
          <textarea id="alamat" name="alamat"><?= e($lama['alamat']) ?></textarea>
        </div>
        <div class="field">
          <label for="catatan">Catatan untuk penjual</label>
          <input type="text" id="catatan" name="catatan" value="<?= e($lama['catatan']) ?>" maxlength="200">
          <p class="bantu">Opsional, misalnya tingkat pedas atau ukuran.</p>
        </div>

        <div class="ringkas" data-subtotal="<?= $subtotal ?>" data-ongkir="<?= $toko['ongkir'] ?>">
          <div><span>Subtotal</span><span><?= rupiah($subtotal) ?></span></div>
          <div><span>Biaya antar</span><span id="v-ongkir"><?= $ongkirNow ? rupiah($ongkirNow) : 'Gratis' ?></span></div>
          <div class="total"><span>Total</span><span id="v-total"><?= rupiah($subtotal + $ongkirNow) ?></span></div>
        </div>

        <button class="btn btn-utama btn-penuh" type="submit">Kirim pesanan</button>
        <div class="sekunder"><a class="link-hapus" href="index.php#katalog" style="margin:0;text-decoration:none">Tambah produk lain</a></div>
      </form>
    </div>
  </section>

  <script>
  (function () {
    var form = document.getElementById('form-pesan');
    var ringkas = form.querySelector('.ringkas');
    var grup = document.getElementById('grup-alamat');
    var sub = +ringkas.dataset.subtotal, ongkir = +ringkas.dataset.ongkir;
    function rp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }
    function perbarui() {
      var antar = form.querySelector('input[name=metode]:checked').value === 'antar';
      grup.hidden = !antar;
      document.getElementById('v-ongkir').textContent = antar ? rp(ongkir) : 'Gratis';
      document.getElementById('v-total').textContent = rp(sub + (antar ? ongkir : 0));
    }
    form.querySelectorAll('input[name=metode]').forEach(function (r) { r.addEventListener('change', perbarui); });
    perbarui();
  })();
  </script>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
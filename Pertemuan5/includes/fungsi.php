<?php
/**
 * fungsi.php
 * Kumpulan fungsi bantu: format, pencarian, keranjang (session), CSRF, dan flash message.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/data.php';

/* ---------- Format & keamanan ---------- */

function e($teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function rupiah(int $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_cek(): void
{
    $kirim = $_POST['csrf'] ?? '';
    if (!is_string($kirim) || !hash_equals(csrf_token(), $kirim)) {
        http_response_code(419);
        exit('Sesi tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

function flash_set(string $tipe, string $teks): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'teks' => $teks];
}

function flash_ambil(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- Produk ---------- */

function ambil_produk(int $id): ?array
{
    global $produk;
    foreach ($produk as $p) {
        if ($p['id'] === $id) {
            return $p;
        }
    }
    return null;
}

function warna_kategori(string $nama): string
{
    global $kategori;
    return $kategori[$nama]['warna'] ?? '#DDDDDD';
}

function hitung_per_kategori(): array
{
    global $produk, $kategori;
    $hasil = array_fill_keys(array_keys($kategori), 0);
    foreach ($produk as $p) {
        $hasil[$p['kategori']]++;
    }
    return $hasil;
}

function filter_produk(array $semua, string $q, string $kat, string $urut): array
{
    $hasil = array_filter($semua, function ($p) use ($q, $kat) {
        if ($kat !== '' && $p['kategori'] !== $kat) {
            return false;
        }
        if ($q !== '') {
            $gudang = mb_strtolower($p['nama'] . ' ' . $p['penjual'] . ' ' . $p['deskripsi']);
            return mb_strpos($gudang, mb_strtolower($q)) !== false;
        }
        return true;
    });

    usort($hasil, function ($a, $b) use ($urut) {
        switch ($urut) {
            case 'murah': return $a['harga'] <=> $b['harga'];
            case 'mahal': return $b['harga'] <=> $a['harga'];
            case 'nama':  return strcasecmp($a['nama'], $b['nama']);
            default:      return [$b['unggulan'], $a['id']] <=> [$a['unggulan'], $b['id']];
        }
    });

    return $hasil;
}

/* ---------- Keranjang ---------- */

function keranjang_ambil(): array
{
    return $_SESSION['keranjang'] ?? [];
}

function keranjang_tambah(int $id, int $jml): bool
{
    $p = ambil_produk($id);
    if (!$p || $p['stok'] < 1) {
        return false;
    }
    $k = keranjang_ambil();
    $k[$id] = min(($k[$id] ?? 0) + max(1, $jml), $p['stok']);
    $_SESSION['keranjang'] = $k;
    return true;
}

function keranjang_set(int $id, int $jml): void
{
    $p = ambil_produk($id);
    $k = keranjang_ambil();
    if (!$p || $jml < 1) {
        unset($k[$id]);
    } else {
        $k[$id] = min($jml, $p['stok']);
    }
    $_SESSION['keranjang'] = $k;
}

function keranjang_kosongkan(): void
{
    $_SESSION['keranjang'] = [];
}

function keranjang_jumlah(): int
{
    return array_sum(keranjang_ambil());
}

function keranjang_rinci(): array
{
    $baris = [];
    foreach (keranjang_ambil() as $id => $jml) {
        $p = ambil_produk((int) $id);
        if (!$p) {
            continue;
        }
        $baris[] = $p + ['jml' => $jml, 'subtotal' => $p['harga'] * $jml];
    }
    return $baris;
}

function keranjang_subtotal(): int
{
    return array_sum(array_column(keranjang_rinci(), 'subtotal'));
}

function hitung_ongkir(string $metode): int
{
    global $toko;
    return $metode === 'antar' ? $toko['ongkir'] : 0;
}

function buat_kode_pesanan(): string
{
    return 'PK-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}
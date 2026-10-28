<?php
/**
 * header.php
 * Variabel yang dibaca: $judul (judul tab), $aktif ('katalog' | 'pesanan').
 */
$judul = $judul ?? 'Katalog';
$aktif = $aktif ?? '';
$flash = flash_ambil();
$jumlahKeranjang = keranjang_jumlah();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?> | <?= e($toko['nama']) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{
  --pine:#0E3B3A; --pine-2:#17504E; --mint:#E8F1EA; --paper:#FAFCFA;
  --marigold:#F5B21B; --chili:#CF3F2E; --ink:#12201F; --muted:#566B68; --line:#CBDAD1;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:var(--mint);color:var(--ink);font:16px/1.55 'Figtree',system-ui,-apple-system,'Segoe UI',sans-serif;-webkit-font-smoothing:antialiased}
h1,h2,h3{font-family:'Bricolage Grotesque','Figtree',system-ui,sans-serif;margin:0;line-height:1.08;letter-spacing:-.02em;font-weight:800}
p{margin:0}
a{color:inherit}
img,svg{max-width:100%}
.wrap{max-width:1160px;margin:0 auto;padding:0 20px}
:focus-visible{outline:3px solid var(--chili);outline-offset:2px;border-radius:6px}

/* ---- Tenda (elemen utama) ---- */
.tenda{height:30px;position:relative;z-index:5;transform-origin:top;animation:gulung .8s cubic-bezier(.2,.8,.2,1) both;
  background:repeating-linear-gradient(90deg,var(--pine) 0 48px,var(--marigold) 48px 96px)}
.tenda::after{content:"";position:absolute;left:0;right:0;top:100%;height:24px;
  background:
   radial-gradient(circle at 24px 0,var(--pine) 23.5px,transparent 24.5px) 0 0/96px 24px repeat-x,
   radial-gradient(circle at 72px 0,var(--marigold) 23.5px,transparent 24.5px) 0 0/96px 24px repeat-x}
@keyframes gulung{from{transform:scaleY(0)}}

/* ---- Navigasi ---- */
.nav{padding:38px 0 14px}
.nav-in{display:flex;align-items:center;justify-content:space-between;gap:16px}
.logo{text-decoration:none;display:flex;flex-direction:column}
.logo b{font-family:'Bricolage Grotesque',sans-serif;font-size:1.65rem;font-weight:800;letter-spacing:-.03em;color:var(--pine)}
.logo span{font-size:.8rem;color:var(--muted)}
.menu{display:flex;gap:6px;align-items:center}
.menu a{text-decoration:none;padding:9px 16px;border-radius:999px;font-weight:600;font-size:.95rem}
.menu a:hover{background:rgba(14,59,58,.08)}
.menu a.aktif{background:var(--pine);color:#fff}
.badge{display:inline-block;min-width:22px;height:22px;line-height:22px;margin-left:6px;padding:0 6px;border-radius:999px;background:var(--marigold);color:var(--ink);font-size:.78rem;font-weight:700;text-align:center}

/* ---- Tombol & input ---- */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;cursor:pointer;text-decoration:none;
  font:600 .95rem/1 'Figtree',sans-serif;padding:13px 22px;border-radius:999px;transition:transform .15s,background .15s}
.btn:hover{transform:translateY(-1px)}
.btn-utama{background:var(--pine);color:#fff}
.btn-utama:hover{background:var(--pine-2)}
.btn-kuning{background:var(--marigold);color:var(--ink)}
.btn-garis{background:transparent;color:var(--pine);box-shadow:inset 0 0 0 2px var(--pine)}
.btn[disabled]{background:#c9d4cf;color:#6c7b77;cursor:not-allowed;transform:none}
.btn-kecil{padding:10px 16px;font-size:.88rem}
input[type=text],input[type=search],input[type=tel],input[type=number],select,textarea{
  width:100%;font:inherit;color:var(--ink);background:#fff;border:2px solid var(--line);border-radius:10px;padding:11px 13px}
input:focus,select:focus,textarea:focus{border-color:var(--pine);outline:none}
textarea{resize:vertical;min-height:84px}

/* ---- Hero ---- */
.hero{display:grid;grid-template-columns:1.1fr .9fr;gap:48px;align-items:center;padding:34px 0 54px}
.hero h1{font-size:clamp(2.4rem,5.4vw,4.2rem);color:var(--pine)}
.hero p{margin:20px 0 28px;max-width:46ch;font-size:1.1rem;color:#2d4442}
.hero .aksi{display:flex;gap:12px;flex-wrap:wrap}

/* Papan tulis harga */
.papan{background:var(--pine);color:#F4F8F4;border-radius:8px;padding:26px 26px 20px;
  box-shadow:0 0 0 10px #B98652,0 0 0 12px #8F6338,0 22px 34px -18px rgba(14,59,58,.6);transform:rotate(1.2deg)}
.papan h2{font-size:1.5rem;color:#fff;margin-bottom:4px}
.papan .sub{font-size:.88rem;color:#B9D2CC;margin-bottom:18px}
.papan ul{list-style:none;margin:0;padding:0}
.papan li a{display:flex;align-items:baseline;gap:8px;padding:9px 0;text-decoration:none}
.papan li a:hover .nama{text-decoration:underline;text-underline-offset:4px}
.papan .titik{flex:1;border-bottom:2px dotted rgba(255,255,255,.35);transform:translateY(-4px)}
.papan .harga{font-weight:600;color:var(--marigold);white-space:nowrap}
.papan .nama{font-weight:500}

/* ---- Katalog ---- */
.katalog{padding-bottom:70px}
.kepala-seksi{display:flex;justify-content:space-between;align-items:end;gap:20px;flex-wrap:wrap;margin-bottom:20px}
.kepala-seksi h2{font-size:clamp(1.7rem,3vw,2.3rem);color:var(--pine)}
.kepala-seksi p{color:var(--muted)}
.toolbar{display:flex;gap:12px;flex-wrap:wrap;align-items:center;margin-bottom:14px}
.toolbar .cari{flex:1 1 260px}
.toolbar .urut{flex:0 0 190px}
.chips{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:26px}
.chip{display:inline-flex;gap:8px;align-items:center;text-decoration:none;padding:8px 15px;border-radius:999px;background:var(--paper);
  box-shadow:inset 0 0 0 2px var(--line);font-weight:500;font-size:.92rem}
.chip small{color:var(--muted);font-weight:600}
.chip:hover{box-shadow:inset 0 0 0 2px var(--pine)}
.chip.aktif{background:var(--pine);color:#fff;box-shadow:none}
.chip.aktif small{color:#B9D2CC}

.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:22px}
.kartu{background:var(--paper);border-radius:20px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 0 0 2px var(--line)}
.tile{height:150px;background:var(--tile);display:grid;place-items:center;position:relative;
  background-image:radial-gradient(rgba(14,59,58,.14) 1.5px,transparent 1.6px);background-size:16px 16px}
.tile .ikon{font-size:3.6rem;filter:drop-shadow(0 6px 0 rgba(14,59,58,.12))}
.tile .kat{position:absolute;left:12px;top:12px;background:var(--paper);padding:4px 10px;border-radius:999px;font-size:.78rem;font-weight:600}
.tile .stok{position:absolute;right:12px;top:12px;background:var(--chili);color:#fff;padding:4px 10px;border-radius:999px;font-size:.78rem;font-weight:600}
.tile .stok.habis{background:var(--ink)}
.kartu .isi{padding:16px 18px 6px;flex:1}
.kartu .penjual{font-size:.85rem;color:var(--muted);margin-bottom:3px}
.kartu h3{font-size:1.2rem;margin-bottom:8px}
.kartu .desk{font-size:.93rem;color:#33504d;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.kartu form{padding:14px 18px 18px}
.kartu .harga{display:block;font-family:'Bricolage Grotesque',sans-serif;font-weight:800;font-size:1.3rem;margin-bottom:10px}
.beli{display:flex;gap:8px}
.beli input[type=number]{width:70px;text-align:center;padding:10px 6px}
.beli .btn{flex:1}
.kosong{background:var(--paper);border-radius:20px;padding:44px 24px;text-align:center;box-shadow:0 0 0 2px var(--line)}
.kosong h3{font-size:1.4rem;margin-bottom:8px}
.kosong p{color:var(--muted);margin-bottom:18px}

/* ---- Halaman pesanan ---- */
.halaman{padding:20px 0 70px}
.halaman > h1{font-size:clamp(2rem,4vw,3rem);color:var(--pine);margin-bottom:6px}
.halaman > .ket{color:var(--muted);margin-bottom:26px}
.dua-kolom{display:grid;grid-template-columns:1.15fr .85fr;gap:28px;align-items:start}
.baris{display:grid;grid-template-columns:72px 1fr auto;gap:16px;align-items:center;background:var(--paper);border-radius:18px;padding:14px;margin-bottom:12px;box-shadow:0 0 0 2px var(--line)}
.baris .mini{width:72px;height:72px;border-radius:14px;background:var(--tile);display:grid;place-items:center;font-size:2rem}
.baris h3{font-size:1.05rem;margin-bottom:2px}
.baris .kecil{font-size:.86rem;color:var(--muted)}
.baris .kanan{text-align:right}
.baris .kanan strong{display:block;font-family:'Bricolage Grotesque',sans-serif;font-size:1.1rem;margin-bottom:8px}
.stepper{display:inline-flex;align-items:center;border-radius:999px;box-shadow:inset 0 0 0 2px var(--line);background:#fff}
.stepper button{border:0;background:transparent;width:34px;height:34px;font-size:1.15rem;cursor:pointer;border-radius:50%;color:var(--pine);font-weight:700}
.stepper button:hover:not([disabled]){background:var(--mint)}
.stepper button[disabled]{color:#b3c0bb;cursor:not-allowed}
.stepper span{min-width:28px;text-align:center;font-weight:600}
.link-hapus{border:0;background:none;color:var(--chili);font:600 .85rem 'Figtree',sans-serif;cursor:pointer;padding:6px 2px;margin-left:8px}
.link-hapus:hover{text-decoration:underline}
.panel{background:var(--paper);border-radius:22px;padding:24px;box-shadow:0 0 0 2px var(--line);position:sticky;top:18px}
.panel h2{font-size:1.4rem;margin-bottom:16px;color:var(--pine)}
.field{margin-bottom:14px}
.field label,.field .lbl{display:block;font-weight:600;font-size:.92rem;margin-bottom:6px}
.field .bantu{font-size:.82rem;color:var(--muted);margin-top:4px}
.pilihan{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.pilihan label{display:flex;flex-direction:column;gap:2px;padding:12px;border-radius:12px;border:2px solid var(--line);cursor:pointer;font-weight:600;background:#fff;margin:0}
.pilihan label small{font-weight:400;color:var(--muted)}
.pilihan input{position:absolute;opacity:0;pointer-events:none}
.pilihan label:has(input:checked){border-color:var(--pine);background:#EEF6F1}
.pilihan label:has(input:focus-visible){outline:3px solid var(--chili);outline-offset:2px}
.ringkas{border-top:2px dashed var(--line);margin:18px 0;padding-top:14px}
.ringkas div{display:flex;justify-content:space-between;padding:3px 0;color:#2d4442}
.ringkas .total{font-weight:800;font-size:1.2rem;color:var(--ink);padding-top:10px;font-family:'Bricolage Grotesque',sans-serif}
.galat{background:#FCE9E6;color:#7d2017;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:.93rem}
.galat strong{display:block;margin-bottom:4px}
.galat ul{margin:0;padding-left:18px}
.btn-penuh{width:100%}
.sekunder{display:flex;justify-content:space-between;margin-top:6px}

/* ---- Struk ---- */
.struk{max-width:560px;margin:10px auto 0;background:var(--paper);border-radius:6px 6px 22px 22px;padding:30px 30px 26px;
  box-shadow:0 0 0 2px var(--line),0 24px 40px -26px rgba(14,59,58,.5);position:relative}
.struk::before{content:"";position:absolute;left:0;right:0;top:-10px;height:10px;
  background:linear-gradient(135deg,transparent 50%,var(--paper) 50%) 0 0/16px 10px,linear-gradient(225deg,transparent 50%,var(--paper) 50%) 0 0/16px 10px}
.struk h1{font-size:1.9rem;color:var(--pine);margin-bottom:4px}
.struk .kode{display:inline-block;margin:10px 0 18px;padding:6px 12px;border-radius:8px;background:var(--marigold);font-weight:700;letter-spacing:.04em}
.struk dl{display:grid;grid-template-columns:110px 1fr;gap:6px 12px;margin:0 0 16px;font-size:.95rem}
.struk dt{color:var(--muted)}
.struk dd{margin:0;font-weight:500}
.struk table{width:100%;border-collapse:collapse;font-size:.95rem}
.struk td{padding:7px 0;border-bottom:1px dotted var(--line)}
.struk td:last-child{text-align:right;white-space:nowrap}
.struk .jml-total{display:flex;justify-content:space-between;font-weight:800;font-size:1.2rem;padding-top:12px;font-family:'Bricolage Grotesque',sans-serif}
.struk .aksi{display:flex;gap:10px;flex-wrap:wrap;margin-top:22px}

/* ---- Toast & footer ---- */
.toast{position:fixed;left:50%;bottom:24px;transform:translateX(-50%);z-index:20;background:var(--ink);color:#fff;padding:13px 20px;border-radius:999px;
  font-weight:500;box-shadow:0 14px 30px -12px rgba(0,0,0,.5);max-width:calc(100% - 32px);transition:opacity .4s,transform .4s}
.toast.gagal{background:var(--chili)}
.toast.hilang{opacity:0;transform:translate(-50%,12px)}
.kaki{background:var(--pine);color:#CFE2DC;padding:34px 0;margin-top:20px}
.kaki .wrap{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap}
.kaki b{font-family:'Bricolage Grotesque',sans-serif;color:#fff;font-size:1.2rem}
.kaki p{font-size:.9rem;max-width:44ch}

@media (max-width:860px){
  .hero{grid-template-columns:1fr;gap:38px}
  .papan{transform:none}
  .dua-kolom{grid-template-columns:1fr}
  .panel{position:static}
}
@media (max-width:520px){
  .logo span{display:none}
  .baris{grid-template-columns:60px 1fr}
  .baris .mini{width:60px;height:60px}
  .baris .kanan{grid-column:1 / -1;text-align:left;display:flex;justify-content:space-between;align-items:center}
  .baris .kanan strong{margin:0}
  .toolbar .urut{flex:1 1 100%}
}
@media (prefers-reduced-motion:reduce){
  *{animation:none !important;transition:none !important}
  html{scroll-behavior:auto}
}

/* ===== Tema profesional ===== */
:root{--pine:#0B3B36;--pine-2:#14524B;--mint:#F3F6F3;--paper:#fff;--marigold:#F2A900;--chili:#C93C2B;--ink:#0F1E1C;--muted:#5B6B68;--line:#E1E8E3}
body{font-feature-settings:"tnum"}
.tenda{height:6px;animation:none;background:linear-gradient(90deg,var(--pine) 0 72%,var(--marigold) 72% 100%)}
.tenda::after{display:none}
.nav{position:sticky;top:0;z-index:30;padding:14px 0;background:rgba(255,255,255,.88);backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}
.logo{flex-direction:row;align-items:center;gap:11px}
.logo::before{content:"K";display:grid;place-items:center;width:38px;height:38px;border-radius:11px;background:var(--pine);color:var(--marigold);font:800 1.25rem 'Bricolage Grotesque',sans-serif}
.logo b{font-size:1.35rem;line-height:1}
.logo span{display:none}
.hero{background-color:var(--pine);color:#fff;border-radius:28px;padding:64px;margin-top:22px;gap:56px;overflow:hidden;
  background-image:radial-gradient(circle at 88% 0,rgba(242,169,0,.25),transparent 42%),radial-gradient(rgba(255,255,255,.07) 1.5px,transparent 1.6px);background-size:auto,22px 22px}
.hero h1{color:#fff;font-size:clamp(2.2rem,4.6vw,3.6rem)}
.hero p{color:#C5DAD4}
.hero .btn-utama{background:var(--marigold);color:var(--ink)}
.hero .btn-utama:hover{background:#ffbd2e}
.hero .btn-garis{color:#fff;box-shadow:inset 0 0 0 2px rgba(255,255,255,.4)}
.papan{background:#fff;color:var(--ink);border-radius:20px;padding:26px;transform:none;box-shadow:0 34px 56px -26px rgba(0,0,0,.55)}
.papan h2{color:var(--pine)}
.papan .sub{color:var(--muted)}
.papan .harga{color:var(--pine);font-weight:700}
.papan .titik{border-color:#C9D6CF}
.papan li a{border-bottom:1px solid var(--line)}
.papan li:last-child a{border-bottom:0}
.jaminan{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin:18px 0 58px}
.jaminan div{background:#fff;border:1px solid var(--line);border-radius:16px;padding:18px 20px}
.jaminan b{display:block;font-family:'Bricolage Grotesque',sans-serif;color:var(--pine);font-size:1.05rem;margin-bottom:2px}
.jaminan span{font-size:.9rem;color:var(--muted)}
.toolbar{background:#fff;border:1px solid var(--line);border-radius:16px;padding:12px}
.chip{background:#fff;box-shadow:none;border:1px solid var(--line)}
.chip:hover{box-shadow:none;border-color:var(--pine)}
.chip.aktif{border-color:var(--pine)}
.kartu{border-radius:18px;box-shadow:none;border:1px solid var(--line);transition:transform .2s,box-shadow .2s}
.kartu:hover{transform:translateY(-4px);box-shadow:0 24px 38px -24px rgba(11,59,54,.5)}
.tile{height:168px;background-image:linear-gradient(160deg,rgba(255,255,255,.55),transparent 60%)}
.tile .ikon{width:88px;height:88px;border-radius:50%;background:#fff;display:grid;place-items:center;font-size:2.8rem;filter:none;box-shadow:0 14px 24px -12px rgba(11,59,54,.5)}
.kartu .harga{color:var(--pine)}
.beli .btn-kuning{background:var(--pine);color:#fff}
.beli .btn-kuning:hover{background:var(--pine-2)}
.beli .btn-kuning[disabled]{background:#d5dcd8;color:#7b8985}
.baris,.kosong{box-shadow:none;border:1px solid var(--line)}
.panel{box-shadow:0 24px 44px -32px rgba(11,59,54,.5);border:1px solid var(--line)}
@media (max-width:860px){.hero{padding:36px 24px}.jaminan{grid-template-columns:1fr;margin-bottom:40px}}
</style>
</head>
<body>
<div class="tenda" aria-hidden="true"></div>
<header class="nav">
  <div class="wrap nav-in">
    <a class="logo" href="index.php">
      <b><?= e($toko['nama']) ?></b>
      <span>Katalog karya UMKM lokal</span>
    </a>
    <nav class="menu" aria-label="Menu utama">
      <a href="index.php" class="<?= $aktif === 'katalog' ? 'aktif' : '' ?>">Katalog</a>
      <a href="pesanan.php" class="<?= $aktif === 'pesanan' ? 'aktif' : '' ?>">Pesanan<?php if ($jumlahKeranjang > 0): ?><span class="badge"><?= $jumlahKeranjang ?></span><?php endif; ?></a>
    </nav>
  </div>
</header>
<?php if ($flash): ?>
  <div class="toast <?= $flash['tipe'] === 'gagal' ? 'gagal' : '' ?>" role="status" id="toast"><?= e($flash['teks']) ?></div>
<?php endif; ?>
<main class="wrap">
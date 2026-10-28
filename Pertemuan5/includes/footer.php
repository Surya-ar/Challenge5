</main>
<footer class="kaki">
  <div class="wrap">
    <div>
      <b><?= e($toko['nama']) ?></b>
      <p><?= e($toko['tagline']) ?>. Pesanan dikonfirmasi langsung oleh penjual lewat WhatsApp.</p>
    </div>
    <p>Katalog UMKM &copy; <?= date('Y') ?>. Dibuat dengan PHP dan dijalankan di XAMPP.</p>
  </div>
</footer>
<script>
(function () {
  var toast = document.getElementById('toast');
  if (toast) {
    setTimeout(function () { toast.classList.add('hilang'); }, 3200);
    setTimeout(function () { toast.remove(); }, 3800);
  }
})();
</script>
</body>
</html>
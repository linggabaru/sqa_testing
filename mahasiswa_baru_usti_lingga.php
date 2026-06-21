<?php
$nama = $pesan = $status = $tgl = '';
$debug_status = ''; // <-- UNTUK BLACKBOX CI

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tgl  = date('d-m-Y H:i:s');

    if ($nama === '') {
        $status = 'gagal';
        $pesan  = 'Field nama tidak boleh kosong.';
        $debug_status = 'ERROR';
    } else {
        $s = str_replace(' ', '', $nama);
        $tipe = ctype_alpha($s)
            ? 'Karakter'
            : (ctype_digit($s) ? 'Number' : 'Karakter + Number');

        $status = 'sukses';
        $pesan  = "Data \"$nama\" ($tipe) berhasil disimpan.";
        $debug_status = 'SUCCESS';
    }

    // 🔥 OUTPUT KHUSUS UNTUK BLACKBOX TEST (CI/CD)
    // Jika request dari CI, langsung return hasil tanpa HTML
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'GitHub') !== false) {
        echo $debug_status;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mahasiswa Baru USTI</title>

<style>
/* (CSS kamu tetap sama, tidak saya ubah agar UI tetap aman) */

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg:#F7F5F0; --surface:#fff; --border:#E4DFD5;
  --gold:#C9A84C; --gold-lt:#F0E2B5;
  --navy:#1A2744; --navy-lt:#2C3E6A;
  --text:#1A2744; --muted:#7A8099;
  --ok-bg:#EEF8F1; --ok-fg:#1D7A40; --ok-bd:#7AC99A;
  --er-bg:#FDF1F1; --er-fg:#A32020; --er-bd:#E8A0A0;
  --r:8px;
}

body { font-family:Segoe UI; background:var(--bg); padding:40px 16px; }

.wrap { max-width:640px; margin:auto; display:flex; flex-direction:column; gap:20px; }

.header {
  background:var(--navy); color:#fff; padding:28px;
  border-radius:var(--r);
}

.header span { color:var(--gold); }

.card {
  background:var(--surface);
  border:1px solid var(--border);
  padding:24px;
  border-radius:var(--r);
}

.notif {
  padding:12px; border-radius:6px; margin-bottom:15px;
}

.notif.sukses { background:var(--ok-bg); color:var(--ok-fg); }
.notif.gagal { background:var(--er-bg); color:var(--er-fg); }

input, button {
  width:100%; padding:10px; margin-top:10px;
}

button {
  background:var(--navy); color:#fff; border:none;
  cursor:pointer;
}
button:hover { background:var(--navy-lt); }

.footer { text-align:center; font-size:12px; color:var(--muted); }
</style>
</head>

<body>

<div class="wrap">

  <div class="header">
    <h1>Aplikasi <span>Mahasiswa Baru</span> USTI</h1>
  </div>

  <div class="card">

    <h3>Entri Data</h3>

    <?php if ($pesan): ?>
      <div class="notif <?= $status ?>">
        <b><?= $status === 'sukses' ? 'BERHASIL' : 'GAGAL' ?></b><br>
        <?= htmlspecialchars($pesan) ?><br>
        <small><?= $tgl ?></small>
      </div>
    <?php endif; ?>

    <form method="POST">
      <label>Nama</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" placeholder="Masukkan nama">
      <button type="submit">SAVE</button>
    </form>

  </div>

  <div class="footer">
    USTI · SQA Project · 2026
  </div>

</div>

</body>
</html>
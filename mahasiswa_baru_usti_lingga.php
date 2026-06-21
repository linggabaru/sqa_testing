<?php
$nama = $pesan = $status = $tgl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $tgl  = date('d-m-Y H:i:s');

    if ($nama === '') {
        $status = 'gagal';
        $pesan  = 'Field nama tidak boleh kosong.';
    } else {
        $s    = str_replace(' ', '', $nama);
        $tipe = ctype_alpha($s) ? 'Karakter' : (ctype_digit($s) ? 'Number' : 'Karakter + Number');
        $status = 'sukses';
        $pesan  = "Data \"$nama\" ($tipe) berhasil disimpan.";
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
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg      : #F7F5F0;
  --surface : #FFFFFF;
  --border  : #E4DFD5;
  --gold    : #C9A84C;
  --gold-lt : #F0E2B5;
  --navy    : #1A2744;
  --navy-lt : #2C3E6A;
  --text    : #1A2744;
  --muted   : #7A8099;
  --ok-bg   : #EEF8F1;
  --ok-fg   : #1D7A40;
  --ok-bd   : #7AC99A;
  --er-bg   : #FDF1F1;
  --er-fg   : #A32020;
  --er-bd   : #E8A0A0;
  --r       : 8px;
}

body {
  font-family: 'Segoe UI', system-ui, sans-serif;
  background: var(--bg);
  min-height: 100vh;
  padding: 40px 16px 60px;
  color: var(--text);
}

.wrap {
  max-width: 640px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* ── Header ── */
.header {
  background: var(--navy);
  border-radius: var(--r);
  padding: 28px 32px;
  position: relative;
  overflow: hidden;
}
.header::after {
  content: '';
  position: absolute;
  right: -30px; top: -30px;
  width: 120px; height: 120px;
  border-radius: 50%;
  border: 2px solid rgba(201,168,76,.2);
}
.header-label {
  font-size: .68rem;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: var(--gold);
  margin-bottom: 8px;
}
.header h1 {
  font-size: 1.35rem;
  font-weight: 700;
  color: #fff;
  line-height: 1.25;
}
.header h1 span { color: var(--gold); }
.header-meta {
  margin-top: 10px;
  font-size: .75rem;
  color: rgba(255,255,255,.4);
}
.header-meta strong { color: rgba(255,255,255,.65); font-weight: 600; }

.gold-line {
  height: 2px;
  background: linear-gradient(90deg, var(--gold) 0%, var(--gold-lt) 60%, transparent 100%);
  border-radius: 2px;
  margin-top: 16px;
}

/* ── Card ── */
.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--r);
  padding: 24px 28px;
}

.section-label {
  font-size: .68rem;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--muted);
  font-weight: 700;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section-label::before {
  content: '';
  display: inline-block;
  width: 16px; height: 2px;
  background: var(--gold);
  border-radius: 1px;
}

/* ── Notif ── */
.notif {
  border-radius: 6px;
  border: 1px solid;
  padding: 12px 16px;
  font-size: .85rem;
  font-weight: 500;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 18px;
}
.notif.sukses { background: var(--ok-bg); border-color: var(--ok-bd); color: var(--ok-fg); }
.notif.gagal  { background: var(--er-bg); border-color: var(--er-bd); color: var(--er-fg); }

.notif-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
.notif-body { flex: 1; }
.notif-title { font-weight: 700; margin-bottom: 3px; }
.notif-detail { font-size: .8rem; opacity: .9; }
.notif-ts { font-size: .72rem; opacity: .65; margin-top: 5px; }

/* ── Form ── */
label {
  display: block;
  font-size: .78rem;
  font-weight: 600;
  color: var(--navy);
  margin-bottom: 6px;
  letter-spacing: .02em;
}
input[type="text"] {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid var(--border);
  border-radius: 6px;
  font-size: .95rem;
  color: var(--text);
  background: var(--bg);
  outline: none;
  transition: border-color .18s, box-shadow .18s;
}
input[type="text"]:focus {
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(201,168,76,.12);
  background: #fff;
}
input[type="text"]::placeholder { color: #C0BBAF; }
.hint { margin-top: 6px; font-size: .72rem; color: var(--muted); }

button[type="submit"] {
  margin-top: 16px;
  width: 100%;
  padding: 12px;
  background: var(--navy);
  color: #fff;
  font-size: .88rem;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background .18s, transform .12s;
}
button[type="submit"]:hover  { background: var(--navy-lt); }
button[type="submit"]:active { transform: scale(.98); }

/* ── Footer ── */
.footer {
  text-align: center;
  font-size: .7rem;
  color: var(--muted);
  padding-top: 4px;
}
</style>
</head>
<body>
<div class="wrap">

  <!-- Header -->
  <div class="header">
    <div class="header-label">Project UAS · SQA · TA. 2026</div>
    <h1>Aplikasi <span>Mahasiswa Baru</span> USTI</h1>
    <div class="header-meta">
      Teknik Informatika &nbsp;·&nbsp; Universitas Sains dan Teknologi Indonesia
    </div>
    <div class="gold-line"></div>
  </div>

  <!-- Form -->
  <div class="card">
    <div class="section-label">Entri Data</div>

    <?php if ($pesan) : ?>
    <div class="notif <?= $status ?>">
      <span class="notif-icon"><?= $status === 'sukses' ? '✓' : '✗' ?></span>
      <div class="notif-body">
        <div class="notif-title"><?= $status === 'sukses' ? 'Entri Berhasil' : 'Entri Gagal' ?></div>
        <div class="notif-detail"><?= htmlspecialchars($pesan) ?></div>
        <div class="notif-ts"><?= $tgl ?></div>
      </div>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="nama">Nama Calon Mahasiswa</label>
      <input
        type="text"
        id="nama"
        name="nama"
        value="<?= htmlspecialchars($nama) ?>"
        placeholder="Masukkan nama…"
        autocomplete="off"
      >
      <p class="hint">Kosongkan untuk menguji skenario gagal.</p>
      <button type="submit">SAVE</button>
    </form>
  </div>

  <!-- Footer -->
  <div class="footer">
    Lingga Prestiawan &nbsp;·&nbsp; NIM 2310031802009 &nbsp;·&nbsp; TI – USTI &nbsp;·&nbsp; TA. 2026
  </div>

</div>
</body>
</html>

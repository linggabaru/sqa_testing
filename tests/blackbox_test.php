<?php
/**
 * Black Box Test - Aplikasi Mahasiswa Baru USTI
 * -----------------------------------------------
 * Pengujian dilakukan murni dari SISI LUAR aplikasi:
 * mengirim request HTTP POST seperti pengguna asli,
 * lalu memeriksa OUTPUT HTML yang dihasilkan.
 * Tidak ada pemeriksaan terhadap source code internal (itu White Box).
 *
 * Cara menjalankan:
 *   1. Jalankan server:  php -S 127.0.0.1:8000
 *   2. Jalankan test  :  php tests/blackbox_test.php
 *
 * Bisa override URL target via environment variable APP_URL,
 * contoh: APP_URL=http://127.0.0.1:8000/mahasiswa_baru_usti_lingga.php php tests/blackbox_test.php
 */

$appUrl = getenv('APP_URL') ?: 'http://localhost/sqa/mahasiswa_baru_usti_lingga.php';

$totalTC  = 0;
$lulusTC  = 0;
$results  = [];

/**
 * Mengirim POST request ke aplikasi dan mengembalikan body HTML.
 */
function kirimPost(string $url, array $data): string
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    if ($response === false) {
        fwrite(STDERR, "Gagal konek ke aplikasi: " . curl_error($ch) . "\n");
        exit(1);
    }
    curl_close($ch);
    return $response;
}

/**
 * Helper assert sederhana untuk CLI.
 */
function jalankanTC(string $id, string $nama, string $deskripsi, string $expectSubstring, bool $expectGagal, string $appUrl, array &$results, int &$total, int &$lulus): void
{
    $total++;
    $html = kirimPost($appUrl, ['nama' => $nama]);

    $adaSukses = strpos($html, 'notif sukses') !== false;
    $adaGagal  = strpos($html, 'notif gagal') !== false;
    $cocokTeks = strpos($html, $expectSubstring) !== false;

    $statusBenar = $expectGagal ? $adaGagal : $adaSukses;
    $lulus_tc    = $statusBenar && $cocokTeks;

    if ($lulus_tc) {
        $lulus++;
    }

    $results[] = [
        'id'        => $id,
        'deskripsi' => $deskripsi,
        'input'     => $nama === '' ? '(kosong)' : $nama,
        'expected'  => $expectGagal ? 'Gagal - ' . $expectSubstring : 'Sukses - ' . $expectSubstring,
        'actual'    => $adaSukses ? 'Sukses' : ($adaGagal ? 'Gagal' : 'Tidak diketahui'),
        'status'    => $lulus_tc ? 'BENAR' : 'SALAH',
    ];

    $simbol = $lulus_tc ? '[OK]  ' : '[FAIL]';
    echo "$simbol $id - $deskripsi\n";
}

echo "============================================================\n";
echo " BLACK BOX TEST - APLIKASI MAHASISWA BARU USTI\n";
echo " Target: $appUrl\n";
echo " Waktu : " . date('d-m-Y H:i:s') . "\n";
echo "============================================================\n\n";

// TC01 - Entri data Karakter
jalankanTC(
    'TC01',
    'Lingga',
    'Entri data Karakter',
    'Karakter',
    false,
    $appUrl,
    $results,
    $totalTC,
    $lulusTC
);

// TC02 - Entri data Number
jalankanTC(
    'TC02',
    '12345',
    'Entri data Number',
    'Number',
    false,
    $appUrl,
    $results,
    $totalTC,
    $lulusTC
);

// TC03 - Entri data Karakter + Number
jalankanTC(
    'TC03',
    'Lingga123',
    'Entri data Karakter + Number',
    'Karakter + Number',
    false,
    $appUrl,
    $results,
    $totalTC,
    $lulusTC
);

// TC04 - Tanpa entri data (kosong)
jalankanTC(
    'TC04',
    '',
    'Tanpa Entri Data (kosong)',
    'Field nama tidak boleh kosong.',
    true,
    $appUrl,
    $results,
    $totalTC,
    $lulusTC
);

echo "\n============================================================\n";
echo " RINGKASAN HASIL\n";
echo "============================================================\n";
printf("%-6s %-32s %-10s %-10s %-8s\n", "ID", "Deskripsi", "Input", "Hasil", "Status");
echo str_repeat('-', 70) . "\n";
foreach ($results as $r) {
    printf(
        "%-6s %-32s %-10s %-10s %-8s\n",
        $r['id'],
        $r['deskripsi'],
        $r['input'],
        $r['actual'],
        $r['status']
    );
}
echo str_repeat('-', 70) . "\n";
echo "Total Test Case : $totalTC\n";
echo "Lulus           : $lulusTC\n";
echo "Gagal           : " . ($totalTC - $lulusTC) . "\n";
echo "============================================================\n";

// Exit code 1 jika ada yang gagal -> berguna untuk CI/CD pipeline
exit($lulusTC === $totalTC ? 0 : 1);

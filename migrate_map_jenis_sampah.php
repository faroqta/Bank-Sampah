<?php
/**
 * migrate_map_jenis_sampah.php
 *
 * Skrip ini membuat pemetaan otomatis untuk nilai kolom `jenisSampah`
 * pada tabel `sampah`.
 *
 * Cara pakai:
 * - Buka di browser: http://localhost/Bank-Sampah/migrate_map_jenis_sampah.php
 *   akan menampilkan pratinjau mapping (tanpa mengubah DB).
 * - Jika setuju, klik link "Apply mapping" atau buka
 *   http://localhost/Bank-Sampah/migrate_map_jenis_sampah.php?apply=1
 *   untuk menjalankan update.
 *
 * Perhatian: skrip ini hanya memproses baris yang saat ini memiliki
 * jenisSampah = 'Organik' atau 'Anorganik'. Hasil mapping dapat diedit
 * manual lewat `sampahAdmin.php` jika ada yang kurang tepat.
 */

require 'functions.php';

// Rules: keyword (lowercase) => target jenis
$mappingRules = [
    'kertas' => 'Kertas',
    'karton' => 'Kertas',
    'kardus' => 'Kertas',

    'plastik' => 'Plastik',
    'botol' => 'Plastik',
    'gelas' => 'Plastik',
    'pet' => 'Plastik',

    'kaleng' => 'Logam',
    'besi' => 'Logam',
    'baja' => 'Logam',
    'tembaga' => 'Logam',
    'aluminium' => 'Logam',

    'jelantah' => 'Jelantah',
    'minyak' => 'Jelantah',
];

// Fetch rows that need mapping
$sql = "SELECT idSampah, jenisSampah, namaSampah FROM sampah WHERE jenisSampah IN ('Organik','Anorganik')";
$res = mysqli_query($conn, $sql);
if (!$res) {
    echo "Error fetching sampah: " . mysqli_error($conn);
    exit;
}

$rows = [];
while ($r = mysqli_fetch_assoc($res)) {
    $rows[] = $r;
}

function detectJenis($nama, $rules) {
    $name = strtolower($nama);
    foreach ($rules as $keyword => $target) {
        if (strpos($name, $keyword) !== false) return $target;
    }
    return 'Lainnya';
}

$proposals = [];
foreach ($rows as $r) {
    $new = detectJenis($r['namaSampah'], $mappingRules);
    $proposals[] = [
        'idSampah' => $r['idSampah'],
        'old' => $r['jenisSampah'],
        'nama' => $r['namaSampah'],
        'new' => $new
    ];
}

$apply = isset($_GET['apply']) && ($_GET['apply'] == '1' || $_GET['apply'] === 'true');

if ($apply) {
    // Apply updates
    $updated = 0;
    foreach ($proposals as $p) {
        if ($p['old'] === $p['new']) continue;
        $id = mysqli_real_escape_string($conn, $p['idSampah']);
        $jenis = mysqli_real_escape_string($conn, $p['new']);
        $q = "UPDATE sampah SET jenisSampah = '$jenis' WHERE idSampah = '$id'";
        if (mysqli_query($conn, $q)) {
            $updated++;
        } else {
            echo "Failed to update id={$p['idSampah']}: " . mysqli_error($conn) . "<br>";
        }
    }
    echo "Applied mapping. Updated $updated rows.<br>\n";
    echo "<a href=\"migrate_map_jenis_sampah.php\">Back to preview</a>";
    exit;
}

// Preview page
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview Mapping Jenis Sampah</title>
    <style>table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:8px}</style>
</head>
<body>
    <h2>Preview Mapping Jenis Sampah</h2>
    <p>Baris berikut akan dipetakan dari <strong>Organik/Anorganik</strong> ke kategori baru berdasarkan nama sampah.</p>
    <p><a href="migrate_map_jenis_sampah.php?apply=1" onclick="return confirm('Apply mapping? This will update database.');">Apply mapping</a></p>

    <table>
        <thead>
            <tr><th>ID</th><th>Nama Sampah</th><th>Old Jenis</th><th>Proposed Jenis</th></tr>
        </thead>
        <tbody>
            <?php foreach ($proposals as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['idSampah']); ?></td>
                <td><?php echo htmlspecialchars($p['nama']); ?></td>
                <td><?php echo htmlspecialchars($p['old']); ?></td>
                <td><?php echo htmlspecialchars($p['new']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Jika ada yang salah setelah dijalankan, Anda bisa edit manual lewat <a href="sampahAdmin.php">Menu Data Sampah</a>.</p>
</body>
</html>

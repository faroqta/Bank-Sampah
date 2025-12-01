<?php
require 'functions.php';

$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
$now = date('Ymd_His');

if ($jenis === 'setoran') {
    $filename = "transaksi_setoran_{$now}.xls";
    $sql = "SELECT s.tglSetor AS tanggal, u.namaUser, sa.jenisSampah, s.berat, s.harga, s.total FROM setoran s JOIN users u ON s.idUser=u.idUser JOIN sampah sa ON s.idSampah=sa.idSampah ORDER BY s.tglSetor DESC";
    $columns = ['Tanggal','Nama Nasabah','Jenis Sampah','Jumlah (Kg)','Harga (Kg)','Total'];
} elseif ($jenis === 'penarikan') {
    $filename = "transaksi_penarikan_{$now}.xls";
    $sql = "SELECT p.tglTarik AS tanggal, u.namaUser, p.jumlahTarik FROM penarikan p JOIN users u ON p.idUser = u.idUser ORDER BY p.tglTarik DESC";
    $columns = ['Tanggal','Nama Nasabah','Jumlah (Rp)'];
} elseif ($jenis === 'pengepul') {
    $filename = "transaksi_pengepul_{$now}.xls";
    $sql = "SELECT tglPenjualan AS tanggal, jumlahKg, hargaTotal FROM penjualan ORDER BY tglPenjualan DESC";
    $columns = ['Tanggal','Jumlah (Kg)','Harga Total (Rp)'];
} else {
    header('Location: transaksiSampah.php');
    exit();
}

// Send headers for Excel (HTML table) - Excel will open this as a worksheet
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
// BOM for Excel to properly detect UTF-8
echo "\xEF\xBB\xBF";

echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body>";
echo "<table border=1 cellspacing=0 cellpadding=4>";

// Header row (A1, B1, ...)
echo '<tr style="background:#f0f0f0;font-weight:700;">';
foreach ($columns as $col) {
    echo '<th>' . htmlspecialchars($col) . '</th>';
}
echo '</tr>';

$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        if ($jenis === 'setoran') {
            echo '<td>' . htmlspecialchars(date('d/m/Y', strtotime($row['tanggal']))) . '</td>';
            echo '<td>' . htmlspecialchars($row['namaUser']) . '</td>';
            echo '<td>' . htmlspecialchars($row['jenisSampah']) . '</td>';
            echo '<td>' . htmlspecialchars($row['berat']) . '</td>';
            echo '<td>' . htmlspecialchars($row['harga']) . '</td>';
            echo '<td>' . htmlspecialchars($row['total']) . '</td>';
        } elseif ($jenis === 'penarikan') {
            echo '<td>' . htmlspecialchars(date('d/m/Y', strtotime($row['tanggal']))) . '</td>';
            echo '<td>' . htmlspecialchars($row['namaUser']) . '</td>';
            echo '<td>' . htmlspecialchars($row['jumlahTarik']) . '</td>';
        } else { // pengepul
            echo '<td>' . htmlspecialchars(date('d/m/Y', strtotime($row['tanggal']))) . '</td>';
            $jumlah = isset($row['jumlahKg']) && $row['jumlahKg'] !== null ? $row['jumlahKg'] : (isset($row['berat']) ? $row['berat'] : '');
            echo '<td>' . htmlspecialchars($jumlah) . '</td>';
            echo '<td>' . htmlspecialchars(isset($row['hargaTotal']) ? $row['hargaTotal'] : '') . '</td>';
        }
        echo '</tr>';
    }
}

echo "</table></body></html>";
exit();

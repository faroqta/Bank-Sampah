<?php
/**
 * Run this script once (open in browser or CLI) to migrate existing
 * sampah jenis 'Organik' and 'Anorganik' to 'Lainnya'.
 */
require 'functions.php';

// Count affected sampah rows
$checkSql = "SELECT COUNT(*) as cnt FROM sampah WHERE jenisSampah IN ('Organik','Anorganik')";
$res = mysqli_query($conn, $checkSql);
$cnt = 0;
if ($res) {
    $cnt = mysqli_fetch_assoc($res)['cnt'];
}

if ($cnt > 0) {
    $sql = "UPDATE sampah SET jenisSampah = 'Lainnya' WHERE jenisSampah IN ('Organik','Anorganik')";
    if (mysqli_query($conn, $sql)) {
        echo "Migrated $cnt sampah rows to 'Lainnya'.\n";
    } else {
        echo "Error running migration: " . mysqli_error($conn) . "\n";
    }
} else {
    echo "No sampah rows with jenis 'Organik' or 'Anorganik' found.\n";
}

// Optional: if you want to update any other references, add here.

echo "Done.\n";

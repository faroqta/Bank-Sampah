<?php
require 'functions.php';
header('Content-Type: application/json');
if (!isset($_GET['idUser'])) {
    echo json_encode(['error' => 'No idUser provided']);
    exit;
}
$id = mysqli_real_escape_string($conn, $_GET['idUser']);
$user = mysqli_query($conn, "SELECT * FROM users WHERE idUser='$id' LIMIT 1");
if ($user && $row = mysqli_fetch_assoc($user)) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'User not found']);
}

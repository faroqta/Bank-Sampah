<?php
require 'functions.php';
if (isset($_GET['idSampah'])) {
    $id = mysqli_real_escape_string($conn, $_GET['idSampah']);
    $query = "DELETE FROM sampah WHERE idSampah='$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        http_response_code(200);
        echo 'success';
    } else {
        http_response_code(500);
        echo 'error';
    }
} else {
    http_response_code(400);
    echo 'invalid';
}

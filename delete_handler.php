<?php
// 1. Include your database connection file (assuming it defines $conn)
include 'functions.php'; // Change 'koneksi.php' to your actual connection file name

// 2. Check if the ID is present in the URL
if (isset($_GET['idSampah'])) {
    // 3. Get and Sanitize the ID (CRITICAL SECURITY STEP)
    $id = mysqli_real_escape_string($conn, $_GET['idSampah']);

    // 4. Perform the delete query
    $query = "DELETE FROM sampah WHERE idSampah='$id'";
    
    if (mysqli_query($conn, $query)) {
        // Deletion successful - Send a success status back
        http_response_code(200); // OK
    } else {
        // Deletion failed - Send an error status
        http_response_code(500); // Internal Server Error
    }
} else {
    // ID not provided - Send an error status
    http_response_code(400); // Bad Request
}

// 5. Close the connection (optional, but good practice)
mysqli_close($conn);
?>
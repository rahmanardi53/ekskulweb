<?php
// db.php
$host = 'localhost';
$dbname = 'blog_db';
$username = 'root';
$password = '';

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

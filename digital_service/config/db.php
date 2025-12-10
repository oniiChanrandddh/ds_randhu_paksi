<?php
$host = '127.0.0.1';
$dbname = 'digital_service';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed");
}
?>

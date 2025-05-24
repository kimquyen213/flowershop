<?php
$servername = "localhost";
$username = "root";
$password = ""; // nếu bạn có mật khẩu thì điền vào đây
$database = "qlcuahang";

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

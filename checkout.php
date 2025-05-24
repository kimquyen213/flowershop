<?php
session_start();
include 'db.php';

if (empty($_SESSION['cart'])) {
  echo "<p>🛒 Giỏ hàng trống!</p>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  // Tính tổng tiền
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  // Lưu vào bảng orders
  $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, total_price) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssi", $name, $phone, $address, $total);
  $stmt->execute();
  $order_id = $stmt->insert_id;

  // Lưu từng sản phẩm vào order_items
  foreach ($_SESSION['cart'] as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
  }

  // Xoá giỏ hàng
  $_SESSION['cart'] = [];

  echo "<h2>🎉 Đặt hàng thành công!</h2>";
  echo "<p>Cảm ơn bạn <strong>$name</strong>, đơn hàng của bạn đã được ghi nhận.</p>";
  echo "<p><strong>Mã đơn hàng:</strong> #$order_id</p>";
  echo "<a href='index.php'>← Quay lại trang chủ</a>";
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Thanh toán - MenFLOWER</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>📦 Thông tin thanh toán</h2>
  <form method="post">
    <label>Họ tên:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Số điện thoại:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Địa chỉ giao hàng:</label><br>
    <textarea name="address" required></textarea><br><br>

    <button type="submit">✔ Xác nhận đặt hàng</button>
  </form>
</body>
</html>
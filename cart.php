<?php session_start(); ?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giỏ hàng - MenFLOWER</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>🛒 Giỏ hàng của bạn</h2>

  <?php
  // Xoá sản phẩm
  if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
  }

  // Cập nhật số lượng
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
      $_SESSION['cart'][$id]['quantity'] = max(1, (int)$qty);
    }
  }

  // Thanh toán giả lập
  if (isset($_POST['checkout'])) {
    $_SESSION['cart'] = [];
    echo "<p style='color:green;'>✅ Đơn hàng đã được ghi nhận! (Thanh toán giả lập)</p>";
  }

  // Hiển thị giỏ
  if (!empty($_SESSION['cart'])):
    $total = 0;
  ?>
    <form method="post">
      <table border="1" cellpadding="10" cellspacing="0">
        <tr>
          <th>Ảnh</th><th>Tên</th><th>Giá</th><th>Số lượng</th><th>Thành tiền</th><th></th>
        </tr>
        <?php foreach ($_SESSION['cart'] as $item): 
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
        ?>
          <tr>
            <td><img src="uploads/<?php echo $item['image']; ?>" width="80"></td>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
            <td>
              <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px;">
            </td>
            <td><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
            <td><a href="cart.php?remove=<?php echo $item['id']; ?>">❌</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
      <h3>Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?> đ</h3>
      <a href="checkout.php" style="text-decoration:none;">
  <button type="button">✔ Thanh toán</button>
</a>
    </form>
  <?php else: ?>
    <p>🪻 Giỏ hàng trống.</p>
  <?php endif; ?>

  <br><a href="index.php">← Tiếp tục mua sắm</a>
</body>
</html>
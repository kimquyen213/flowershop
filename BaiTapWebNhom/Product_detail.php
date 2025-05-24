<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
  echo "Không tìm thấy sản phẩm.";
  exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if (!$product) {
  echo "Sản phẩm không tồn tại.";
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $cart_item = [
      'id' => $product['id'],
      'name' => $product['name'],
      'price' => $product['price'],
      'image' => $product['image'],
      'quantity' => $_POST['quantity']
    ];
  
    $_SESSION['cart'][$product['id']] = $cart_item;
    header("Location: cart.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?php echo $product['name']; ?> - MenFLOWER</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="detail-container">
    <div class="detail-image">
      <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
    </div>
    <div class="detail-info">
      <h2><?php echo $product['name']; ?></h2>
      <p class="detail-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
      <p class="detail-stock">Tình trạng: <?php echo $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?></p>
      <p class="detail-description"><?php echo nl2br($product['description']); ?></p>
      <?php if ($product['stock'] > 0): ?>
  <form method="post">
    <label>Số lượng:</label>
    <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
    <button type="submit" name="add_to_cart">🛒 Thêm vào giỏ</button>
  </form>
<?php else: ?>
  <p style="color: red;">Sản phẩm đã hết hàng.</p>
<?php endif; ?>
      <a href="index.php" class="back-btn">← Quay về trang chủ</a>
    </div>
  </div>
</body>
</html>
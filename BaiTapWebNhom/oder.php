<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = new mysqli("localhost", "root", "", "qlcuahang");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($product_id > 0) {
    $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    $product = $result->fetch_assoc();
}

$isConfirming = isset($_POST['confirm_step']); // Kiểm tra có đang ở bước xác nhận không
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; }
        .product-detail { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 8px #ccc; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        .btn { background: #6f42c1; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 6px; }
        .btn:hover { background: #5a32a3; }
    </style>
</head>
<body>

<div class="product-detail">
    <?php if ($product): ?>
        <h2><?= $product['product_name'] ?></h2>
        <img src="assets/images/<?= $product['image_url'] ?>" width="100%">
        <p><strong>Giá:</strong>
            <?php
            if ($product['discount_price'] && $product['discount_price'] > 0) {
                echo "<span style='color:red'>" . number_format($product['discount_price'], 0, ',', '.') . "đ</span> 
                      <del>" . number_format($product['price'], 0, ',', '.') . "đ</del>";
            } else {
                echo number_format($product['price'], 0, ',', '.') . "đ";
            }
            ?>
        </p>
        <p><?= $product['description'] ?></p>

        <?php if (!$isConfirming): ?>
            <!-- Bước 1: Điền form đặt hàng -->
            <form method="POST">
                <h3>Thông tin đặt hàng</h3>
                <input type="text" name="name" required placeholder="Họ tên">
                <input type="text" name="phone" required placeholder="Số điện thoại">
                <textarea name="address" required placeholder="Địa chỉ giao hàng"></textarea>

                <!-- Ẩn thông tin sản phẩm và đánh dấu xác nhận -->
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <input type="hidden" name="confirm_step" value="1">
                <button type="submit" class="btn">Tiếp tục → Xác nhận đơn hàng</button>
            </form>

        <?php else: ?>
            <!-- Bước 2: Hiển thị thông tin xác nhận -->
            <h3>Xác nhận thông tin đơn hàng</h3>
            <p><strong>Sản phẩm:</strong> <?= $product['product_name'] ?></p>
            <p><strong>Giá:</strong>
                <?php
                if ($product['discount_price'] && $product['discount_price'] > 0) {
                    echo number_format($product['discount_price'], 0, ',', '.') . "đ";
                } else {
                    echo number_format($product['price'], 0, ',', '.') . "đ";
                }
                ?>
            </p>
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($_POST['name']) ?></p>
            <p><strong>SĐT:</strong> <?= htmlspecialchars($_POST['phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($_POST['address'])) ?></p>

            <!-- Gửi dữ liệu qua dat-hang.php -->
            <form method="POST" action="dat-hang.php">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($_POST['name']) ?>">
                <input type="hidden" name="phone" value="<?= htmlspecialchars($_POST['phone']) ?>">
                <input type="hidden" name="address" value="<?= htmlspecialchars($_POST['address']) ?>">
                <button type="submit" class="btn">Xác nhận đặt hàng</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm.</p>
    <?php endif; ?>
</div>

</body>
</html>

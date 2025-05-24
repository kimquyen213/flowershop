<?php
include 'db.php';

$category_id = $_GET['category_id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$cat_result = $stmt->get_result();
if ($cat_result->num_rows === 0) {
  echo "<h2>Không tìm thấy chủ đề</h2>";
  exit;
}
$category = $cat_result->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$products = $stmt->get_result();
?>

<h2>🌸 Chủ đề: <?php echo $category['name']; ?></h2>

<div class="product-grid">
<?php while ($row = $products->fetch_assoc()): ?>
  <div class="product-card">
    <img src="uploads/<?php echo $row['image']; ?>" alt="">
    <h3><?php echo $row['name']; ?></h3>
    <p><?php echo number_format($row['price']); ?>đ</p>
    <a href="product_detail.php?id=<?php echo $row['id']; ?>">Xem chi tiết</a>
  </div>
<?php endwhile; ?>
</div>
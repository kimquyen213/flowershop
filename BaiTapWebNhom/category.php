<?php
include 'db.php';
session_start();

$type = isset($_GET['type']) ? $_GET['type'] : '';
$category_names = [
    'hoa-bo' => 'Hoa bó',
    'hoa-vo' => 'Hoa vỏ',
    'lang-hoa' => 'Lẵng hoa',
    'hoa-chuc-mung' => 'Hoa chúc mừng',
    'vo-hoa-trai-cay' => 'Vỏ hoa trái cây'
];

$category_name = isset($category_names[$type]) ? $category_names[$type] : 'Danh mục không tồn tại';

// Get products from database based on category
$sql = "SELECT * FROM products WHERE category = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $type);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title><?php echo $category_name; ?> - MenFlower</title>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>

    <div class="category-page">
        <h1><?php echo $category_name; ?></h1>
        
        <div class="products-grid">
            <?php while($product = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="price"><?php echo number_format($product['price']); ?>đ</p>
                    <a href="order.php?id=<?php echo $product['id']; ?>" class="buy-btn">Đặt mua</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
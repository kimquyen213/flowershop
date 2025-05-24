<?php
include 'db.php';

// Bật báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Lấy theme_id từ URL
$theme_id = isset($_GET['theme_id']) ? (int)$_GET['theme_id'] : 1;

// Lấy thông tin chủ đề với prepared statement
$theme_stmt = $conn->prepare("SELECT * FROM flower_themes WHERE theme_id = ?");
$theme_stmt->bind_param("i", $theme_id);
$theme_stmt->execute();
$theme_result = $theme_stmt->get_result();
$theme = $theme_result->fetch_assoc();

if (!$theme) {
    die("<h2>Chủ đề không tồn tại</h2>");
}

// Xử lý sắp xếp và giới hạn
$sort = $_GET['sort'] ?? 'default';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;

$sort_options = [
    'default' => 'product_id DESC',
    'name_asc' => 'product_name ASC',
    'name_desc' => 'product_name DESC',
    'price_high' => 'price DESC',
    'price_low' => 'price ASC'
];
$order_by = $sort_options[$sort] ?? $sort_options['default'];

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_condition = '';
$search_params = [];

if ($search) {
    $search_condition = " AND product_name LIKE ?";
    $search_params = ["%$search%"];
}

// Truy vấn sản phẩm với prepared statement
$sql = "SELECT product_id, product_name, price, discount_price, image_url 
        FROM products 
        WHERE theme_id = ? $search_condition 
        ORDER BY $order_by 
        LIMIT ?";
        
$stmt = $conn->prepare($sql);
$types = "i" . ($search ? "s" : "") . "i";
$params = array_merge([$theme_id], $search_params, [$limit]);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
// Truy vấn sản phẩm khuyến mãi với các cải tiến
$discount_sql = "SELECT 
                    product_id, 
                    product_name, 
                    price, 
                    discount_price, 
                    image_url,
                    ROUND((price - discount_price) / price * 100) AS discount_percent
                FROM products 
                WHERE theme_id = ? 
                AND discount_price IS NOT NULL 
                AND discount_price > 0 
                AND discount_price < price
                ORDER BY discount_percent DESC, RAND()
                LIMIT 4";

$discount_stmt = $conn->prepare($discount_sql);
$discount_stmt->bind_param("i", $theme_id);
$discount_stmt->execute();
$discount_result = $discount_stmt->get_result();
$discounted_products = $discount_result->fetch_all(MYSQLI_ASSOC);

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($theme['theme_name']) ?> - MenFlower</title>
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="oder.php">
</head>
<body>
<div class="top-bar">
    <span>Hotline: 0987 654 321</span>
    <div class="social-icons">
      <a href="#"><img src="assets/images/Facebook.png" alt="Facebook"></a>
      <a href="#"><img src="assets/images/instagram.png" alt="Instagram"></a>
      <a href="#"><img src="assets/images/twett.png" alt="Twitter"></a>
    </div>
    <div class="account">
      <a href="#">👤 Tài khoản</a> | <a href="#">🛒 Giỏ hàng</a>
    </div>
  </div>
    <!-- Header -->
    <header class="header">
  <div class="logo">
  <img src="assets/images/logo.png" alt="Logo" width="55">
  <div class="logo-text">
    <h1>MenFLOWER</h1>
    <p class="slogan">Bloom louder, whisper sweeter.</p>
  </div>
</div>
<form class="search-box" method="GET">
  <input type="hidden" name="theme_id" value="<?= $theme_id ?>">
  <input type="hidden" name="sort" value="<?= $sort ?>">
  <input type="hidden" name="limit" value="<?= $limit ?>">
  <input type="text" name="search" placeholder="Tìm kiếm" value="<?= htmlspecialchars($search) ?>">
  <button type="submit">🔍</button>
</form>
  </header>
    
    <!-- Menu chính -->
  <nav class="main-nav">
    <a href="index.php">Trang chủ</a>
    <div class="dropdown">
  <a href="#">Chủ đề</a>
  <div class="dropdown-content">
    <a href="chude.php?theme_id=1">Hoa sinh nhật</a>
    <a href="chude.php?theme_id=2">Hoa khai trương</a>
    <a href="chude.php?theme_id=3">Hoa chúc mừng</a>
    <a href="chude.php?theme_id=4">Hoa cưới cầm tay</a>
    <a href="chude.php?theme_id=5">Hoa chia buồn</a>
    <a href="chude.php?theme_id=6">Hoa tốt nghiệp</a>
    <a href="chude.php?theme_id=7">Hoa tình yêu</a>
  </div>
</div>
    
<li class="dropdown">
  <a href="#">Hoa chúc mừng</a>
  <ul class="dropdown-menu">
    <li><a href="#">Hoa sự kiện</a></li>
    <li><a href="#">Hoa tết</a></li>
    <li><a href="#">Hoa ngày 8/3</a></li>
    <li><a href="#">Hoa khai trương</a></li>
    <li><a href="#">Hoa chúc sức khỏe</a></li>
    <li><a href="#">Hoa chúc mừng</a></li>
    <li><a href="#">Hoa kỷ niệm</a></li>
    <li><a href="#">Hoa ngày lễ</a></li>
  </ul>
</li>
    <li class="dropdown">
  <a href="#">Hoa sinh nhật</a>
  <ul class="dropdown-menu">
    <li><a href="#">Hoa sinh nhật giá rẻ</a></li>
    <li><a href="#">Hoa sinh nhật tặng mẹ</a></li>
    <li><a href="#">Hoa sinh nhật tặng người iu</a></li>
    <li><a href="#">Hoa sinh nhật tặng bạn</a></li>
    <li><a href="#">Lẵng hoa sinh nhật</a></li>
    <li><a href="#">Giỏ hoa sinh nhật</a></li>
  </ul>
</li>
    <li class="dropdown">
                <a href="#">Loại hoa</a>
                <ul class="dropdown-menu">
                    <li><a href="#">Hoa hồng</a></li>
                    <li><a href="#">Hoa cắm tủ cầu</a></li>
                    <li><a href="#">Hoa hướng dương</a></li>
                    <li><a href="#">Hoa cúc</a></li>
                    <li><a href="#">Hoa baby</a></li>
                    <li><a href="#">Hoa tulip</a></li>
                    <li><a href="#">Hoa sen</a></li>
                    <li><a href="#">Hoa mẫu đơn</a></li>
                </ul>
            </li>
            <li class="dropdown">
                    <a href="#">Kiểu dáng</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Bó hoa</a></li>
                        <li><a href="#">Lẳng hoa</a></li>
                        <li><a href="#">Giỏ hoa</a></li>
                        <li><a href="#">Bình hoa</a></li>
                        <li><a href="#">Hộp hoa</a></li>
                        <li><a href="#">Kệ hoa</a></li>
                    </ul>
                </li>
                <li class="dropdown">
  <a href="#">Hoa sáp</a> 
  <ul class="dropdown-menu">
    <li><a href="#">Hoa sáp thơm</a></li> 
    <li><a href="#">Hoa sáp kim tuyến </a></li>  
  </ul>
</li>
<li class="dropdown">
  <a href="#">Quà tặng</a> 
  <ul class="dropdown-menu">
    <li><a href="#">Giỏ trái cây</a></li> 
    <li><a href="#">Gấu bông </a></li> 
    <li><a href="#">Nến thơm</a></li> 
    <li><a href="#">Bánh kem & Hoa tươi</a></li> 
    <li><a href="#">Chocola</a></li> 
  </ul>
</li>
    <a href="lienhe.php">Liên hệ</a>
    
  </nav>
  
<!-- Tiêu đề chủ đề -->
<h2 class="page-title"><?= htmlspecialchars($theme['theme_name']) ?></h2>

<!-- Bộ lọc và sắp xếp -->
<div class="sort-options">
    <div class="sort-group">
        <span>Sắp xếp theo: </span>
        <select onchange="window.location.href='?theme_id=<?= $theme_id ?>&sort='+this.value">
            <option value="default" <?= $sort == 'default' ? 'selected' : '' ?>>Mặc định</option>
            <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Tên (A-Z)</option>
            <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Tên (Z-A)</option>
            <option value="price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Giá (Cao-Thấp)</option>
            <option value="price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Giá (Thấp-Cao)</option>
            
        </select>
    </div>

    <div class="sort-group">
        <span>Hiển thị: </span>
        <select onchange="window.location.href='?theme_id=<?= $theme_id ?>&limit='+this.value+'&sort=<?= $sort ?>'">
            <option value="12" <?= $limit == 12 ? 'selected' : '' ?>>12</option>
            <option value="24" <?= $limit == 24 ? 'selected' : '' ?>>24</option>
            <option value="36" <?= $limit == 36 ? 'selected' : '' ?>>36</option>
        </select>
    </div>
</div>
<!-- Thêm section hoa khuyến mãi -->
<?php if (!empty($discounted_products)): ?>
<div class="discount-banner">
    <h2><i class="fas fa-tag"></i> Sản phẩm khuyến mãi</h2>
</div>
<div class="discounted-products">
    <?php foreach ($discounted_products as $product): ?>
    <div class="product">
        <div class="discount-flag">-<?= $product['discount_percent'] ?>%</div>
        <img src="uploads/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
        <h3><?= htmlspecialchars($product['product_name']) ?></h3>
        <div class="price">
            <span class="old-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>
            <span class="new-price"><?= number_format($product['discount_price'], 0, ',', '.') ?>đ</span>
        </div>
        <button class="add-to-cart" data-id="<?= $product['product_id'] ?>">
            <i class="fas fa-cart-plus"></i> Thêm giỏ hàng
        </button>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
  <!-- Hiển thị sản phẩm -->
  <div class="product-grid">
    <?php if (empty($products)): ?>
      <p class="no-products">Không có sản phẩm nào trong chủ đề này.</p>
    <?php else: ?>
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <div class="product-image">
            <img src="assets/images/<?= htmlspecialchars($product['image_url'] ?? 'default.jpg') ?>" 
                 alt="<?= htmlspecialchars($product['product_name'] ?? 'Không có tên') ?>">
          </div>
          <div class="product-info">
            <p class="product-name"><?= htmlspecialchars($product['product_name'] ?? 'Sản phẩm không tên') ?></p>
            
            <?php if (!empty($product['discount_price'])): ?>
              <p class="price-sale"><?= number_format((float)$product['discount_price'], 0, ',', '.') ?>đ</p>
            <?php endif; ?>
            
            <p class="price-root <?= !empty($product['discount_price']) ? 'line-through' : '' ?>">
              <?= isset($product['price']) ? number_format((float)$product['price'], 0, ',', '.') . 'đ' : 'Không có giá' ?>
            </p>
            
            <a href="oder.php?id=<?= $product['product_id'] ?? '' ?>" class="order-button">Đặt hàng</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>   <!-- Footer -->
<footer class="footer">
  <div class="footer-logo">
    <img src="assets/images/logo.png" alt="MenFlower Logo">
    <h2>MenFLOWER</h2>
    <p>Bloom louder, whisper sweeter.</p>
    <p><strong>Hotline:</strong> 0987 654 321<br>
    <strong>Email:</strong> Menflower2103@gmail.com</p>
  </div>
  <div class="footer-column">
    <h4>Chăm sóc khách hàng</h4>
    <ul>
      <li>Chính sách vận chuyển</li>
      <li>Bảo mật thông tin</li>
      <li>Hình thức thanh toán</li>
      <li>Chính sách hoàn tiền</li>
      <li>Xử lý khiếu nại</li>
    </ul>
  </div>
  <div class="footer-column">
    <h4>Theo dõi</h4>
    <ul>
      <li>Facebook</li>
      <li>Instagram</li>
      <li>Zalo</li>
      <li>Twitter</li>
    </ul>
  </div>
  <div class="footer-column">
    <h4>Shop hoa MenFlower</h4>
    <p>Chi nhánh chính: Số 1 Hoàng Lê Kha, P.3, Tp. Tây Ninh</p>
  </div>
</footer>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
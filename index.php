<?php include 'db.php';
 session_start(); // Khởi động session để làm việc với $_SESSION

function getCartCount() {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        return count($_SESSION['cart']);
    }
    return 0;
    // Truy vấn sản phẩm
$sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 8";
$result = mysqli_query($conn, $sql);

// Kiểm tra có dữ liệu không
if ($result && mysqli_num_rows($result) > 0) {
    while ($product = mysqli_fetch_assoc($result)) {
        echo '<div class="product-item">';
        echo '<img src="uploads/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
        echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
        echo '<p class="price">' . number_format($product['price']) . 'đ</p>';
        echo '<a href="oder.php?id=' . $product['id'] . '" class="order-btn">Đặt mua</a>';
        echo '</div>';
    }
} else {
    echo '<p>Không có sản phẩm nào.</p>';
}
}?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="MenFlower - Cửa hàng hoa tươi chuyên nghiệp, giao nhanh trong 60 phút, miễn phí thiệp, băng rôn, và chụp hình trước khi giao.">
  <title>MenFlower - Shop Hoa Tươi</title>
  <link rel="stylesheet" href="assets/style.css">
  <script src="assets/script.js"></script>

</head>
<body>

  <!-- Hotline + mạng xã hội -->
  <div class="top-bar">
    <span>Hotline: 0987 654 321</span>
    <div class="social-icons">
      <a href="#"><img src="assets/images/Facebook.png" alt="Facebook"></a>
      <a href="#"><img src="assets/images/instagram.png" alt="Instagram"></a>
      <a href="#"><img src="assets/images/twett.png" alt="Twitter"></a>
    </div>
    <div class="account">
    <a href="#">👤 Tài khoản</a> | 
    <a href="cart.php">🛒 Giỏ hàng <span class="cart-count"><?= getCartCount() ?></span></a>
</div>
  </div>

  <!-- Logo + Tìm kiếm -->
  <header class="header">
  <div class="logo">
  <img src="assets/images/logo.png" alt="Logo" width="55">
  <div class="logo-text">
    <h1>MenFLOWER</h1>
    <p class="slogan">Bloom louder, whisper sweeter.</p>
  </div>
</div>
    <form class="search-box" method="GET">
      <input type="text" name="search" placeholder="Tìm kiếm">
      <button type="submit">🔍</button>
    </form>
  </header>

  <!-- Menu chính -->
  <nav class="main-nav">
    <a href="index.php">Trang chủ</a>
    <div class="dropdown">
  <a href="chude.php">Chủ đề</a>
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
  
  
  <section class="products-section">
    <div class="products-header">
        <div class="products-filters">
            <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm...">
            <select id="sortSelect">
                <option value="">Sắp xếp theo</option>
                <option value="asc">Giá: Thấp đến cao</option>
                <option value="desc">Giá: Cao đến thấp</option>
                <option value="new">Mới nhất</option>
            </select>
        </div>
    </div>
</select>
  
<div class="slider-wrapper">
  <div class="slider-container">
    <div class="slides">
      <div class="slide">
        <img src="assets/images/banner1.png" alt="Banner 1">
      </div>
      <div class="slide">
        <img src="assets/images/banner2.png" alt="Banner 2">
      </div>
      <div class="slide">
        <img src="assets/images/banner3.png" alt="Banner 3">
      </div>
      <div class="slide">
        <img src="assets/images/banner4.png" alt="Banner 4">
      </div>
      <div class="slide">
        <img src="assets/images/banner5.png" alt="Banner 5">
      </div>
    </div>
    <button class="nav-arrow prev">&#10094;</button>
    <button class="nav-arrow next">&#10095;</button>
  </div>
</div>

<script>
  const slider = document.querySelector('.slides'); // fix class name
const images = document.querySelectorAll('.slides .slide'); // để đúng vùng hiển thị
const totalImages = images.length;
let currentIndex = 0;

setInterval(() => {
  currentIndex = (currentIndex + 1) % totalImages;
  slider.style.transform = `translateX(-${currentIndex * 100}%)`;
  slider.style.transition = 'transform 0.5s ease';
}, 3000);

</script>

  <!-- Dịch vụ -->
  <section class="services">
    <h2>Dịch vụ MenFlower</h2>
    <div class="service-boxes">
      <div class="service blue">🚀<br>Giao hàng hỏa tốc<br><small>60 phút nội thành</small></div>
      <div class="service green">🚚<br>Giao hoa miễn phí<br><small>cho đơn từ 500k</small></div>
      <div class="service pink">🎀<br>Miễn phí thiệp, băng rôn</div>
      <div class="service yellow">📷<br>Chụp hình trước<br><small>trước khi giao</small></div>
    </div>
  </section>

  <!-- Danh mục sản phẩm -->
  <section class="categories">
    <h2>Danh mục sản phẩm</h2>
    <div class="category-list">
        <a href="category.php?type=hoa-bo" class="category-item">
            <img src="assets/images/dmhoabo.png" alt="Hoa bó">
            <div class="category-name">Hoa bó</div>
            <div class="category-desc">Bó hoa tươi thắm, gửi trao yêu thương</div>
        </a>
        <a href="category.php?type=hoa-vo" class="category-item">
            <img src="assets/images/dmhoavo.png" alt="Hoa vỏ">
            <div class="category-name">Hoa vỏ</div>
            <div class="category-desc">Vỏ hoa đặc biệt, độc đáo</div>
        </a>
        <a href="category.php?type=lang-hoa" class="category-item">
            <img src="assets/images/dmlanghoa.png" alt="Lẵng hoa">
            <div class="category-name">Lẵng hoa</div>
            <div class="category-desc">Lẵng hoa trang trọng, sang trọng</div>
        </a>
        <a href="category.php?type=hoa-chuc-mung" class="category-item">
            <img src="assets/images/dmhoachucmung" alt="Hoa chúc mừng">
            <div class="category-name">Hoa chúc mừng</div>
            <div class="category-desc">Chúc mừng những dịp đặc biệt</div>
        </a>
        <a href="category.php?type=vo-hoa-trai-cay" class="category-item">
            <img src="assets/images/dmhoatraicay.png" alt="Vỏ hoa trái cây">
            <div class="category-name">Vỏ hoa trái cây</div>
            <div class="category-desc">Kết hợp độc đáo hoa và trái cây</div>
        </a>
    </div>
  </section>
  <section class="about">
    
  <div class="hotline-banner">Điện thoại đặt hoa: <strong>0987 654 321</strong></div>
  
  <div class="about-content">
    <div class="about-left">
      <h3>Chào mừng đến với MenFlower – Thế giới của những bông hoa tươi rực rỡ!</h3>
      <p>Chúng tôi là cửa hàng hoa tươi chuyên nghiệp, mang đến cho bạn những mẫu hoa đẹp, ý nghĩa và tinh tế nhất cho mọi dịp:</p>
      <ul>
        <li>Hoa chúc mừng – gửi lời yêu thương và chúc phúc</li>
        <li>Hoa khai trương – khởi đầu may mắn, phát tài</li>
        <li>Hoa tình yêu – lãng mạn, nồng nàn</li>
        <li>Hoa sinh nhật – món quà bất ngờ ý nghĩa</li>
        <li>Hoa hồng, hoa hướng dương, hoa cúc,...</li>
      </ul>
      <p>Với đội ngũ cắm hoa chuyên nghiệp, dịch vụ giao hàng nhanh chóng, và chăm sóc khách hàng tận tâm, chúng tôi cam kết mang đến trải nghiệm mua hoa tuyệt vời nhất.</p>
      <p><strong>Hoa tươi – Tình cảm tươi mới – Gắn kết bền lâu.</strong></p>
    </div>
    
    <div class="about-right">
      <h3>Cam kết từ shop hoa tươi MenFlower!</h3>
      <ul>
        <li>Chỉ sử dụng hoa tươi mới nhập về trong ngày</li>
        <li>Hoa đẹp và 90% giống như hình</li>
        <li>Giao hoa nhanh, đúng giờ</li>
        <li>Miễn phí chụp hình trước khi giao</li>
      </ul>
      <p>Nếu bạn đang cần đặt hoa để gửi tặng người thân trong những dịp đặc biệt – gọi ngay 0987 654 321 để được tư vấn hoặc đặt hoa giao nhanh với shop hoa tươi MenFlower!</p>
    </div>
  </div>
</section>

<!-- Footer -->
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

<li><a href="#">Chính sách vận chuyển</a></li>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
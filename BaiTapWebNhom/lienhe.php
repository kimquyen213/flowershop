
<?php
include 'db.php';

$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ten = $_POST["ten"];
  $email = $_POST["email"];
  $sdt = $_POST["sdt"];
  $noidung = $_POST["noidung"];

  $sql = "INSERT INTO lienhe (ten, email, sdt, noidung) 
          VALUES ('$ten', '$email', '$sdt', '$noidung')";

  if ($conn->query($sql) === TRUE) {
    $thongbao = "✅ Gửi thông tin thành công!";
  } else {
    $thongbao = "❌ Lỗi: " . $conn->error;
  }
}
?>
<?php
// PHẦN 1: XỬ LÝ FORM LIÊN HỆ (ĐẶT ĐẦU FILE)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form với kiểm tra tồn tại
    $ten = isset($_POST['ten']) ? $_POST['ten'] : '';
    $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : '';
    $noidung = isset($_POST['noidung']) ? $_POST['noidung'] : '';
    
    // Kiểm tra dữ liệu không rỗng
    if (!empty($ten) && !empty($sdt) && !empty($noidung)) {
        // Xử lý dữ liệu ở đây
        $thongbao = "Cảm ơn $ten đã gửi liên hệ!";
    } else {
        $thongbao = "Vui lòng điền đầy đủ thông tin!";
    }
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lọc và validate dữ liệu
    $ten = filter_input(INPUT_POST, 'ten', FILTER_SANITIZE_STRING) ?? '';
    $sdt = filter_input(INPUT_POST, 'sdt', FILTER_SANITIZE_STRING) ?? '';
    $noidung = filter_input(INPUT_POST, 'noidung', FILTER_SANITIZE_STRING) ?? '';
    
    $errors = [];
    
    if (empty($ten)) {
        $errors[] = "Vui lòng nhập tên";
    }
    
    if (empty($sdt)) {
        $errors[] = "Vui lòng nhập số điện thoại";
    } elseif (!preg_match('/^[0-9]{10,11}$/', $sdt)) {
        $errors[] = "Số điện thoại không hợp lệ";
    }
    
    if (empty($noidung)) {
        $errors[] = "Vui lòng nhập nội dung";
    }
    
    if (empty($errors)) {
        // Xử lý dữ liệu - ví dụ: lưu vào CSDL
        echo "Gửi liên hệ thành công!";
    } else {
        foreach ($errors as $error) {
            echo "<p>Lỗi: $error</p>";
        }
    }
}
?>

<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Liên hệ - MenFlower</title>
  <link rel="stylesheet" href="assets/style.css">
  
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="contact-container">
  <div class="contact-info">
    <h2>Thông tin liên hệ</h2>
    <p><strong>Địa chỉ:</strong> Số 1 Hoàng Lê Kha, P.3, Tp. Tây Ninh</p>
    <p><strong>Hotline:</strong> 0987 654 321</p>
    <p><strong>Email:</strong> menflower2103@gmail.com</p>
    <div class="map">
      <h3>Địa chỉ trên bản đồ</h3>
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.9275981018465!2d106.65842811533463!3d10.738045392343464!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3d490f2c21%3A0x80f7be540f3c3aa2!2zUXXhuq1uIMSQw6B5IFRodeG6rW4!5e0!3m2!1svi!2s!4v1600000000000!5m2!1svi!2s" 
        allowfullscreen="" 
        loading="lazy">
      </iframe>
    </div>
  </div>

  <div class="contact-wrapper">
  <section class="contact-section">
    <h2>Liên hệ với MenFlower</h2>
    <form method="post" action="lienhe.php" class="contact-form">
      <input type="text" name="ten" placeholder="Họ và tên*" required>
      <input type="tel" name="sdt" placeholder="Số điện thoại">
      <input type="email" name="email" placeholder="Email*" required>
      <input type="text" name="subject" placeholder="Chủ đề">
      <textarea name="noidung" placeholder="Nội dung liên hệ*" rows="5" required></textarea>
      <button type="submit">📨 Gửi liên hệ</button>
      <?php if ($thongbao) echo "<p class='alert'>$thongbao</p>"; ?>
    </form>
  </section>
</div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>

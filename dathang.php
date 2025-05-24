<?php
$conn = new mysqli("localhost", "root", "", "qlcuahang");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$product_id = intval($_POST['product_id']);

$product_result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
$product = $product_result->fetch_assoc();
  
$to = $email;
$subject = "Xác nhận đơn hàng từ MenFlower";
$message = "Cảm ơn bạn đã đặt hàng! Mã đơn hàng: #" . $order_id;
$headers = "From: donhang@menflower.vn";

mail($to, $subject, $message, $headers);
// Lấy dữ liệu từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Giả định lấy giá sản phẩm từ DB
    $stmt = $mysqli->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    // Thêm đơn hàng vào bảng orders
    $stmt = $mysqli->prepare("INSERT INTO orders (customer_name, email, address, phone, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $customer_name, $email, $address, $phone);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Thêm vào bảng order_items
    $stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    $stmt->execute();
    $stmt->close();

    // Xác nhận
    echo "<h2>✅ Đặt hàng thành công!</h2>";
    echo "<p>Mã đơn hàng: <strong>#{$order_id}</strong></p>";
    echo "<p>Cảm ơn bạn đã mua hàng tại shop!</p>";
}
if ($product) {
    $price = ($product['discount_price'] && $product['discount_price'] > 0) ? $product['discount_price'] : $product['price'];

    // Lưu vào bảng orders
    $conn->query("INSERT INTO orders (customer_name, phone, address, total_amount, created_at)
                  VALUES ('$name', '$phone', '$address', '$price', NOW())");

    $order_id = $conn->insert_id;

    // Lưu vào bảng order_items
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price)
                  VALUES ($order_id, $product_id, 1, $price)");

    echo "<h2>✅ Đặt hàng thành công!</h2>
          <p>Cảm ơn bạn đã đặt hàng.</p>
          <p><a href='index.php'>Quay lại trang chủ</a></p>";
} else {
    echo "<p>Lỗi: Không tìm thấy sản phẩm.</p>";
}
<label><input type="radio" name="payment_method" value="cod" checked> Thanh toán khi nhận hàng</label>
// Gửi email xác nhận đơn hàng
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Ví dụ: dùng Gmail
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your_email@gmail.com'; // Email gửi đi
    $mail->Password   = 'your_email_password'; // Mật khẩu ứng dụng (không phải mật khẩu Gmail thường)
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('your_email@gmail.com', 'MenFlower Shop');
    $mail->addAddress($email, $customer_name);

    // Nội dung
    $mail->isHTML(true);
    $mail->Subject = 'Xác nhận đơn hàng #' . $order_id;
    $mail->Body    = "
        <h3>Chào $customer_name,</h3>
        <p>Cảm ơn bạn đã đặt hàng tại <strong>MenFlower</strong>.</p>
        <p>Thông tin đơn hàng:</p>
        <ul>
            <li><strong>Mã đơn hàng:</strong> #$order_id</li>
            <li><strong>Sản phẩm:</strong> ID $product_id</li>
            <li><strong>Số lượng:</strong> $quantity</li>
            <li><strong>Tổng tiền:</strong> " . number_format($price * $quantity, 0, ',', '.') . "đ</li>
        </ul>
        <p>Chúng tôi sẽ sớm liên hệ với bạn để giao hàng.</p>
        <p>Trân trọng,<br>MenFlower</p>
    ";

    $mail->send();
    echo "<p>📧 Email xác nhận đã được gửi tới <strong>$email</strong></p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Không thể gửi email: {$mail->ErrorInfo}</p>";
}

?>

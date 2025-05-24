<?php
include 'db.php';

// Thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
  $name = $_POST['name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $description = $_POST['description'];

  $image = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];
  move_uploaded_file($image_tmp, "uploads/" . $image);

  $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, image, description) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssdisb", $name, $category, $price, $stock, $image, $description);
  $stmt->execute();
  header("Location: admin_products.php");
}

// Xoá sản phẩm
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM products WHERE id = $id");
  header("Location: admin_products.php");
  echo "<td>
        <a href='?edit={$row['id']}'>Sửa</a> |
        <a href='?delete={$row['id']}' onclick='return confirm(\"Xoá sản phẩm này?\")'>Xoá</a>
      </td>";
}
?>

<h2>Quản lý sản phẩm</h2>

<?php if (isset($edit_product)): ?>
  <h3>Sửa sản phẩm</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
    <input type="text" name="name" value="<?php echo $edit_product['name']; ?>" required><br>
    <input type="text" name="category" value="<?php echo $edit_product['category']; ?>" required><br>
    <input type="number" name="price" step="0.01" value="<?php echo $edit_product['price']; ?>" required><br>
    <input type="number" name="stock" value="<?php echo $edit_product['stock']; ?>" required><br>
    <input type="file" name="image"><br>
    <textarea name="description"><?php echo $edit_product['description']; ?></textarea><br>
    <button type="submit" name="update">Cập nhật</button>
    <a href="admin_products.php" style="margin-left:10px;">Huỷ</a>
  </form>
<?php else: ?>
  <h3>Thêm sản phẩm</h3>
  <form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Tên hoa" required><br>
    <input type="text" name="category" placeholder="Phân loại" required><br>
    <input type="number" name="price" step="0.01" placeholder="Giá" required><br>
    <input type="number" name="stock" placeholder="Số lượng" required><br>
    <input type="file" name="image" accept="image/*" required><br>
    <textarea name="description" placeholder="Mô tả"></textarea><br>
    <button type="submit" name="add">Thêm</button>
  </form>
<?php endif; ?>

<!-- Danh sách sản phẩm -->
<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>Hình</th>
    <th>Tên</th>
    <th>Phân loại</th>
    <th>Giá</th>
    <th>Tồn kho</th>
    <th>Mô tả</th>
    <th>Hành động</th>
  </tr>

  <?php
  $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td><img src='uploads/{$row['image']}' width='60'></td>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['category']}</td>";
    echo "<td>{$row['price']} đ</td>";
    echo "<td>{$row['stock']}</td>";
    echo "<td>{$row['description']}</td>";
    echo "<td><a href='?delete={$row['id']}' onclick='return confirm(\"Xoá sản phẩm này?\")'>Xoá</a></td>";
    echo "</tr>";
  }
  if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id = $edit_id");
    $edit_product = $result->fetch_assoc();
  }
  
  // Gửi cập nhật
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
  
    if (!empty($_FILES['image']['name'])) {
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
      $conn->query("UPDATE products SET name='$name', category='$category', price=$price, stock=$stock, image='$image', description='$description' WHERE id=$id");
    } else {
      $conn->query("UPDATE products SET name='$name', category='$category', price=$price, stock=$stock, description='$description' WHERE id=$id");
    }
  
    header("Location: admin_products.php");
  }
  <select name="category_id" required>
  <option value="">-- Chọn chủ đề hoa --</option>
  <option value="1">Hoa sinh nhật</option>
  <option value="2">Hoa khai trương</option>
  <option value="3">Hoa chúc mừng</option>
  <option value="4">Hoa cưới cầm tay</option>
  <option value="5">Hoa chia buồn</option>
  <option value="6">Hoa tốt nghiệp</option>
  <option value="7">Hoa tình yêu</option>
</select>
  ?>
</table>
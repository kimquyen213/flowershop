<?php
include 'db.php';

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

$sql = "SELECT * FROM products WHERE name LIKE ?";
$searchParam = "%" . $search . "%";

if ($sort == 'asc') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == 'desc') {
    $sql .= " ORDER BY price DESC";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div class='product'>";
    echo "<img src='uploads/{$row['image']}' width='120'>";
    echo "<h4>{$row['name']}</h4>";
    echo "<p>Giá: {$row['price']}đ</p>";
    echo "<p>Còn lại: {$row['stock']}</p>";
    echo "<a href='product_detail.php?id={$row['id']}'>Chi tiết</a>";
    echo "</div>";
}
?>
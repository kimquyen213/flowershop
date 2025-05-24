$result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");

while($order = $result->fetch_assoc()) {
  echo "<tr>
    <td>{$order['id']}</td>
    <td>{$order['customer_name']}</td>
    <td>{$order['total_amount']}</td>
    <td>{$order['payment_method']}</td>
    <td>{$order['created_at']}</td>
    <td><a href='order-details.php?id={$order['id']}'>Chi tiết</a></td>
  </tr>";
}

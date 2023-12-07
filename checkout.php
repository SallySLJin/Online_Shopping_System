<?php
session_start();
include 'config.php';

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    // Update 'status' in table 'Order' to "Processed"
    $updateOrderSql = "UPDATE `Order` SET `status` = 'Processed' WHERE user_id = $userId AND status = 'In Cart'";
    $conn->query($updateOrderSql);

    // Store 'user_id' and 'order_id' from 'Shopping_Cart' to 'Historical_Order'
    $insertHistoricalOrderSql = "INSERT INTO Historical_Order (user_id, order_id) 
                             SELECT user_id, order_id 
                             FROM Shopping_Cart 
                             WHERE user_id = $userId AND NOT EXISTS (
                                SELECT 1 FROM Historical_Order 
                                WHERE Historical_Order.order_id = Shopping_Cart.order_id
                             )";
    $conn->query($insertHistoricalOrderSql);

    // Create a new tuple in table 'Order' with 'status' value 'In Cart'
    $createNewOrderSql = "INSERT INTO `Order` (user_id, status) VALUES ($userId, 'In Cart')";
    $conn->query($createNewOrderSql);

    // Get the ID of the new order created
    $orderSql = "SELECT * FROM `Order` WHERE user_id = $userId AND status = 'In Cart'";
    $orderResult = $conn->query($orderSql);
    $orderRow = $orderResult->fetch_assoc();
    $newOrderId = $orderRow['id'];

    // Update 'order_id' in 'Shopping_Cart' to the one just created
    $updateShoppingCartSql = "UPDATE Shopping_Cart SET order_id = $newOrderId WHERE user_id = $userId";
    $conn->query($updateShoppingCartSql);

    // Redirect to a confirmation page
    header("Location: index.php");
} else {
    echo "<p>User not logged in.</p>";
}

$conn->close();
?>

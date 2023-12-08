<?php
session_start();
// addToCart.php

header('Content-Type: application/json'); // Set the Content-Type header to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Retrieve data from the request
    $productId = $data['productId'];
    $quantity = $data['quantity'];

    // Example database connection
    include '../config.php';

    // Assume you have a 'shopping_cart' table with columns 'user_id', 'order_id', 'product_id', and 'quantity'
    $userId = $_SESSION['id']; // Make sure to handle sessions properly
    $orderId = getOrderIDFromShoppingCart($userId);

    // Insert data into 'Order_Item'
    $insertOrderItemSql = "INSERT INTO Order_Item (order_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertOrderItemSql);
    $stmt->bind_param('iii', $orderId, $productId, $quantity);
    $stmt->execute();
    $stmt->close();

    // Calculate total_quantity and total_amount
    $updateOrderSql = "UPDATE `Order` SET total_quantity = (SELECT SUM(quantity) FROM Order_Item WHERE order_id = ?), total_amount = (SELECT SUM(quantity * price) FROM Order_Item JOIN Product ON Order_Item.product_id = Product.ID WHERE order_id = ?) WHERE ID = ?";
    $stmt = $conn->prepare($updateOrderSql);
    $stmt->bind_param('iii', $orderId, $orderId, $orderId);
    $stmt->execute();
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Return a response
    $response = ['success' => true, 'message' => 'Product added to cart successfully'];
    echo json_encode($response);
} else {
    // Handle invalid requests
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

// Function to get the order ID from the 'shopping_cart' table
function getOrderIDFromShoppingCart($userId) {
    // Modify this function based on your actual database schema
    include '../config.php';

    // Assuming there's a 'status' column in the 'shopping_cart' table
    $orderSql = "SELECT order_id FROM shopping_cart WHERE user_id = ?";
    $stmt = $conn->prepare($orderSql);

    if ($stmt) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($orderId);
        $stmt->fetch();
        $stmt->close();

        $conn->close();

        return $orderId;
    } else {
        // Handle the case where the SQL query preparation fails
        return null;
    }
}

?>

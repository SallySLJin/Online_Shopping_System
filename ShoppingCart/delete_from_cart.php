<?php
session_start();
include '../config.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you receive the orderId and productId in the request body
    $data = json_decode(file_get_contents("php://input"), true);

    $orderId = $data['orderId'];
    $productId = $data['productId'];
    $quantity = $data['quantity'];

    // Check if the user is logged in
    if (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Delete the item from the Order_Item table
        $deleteItemSql = "DELETE FROM Order_Item WHERE order_id = $orderId AND product_id = $productId AND quantity = $quantity LIMIT 1";
        $deleteItemResult = $conn->query($deleteItemSql);

        // Check if the item was successfully deleted
        if ($deleteItemResult) {
            // Update the total quantity and amount in the Order table
            $updateOrderSql = "UPDATE `Order` SET total_quantity = COALESCE((SELECT SUM(quantity) FROM Order_Item WHERE order_id = $orderId), 0), total_amount = COALESCE((SELECT SUM(quantity * COALESCE(price, 0)) FROM Order_Item JOIN Product ON Order_Item.product_id = Product.ID WHERE order_id = $orderId), 0) WHERE id = $orderId";
            $updateOrderResult = $conn->query($updateOrderSql);

            if ($updateOrderResult) {
                // Respond with success
                echo json_encode(['success' => true]);
                exit;
            } else {
                // Respond with an error
                echo json_encode(['error' => 'Error updating order details']);
                exit;
            }
        } else {
            // Respond with an error
            echo json_encode(['error' => 'Error deleting item from cart']);
            exit;
        }
    } else {
        // Respond with an error
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }
} else {
    // Respond with an error for non-POST requests
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}
?>

<?php
// Include your database configuration
include '../config.php';

// Check if the order_id is provided in the GET request
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Fetch order details from Product and Order_Item tables
    $orderDetailsSql = "SELECT p.Name, oi.quantity, p.Price FROM Product p
                        JOIN Order_Item oi ON p.ID = oi.product_id
                        WHERE oi.order_id = ?";
    
    $stmt = $conn->prepare($orderDetailsSql);

    if ($stmt) {
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $orderDetailsResult = $stmt->get_result();
        $stmt->close();

        // Display order details
        if ($orderDetailsResult->num_rows > 0) {
            echo "<h3>Order Details</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Name</th><th>Quantity</th><th>Price</th></tr>";
            while ($orderDetailsRow = $orderDetailsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $orderDetailsRow['Name'] . "</td>";
                echo "<td>" . $orderDetailsRow['quantity'] . "</td>";
                echo "<td>" . $orderDetailsRow['Price'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Display total quantity and total amount from the Order table
            $orderSql = "SELECT * FROM `Order` WHERE id = $orderId";
            $orderResult = $conn->query($orderSql);
            $orderRow = $orderResult->fetch_assoc();
    
            echo "<p>Total Quantity: " . $orderRow['total_quantity'] . "</p>";
            echo "<p>Total Amount: $" . $orderRow['total_amount'] . "</p>";
        } else {
            echo "No details found for the selected order.";
        }
    } else {
        echo "Error preparing order details statement";
    }

    $conn->close();
} else {
    echo "Order ID not provided.";
}
?>
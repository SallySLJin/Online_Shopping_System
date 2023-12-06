<?php
session_start();
include 'config.php';

if (isset($_SESSION['id'])) {
    // Retrieve user's cart data
    $userId = $_SESSION['id'];

    // Get the cart information from the Shopping_Cart table
    $cartSql = "SELECT * FROM Shopping_Cart WHERE user_id = $userId";
    $cartResult = $conn->query($cartSql);

    if ($cartResult->num_rows > 0) {
        // Display user information and cart contents
        $userSql = "SELECT * FROM User WHERE id = $userId";
        $userResult = $conn->query($userSql);
        $userRow = $userResult->fetch_assoc();

        echo "<h2>Cart for User: " . $userRow['name'] . "</h2>";

        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th></tr>";

        // Iterate over cart items
        while ($cartRow = $cartResult->fetch_assoc()) {
            if (isset($cartRow['product_id'])) {
                $productId = $cartRow['product_id'];

                // Get product details from the Order_Item table
                $orderItemSql = "SELECT * FROM Order_Item WHERE order_id = (SELECT id FROM `Order` WHERE user_id = $userId AND status = 'in_cart') AND product_id = $productId";
                echo "Debug SQL: $orderItemSql"; // Add this line to debug the SQL query

                $orderItemResult = $conn->query($orderItemSql);

                if ($orderItemResult->num_rows > 0) {
                    $orderItemRow = $orderItemResult->fetch_assoc();
                    echo "<tr>";
                    echo "<td>" . $orderItemRow['product_name'] . "</td>";
                    echo "<td>" . $orderItemRow['quantity'] . "</td>";
                    echo "<td>$" . $orderItemRow['product_price'] . "</td>";
                    echo "</tr>";
                }
            }
        }

        echo "</table>";

        // Display total quantity and total amount from the Order table
        $orderSql = "SELECT * FROM `Order` WHERE user_id = $userId AND status = 'in_cart'";
        $orderResult = $conn->query($orderSql);
        $orderRow = $orderResult->fetch_assoc();

        echo "<p>Total Quantity in Cart: " . $orderRow['total_quantity'] . "</p>";
        echo "<p>Total Amount: $" . $orderRow['total_amount'] . "</p>";
    } else {
        echo "<p>No items in the cart.</p>";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "<p>User not logged in.</p>";
}
?>

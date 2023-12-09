<?php
session_start();
include '../config.php';

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
                
        // Get product details from the Order_Item table
        $orderItemSql = "SELECT OI.*, P.Name, P.Price 
        FROM Order_Item OI
        INNER JOIN Product P ON OI.product_id = P.id
        WHERE OI.order_id IN (SELECT id FROM `Order` WHERE user_id = $userId AND status = 'In Cart')";

        // Execute the query
        $orderItemResult = $conn->query($orderItemSql);

        // Check for errors
        if (!$orderItemResult) {
         echo "Error: " . $conn->error;
        }

        // Display the results
        if ($orderItemResult->num_rows > 0) {
            while ($orderItemRow = $orderItemResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $orderItemRow['Name'] . "</td>";
                echo "<td>" . $orderItemRow['quantity'] . "</td>";
                echo "<td>$" . $orderItemRow['Price'] . "</td>";
                echo "<td><button onclick='deleteItem(\"{$orderItemRow['order_id']}\", \"{$orderItemRow['product_id']}\")'>Delete</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<p>No items in the cart.</p>";
        }

        echo "</table>";

        // Display total quantity and total amount from the Order table
        $orderSql = "SELECT * FROM `Order` WHERE user_id = $userId AND status = 'In Cart'";
        $orderResult = $conn->query($orderSql);
        $orderRow = $orderResult->fetch_assoc();

        echo "<p>Total Quantity in Cart: " . $orderRow['total_quantity'] . "</p>";
        echo "<p>Total Amount: $" . $orderRow['total_amount'] . "</p>";
        // Add the checkout button
        echo "<button onclick='checkout()'>Checkout</button>";
        ?>
        <a href="../index.php">Continue Shopping</a>
        <a href="historical_purchases.php">View historical purchases.</a>
        <?php

    } 

    // Close the database connection
    $conn->close();
} else {
    echo "<p>User not logged in.</p>";
}
?>

<script>
function checkout() {
    // Redirect to the checkout page
    window.location.href = "checkout.php";
}

function deleteItem(orderId, productId) {
    // Implement logic to delete the item from the cart
    // You may use AJAX to send a request to the server to delete the item

    // Example using Fetch API (you may need to adjust based on your backend implementation)
    fetch('delete_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            orderId: orderId,
            productId: productId,
        }),
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response from the server
        console.log('Item deleted from cart:', data);

        // Reload the page to reflect the changes
        location.reload();
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
</script>
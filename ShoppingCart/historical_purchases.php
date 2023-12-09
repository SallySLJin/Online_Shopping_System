<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['name'])) {
    header('Location: /LoginFile/loginpage.php');
    exit();
}

// Include your database configuration
include '../config.php';

// Retrieve historical orders for the user
$userId = $_SESSION['id'];
$historicalOrdersSql = "SELECT * FROM `Historical_Order` WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($historicalOrdersSql);

if ($stmt) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $historicalOrdersResult = $stmt->get_result();
    $stmt->close();

    // Display historical orders
    if ($historicalOrdersResult->num_rows > 0) {
        echo "<h2>Historical Purchases</h2>";
        ?>
        <a href="../index.php">Continue Shopping</a>
        <?php
        echo "<ul>";
        while ($historicalOrderRow = $historicalOrdersResult->fetch_assoc()) {
            echo "<li>";
            echo "<strong>Order ID:</strong> " . $historicalOrderRow['order_id'] . "<br>";
            echo "<strong>Date:</strong> " . $historicalOrderRow['date'] . "<br>";
            
            // Add a span to display order details dynamically
            echo "<span id='orderDetails_" . $historicalOrderRow['order_id'] . "'></span>";
            
            // Add a button to trigger fetching and displaying order details
            echo "<button onclick='showOrderDetails(" . $historicalOrderRow['order_id'] . ")'>View Details</button>";
            
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "No historical purchases found.";
    }

    $conn->close();
} else {
    echo "Error preparing historical orders statement";
}
?>

<script>
// JavaScript function to fetch and display order details
function showOrderDetails(orderId) {
    // AJAX request to fetch order details
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Display the order details in the corresponding span
            document.getElementById('orderDetails_' + orderId).innerHTML = xhr.responseText;
        }
    };

    // Adjust the URL based on your backend endpoint to fetch order details
    xhr.open('GET', 'get_order_details.php?order_id=' + orderId, true);
    xhr.send();
}
</script>

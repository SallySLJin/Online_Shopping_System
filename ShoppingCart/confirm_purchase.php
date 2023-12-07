<?php
session_start();

// Include your database configuration
include '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['name'])) {
    header('Location: /LoginFile/loginpage.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h3>Order Confirmation</h3>
</div>

<div class="content">
    <p>Hello, <?php echo $_SESSION['name']; ?>!</p>
    
    <p>Thank you for your order. Your order details are as follows:</p>

    <!-- Display order details 
    <ul>
        <li><strong>Order ID:</strong> <?php echo $orderDetails['order_id']; ?></li>
        <li><strong>Total Amount:</strong> $<?php echo $orderDetails['total_amount']; ?></li>
    </ul>
    -->

    <p>Your order will be processed and shipped soon. If you have any questions, please contact our support team.</p>

    <a href="historical_purchases.php">View historical purchases.</a>

    <p>Thank you for shopping with us!</p>

    <a href="../index.php">Continue Shopping</a>
</div>

</body>
</html>

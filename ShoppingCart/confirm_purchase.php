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
    <title>訂購確認</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h3>訂購確認</h3>
</div>

<div class="content">
    <p>您好， <?php echo $_SESSION['name']; ?>!</p>
    
    <p>感謝您訂購我們的產品。</p>

    <!-- Display order details 
    <ul>
        <li><strong>Order ID:</strong> <?php echo $orderDetails['order_id']; ?></li>
        <li><strong>Total Amount:</strong> $<?php echo $orderDetails['total_amount']; ?></li>
    </ul>
    -->

    <p>我們將即刻開始處理您的訂單。如果您有任何需要，請和我們的團隊聯絡。</p>

    <a href="historical_purchases.php">檢視歷史清單</a>

    <p>再次感謝您的青睞！</p>

    <a href="../index.php">回到首頁</a>
</div>

</body>
</html>

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
    <title>訂購確認 - 資料庫專題-網購系統</title>
    <link rel="stylesheet" type="text/css" href="cart_style.css">
</head>
<body>

<div class="header">
    <h1>已收到您的訂單！</h1>
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

    <button class="others" onclick="window.location.href='historical_purchases.php'">檢視歷史清單</button>


    <p>再次感謝您的青睞！期待您再度光臨！</p>

    <button class="others" onclick="window.location.href='../index.php'">回到首頁</button>

</div>

</body>
</html>

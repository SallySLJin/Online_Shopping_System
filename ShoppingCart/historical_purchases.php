<?php
session_start();

?>

<head>
    <title>歷史清單 - 資料庫專題-網購系統</title>
    <link rel="stylesheet" type="text/css" href="cart_style.css">
</head>

<?php
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
        ?>
        <div class="header">
            <h1><?php echo "歷史訂購清單"?></h1>
        </div>
        <?php
        
        ?>
        <button class="others" onclick="window.location.href='../index.php'">回到首頁</button>
        <?php
        echo "<ul>";
        while ($historicalOrderRow = $historicalOrdersResult->fetch_assoc()) {
            echo "<li>";
            echo "<p style='text-align: center;'>訂單ID編號：" . $historicalOrderRow['order_id'] . "</p>";
            echo "<p style='text-align: center;'>訂購日期：" . $historicalOrderRow['date'] . "</p>";
            
            // Add a span to display order details dynamically
            echo "<span id='orderDetails_" . $historicalOrderRow['order_id'] . "'></span>";
            // Add a button to trigger fetching and displaying order details
            echo '<button class="detail" onclick="showOrderDetails(' . $historicalOrderRow['order_id'] . ')">查看細項</button>';
            
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "無歷史紀錄";
    }

    $conn->close();
} else {
    echo "發生錯誤！";
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

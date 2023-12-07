<?php
session_start();
include "../config.php";
$sql = "SELECT * FROM cartlist";//選取cartlist資料庫

// 假設購物車資料存在於 $_SESSION['cart'] 中
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

//go to cart按鈕按下去瞬間可以連結到cartlist

// 假設有一個函數用於計算購物車中商品的總價格
function calculateTotalPrice($cart) {
    $totalPrice = 0;
    foreach ($cart as $item) {
        $totalPrice += $item['quantity'] * $item['price'];
    }
    return $totalPrice;
}


// 在實際的應用中，你可能需要處理更多的結帳邏輯，例如支付、更新庫存等
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結帳頁面</title>
    <form action="cartlist.php" method="post">
    <?php if(isset($_GET['error'])) { ?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>訂單編號：</label>
        <?php 
        echo $row[$Purchase_item];//print cartlist資料庫中的Purchase_item
        ?>
        <label>訂單日期：</label>
        <?php 
        echo $row[$Purchase_unit_price];//print cartlist資料庫中的Purchase_unit_price
        ?>
        <label>訂單金額：</label>
        <?php
        echo $row[$Purchase_quantity];//print cartlist資料庫中的Purchase_quantity
        ?>
        <?php
        echo $row[$Purchase_total_price	];//print cartlist資料庫中的Purchase_total_price
        ?>
    </form>
    <!-- 這裡可以加入其他所需的 CSS 樣式 -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }


        h1 {
            text-align: center;
        }


        .cart-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }


        ul {
            list-style-type: none;
            padding: 0;
        }


        li {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }


        .total-price {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>


<h1>結帳</h1>


<div class="cart-container">
    <h2>購物車內容</h2>
    <ul>
        <?php foreach ($cart as $item): ?>
            <li>
                <strong><?php echo $item['name']; ?></strong>
                (數量: <?php echo $item['quantity']; ?>)
                - 價格: $<?php echo $item['price']; ?> each
            </li>
        <?php endforeach; ?>
    </ul>


    <div class="total-price">
        總價格: $<?php echo calculateTotalPrice($cart); ?>
    </div>


    <!-- 在實際應用中，你可能需要加入更多的結帳相關的表單，例如送貨地址、支付方式等 -->


    <form action="process_checkout.php" method="post">
        <!-- 加入其他結帳相關的表單元素 -->


        <button type="submit">確認結帳</button>
    </form>
</div>


</body>
</html>





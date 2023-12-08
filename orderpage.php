<!DOCTYPE html>
<html>
<head>
    <title>網購歷史訂單紀錄</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="orderhistory.php" method="post">
        <h2>消費歷史訂單</h2>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>訂單編號：</label>
        <?php 
        echo $row["order_number"];//print orderhistory資料庫中的order_number(有問題)
        ?>
        <label>訂單日期：</label>
        <?php 
        echo $row["order_date"];//print orderhistory資料庫中的order_date(有問題)
        ?>
        <label>訂單金額：</label>
        <?php
        echo $row["Purchase_total_price"];//print cartlist資料庫中的Purchase_total_price(有問題)
        ?>
    </form>
</body>
</html>
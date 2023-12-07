<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>會員資料填寫</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="information.php" method="post">
        <h2>會員資料</h2>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>請輸入本人名稱</label>
        <input type="text" name="order_name" placeholder="order_name"><br>
        <label>請輸入手機號碼</label>
        <input type="int" name="phone_number" placeholder="phone_number"><br>
        <label>請輸入收件地址</label>
        <input type="text" name="address" placeholder="address"><br>

        
        <button type="submit">確認</button>
    </form>
</body>
</html>
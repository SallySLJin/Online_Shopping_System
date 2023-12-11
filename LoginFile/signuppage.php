<?php
    session_start();
    include "../config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>註冊 - 資料庫專題-網購系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <h1 style="font-size: 3em;">註冊</h1> 
</div>
    

    <form action="signup.php" method="post">
        
        <?php
            if (isset($_GET['error'])) {
                $errorMessage = $_GET['error'];
                echo '<div class="error-message">' . htmlspecialchars($errorMessage) . '</div>';
            }
        ?>
        <label>請設定使用者名稱</label>
        <input type="text" name="uname" placeholder="User Name"><br>
        <label>請設定密碼</label>
        <input type="password" name="password" placeholder="Password"><br>
        <label>請設定電子信箱</label>
        <input type="text" name="email" placeholder="Email"><br>
        <label>請輸入宅配地址</label>
        <input type="text" name="address" placeholder="Address"><br>
        <p></p>
        <button type="submit">註冊</button>

    </form>
</body>
</html>
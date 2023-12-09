<?php
session_start();

    include "../config.php";
    /*include "function.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $uname = $_POST['uname'];
        $password = $_POST['password'];

        if(empty($uname)){
            header ("Location: signuppage.php?error=請輸入使用者名稱");
            exit();
        }
        else if(empty($password)){
            header ("Location: signuppage.php?error=請輸入密碼");
            exit();
        }
        else if(is_numeric($uname)){
            header ("Location: signuppage.php?error=請輸入包含英文字母的使用者名稱");
            exit();
        }
        else{

        }
    }
    */
?>
<!DOCTYPE html>
<html>
<head>
    <title>註冊 - 資料庫專題-網購系統</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="signup.php" method="post">
        <h1>註冊</h1>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>請設定使用者名稱</label>
        <input type="text" name="uname" placeholder="User Name"><br>
        <label>請設定密碼</label>
        <input type="password" name="password" placeholder="Password"><br>
        <label>請設定電子信箱</label>
        <input type="text" name="email" placeholder="Email"><br>

        <button type="submit">註冊</button>

    </form>
</body>
</html>
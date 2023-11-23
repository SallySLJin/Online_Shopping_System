<!DOCTYPE html>
<html>
<head>
    <title>登入 - 資料庫專題-網購系統</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="login.php" method="post">
        <h2>登入</h2>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error"> <?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>使用者名稱</label>
        <input type="text" name="uname" placeholder="User Name"><br>
        <label>密碼</label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit">登入</button>
    </form>
</body>
</html>
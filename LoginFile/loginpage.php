<?php
session_start();
include "../config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 登入 - 資料庫專題 - 網購系統</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1 style="font-size: 3em;">登入</h1>

    <div class="navigation">
        <?php
        if(isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
            header("Location: ../index.php");
        }
        ?>
    </div>
</div>

<form action="login.php" method="post">
        <?php
            if (isset($_GET['error'])) {
                $errorMessage = $_GET['error'];
                echo '<div class="error-message">' . htmlspecialchars($errorMessage) . '</div>';
            }
        ?>
        <label>使用者名稱</label>
        <input type="text" name="uname" placeholder="User Name"><br>
        <label>密碼</label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit">登入</button>
        <p></p>
        <p></p>
        <p>還沒有帳號嗎？</p>
        <a href=signuppage.php>立即註冊</a>
    </form>

<style>
  table {
    width: 100vw;
    padding: 20px;
    
  }

  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
</style>
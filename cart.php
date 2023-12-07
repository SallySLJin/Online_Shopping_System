<?php
session_start();
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1>Shopping Cart</h1>
    <?php
    if(isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    ?>
        <p id = user_id_style>使用者:<?php echo $_SESSION['user_name']; ?></p>
    <?php
    }
    else{
        ?>
        <p id = user_id_style>目前未登入</p>
    <?php
    }
    ?>
    
    <div class="navigation">
        <?php
        if(isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
        ?>
            <a href="/LoginFile/logout.php">Logout</a>
        <?php
        }
        else{
            ?>
            <a href="/LoginFile/signuppage.php">Sign Up</a>
            <a href="/LoginFile/loginpage.php">Login</a>
        <?php
        }
        ?>
    </div>
</div>

<div class="table_container">
    <?php
        $query = "SELECT * FROM `cart_item`";
        $result = mysqli_query($conn, $query);
        $total = 0;
        if(mysqli_num_rows($result) > 0){
    ?>
        <table>
        
        <tr>
            <th>名稱</th>
            <!-- <th>圖片</th> -->
            <th>價格</th>
            <th>數量</th>
            <th>總價</th>
            <th>移除商品</th>
        </tr>
        
        <?php
            while($row = mysqli_fetch_array($result)){
                ?>
                <tr>
                    <td><?php echo $row["Name"];?></td>
                    <!-- <td><img src="img/{$row['image']}" alt=""></td> -->
                    <td><?php echo $row["Price"];?></td>
                    <td><?php echo $row["Quantity"];?></td>
                    <td><?php echo number_format($row["Price"] * $row["Quantity"],2);?></td>
                    <td><a href="cart.php?action=delete&name=<?php echo $row["Name"];?>"><span>移除</span></a></td>
                    <?php
                    $total += ($row["Quantity"] * $row["Price"]);
                    ?>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td><?php echo number_format($total, 2);?></td>
                        <td><button>結帳</button></td>
                    </tr>
                    <?php
                
            }
        }
        else{
            echo "<div style='text-align: center; font-size: 24px;'>";
            echo "尚未添加任何商品至購物車！";
            echo "</div>";
        }
        ?>
        
    </table>

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
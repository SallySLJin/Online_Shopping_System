<?php
session_start();
include "config.php";
$sql = "SELECT * FROM orderhistory where order_name='$order_name' AND order_date='$order_date'";
//選取orderhistory資料庫
$sql = "SELECT * FROM cartlist where Purchase_total_price='$Purchase_total_price'";
//選取cartlist資料庫

if(isset($_POST['order_name']) && isset($_POST['order_date']) && isset($_POST['Purchase_total_price'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

$result = mysqli_query($conn, $sql);


?>
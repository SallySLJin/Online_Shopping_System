<?php
session_start();
include "../config.php";
$sql = "SELECT * FROM order_information";

if(isset($_POST['order_name']) && isset($_POST['phone_number']) && isset($_POST['address'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

$order_name = validate($_POST['order_name']);
$phone_number = validate($_POST['phone_number']);
$address = validate($_POST['address']);

if(empty($order_name)){
    header ("Location: informationpage.php?error=請輸入本人名稱");
    exit();
}
else if(empty($phone_number)){
    header ("Location: informationpage.php?error=請輸入手機號碼");
    exit();
}
else if(empty($address)){
    header ("Location: informationpage.php?error=請輸入收件地址");
    exit();
}

else{
    $queryCheck = "SELECT COUNT(*) as count FROM `order_information` WHERE `order_name` = '$order_name'";
    $resultCheck = mysqli_query($conn, $queryCheck);//連結資料庫(有問題)

    if ($resultCheck){
        $row = mysqli_fetch_assoc($resultCheck);
        if ($row['count'] > 0){
            //建立插入資料的sql查詢
            $query = "insert into order_information (order_name, phone_number, address) values('$order_name', '$phone_number', '$address')";

            mysqli_query($conn, $query);//執行sql查詢

            header("Location: index.php");//返回主頁
            die;
        }
        else{
            header ("Location: index.php");//返回主頁
            exit();
        }
    }
}
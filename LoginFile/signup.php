<?php
session_start();
include "../config.php";

if (isset($_POST['uname']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['address'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

$uname = validate($_POST['uname']);
$pass = validate($_POST['password']);
$mail = validate($_POST['email']);
$adrs = validate($_POST['address']);

if(empty($uname)){
    header("Location: signuppage.php?error=" . urlencode("請輸入使用者名稱！"));
    exit();
}
else if(empty($pass)){
    header ("Location: signuppage.php?error=" . urlencode("請輸入密碼！"));
    exit();
}
else if(is_numeric($uname)){
    header ("Location: signuppage.php?error=" . urlencode("請輸入含有英文字母的名稱！"));
    exit();
}
else if (empty($mail)) {
    header("Location: signuppage.php?error=" . urlencode("請輸入電子信箱！"));
    exit();
}
else if (empty($adrs)) {
    header("Location: signuppage.php?error=" . urlencode("請輸入宅配地址！"));
    exit();
}
else{
    $queryCheck = "SELECT COUNT(*) as count FROM `User` WHERE `name` = '$uname'";
    $resultCheck = mysqli_query($conn, $queryCheck);

    if ($resultCheck) {
        $row = mysqli_fetch_assoc($resultCheck);
        if ($row['count'] == 0) {
            $query = "insert into User (name, password, email, address) values('$uname', '$pass', '$mail', '$adrs')";
            mysqli_query($conn, $query);
            header("Location: loginpage.php");
            die;
        } else {
            header("Location: signuppage.php?error=該使用者名稱已存在");
            exit();
        }
    }
}
    
/*
    function random_num($length){
        $text = "";
        if($length < 5){
            $length = 5;
        }
        $len = rand(4,$length);

        for($i = 0;$i < $len;$i++){
            $text .= rand(0,9);
        }

        return $text;
    }

    //$user_id = random_num(20);
    */
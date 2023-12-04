<?php
session_start();
include "../config.php";

if(isset($_POST['uname']) && isset($_POST['password'])) {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

$uname = validate($_POST['uname']);
$pass = validate($_POST['password']);


if(empty($uname)){
    header ("Location: signuppage.php?error=請輸入使用者名稱");
    exit();
}
else if(empty($pass)){
    header ("Location: signuppage.php?error=請輸入密碼");
    exit();
}
else if(is_numeric($uname)){
    header ("Location: signuppage.php?error=請輸入含有英文字母的名稱");
    exit();
}
else{
    
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

    $query = "insert into user (user_name, password) values('$uname', '$pass')";

    mysqli_query($conn, $query);

    header("Location: loginpage.php");
    die;
}
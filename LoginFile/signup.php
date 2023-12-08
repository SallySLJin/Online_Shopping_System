<?php
session_start();
include "../config.php";

if (isset($_POST['uname']) && isset($_POST['password']) && isset($_POST['email'])) {

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

if (empty($uname)) {
    header("Location: signuppage.php?error=請輸入使用者名稱");
    exit();
} else if (empty($pass)) {
    header("Location: signuppage.php?error=請輸入密碼");
    exit();
} else if (is_numeric($uname)) {
    header("Location: signuppage.php?error=請輸入含有英文字母的名稱");
    exit();
} else if (empty($mail)) {
    header("Location: signuppage.php?error=請輸入電子信箱");
    exit();
} else {
    $queryCheck = "SELECT COUNT(*) as count FROM `User` WHERE `name` = '$uname'";
    $resultCheck = mysqli_query($conn, $queryCheck);

    if ($resultCheck) {
        $row = mysqli_fetch_assoc($resultCheck);
        if ($row['count'] == 0) {
            $query = "insert into User (name, password, email) values('$uname', '$pass', '$mail')";
            mysqli_query($conn, $query);
            header("Location: loginpage.php");
            die;
        } else {
            header("Location: signuppage.php?error=該使用者名稱已存在");
            exit();
        }
    }
}

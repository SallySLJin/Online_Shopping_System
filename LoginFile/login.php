<?php
session_start();
include "../config.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

$uname = validate($_POST['uname']);
$pass = validate($_POST['password']);

if (empty($uname)) {
    header("Location: loginpage.php?error=User Name is required");
    exit();
} else if (empty($pass)) {
    header("Location: loginpage.php?error=Password is required");
    exit();
}

$sql = "SELECT * FROM user WHERE name='$uname' AND password='$pass'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    if ($row['name'] === $uname && $row['password'] === $pass) {
        echo "Logged in!";
        $_SESSION['name'] = $row['name'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['id'] = $row['id'];

        // Check if a tuple with user_id exists in Shopping_Cart
        $checkCartSql = "SELECT * FROM Shopping_Cart WHERE user_id = {$row['id']}";
        $checkCartResult = mysqli_query($conn, $checkCartSql);

        if (mysqli_num_rows($checkCartResult) === 0) {
            // If no tuple, insert into Order and Shopping_Cart
            $insertOrderSql = "INSERT INTO `Order` (user_id, date, total_quantity, total_amount, status) VALUES ({$row['id']}, NOW(), 0, 0, 'In Cart')";
            mysqli_query($conn, $insertOrderSql);

            // Get the order_id of the newly inserted order
            $orderId = mysqli_insert_id($conn);

            $insertShoppingCartSql = "INSERT INTO Shopping_Cart (user_id, order_id) VALUES ({$row['id']}, $orderId)";
            mysqli_query($conn, $insertShoppingCartSql);
        }

        header("Location: ../index.php");
        exit();
    } else {
        header("Location: loginpage.php?error=Incorrect User Name or Password");
        exit();
    }
} else {
    header("Location: loginpage.php");
    exit();
}
?>

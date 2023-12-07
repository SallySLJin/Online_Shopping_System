<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple E-commerce</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h3>!Carrefour</h3>
    <?php
    if(isset($_SESSION['id']) && isset($_SESSION['name'])) {
        include 'config.php';

        $userId = $_SESSION['id']; // Corrected definition of $userId

        // Display total quantity from the Order table
        $orderSql = "SELECT * FROM `Order` WHERE user_id = $userId AND status = 'In Cart'";
        $orderResult = $conn->query($orderSql);
        $orderRow = $orderResult->fetch_assoc();

        echo "<p id = user_id_style>" .  $_SESSION['name'] . "'s Total Quantity in Cart: " . $orderRow['total_quantity'] . "</p>";
        ?>
    <?php
    }
    else{
        ?>
        <p id = user_id_style>目前未登入</p>
    <?php
    }
    ?>
    
    <div class="navigation">
        <a href="?category=生鮮冷凍">生鮮冷凍</a>
        <a href="?category=飲料零食">飲料零食</a>
        <a href="?category=米油沖泡">米油沖泡</a>
        <a href="?category=生活家電">生活家電</a>
        <a href="?category=熱門3C">熱門3C</a>
        <a href="?category=美妝個清">美妝個清</a>
        <a href="?category=嬰童保健">嬰童保健</a>
        <a href="?category=休閒娛樂">休閒娛樂</a>
        <a href="?category=日用生活">日用生活</a>
        <a href="?category=傢俱寢飾">傢俱寢飾</a>
        <a href="?category=服飾鞋包">服飾鞋包</a>
        <?php
        if(isset($_SESSION['id']) && isset($_SESSION['name'])) {
        ?>
            <a href="/LoginFile/logout.php">登出</a>
        <?php
        }
        else{
            ?>
            <a href="/LoginFile/signuppage.php">註冊</a>
            <a href="/LoginFile/loginpage.php">登入</a>
        <?php
        }
        ?>
        
    </div>
</div>

<!-- Cart summary at the bottom of the screen -->
<div id="cartSummary">
    <span id="totalQuantity">Total Quantity in Cart: 0</span>
    <button onclick="redirectToCart()" style="margin-left: auto;">Go to Cart</button>
</div>

<script>
    function redirectToCart() {
        // Add logic to redirect to the cart page
        window.location.href = 'cart.php';
    }
</script>

<form action="" method="get">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .navigation {
            font-size: 18px;
            margin-top: 10px;
        }

        .navigation a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .content {
            padding: 20px;
        }

        .product {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        h3 {
            text-align: left;
            color: #666;
            line-height: 10%; /* Adjust the line height as needed */
        }

        ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        li {
            border: 1px solid #ccc;
            padding: 15px;
            margin: 15px;
            box-sizing: border-box;
            text-align: center;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            width: calc(25% - 30px); /* Initially 4 products per row */
        }

        li:hover {
            transform: scale(1.05);
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        p {
            margin: 0;
            color: #666;
        }

        select {
            margin-bottom: 10px;
            padding: 5px;
            font-size: 16px;
        }

        input[type="submit"], button {
            padding: 10px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover, button:hover {
            background-color: #555;
        }

        #user_id_style{
                position: fixed; /* or absolute, depending on your layout needs */
                top: 5%;
                left: 20%; /* optional, adjust as needed */
                text-align: center;
                color: white;
                text-decoration: none;
                margin: 0 10px;
        }

        /* Cart summary styles */
        #cartSummary {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        #cartSummary button {
            padding: 5px;
            font-size: 14px;
            background-color: #fff;
            color: #333;
            border: none;
            cursor: pointer;
        }

        #cartSummary button:hover {
            background-color: #ddd;
        }
        
    </style>

    <label for="sortOrder">排序:</label>
    <select name="sortOrder" id="sortOrder">
        <option value="name">品名</option>
        <option value="price">價格</option>
    </select>

    <input type="submit" value="Apply Changes">

    <!-- Add a button to switch between two display modes -->
    <button type="button" id="switchViewButton" onclick="switchView()">Switch View</button>
</form>

<ul>
    <?php 
        // Create connection
        include 'config.php';

        // Retrieve products from the database
        $productsPerRow = isset($_GET['productsPerRow']) ? intval($_GET['productsPerRow']) : 4;
        $sortOrder = isset($_GET['sortOrder']) && ($_GET['sortOrder'] === 'name' || $_GET['sortOrder'] === 'price') ? $_GET['sortOrder'] : 'price';

        $sql = "SELECT * FROM Product";

        // Add category filter if a category is selected
        if(isset($_GET['category'])) {
            $categoryFilter = $_GET['category'];
            $sql .= " WHERE Category1 = '$categoryFilter'";
        }

        $sql .= " ORDER BY $sortOrder";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li style='width: calc(" . (100 / $productsPerRow) . "% - 30px);'>";
                echo "<h1 style='font-size: 18px;'>" . $row["Name"] . "</h1>";                        
                echo '<img src="' . $row["Image"] . '" alt="' . $row["Name"] . '">';
                echo "<p style='font-size: 16px;'>價格: $" . $row["Price"] . "</p>";
                echo "<p style='font-size: 14px;'>" . $row["Category1"] . "/" . $row["Category2"];
                if ($row["Category3"] != null) {
                    echo "/" . $row["Category3"];
                }
                if ($row["Category4"] != null) {
                    echo "/" . $row["Category4"];
                }
                echo "</p>";
                echo "<p style='font-size: 14px;'>" . $row["Description"] . "</p>";

                // Quantity controls and Add to Cart button
                echo "<div>";
                echo "<button onclick='updateQuantity(\"$row[ID]\", -1)'>-</button>";
                echo "<span id='quantity_$row[ID]'>" . getQuantityFromLocalStorage($row['ID']) . "</span>";
                echo "<button onclick='updateQuantity(\"$row[ID]\", 1)'>+</button>";
                echo "<button onclick='addToCart(\"$row[ID]\", \"$row[Name]\", $row[Price])'>Add to Cart</button>";
                echo "</div>";

                echo "</li>";
            }
        } else {
            echo "No products available.";
        }

        function getQuantityFromLocalStorage($productId) {
            // Return 0 for now, as local storage is managed on the client-side
            return 0;
        }
        
        $conn->close();
    ?>
</ul>

<script>
    // Use a global variable to store the total quantity of products in the cart
    var totalCartQuantity = parseInt(localStorage.getItem('totalCartQuantity')) || 0;

    function updateCartSummary() {
        // Update the cart summary at the bottom of the page
        var cartSummaryElement = document.getElementById('cartSummary');
        var totalQuantityElement = document.getElementById('totalQuantity');

        if (cartSummaryElement && totalQuantityElement) {
            // Display the total quantity, and move the "Go to Cart" button to the right
            totalQuantityElement.innerHTML = "Total Quantity in Cart: " + totalCartQuantity;
            cartSummaryElement.innerHTML = totalCartQuantity > 0
                ? "<span id='totalQuantity'>Total Quantity in Cart: " + totalCartQuantity + "</span><button onclick='goToCartPage()' style='margin-left: auto;'>Go to Cart</button>"
                : "<span id='totalQuantity'>Total Quantity in Cart: " + totalCartQuantity + "</span><button onclick='goToCartPage()' style='margin-left: auto; visibility: hidden;'>Go to Cart</button>";
        }
    }

    // Use a global variable to track the view mode
    var isGridView = true;

    function switchView() {
        var lis = document.querySelectorAll('li');
        lis.forEach(function (li) {
            // Toggle between view modes
            li.style.width = isGridView ? 'calc(50% - 30px)' : 'calc(25% - 30px)';
        });

        // Toggle the global variable
        isGridView = !isGridView;
    }

    // Use a global variable to store the total quantity of products in the cart
    var totalCartQuantity = parseInt(localStorage.getItem('totalCartQuantity')) || 0;

    function updateQuantity(productId, change) {
        var quantityElement = document.getElementById('quantity_' + productId);
        var currentQuantity = parseInt(quantityElement.innerHTML);
        var newQuantity = currentQuantity + change;

        // Ensure the quantity doesn't go below 0
        newQuantity = Math.max(newQuantity, 0);

        // Update the quantity in local storage
        localStorage.setItem('quantity_' + productId, newQuantity);

        quantityElement.innerHTML = newQuantity;
    }

    function addToCart(productId, productName, productPrice) {
        var quantityElement = document.getElementById('quantity_' + productId);
        var quantity = parseInt(quantityElement.innerHTML);

        // Ensure the quantity is non-negative
        quantity = Math.max(quantity, 0);

        // User is logged in, proceed with adding to cart
        if (quantity > 0) {
            // Display an alert (you can replace this with your actual cart logic)
            alert("Added " + quantity + " " + productName + " to the cart. ");

            // Update the totalCartQuantity variable
            totalCartQuantity += quantity;
            updateCartSummary();
        }
    }

</script>

</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Add a meta tag for better mobile responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>資料庫專題 - 網購系統</title>
    <style>
    #switchButton {
      position: absolute;
      top: 25px; /* 距離頂部的距離，可以根據需要調整 */
      left: 25px; /* 距離右側的距離，可以根據需要調整 */
      padding: 10px;
      font-size: 16px;
      background-color: #333;
      color: white;
      border: none;
      cursor: pointer;
    }

    #cartButton {
      position: absolute;
      top: 25px; /* 距離頂部的距離，可以根據需要調整 */
      right: 50px; /* 距離右側的距離，可以根據需要調整 */
      padding: 10px;
      font-size: 16px;
      background-color: #333;
      color: white;
      border: none;
      cursor: pointer;
    }

    </style>

    <!-- Include the external stylesheet -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h1 style='text-align: center;'>資料庫專題 - 網購系統</h1>
    <?php
    if(isset($_SESSION['id']) && isset($_SESSION['name'])) {
        include 'config.php';

        $userId = $_SESSION['id'];

        // Display total quantity from the Order table
        $orderSql = "SELECT * FROM `Order` WHERE user_id = ? AND status = 'In Cart'";
        $stmt = $conn->prepare($orderSql);

        if ($stmt) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $orderResult = $stmt->get_result();
            $orderRow = $orderResult->fetch_assoc();

            // Wrap the element in a container with a unique ID
            echo "<div id='totalQuantityContainer'>";
            echo "<p style='text-align: center; color: #FFFFFF; font-size: 20px;'>使用者：" .  $_SESSION['name'] . "　當前購物車商品數量：" . $orderRow['total_quantity'] . "</p>";
            
            echo "</div>";

            $stmt->close();
        } else {
            echo "Error preparing statement";
        }
        ?>

        <!-- Cart summary at the bottom of the screen -->
        <div>
            <!-- <span id="totalQuantity">Total Quantity in Cart: 0</span> -->
            <button id="cartButton"
                style='display: block;
                margin: 10px auto;
                text-align: center;
                padding: 8px 16px;
                font-size: 16px;
                text-decoration: none;
                background-color: #ffffff;
                color: #333;
                border: none;
                cursor: pointer;
                width: auto;'
                onclick="redirectToCart()">前往購物車</button>
        </div>
    <?php
    } else {
        ?>
        <p style="color: #FFFFFF; font-size: 20px;">目前未登入</p>
    <?php
    }
    ?>
    <button id="switchButton" style='display: block;
                margin: 10px auto;
                text-align: center;
                padding: 8px 16px;
                font-size: 16px;
                text-decoration: none;
                background-color: #ffffff;
                color: #333;
                border: none;
                cursor: pointer;
                width: auto;' onclick="switchView()">切換介面顯示模式</button>
    
    <div class="navigation">
        <a href="index.php">所有商品</a>
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
        } else {
            ?>
            <a href="/LoginFile/signuppage.php">註冊</a>
            <a href="/LoginFile/loginpage.php">登入</a>
        <?php
        }
        ?>
    </div>

<form action="" method="get">
    <input type="hidden" name="category" value="<?php echo isset($_GET['category']) ? htmlspecialchars($_GET['category']) : ''; ?>">
    <div id="sortOrderContainer">
        <?php if (isset($_GET['category'])): ?>
        <!-- <label for="sortOrder">按照:</label> -->
        <select name="sortOrder" id="sortOrder">
            <option value="name" <?php echo isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'name' ? 'selected' : ''; ?>>品名</option>
            <option value="price" <?php echo isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'price' ? 'selected' : ''; ?>>價格</option>
        </select>

        <input type="submit" value="排序">
        <?php endif; ?>

        <!-- Add a button to switch between two display modes -->
        
    </div>

</form>
</div>

<script>
    // Function to get the quantity of a product from local storage
    function getQuantityFromLocalStorage(productId) {
        // You can implement logic to retrieve the quantity from local storage
        // For now, return 0 as a placeholder
        return parseInt(localStorage.getItem('quantity_' + productId)) || 0;
    }

    // Store PHP session information in a JavaScript variable
    var sessionInfo = {
        id: <?php echo json_encode($_SESSION['id']); ?>,
        name: <?php echo json_encode($_SESSION['name']); ?>
    };

    // Use a global variable to store the total quantity of products in the cart
    var totalCartQuantity = parseInt(localStorage.getItem('totalCartQuantity')) || 0;

    // Function to update the quantity of a product
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

    // Function to add a product to the cart
    function addToCart(productId, productName, productPrice) {
        var quantityElement = document.getElementById('quantity_' + productId);
        var quantity = parseInt(quantityElement.innerHTML);

        // Ensure the quantity is non-negative
        quantity = Math.max(quantity, 0);

        // User is logged in, proceed with adding to cart
        if (quantity > 0) {
            // Display an alert (you can replace this with your actual cart logic)
            alert("放入購物車 " + quantity + " 件 " + productName + " ");

            // Update the totalCartQuantity variable
            totalCartQuantity += quantity;
            updateCartSummary();

            // Log to console for debugging
            console.log('Adding to cart:', {
                productId: productId,
                quantity: quantity,
                productName: productName,
                productPrice: productPrice,
            });

            // Now, you need to perform the backend logic to update the database
            // You may use AJAX to send a request to the server to update the database

            // Example using Fetch API (you may need to adjust based on your backend implementation)
            fetch('/ShoppingCart/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    productId: productId,
                    quantity: quantity,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Handle the response from the server
                console.log('Server response:', data);

                // Update the displayed total quantity dynamically
                var totalQuantityContainer = document.getElementById('totalQuantityContainer');
                if (totalQuantityContainer) {
                    totalQuantityContainer.innerHTML = "<p id='user_id_style' style='text-align: center;'>" +  sessionInfo.name + " </p>";
                // totalQuantityContainer.innerHTML = "<p id='user_id_style'>" +  sessionInfo.name + " ( " + data.totalQuantity + " ) </p>";
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
            location.reload();
        }
    }

    // Function to update the cart summary at the bottom of the page
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

    // Function to switch between grid and list view
    function switchView() {
        var lis = document.querySelectorAll('li');
        lis.forEach(function (li) {
            // Toggle between view modes
            li.style.width = isGridView ? 'calc(50% - 30px)' : 'calc(25% - 30px)';
        });

        // Toggle the global variable
        isGridView = !isGridView;
    }
</script>

<ul>
    <?php 
        // Create connection
        include 'config.php';
        // Retrieve products from the database with pagination
        $productsPerPage = 24;
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($currentPage - 1) * $productsPerPage;

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

        // Calculate total number of pages
        $totalProducts = $result->num_rows;
        $totalPages = ceil($totalProducts / $productsPerPage);

        // Adjust the SQL query to include pagination
        $sql .= " LIMIT $productsPerPage OFFSET $offset";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li style='width: calc(" . (100 / $productsPerRow) . "% - 30px);'>";
                echo "<h1 style='font-size: 18px; color: #000000;'>" . $row["Name"] . "</h1>";                        
                echo '<img src="' . $row["Image"] . '" alt="' . $row["Name"] . '">';
                echo "<p style='font-size: 16px; color: #000000;'>價格: $" . $row["Price"] . "</p>";
                echo "<p style='font-size: 14px; color: #000000;'>" . $row["Category1"] . "/" . $row["Category2"];
                if ($row["Category3"] != null) {
                    echo "<p style='font-size: 14px; color: #000000;'>/" . $row["Category3"] . "</p>";
                }
                if ($row["Category4"] != null) {
                    echo "<p style='font-size: 14px; color: #000000;'>/" . $row["Category4"] . "</p>";
                }
                echo "</p>";
                echo "<p style='font-size: 14px;color: #000000;'>" . $row["Description"] . "</p>";

                // Quantity controls and Add to Cart button
                echo "<div>";
                echo "<button onclick='updateQuantity(\"$row[ID]\", -1)'>-</button>";
                echo "<span id='quantity_$row[ID]'>" . getQuantityFromLocalStorage($row['ID']) . "</span>";
                echo "<button onclick='updateQuantity(\"$row[ID]\", 1)'>+</button>";
                echo "<button onclick='addToCart(\"$row[ID]\", \"$row[Name]\", $row[Price])' id='addToCartButton_$row[ID]'>放入購物車</button>";
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

<!-- Add pagination links -->
<div id="paginationContainer">
    <span>第 <?php echo $currentPage; ?> 頁／共 <?php echo $totalPages; ?> 頁數</span>
    <?php if ($totalPages > 1): ?>
        <?php for ($page = 1; $page <= $totalPages; $page++): ?>
            <a href="?page=<?php echo $page; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>&sortOrder=<?php echo isset($_GET['sortOrder']) ? $_GET['sortOrder'] : ''; ?>&productsPerRow=<?php echo isset($_GET['productsPerRow']) ? $_GET['productsPerRow'] : 4; ?>"><?php echo $page; ?></a>
        <?php endfor; ?>
    <?php endif; ?>
</div>

<script>
    function redirectToCart() {
        // Add logic to redirect to the cart page
        window.location.href = '/ShoppingCart/cart.php';
    }
</script>

</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Add a meta tag for better mobile responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Simple E-commerce</title>
   
    <!-- Include the external stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h3>Carrefour</h3>
    <form action="search.php" method="get" class="search-form">
        <input type="text" name="query" placeholder="Search...">
        <button type="submit">Search</button>
    </form>
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
            echo "<p id='user_id_style'>" .  $_SESSION['name'] . "'s Total Quantity in Cart: " . $orderRow['total_quantity'] . " ( Refresh page to update. )</p>";
            echo "</div>";

            $stmt->close();
        } else {
            echo "Error preparing statement";
        }
        ?>

        <!-- Cart summary at the bottom of the screen -->
        <div id="cartSummary">
            <!-- <span id="totalQuantity">Total Quantity in Cart: 0</span> -->
            <button onclick="redirectToCart()" style="margin-left: auto;">Go to Cart</button>
        </div>
    <?php
    } else {
        ?>
        <p id="user_id_style">目前未登入</p>
    <?php
    }
    ?>
    
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
</div>

<form action="" method="get">

    <div id="sortOrderContainer">
        <label for="sortOrder">排序:</label>
        <select name="sortOrder" id="sortOrder">
            <option value="name">品名</option>
            <option value="price">價格</option>
        </select>

        <input type="submit" value="Apply Changes">
        <!-- Add a button to switch between two display modes -->
        <button type="button" id="switchViewButton" onclick="switchView()">Switch View</button>
    </div>

</form>

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
            alert("Added " + quantity + " " + productName + " to the cart.");

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
                    totalQuantityContainer.innerHTML = "<p id='user_id_style'>" +  sessionInfo.name + "'s Total Quantity in Cart: " + data.totalQuantity + "</p>";
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
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
                echo "<button onclick='addToCart(\"$row[ID]\", \"$row[Name]\", $row[Price])' id='addToCartButton_$row[ID]'>Add to Cart</button>";
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
    function redirectToCart() {
        // Add logic to redirect to the cart page
        window.location.href = '/ShoppingCart/cart.php';
    }
</script>

</body>
</html>

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
    <h1>Simple E-commerce</h1>
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
        <a href="?category=好康主題">好康主題</a>
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

<form action="" method="get">
    <style>
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
                color: white;
                text-decoration: none;
                margin: 0 10px;
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
                echo "</li>";
            }
        } else {
            echo "No products available.";
        }
        
        $conn->close();
    ?>
</ul>

<script>
    // Use a global variable to track the view mode
    var isGridView = true;

    function switchView() {
        var lis = document.querySelectorAll('li');
        lis.forEach(function(li) {
            // Toggle between view modes
            li.style.width = isGridView ? 'calc(50% - 30px)' : 'calc(25% - 30px)';
        });

        // Toggle the global variable
        isGridView = !isGridView;
    }
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple E-commerce</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add the styles from 'products.php' here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1, h2 {
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
    </style>
</head>
<body>

<div class="header">
    <h1>Simple E-commerce</h1>
    <div class="navigation">
        <a href="index.php">All Products</a>
        <a href="?category=electronics">Electronics</a>
        <a href="?category=clothing">Clothing</a>
        <a href="?category=books">Books</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="content">
    <h1>Welcome to Our Online Store</h1>
    <h2>Product Page</h2>

    <form action="" method="get">
        <!-- 
        <label for="productsPerRow">Products per Row:</label>
        <select name="productsPerRow" id="productsPerRow">
            <option value="1">1</option>
            <option value="4" selected>4</option>
        </select>
         -->

        <label for="sortOrder">Sort Order:</label>
        <select name="sortOrder" id="sortOrder">
            <option value="name">品名</option>
            <option value="price">價格</option>
            <option value="category">類別</option>
        </select>

        <input type="submit" value="Apply Changes">

        <!-- Add a button to switch between two display modes -->
        <button type="button" id="switchViewButton" onclick="switchView()">Switch View</button>
    </form>

    <ul>
        <?php 
            // Include your product data or connect to a database here
            $products = [
                ['name' => 'Laptop', 'category' => 'electronics', 'price' => 800, 'image' => 'laptop.jpg', 'description' => 'High-performance laptop'],
                ['name' => 'T-shirt', 'category' => 'clothing', 'price' => 20, 'image' => 'tshirt.jpg', 'description' => 'Comfortable cotton T-shirt'],
                ['name' => 'Book', 'category' => 'books', 'price' => 15, 'image' => 'book.jpg', 'description' => 'Bestseller book'],
                // Add more products as needed
            ];

            // Filter products based on the selected category
            $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';
            if ($selectedCategory !== 'all') {
                $products = array_filter($products, function ($product) use ($selectedCategory) {
                    return $product['category'] === $selectedCategory;
                });
            }

            // Display products
            foreach ($products as $product) {
                echo "<li style='width: calc(" . (100 / 4) . "% - 30px);'>";
                echo "<h1 style='font-size: 18px;'>" . $product['name'] . "</h1>";                        
                echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                echo "<p style='font-size: 16px;'>價格: $" . $product['price'] . "</p>";
                echo "<p style='font-size: 14px;'>" . $product['category'] . "</p>";
                echo "<p style='font-size: 14px;'>" . $product['description'] . "</p>";
                echo "</li>";
            }
        ?>
    </ul>

    <script>
        function switchView() {
            var lis = document.querySelectorAll('li');
            lis.forEach(function(li) {
                li.style.width = li.style.width === 'calc(100% - 30px)' ? 'calc(25% - 30px)' : 'calc(100% - 30px)';
            });
        }
    </script>
</div>

</body>
</html>

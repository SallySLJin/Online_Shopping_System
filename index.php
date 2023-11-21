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
    <div class="navigation">
        <a href="products.php">All Products</a>
        <a href="?category=electronics">Electronics</a>
        <a href="?category=clothing">Clothing</a>
        <a href="?category=books">Books</a>
        <a href="login.php">Login</a>
    </div>
</div>

<div class="content">
    <?php
    // Include your product data or connect to a database here
    $products = [
        ['name' => 'Laptop', 'category' => 'electronics'],
        ['name' => 'T-shirt', 'category' => 'clothing'],
        ['name' => 'Book', 'category' => 'books'],
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
        echo '<div class="product">' . $product['name'] . '</div>';
    }
    ?>
</div>

</body>
</html>

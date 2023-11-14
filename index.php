<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shopping System Project</title>
</head>
<body>
    <h1>Welcome to Our Online Store</h1>
    <h2>All Product</h2>
        <ul>
            <?php 
                // Create connection
                include 'connect.php'
            ?>

            <?php
                // Retrieve products from the database
                $sql = "SELECT * FROM Product";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "<h1>" . $row["Name"] . "</h2>";                        
                        // echo $row["Image"];
                        echo '<img src="' . $row["Image"] . '" alt="' . $row["name"] . '">';
                        echo "<p>Price: $" . $row["Price"] . "</p>";
                        echo "<p>Category: " . $row["Category"] . "</p>";
                        echo "<p>Description: " . $row["Description"] . "</p>";
                        echo "<br>";                    
                        echo "</li>";
                    }
                } else {
                    echo "No products available.";
                }
                
                $conn->close();
            ?>
        </ul>
</body>
</html>
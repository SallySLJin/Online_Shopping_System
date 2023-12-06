import mysql.connector
# import csv

# Replace these with your actual database connection details
host = "localhost"
user = "phpmyadmin"
password = "15"
database = "Online_Shopping_System"

try:
    # Establish a connection to the MySQL server
    conn = mysql.connector.connect(host=host, user=user, password=password)

    # Create a cursor object to interact with the database
    cursor = conn.cursor()

    # Create the database if it doesn't exist
    create_database_query = f"CREATE DATABASE IF NOT EXISTS {database};"
    cursor.execute(create_database_query)

    # Use the specified database
    cursor.execute(f"USE {database};")

    # Define the SQL query to create the table 'User'
    create_user_table_query = """
    CREATE TABLE IF NOT EXISTS `User` (
        `id` BIGINT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(20) NOT NULL,
        `password` VARCHAR(20) NOT NULL,
        `email` VARCHAR(320) NOT NULL,
        `date` TIMESTAMP NOT NULL,
        PRIMARY KEY (`ID`)
    ) ENGINE = InnoDB;
    """

    # Execute the query to create the 'User' table
    cursor.execute(create_user_table_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to create the 'Product' table
    # without foreign keys
    create_table_query = """
    CREATE TABLE IF NOT EXISTS `Product` (
        `ID` CHAR(50) NOT NULL,
        `Name` TEXT NOT NULL,
        `Price` INT NOT NULL,
        `Category1` TEXT NOT NULL,
        `Category2` TEXT NOT NULL,
        `Category3` TEXT NOT NULL,
        `Category4` TEXT NOT NULL,
        `Image` TEXT NOT NULL,
        `Description` TEXT NOT NULL,
        PRIMARY KEY (`ID`(50))
    ) ENGINE = InnoDB;
    """

    # Execute the query to create the 'Product' table
    cursor.execute(create_table_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to create the 'Order_Item' table
    # without foreign keys
    create_order_item_query = """
    CREATE TABLE IF NOT EXISTS `Order_Item` (
        `id` BIGINT NOT NULL AUTO_INCREMENT,
        `order_id` BIGINT NOT NULL,
        `product_id` CHAR(50) NOT NULL,
        `quantity` INT NOT NULL,
        `unit_price` INT NOT NULL,
        `subtotal` INT NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;
    """

    # Execute the query to create the 'Order_Item' table
    cursor.execute(create_order_item_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to create the 'Order' table
    # without foreign keys
    create_order_query = """
    CREATE TABLE IF NOT EXISTS `Order` (
        `id` BIGINT NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT NOT NULL,
        `item_id` BIGINT NOT NULL,
        `date` TIMESTAMP NOT NULL,
        `total_amount` INT NOT NULL,
        `status` VARCHAR(50) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;
    """

    # Execute the query to create the 'Order' table
    cursor.execute(create_order_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to create the 'Historical_Order' table
    # without foreign keys
    create_historical_order_query = """
    CREATE TABLE IF NOT EXISTS `Historical_Order` (
        `user_id` BIGINT NOT NULL,
        `order_id` BIGINT NOT NULL,
        `date` TIMESTAMP NOT NULL
    ) ENGINE = InnoDB;
    """

    # Execute the query to create the 'Historical_Order' table
    cursor.execute(create_historical_order_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to create the 'Shopping_Cart' table
    # without foreign keys
    create_shopping_cart_query = """
    CREATE TABLE IF NOT EXISTS `Shopping_Cart` (
        `user_id` BIGINT NOT NULL,
        `order_id` BIGINT NOT NULL
    ) ENGINE = InnoDB;
    """

    # Execute the query to create the 'Shopping_Cart' table
    cursor.execute(create_shopping_cart_query)

    # Commit the changes
    conn.commit()

    # Enable foreign key checks
    cursor.execute("SET FOREIGN_KEY_CHECKS=0;")

    # Define the SQL query to alter the 'Order_Item' table
    # and add foreign keys with CASCADE
    alter_order_item_query = """
    ALTER TABLE `Order_Item`
    ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`)
    REFERENCES `Order`(`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_order_item_product` FOREIGN KEY (`product_id`)
    REFERENCES `Product`(`id`) ON DELETE CASCADE;
    """

    cursor.execute(alter_order_item_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to alter the 'Order' table
    # and add foreign keys with CASCADE
    alter_order_query = """
    ALTER TABLE `Order`
    ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`)
    REFERENCES `User`(`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_order_orderitem` FOREIGN KEY (`item_id`)
    REFERENCES `OrderItem`(`id`) ON DELETE CASCADE;
    """

    cursor.execute(alter_order_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to alter the 'Historical_Order' table
    # and add foreign keys with CASCADE
    alter_historical_order_query = """
    ALTER TABLE `Historical_Order`
    ADD CONSTRAINT `fk_historical_order_user` FOREIGN KEY (`user_id`)
    REFERENCES `User`(`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_historical_order_order` FOREIGN KEY (`order_id`)
    REFERENCES `Order`(`id`) ON DELETE CASCADE;
    """

    cursor.execute(alter_historical_order_query)

    # Commit the changes
    conn.commit()

    # Define the SQL query to alter the 'Shopping_Cart' table
    # and add foreign keys with CASCADE
    alter_shopping_cart_query = """
    ALTER TABLE `Shopping_Cart`
    ADD CONSTRAINT `fk_shopping_cart_user` FOREIGN KEY (`user_id`)
    REFERENCES `User`(`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_shopping_cart_order` FOREIGN KEY (`order_id`)
    REFERENCES `Order`(`id`) ON DELETE CASCADE;
    """

    cursor.execute(alter_shopping_cart_query)

    # Commit the changes
    conn.commit()

    # Enable foreign key checks
    cursor.execute("SET FOREIGN_KEY_CHECKS=1;")

    # Load data from scraped_data.csv into table 'Product'
    csv_file_path = "C:/xampp/htdocs/scraper/scraped_data.csv"
    load_data_query = f"""
    LOAD DATA INFILE '{csv_file_path}'
    REPLACE INTO TABLE `Product`
    FIELDS TERMINATED BY ','
    ENCLOSED BY '"'
    LINES TERMINATED BY '\\n'
    IGNORE 1 ROWS
    (`ID`, `Name`, `Price`, `Category1`, `Category2`, `Category3`,
    `Category4`, `Image`, `Description`)
    """

    cursor.execute(load_data_query)
    conn.commit()

    # Delete rows with empty or whitespace-only ID
    delete_query = """
    DELETE FROM `Product` WHERE `product`.`ID` = '\r';
    """

    cursor.execute(delete_query)
    conn.commit()  # Commit the changes

except mysql.connector.Error as err:
    print(f"Error: {err}")

finally:
    # Close the cursor and connection if they are open
    if 'cursor' in locals() and cursor is not None:
        cursor.close()
    if 'conn' in locals() and conn is not None:
        conn.close()

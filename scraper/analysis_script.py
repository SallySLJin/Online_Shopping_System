import mysql.connector
# import csv

# Replace these with your actual database connection details
host = "localhost"
user = "phpmyadmin"
password = "15"
database = "Online_Shopping_System"

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
    `user_name` VARCHAR(20) NOT NULL,
    `password` VARCHAR(20) NOT NULL,
    `date` TIMESTAMP NOT NULL,
    PRIMARY KEY (`ID`)) ENGINE = InnoDB;
"""

# Execute the query to create the 'User' table
cursor.execute(create_user_table_query)

# Insert data into the 'user' table
insert_user_query = """
INSERT INTO `User` (`user_name`, `password`)
VALUES (%s, %s);
"""

# Sample data for insertion
user_data = [
    ('tim0406', '1234'),
    ('amy0305', 'abcd')
]

# Insert data into the 'User' table
cursor.executemany(insert_user_query, user_data)

# Commit the changes
conn.commit()


# Define the SQL query to create the table 'Product'
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

# Execute the query to create the table
cursor.execute(create_table_query)

# Commit the changes
conn.commit()


try:

    # Load data from scraped_data.csv into table 'Product'
    csv_file_path = "/scraper/scrape_data.py"
    load_data_query = f"""
    LOAD DATA LOCAL INFILE '{csv_file_path}'
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


# Close the cursor and connection
cursor.close()
conn.close()

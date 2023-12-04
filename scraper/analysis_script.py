import mysql.connector
import csv

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

# Define the SQL query to create the table 'Prodect'
create_table_query = """
CREATE TABLE IF NOT EXISTS `Product` (
    `ID` CHAR(50) NOT NULL,
    `Name` TEXT NOT NULL,
    `Price` INT NOT NULL,
    `Category` TEXT NOT NULL,
    `Image` TEXT NOT NULL,
    `Description` TEXT NOT NULL,
    PRIMARY KEY (`ID`(50))
) ENGINE = InnoDB;
"""

# Execute the query to create the table
cursor.execute(create_table_query)

# Commit the changes
conn.commit()

# Insert data from scraped_data.csv into table 'Product'
csv_file_path = "scraped_data.csv"
insert_query = """
INSERT INTO `Product` (
    `ID`, `Name`, `Price`, `Category`, `Image`, `Description`)
VALUES (%s, %s, %s, %s, %s, %s)
ON DUPLICATE KEY UPDATE
    `Name` = VALUES(`Name`),
    `Price` = VALUES(`Price`),
    `Category` = VALUES(`Category`),
    `Image` = VALUES(`Image`),
    `Description` = VALUES(`Description`);
"""

with open(csv_file_path, "r", encoding="utf-8") as csvfile:
    csvreader = csv.reader(csvfile)
    next(csvreader)  # Skip header row

    for row in csvreader:
        data_tuple = tuple(row)
        cursor.execute(insert_query, data_tuple)


# Define the SQL query to create the table 'user'
create_user_table_query = """
CREATE TABLE IF NOT EXISTS `user` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(20) NOT NULL,
    `email` VARCHAR(60) NOT NULL,
    `password` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
"""

# Execute the query to create the 'user' table
cursor.execute(create_user_table_query)

# Insert data into the 'user' table
insert_user_query = """
INSERT INTO `user` (`user_name`, `email`, `password`)
VALUES (%s, %s, %s);
"""

# Sample data for insertion
user_data = [
    ('tim0406', 'tim@example.com', '1234'),
    ('amy0305', 'amy@example.com', 'abcd')
]

# Insert data into the 'user' table
cursor.executemany(insert_user_query, user_data)

# Commit the changes
conn.commit()

# Close the cursor and connection
cursor.close()
conn.close()

# ChatGPT work

import csv
import mysql.connector

# MySQL database configuration
db_config = {
    'host': 'your_database_host',
    'user': 'your_database_username',
    'password': 'your_database_password',
    'database': 'your_database_name',
}

# Open CSV file for reading
csv_file_path = 'data/scraped_data.csv'

# Connect to the MySQL database
try:
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()

    # Create a table if not exists
    create_table_query = '''
        CREATE TABLE IF NOT EXISTS your_table_name (
            column1_type DATATYPE,
            column2_type DATATYPE,
            -- Add more columns as needed
        );
    '''
    cursor.execute(create_table_query)

    # Read and insert data from CSV into MySQL
    with open(csv_file_path, 'r', encoding='utf-8') as csv_file:
        csv_reader = csv.reader(csv_file)
        header = next(csv_reader)  # Skip the header row

        # Prepare the INSERT query
        insert_query = f'''
            INSERT INTO your_table_name ({', '.join(header)})
            VALUES ({', '.join(['%s'] * len(header))});
        '''

        # Insert data row by row
        for row in csv_reader:
            cursor.execute(insert_query, row)

    # Commit changes
    connection.commit()

except mysql.connector.Error as err:
    print(f"Error: {err}")

finally:
    # Close the database connection
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection is closed")

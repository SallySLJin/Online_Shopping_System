<?php 
    // Create connection
    $server = "localhost";
    $user = "phpmyadmin";
    $password = "15";
    $database = "Online_Shopping_System";

    $conn = mysqli_connect($server, $user, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    /*
    CREATE TABLE `Online_Shopping_System`.`Product` ( `ID` CHAR(50) NOT NULL , `Name` TEXT NOT NULL , `Price` INT NOT NULL , `Category` TEXT NOT NULL , `Image` TEXT NOT NULL , `Description` TEXT NOT NULL , PRIMARY KEY (`ID`(50))) ENGINE = InnoDB;
    */
?>
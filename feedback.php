<?php
// Enable error reporting to display errors for debugging
error_reporting(E_ALL);   // Report all PHP errors
ini_set('display_errors', 1);  // Show errors in the browser

// Database connection details
$server_name = "localhost";
$username = "root";
$password = "root";

// Establish connection to the MySQL server
$connection = mysqli_connect($server_name, $username, $password);

// Check if connection is successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connection established successfully.<br>";

// if user clicks the submit button
if (isset($_POST['submit_feedback'])) {
    // Get form data
    $name = mysqli_real_escape_string($connection, $_POST['user_name']);
    $email = mysqli_real_escape_string($connection, $_POST['user_email']);
    $phone = mysqli_real_escape_string($connection, $_POST['user_phone']);
    $rating = $_POST['user_rating'];
    $suggestion = mysqli_real_escape_string($connection, $_POST['user_suggestion']);

    // Database name
    $db_name = "feedback";

    // Create database if it doesn't exist
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
    if (mysqli_query($connection, $sql_create_db)) {
        echo "Database '$db_name' created successfully.<br>";
    } else {
        echo "Error creating database: " . mysqli_error($connection) . "<br>";
    }

    // Select the database to work with
    mysqli_select_db($connection, $db_name);

    // Table name
    $table = "feedback_data";

    // Create table if it doesn't exist
    $sql_create_table = "CREATE TABLE IF NOT EXISTS $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        rating INT NOT NULL,
        suggestion TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (mysqli_query($connection, $sql_create_table)) {
        echo "Table '$table' created successfully.<br>";
    } else {
        echo "Error creating table: " . mysqli_error($connection) . "<br>";
    }

    // Insert user data into the table
    $sql_insert = "INSERT INTO $table (name, email, phone, rating, suggestion) 
                   VALUES ('$name', '$email', '$phone', '$rating', '$suggestion')";

    if (mysqli_query($connection, $sql_insert)) {
        // On successful insertion, show alert 
        echo "<script>
                alert('Thank you for your feedback! Your suggestion is valuable for us.');
                window.location.href='projectindex.html';
              </script>";
    } else {
        echo "Error inserting data: " . mysqli_error($connection) . "<br>";
    }
}

// Close the database connection
mysqli_close($connection);
?>

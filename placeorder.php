<?php
// Database connection settings
$servername = "localhost";
$username = "root";  // Use your database username
$password = "root";  // Use your database password
$dbname = "customers_order";  // Database name

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default values
$product_name = isset($_GET['product']) ? $_GET['product'] : '';  // Get the product name from the URL
$quantity = 1;  // Default quantity

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form inputs
    $product_name = $conn->real_escape_string($_POST['product-name']);
    $quantity = !empty($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Ensure quantity is a valid number
    $address = $conn->real_escape_string($_POST['address']);
    $paytm_method = $conn->real_escape_string($_POST['paytm']);
    
    // Check if the required fields are not empty
    if (empty($product_name) || empty($quantity) || empty($address) || empty($paytm_method)) {
        echo "<script>alert('All fields must be filled out!');</script>";
    } else {
        // Prepare SQL query to insert data into the orders table
        $sql = "INSERT INTO orders (product_name, quantity, address, paytm_method) 
                VALUES ('$product_name', '$quantity', '$address', '$paytm_method')";

        if ($conn->query($sql) === TRUE) {
            // If successful, set a session variable or redirect directly
            echo "<script>
                    alert('Your order is placed!');
                    window.location.href = 'projectindex.html';
                  </script>";
        } else {
            // If there's an error, show an alert
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Form</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Form Styles */
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        /* Label and Input styles */
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        input[type="text"], textarea, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            resize: vertical;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        textarea {
            height: 100px;
        }

        /* Submit Button */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Additional Styles for Radio Button */
        .radio-group {
            margin-bottom: 20px;
        }

        .status {
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>

    <form action="" method="POST">
        <h2>Order Form</h2>

        <!-- Product Name and Quantity fields -->
        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" name="product-name" value="<?php echo htmlspecialchars($product_name); ?>" required> <!-- Dynamic Product Name -->

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" required min="1"> <!-- Quantity field -->

        <!-- Address field -->
        <label for="address">Address:</label>
        <textarea id="address" name="address" placeholder="Enter your address" required></textarea>

        <!-- Paytm Method -->
        <div class="radio-group">
            <label>Paytm Method:</label>
            <input type="radio" name="paytm" value="cash" required> Cash
            <input type="radio" name="paytm" value="UPI" required> UPI
        </div>

        <div class="status">Status: None</div>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>

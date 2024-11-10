<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "root";
$db = "login";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session (important for tracking logged-in users)
session_start();

// Logout logic
if (isset($_GET['logout'])) {
    // Destroy the session to log the user out
    session_destroy();
    header("Location: index.php"); // Redirect back to login page
    exit();
}

// Sign up logic
if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encrypt password using MD5 (not recommended in production)

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "Email address already exists!";
    } else {
        // Insert user data into database
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password) 
                        VALUES ('$firstName', '$lastName', '$email', '$password')";
        
        if ($conn->query($insertQuery) === TRUE) {
            echo "Registration successful!";
            header("Location: index.php"); // Redirect to homepage after successful registration
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Sign-in logic
if (isset($_POST['signIN'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encrypt password using MD5 (not recommended in production)

    // Check if user exists with provided credentials
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Store user email in session to keep track of login
        $_SESSION['email'] = $row['email'];

        // Redirect to project index page after successful login
        header("Location: projectindex.html"); 
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="signin.css">
</head>
<body>
    <!-- Sign-Up Form -->
    <div class="container" id="signUp">
        <h1>Sign Up</h1>
        <form method="post" action="index.php">
            <div class="input-group">
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <button type="submit" class="btn" name="signUp">Sign Up</button>
        </form>
        <p class="or">---- Or ----</p>
        <button class="link-btn" id="showSignIn">Already have an account? Sign In</button>
    </div>

    <!-- Sign-In Form -->
    <div class="container" id="signIn" style="display: none;">
        <h1>Sign In</h1>
        <form method="post" action="index.php">
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <button type="submit" class="btn" name="signIN">Sign In</button>
        </form>
        <p class="or">---- Or ----</p>
        <button class="link-btn" id="showSignUp">Don't have an account? Sign Up</button>
    </div>

    <!-- Logout Button (Only shown when user is logged in) -->
    <?php if (isset($_SESSION['email'])): ?>
        <div class="container" id="logoutContainer">
            <p>Welcome, <?php echo $_SESSION['email']; ?>!</p>
            <a href="index.php?logout=true" class="btn logout-btn">Logout</a>
        </div>
    <?php endif; ?>

    <script src="signin.js"></script>
    
</body>
</html>

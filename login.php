<?php
session_start(); // Start the session to use session variables

// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "project5"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare and bind for login
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if username exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if ($inputPassword === $user['Password']) {
            // Set session variables
            $_SESSION['username'] = $user['Username'];
            // Redirect to homepage
            header("Location: homepage.php");
            exit();
        } else {
            $errorMessage = "Invalid password. Please try again.";
        }
    } else {
        $errorMessage = "Username not found. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css"> <!-- Linking external CSS file -->
    <script>
        <?php if (isset($errorMessage)) { ?>
            window.onload = function() {
                alert("<?php echo $errorMessage; ?>");
            };
        <?php } ?>
    </script>
</head>
<body>

<div class="centre frame-div">
    <!-- Bedrijfs naam DIV -->
    <div class="bedrijf-div">
        <div class="bedrijf-naam">
            <h1>Energy With You</h1>
        </div>
    </div>
    <form method="POST" action="login.php"> <!-- Set action to login.php -->
    <div class="login-parent">
        <div class="field-parent">
            
                <input type="text" class="username" name="username" placeholder="Username" required />
                <input type="password" class="password" name="password" placeholder="Password" required />
                <div class="login">
                    <button type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>


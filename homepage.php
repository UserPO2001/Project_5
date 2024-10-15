<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project5";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user data
    $stmt = $conn->prepare("SELECT user_id, First_name FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['user_id'];
        $firstName = $user['First_name'];

        // Fetch consumption data for this user
        $stmt = $conn->prepare("SELECT electricity_consumed_kWh, water_consumed_m3 FROM consumption WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $consumptionResult = $stmt->get_result();

        if ($consumptionResult->num_rows > 0) {
            $consumption = $consumptionResult->fetch_assoc();
            $electricityConsumed = $consumption['electricity_consumed_kWh'];
            $waterConsumed = $consumption['water_consumed_m3'];
        } else {
            $electricityConsumed = 0;
            $waterConsumed = 0;
        }
    } else {
        die("User not found.");
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireframe Layout</title>
    <link rel="stylesheet" href="homepage.css"> <!-- Linking external CSS file -->
</head>
<body>

<div class="centre frame-div">
    <!-- Welkom *User* DIV -->
    <div class="Welkom-div">
        <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>!</h1>
    </div>

    <!-- Use DIVS -->
    <div class="use-parent">
        <div class="electricitykwh">
            <div>Uw verbruik is <?php echo $electricityConsumed; ?> kWh</div>
            <div class="icon1"></div>
        </div>
        <div class="waterm3">
            <div>Uw verbruik is <?php echo $waterConsumed; ?> m³</div>
            <div class="icon2"></div>
        </div>
    </div>

    <!-- Visual use DIVS -->
    <div class="tips-parent">
        <div class="tips"> <!-- Here you can add dynamic tips based on consumption data --> </div>
    </div>
</div>

</body>
</html>

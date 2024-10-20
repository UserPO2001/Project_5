<?php
session_start();

// Database class to handle the connection
class Database {
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// User class to handle user data
class User {
    private $username;
    private $userId;
    private $firstName;
    private $db;

    public function __construct($username, $db) {
        $this->username = $username;
        $this->db = $db;
        $this->fetchUserData();
    }

    private function fetchUserData() {
        $stmt = $this->db->prepare("SELECT user_id, First_name FROM users WHERE Username = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $this->userId = $user['user_id'];
            $this->firstName = $user['First_name'];
        } else {
            die("User not found.");
        }
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getUserId() {
        return $this->userId;
    }
}

// Consumption class to handle fetching consumption data
  class Consumption {
    private $userId;
    private $electricityConsumed;
    private $waterConsumed;
    private $db;

    public function __construct($userId, $db) {
        $this->userId = $userId;
        $this->db = $db;
        $this->fetchConsumptionData();
    }

    private function fetchConsumptionData() {
        $stmt = $this->db->prepare("SELECT electricity_consumed_kWh, water_consumed_m3 FROM consumption WHERE user_id = ?");
        $stmt->bind_param("i", $this->userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $consumption = $result->fetch_assoc();
            $this->electricityConsumed = $consumption['electricity_consumed_kWh'];
            $this->waterConsumed = $consumption['water_consumed_m3'];
        } else {
            $this->electricityConsumed = 0;
            $this->waterConsumed = 0;
        }
    }

    public function getElectricityConsumed() {
        return $this->electricityConsumed;
    }

    public function getWaterConsumed() {
        return $this->waterConsumed;
    }
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Create a Database object
    $db = new Database("localhost", "root", "", "project5");
    $conn = $db->getConnection();

    // Create a User object
    $user = new User($username, $conn);

    // Create a Consumption object
    $consumption = new Consumption($user->getUserId(), $conn);
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
    <link rel="stylesheet" type="text/css" href="homepage.css?<?php echo time(); ?>" />
    </head>
<body>
<script src="animation.js" defer></script>
    <div class="centre frame-div">


        <div class='console-container'>
            <span id='text' class="console-welcome"></span>
            <span id="user" class="console-welcome" data-first-name="<?php echo htmlspecialchars($user->getFirstName()); ?>"></span>
            <div class='console-underscore' id='console'>&#95;</div>
        </div>



            <br>
            <!-- Use DIVS -->
            <div class="use-parent">
                <div class="electricitykwh">
                    <div class="test1">Uw verbruik: <br> <?php echo $consumption->getElectricityConsumed(); ?> kWh</div>
                    <div class="icon1"></div>
                </div>


                <div class="waterm3">
                    <div class="test2">Uw verbruik: <br>  <?php echo $consumption->getWaterConsumed(); ?> m³</div>
                    <div class="icon2"></div>
                </div>
            </div>


            <br>
            <!-- Visual use DIVS -->
            <div class="tips-parent">
                <div class="tips"> 
                    <h2>Tips:</h2>
                </div>

                <br>

                <div class="tips-velden-container">
                        <div class="tips-veld">Hier komt de tips</div>
                        <div class="tips-veld">Hier komt de tips</div>
                        <div class="tips-veld">Hier komt de tips</div>

                    </div>
            </div>
    </div>

</body>
</html>

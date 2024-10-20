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

// Function to generate tips based on electricity and water consumption
function generateTips($electricity, $water) {
    $tips = [];

    // Electricity tips
    if ($electricity <= 125) {
        $tips[] = "Uw stroomverbruik is laag. U bent goed bezig";
    } elseif ($electricity <= 292) {
        $tips[] = "Uw stroomverbruik is gemiddeld. Gebruik energiezuinige apparaten en overweeg LED-verlichting.";
    } else {
        $tips[] = "Uw stroomverbruik is hoog. Schakel over op zonne-energie of verminder het gebruik van zware apparaten.";
    }

    // Water tips
    if ($water <= 42) {
        $tips[] = "Uw waterverbruik is laag. Geweldig! U bent goed bezig!.";
    } elseif ($water <= 100) {
        $tips[] = "Uw waterverbruik is gemiddeld. Overweeg een waterbesparende douchekop te installeren.";
    } else {
        $tips[] = "Uw waterverbruik is hoog. Controleer op lekkages en beperk het gebruik van de kraan tijdens het tandenpoetsen.";
    }

    return $tips;
}

// Generate the tips based on the user's consumption
$tips = generateTips($consumption->getElectricityConsumed(), $consumption->getWaterConsumed());

// Determine the icon colors based on consumption levels
function getIconColor($consumption, $type) {
    if ($type === 'electricity') {
        if ($consumption <= 125) {
            return 'green'; // Low usage
        } elseif ($consumption <= 292) {
            return 'orange'; // Medium usage
        } else {
            return 'red'; // High usage
        }
    } elseif ($type === 'water') {
        if ($consumption <= 42) {
            return 'green'; // Low usage
        } elseif ($consumption <= 100) {
            return 'orange'; // Medium usage
        } else {
            return 'red'; // High usage
        }
    }
}

$electricityColor = getIconColor($consumption->getElectricityConsumed(), 'electricity');
$waterColor = getIconColor($consumption->getWaterConsumed(), 'water');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wireframe Layout</title>
    <link rel="stylesheet" type="text/css" href="homepage.css?<?php echo time(); ?>" />
    <style>
        .icon1 {
            border: 4px solid rgba(0, 0, 0, 0);
            border-bottom: 22px solid <?php echo $electricityColor; ?>; /* Use dynamic color */
            transform: rotateZ(-160deg);
            padding: 2px;
            width: 0;
            height: 0;
            margin: 0;
            overflow: visible;
            border-top: 0 solid;
            border-radius: 0;
            position: absolute;
            top: 50%;
            transform: translateY(-50%) rotateZ(-160deg);
        }

        .icon1:after {
            content: "";
            top: -9px;
            left: -8px;
            border: 5px solid rgba(0, 0, 0, 0);
            border-bottom: 25px solid <?php echo $electricityColor; ?>; /* Use dynamic color */
            transform: rotateZ(4deg);
            padding: 0;
            width: 0;
            height: 0;
            position: absolute;
            margin: 0;
            overflow: visible;
            border-top: 0 solid;
            border-radius: 0;
        }

        .icon2 {
            position: absolute;
            padding: 0;
            margin: 0 auto;
            width: 25px;
            height: 25px;
            border-radius: 0% 100% 100% 100%;
            background-color: <?php echo $waterColor; ?>; /* Use dynamic color */
            transform: rotate(45deg);
        }
    </style>
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
                <div class="test2">Uw verbruik: <br> <?php echo $consumption->getWaterConsumed(); ?> m³</div>
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
                <?php foreach ($tips as $tip) : ?>
                    <div class="tips-veld"><?php echo $tip; ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>

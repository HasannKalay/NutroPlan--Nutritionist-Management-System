<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

if (!isset($_GET['patient_id'])) {
    header("Location: myclients.php");
    exit();
}

$patient_id = $_GET['patient_id'];
$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT p.*, bm.weight, bm.height, bm.fat_percentage, bm.muscle_mass, bm.bmi, bm.waist_circumference, bm.hip_circumference FROM Patients p LEFT JOIN BodyMetrics bm ON p.patient_id = bm.patient_id WHERE p.patient_id = :patient_id AND p.user_id = :user_id");
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo "No client found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $health_status = $_POST['health_status'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $fat_percentage = $_POST['fat_percentage'];
    $muscle_mass = $_POST['muscle_mass'];
    $bmi = $_POST['bmi'];
    $waist_circumference = $_POST['waist_circumference'];
    $hip_circumference = $_POST['hip_circumference'];

    try {
        $stmt = $conn->prepare("UPDATE Patients SET name = :name, email = :email, phone_number = :phone_number, age = :age, gender = :gender, health_status = :health_status WHERE patient_id = :patient_id AND user_id = :user_id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':health_status', $health_status);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Update or insert body metrics
        $stmt = $conn->prepare("INSERT INTO BodyMetrics (patient_id, weight, height, fat_percentage, muscle_mass, bmi, waist_circumference, hip_circumference) VALUES (:patient_id, :weight, :height, :fat_percentage, :muscle_mass, :bmi, :waist_circumference, :hip_circumference) ON DUPLICATE KEY UPDATE weight = :weight, height = :height, fat_percentage = :fat_percentage, muscle_mass = :muscle_mass, bmi = :bmi, waist_circumference = :waist_circumference, hip_circumference = :hip_circumference");
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':fat_percentage', $fat_percentage);
        $stmt->bindParam(':muscle_mass', $muscle_mass);
        $stmt->bindParam(':bmi', $bmi);
        $stmt->bindParam(':waist_circumference', $waist_circumference);
        $stmt->bindParam(':hip_circumference', $hip_circumference);
        $stmt->execute();

        header("Location: myclients.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link rel="icon" type="image/svg+xml" href="../../images/logo.svg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/dashboard styles/dashboard.css">
</head>
<body>
    <div id="wrapper" class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark border-right d-flex flex-column" id="sidebar-wrapper">
            <div class="sidebar-heading text-white">
                <img src="../../images/logo.svg" alt="Logo" class="logo"><span style="color: #e8491d;">Nutro</span>Plan
            </div>
            <div class="list-group list-group-flush flex-grow-1">
                <a href="dashboard.php" class="list-group-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="myclients.php" class="list-group-item">
                    <i class="fas fa-users"></i> My Clients
                </a>
                <a href="appointment.php" class="list-group-item">
                    <i class="fas fa-calendar-check"></i> Appointment
                </a>
                <a href="mycalendar.php" class="list-group-item">
                    <i class="fas fa-calendar-alt"></i> My Calendar
                </a>
                <a href="reports.php" class="list-group-item">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
                <a href="mealplans.php" class="list-group-item">
                    <i class="fas fa-utensils"></i> Meal Plans
                </a>
                <a href="food_nutrients.php" class="list-group-item">
                    <i class="fas fa-book"></i> Food Nutrients
                </a>
                <a href="payments.php" class="list-group-item">
                    <i class="fas fa-credit-card"></i> Payments
                </a>
                <a href="myprofile.php" class="list-group-item">
                    <i class="fas fa-user"></i> My Profile
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $_SESSION['username']; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="myprofile.php">My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
    <div class="container mt-5">
        <h1>Edit Client</h1>
        <form method="POST">
            <div class="form-group">
                <label for="clientName">Name</label>
                <input type="text" class="form-control" id="clientName" name="name" value="<?php echo htmlspecialchars($client['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientEmail">Email</label>
                <input type="email" class="form-control" id="clientEmail" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientPhone">Phone Number</label>
                <input type="text" class="form-control" id="clientPhone" name="phone_number" value="<?php echo htmlspecialchars($client['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientAge">Age</label>
                <input type="number" class="form-control" id="clientAge" name="age" value="<?php echo htmlspecialchars($client['age']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientGender">Gender</label>
                <select class="form-control" id="clientGender" name="gender" required>
                    <option value="male" <?php if ($client['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($client['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if ($client['gender'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="clientHealthStatus">Health Status</label>
                <textarea class="form-control" id="clientHealthStatus" name="health_status" rows="3" required><?php echo htmlspecialchars($client['health_status']); ?></textarea>
            </div>
            <!-- Body Metrics Fields -->
            <div class="form-group">
                <label for="clientWeight">Weight (kg)</label>
                <input type="number" step="0.01" class="form-control" id="clientWeight" name="weight" value="<?php echo htmlspecialchars($client['weight']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientHeight">Height (cm)</label>
                <input type="number" step="0.01" class="form-control" id="clientHeight" name="height" value="<?php echo htmlspecialchars($client['height']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientFatPercentage">Fat Percentage (%)</label>
                <input type="number" step="0.01" class="form-control" id="clientFatPercentage" name="fat_percentage" value="<?php echo htmlspecialchars($client['fat_percentage']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientMuscleMass">Muscle Mass (kg)</label>
                <input type="number" step="0.01" class="form-control" id="clientMuscleMass" name="muscle_mass" value="<?php echo htmlspecialchars($client['muscle_mass']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientBMI">BMI</label>
                <input type="number" step="0.01" class="form-control" id="clientBMI" name="bmi" value="<?php echo htmlspecialchars($client['bmi']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientWaistCircumference">Waist Circumference (cm)</label>
                <input type="number" step="0.01" class="form-control" id="clientWaistCircumference" name="waist_circumference" value="<?php echo htmlspecialchars($client['waist_circumference']); ?>" required>
            </div>
            <div class="form-group">
                <label for="clientHipCircumference">Hip Circumference (cm)</label>
                <input type="number" step="0.01" class="form-control" id="clientHipCircumference" name="hip_circumference" value="<?php echo htmlspecialchars($client['hip_circumference']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Client</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
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
        $stmt = $conn->prepare("INSERT INTO Patients (user_id, name, email, phone_number, age, gender, health_status, created_at) VALUES (:user_id, :name, :email, :phone_number, :age, :gender, :health_status, NOW())");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':health_status', $health_status);
        $stmt->execute();

        $patient_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO BodyMetrics (patient_id, weight, height, fat_percentage, muscle_mass, bmi, waist_circumference, hip_circumference) VALUES (:patient_id, :weight, :height, :fat_percentage, :muscle_mass, :bmi, :waist_circumference, :hip_circumference)");
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
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Client</title>
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
                <a href="recipes.php" class="list-group-item">
                    <i class="fas fa-book"></i> Recipes
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

            <div class="container-fluid">
                <h1 class="mt-4">Add New Client</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="clientName">Name</label>
                        <input type="text" class="form-control" id="clientName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="clientEmail">Email</label>
                        <input type="email" class="form-control" id="clientEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="clientPhone">Phone Number</label>
                        <input type="text" class="form-control" id="clientPhone" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="clientAge">Age</label>
                        <input type="number" class="form-control" id="clientAge" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="clientGender">Gender</label>
                        <select class="form-control" id="clientGender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clientHealthStatus">Health Status</label>
                        <textarea class="form-control" id="clientHealthStatus" name="health_status" rows="3" required></textarea>
                    </div>
                    <h3>Body Metrics</h3>
                    <div class="form-group">
                        <label for="clientWeight">Weight (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="clientWeight" name="weight" required>
                    </div>
                    <div class="form-group">
                        <label for="clientHeight">Height (cm)</label>
                        <input type="number" step="0.01" class="form-control" id="clientHeight" name="height" required>
                    </div>
                    <div class="form-group">
                        <label for="clientFatPercentage">Fat Percentage (%)</label>
                        <input type="number" step="0.01" class="form-control" id="clientFatPercentage" name="fat_percentage" required>
                    </div>
                    <div class="form-group">
                        <label for="clientMuscleMass">Muscle Mass (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="clientMuscleMass" name="muscle_mass" required>
                    </div>
                    <div class="form-group">
                        <label for="clientBMI">BMI</label>
                        <input type="number" step="0.01" class="form-control" id="clientBMI" name="bmi" required>
                    </div>
                    <div class="form-group">
                        <label for="clientWaistCircumference">Waist Circumference (cm)</label>
                        <input type="number" step="0.01" class="form-control" id="clientWaistCircumference" name="waist_circumference" required>
                    </div>
                    <div class="form-group">
                        <label for="clientHipCircumference">Hip Circumference (cm)</label>
                        <input type="number" step="0.01" class="form-control" id="clientHipCircumference" name="hip_circumference" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Client</button>
                </form>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

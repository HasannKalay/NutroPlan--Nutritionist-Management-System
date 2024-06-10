<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $patient_id = $_POST['patient_id'];
    $report_type = $_POST['report_type'];
    $report_name = $_POST['report_name'];
    $created_at = date('Y-m-d H:i:s');

    $target_dir = "../../uploads/reports/";
    $file_path = $target_dir . basename($_FILES["report_file"]["name"]);
    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    if ($file_type != "pdf") {
        echo "Error: Only PDF files are allowed.";
        exit();
    }

    if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $file_path)) {
        try {
            $stmt = $conn->prepare("INSERT INTO reports (user_id, patient_id, report_type, created_at, file_path) VALUES (:user_id, :patient_id, :report_type, :created_at, :file_path)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':patient_id', $patient_id);
            $stmt->bindParam(':report_type', $report_type);
            $stmt->bindParam(':created_at', $created_at);
            $stmt->bindParam(':file_path', $file_path);
            $stmt->execute();
            header("Location: reports.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Error uploading the file.";
        exit();
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM Patients WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report</title>
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
            </nav>
            <div class="container mt-5">
                <h1>Add New Report</h1>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="reportName">Report Name</label>
                        <input type="text" class="form-control" id="reportName" name="report_name" required>
                    </div>
                    <div class="form-group">
                        <label for="patientSelect">Patient</label>
                        <select class="form-control" id="patientSelect" name="patient_id" required>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['patient_id']; ?>"><?php echo htmlspecialchars($patient['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reportType">Report Type</label>
                        <input type="text" class="form-control" id="reportType" name="report_type" required>
                    </div>
                    <div class="form-group">
                        <label for="reportFile">Upload Report (PDF only)</label>
                        <input type="file" class="form-control-file" id="reportFile" name="report_file" accept="application/pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Report</button>
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

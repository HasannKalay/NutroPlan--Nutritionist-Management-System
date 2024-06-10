<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

$user_id = $_SESSION['user_id'];

// Fetch patients
try {
    $stmt = $conn->prepare("SELECT * FROM Patients WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching patients: " . $e->getMessage();
}

// Handle new meal plan creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_meal_plan'])) {
    $patient_id = $_POST['patient_id'];
    $plan_name = $_POST['plan_name'];
    $description = $_POST['description'];

    try {
        $stmt = $conn->prepare("INSERT INTO mealplans (user_id, patient_id, plan_name, description) VALUES (:user_id, :patient_id, :plan_name, :description)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':plan_name', $plan_name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    } catch (PDOException $e) {
        $error_message = "Error creating meal plan: " . $e->getMessage();
    }
}

// Handle meal plan deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_meal_plan'])) {
    $meal_plan_id = $_POST['meal_plan_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM mealplans WHERE meal_plan_id = :meal_plan_id AND user_id = :user_id");
        $stmt->bindParam(':meal_plan_id', $meal_plan_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    } catch (PDOException $e) {
        $error_message = "Error deleting meal plan: " . $e->getMessage();
    }
}

// Fetch meal plans
try {
    $stmt = $conn->prepare("SELECT mp.*, p.name as patient_name FROM mealplans mp JOIN Patients p ON mp.patient_id = p.patient_id WHERE mp.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $mealplans = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching meal plans: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Plans</title>
    <link rel="icon" type="image/svg+xml" href="../../images/logo.svg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/dashboard styles/dashboard.css">
    <link rel="stylesheet" href="../../css/dashboard styles/myclients.css">
</head>
<body>
    <div id="wrapper" class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark border-right d-flex flex-column" id="sidebar-wrapper">
            <div class="sidebar-heading text-white">
                <img src="../../images/logo.svg" alt="Logo" class="logo"><span style="color: #e8491d;">Nutro</span>Plan
            </div>
            <div class="list-group list-group-flush flex-grow-1">
                <a href="dashboard.php" class="list-group-item ">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="myclients.php" class="list-group-item ">
                    <i class="fas fa-users"></i> My Clients
                </a>
                <a href="appointment.php" class="list-group-item ">
                    <i class="fas fa-calendar-check"></i> Appointment
                </a>
                <a href="mycalendar.php" class="list-group-item ">
                    <i class="fas fa-calendar-alt"></i> My Calendar
                </a>
                <a href="reports.php" class="list-group-item ">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
                <a href="mealplans.php" class="list-group-item ">
                    <i class="fas fa-utensils"></i> Meal Plans
                </a>
                <a href="food_nutrients.php" class="list-group-item ">
                    <i class="fas fa-book"></i> Food Nutrients
                </a>
                <a href="payments.php" class="list-group-item ">
                    <i class="fas fa-credit-card"></i> Payments
                </a>
                <a href="myprofile.php" class="list-group-item ">
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

            <div class="container-fluid">
                <h1 class="mt-4">Meal Plans</h1>
                <button class="btn btn-primary mb-4" id="add-new-meal-plan" style="background-color: #e8491d; border-color: #e8491d;">Add New Meal Plan</button>
                <div id="new-meal-plan-container" class="d-none">
                    <form method="POST">
                        <div class="form-group">
                            <label for="patientSelect">Patient Name</label>
                            <select class="form-control" id="patientSelect" name="patient_id" required>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo $patient['patient_id']; ?>"><?php echo htmlspecialchars($patient['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="planName">Plan Name</label>
                            <input type="text" class="form-control" id="planName" name="plan_name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Meal Plan Description</label>
                            <textarea class="form-control" id="description" name="description" rows="10" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="create_meal_plan" style="background-color: #e8491d; border-color: #e8491d;">Create Meal Plan</button>
                    </form>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-bar" placeholder="Search meal plans...">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" style="background-color: #35424a; border-color: #35424a;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-utensils mr-1"></i>
                        Meal Plans List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Plan ID</th>
                                        <th>Plan Name</th>
                                        <th>Patient Name</th>
                                        <th>Creation Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mealplans as $mealplan): ?>
                                    <tr>
                                        <td><?php echo $mealplan['meal_plan_id']; ?></td>
                                        <td><?php echo htmlspecialchars($mealplan['plan_name']); ?></td>
                                        <td><?php echo htmlspecialchars($mealplan['patient_name']); ?></td>
                                        <td><?php echo $mealplan['created_at']; ?></td>
                                        <td>
                                            <a href="viewmealplan.php?id=<?php echo $mealplan['meal_plan_id']; ?>" class="btn btn-info btn-sm" style="background-color: #35424a; border-color: #35424a;">View</a>
                                            <a href="editmealplan.php?id=<?php echo $mealplan['meal_plan_id']; ?>" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Edit</a>
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="meal_plan_id" value="<?php echo $mealplan['meal_plan_id']; ?>">
                                                <button type="submit" name="delete_meal_plan" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($mealplans)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No meal plans found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-new-meal-plan').on('click', function() {
                $('#new-meal-plan-container').toggleClass('d-none');
            });
        });
    </script>
</body>
</html>

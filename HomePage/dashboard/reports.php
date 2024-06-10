<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT r.report_id, r.report_type, r.created_at, r.file_path, p.name as patient_name 
                            FROM reports r 
                            JOIN Patients p ON r.patient_id = p.patient_id 
                            WHERE r.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Reports</title>
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

            <div class="container-fluid">
                <h1 class="mt-4">Reports</h1>
                <a href="add_report.php" class="btn btn-primary mb-4" id="add-new-report" style="background-color: #e8491d; border-color: #e8491d;">Add New Report</a>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Reports List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Report Type</th>
                                        <th>Patient Name</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $report): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($report['report_id']); ?></td>
                                                <td><?php echo htmlspecialchars($report['report_type']); ?></td>
                                                <td><?php echo htmlspecialchars($report['patient_name']); ?></td>
                                                <td><?php echo htmlspecialchars($report['created_at']); ?></td>
                                                <td>
                                                    <a href="<?php echo htmlspecialchars($report['file_path']); ?>" class="btn btn-info btn-sm" style="background-color: #35424a; border-color: #35424a;" target="_blank">View</a>
                                                    <a href="<?php echo htmlspecialchars($report['file_path']); ?>" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;" download>Download</a>
                                                    <button class="btn btn-danger btn-sm delete-report" data-report-id="<?php echo htmlspecialchars($report['report_id']); ?>">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No reports found.</td>
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
            $('.delete-report').click(function() {
                var reportId = $(this).data('report-id');
                if (confirm('Are you sure you want to delete this report?')) {
                    $.ajax({
                        url: 'delete_report.php',
                        type: 'POST',
                        data: { report_id: reportId },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.status === 'success') {
                                location.reload();
                            } else {
                                alert('Error: ' + result.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred while deleting the report.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

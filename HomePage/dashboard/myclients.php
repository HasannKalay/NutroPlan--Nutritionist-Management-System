<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

$user_id = $_SESSION['user_id'];
$clients = [];

try {
    $stmt = $conn->prepare("SELECT * FROM Patients WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Clients</title>
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
                <h1 class="mt-4">My Clients</h1>
                <a href="add_client.php" class="btn btn-primary mb-4" id="add-new-client" style="background-color: #e8491d; border-color: #e8491d;">Add New Client</a>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Client List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Created At</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="client-table-body">
                                    <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($client['created_at']); ?></td>
                                        <td><?php echo htmlspecialchars($client['name']); ?></td>
                                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                                        <td><?php echo htmlspecialchars($client['phone_number']); ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm view-client" data-id="<?php echo $client['patient_id']; ?>" data-name="<?php echo $client['name']; ?>" data-email="<?php echo $client['email']; ?>" data-phone="<?php echo $client['phone_number']; ?>" data-age="<?php echo $client['age']; ?>" data-gender="<?php echo $client['gender']; ?>" data-health="<?php echo $client['health_status']; ?>" style="background-color: #35424a; border-color: #35424a;">View</button>
                                            <a href="edit_client.php?patient_id=<?php echo $client['patient_id']; ?>" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Edit</a>
                                            <button class="btn btn-danger btn-sm delete-client" data-id="<?php echo $client['patient_id']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="client-details" style="display: none;">
                    <h3>Client Details</h3>
                    <p><strong>Name:</strong> <span id="clientName"></span></p>
                    <p><strong>Email:</strong> <span id="clientEmail"></span></p>
                    <p><strong>Phone Number:</strong> <span id="clientPhone"></span></p>
                    <p><strong>Age:</strong> <span id="clientAge"></span></p>
                    <p><strong>Gender:</strong> <span id="clientGender"></span></p>
                    <p><strong>Health Status:</strong> <span id="clientHealthStatus"></span></p>
                    <h5>Body Metrics</h5>
                    <p><strong>Weight (kg):</strong> <span id="clientWeight"></span></p>
                    <p><strong>Height (cm):</strong> <span id="clientHeight"></span></p>
                    <p><strong>Fat Percentage (%):</strong> <span id="clientFatPercentage"></span></p>
                    <p><strong>Muscle Mass (kg):</strong> <span id="clientMuscleMass"></span></p>
                    <p><strong>BMI:</strong> <span id="clientBMI"></span></p>
                    <p><strong>Waist Circumference (cm):</strong> <span id="clientWaistCircumference"></span></p>
                    <p><strong>Hip Circumference (cm):</strong> <span id="clientHipCircumference"></span></p>
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
            $('.view-client').click(function() {
                var clientDetails = {
                    name: $(this).data('name'),
                    email: $(this).data('email'),
                    phone: $(this).data('phone'),
                    age: $(this).data('age'),
                    gender: $(this).data('gender'),
                    health: $(this).data('health')
                };

                // Fetch body metrics
                var patient_id = $(this).data('id');
                $.ajax({
                    url: 'get_body_metrics.php',
                    type: 'GET',
                    data: { patient_id: patient_id },
                    success: function(response) {
                        var metrics = JSON.parse(response);
                        $('#clientWeight').text(metrics.weight);
                        $('#clientHeight').text(metrics.height);
                        $('#clientFatPercentage').text(metrics.fat_percentage);
                        $('#clientMuscleMass').text(metrics.muscle_mass);
                        $('#clientBMI').text(metrics.bmi);
                        $('#clientWaistCircumference').text(metrics.waist_circumference);
                        $('#clientHipCircumference').text(metrics.hip_circumference);
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching body metrics');
                    }
                });

                $('#clientName').text(clientDetails.name);
                $('#clientEmail').text(clientDetails.email);
                $('#clientPhone').text(clientDetails.phone);
                $('#clientAge').text(clientDetails.age);
                $('#clientGender').text(clientDetails.gender);
                $('#clientHealthStatus').text(clientDetails.health);

                $('#client-details').show();
            });

            $('.delete-client').click(function() {
                if (confirm('Are you sure you want to delete this client?')) {
                    var patient_id = $(this).data('id');
                    $.ajax({
                        url: 'delete_client.php',
                        type: 'POST',
                        data: { patient_id: patient_id },
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.status === "success") {
                                location.reload();
                            } else {
                                alert('Error deleting client: ' + result.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error deleting client');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

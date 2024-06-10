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

// Handle new payment creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_payment'])) {
    $patient_id = $_POST['patient_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare("INSERT INTO payments (user_id, patient_id, amount, status) VALUES (:user_id, :patient_id, :amount, :status)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    } catch (PDOException $e) {
        $error_message = "Error creating payment: " . $e->getMessage();
    }
}

// Handle payment deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_payment'])) {
    $payment_id = $_POST['payment_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM payments WHERE payment_id = :payment_id AND user_id = :user_id");
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    } catch (PDOException $e) {
        $error_message = "Error deleting payment: " . $e->getMessage();
    }
}

// Handle payment status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_payment'])) {
    $payment_id = $_POST['payment_id'];
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare("UPDATE payments SET status = :status WHERE payment_id = :payment_id AND user_id = :user_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    } catch (PDOException $e) {
        $error_message = "Error updating payment: " . $e->getMessage();
    }
}

// Fetch payments
try {
    $stmt = $conn->prepare("SELECT p.*, pat.name as patient_name FROM payments p JOIN Patients pat ON p.patient_id = pat.patient_id WHERE p.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching payments: " . $e->getMessage();
}

// Fetch single payment for editing
$edit_payment = null;
if (isset($_GET['edit_id'])) {
    $edit_payment_id = $_GET['edit_id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id = :payment_id AND user_id = :user_id");
        $stmt->bindParam(':payment_id', $edit_payment_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $edit_payment = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Error fetching payment: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
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
                <a href="payments.php" class="list-group-item">
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
                <h1 class="mt-4">Online Payments</h1>
                <button class="btn btn-primary mb-4" id="add-new-payment" style="background-color: #e8491d; border-color: #e8491d;">Add New Payment</button>
                <div id="new-payment-container" class="d-none">
                    <form method="POST">
                        <div class="form-group">
                            <label for="patientSelect">Client Name</label>
                            <select class="form-control" id="patientSelect" name="patient_id" required>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo $patient['patient_id']; ?>"><?php echo htmlspecialchars($patient['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="create_payment" style="background-color: #e8491d; border-color: #e8491d;">Create Payment</button>
                    </form>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-credit-card mr-1"></i>
                        Payments List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Client Name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?php echo $payment['payment_id']; ?></td>
                                        <td><?php echo htmlspecialchars($payment['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['created_at']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                        <td>
                                            <a href="payments.php?edit_id=<?php echo $payment['payment_id']; ?>" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Edit</a>
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                                                <button type="submit" name="delete_payment" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($payments)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No payments found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php if (isset($_GET['edit_id']) && $edit_payment): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Payment
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="payment_id" value="<?php echo $edit_payment['payment_id']; ?>">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="paid" <?php echo $edit_payment['status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                    <option value="unpaid" <?php echo $edit_payment['status'] == 'unpaid' ? 'selected' : ''; ?>>Unpaid</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="edit_payment" style="background-color: #e8491d; border-color: #e8491d;">Update Payment</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
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
            $('#add-new-payment').on('click', function() {
                $('#new-payment-container').toggleClass('d-none');
            });
        });
    </script>
</body>
</html>

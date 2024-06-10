<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

$user_id = $_SESSION['user_id'];

// Fetch appointments
function fetchAppointments($conn, $user_id) {
    $stmt = $conn->prepare("SELECT a.*, p.name as patient_name FROM Appointments a JOIN Patients p ON a.patient_id = p.patient_id WHERE a.user_id = :user_id ORDER BY a.date, a.time");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch patients
try {
    $stmt = $conn->prepare("SELECT * FROM Patients WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching patients: " . $e->getMessage();
}

// Handle new appointment creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_appointment'])) {
    $patient_id = $_POST['patient_id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time']; 
    $notes = $_POST['appointment_notes'];

    try {
        // Check for existing appointment at the same date and time
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Appointments WHERE user_id = :user_id AND date = :date AND time = :time");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(["status" => "error", "message" => "An appointment already exists at the selected date and time."]);
            exit();
        } else {
            // Insert the appointment
            $stmt = $conn->prepare("INSERT INTO Appointments (user_id, patient_id, date, time, description) VALUES (:user_id, :patient_id, :date, :time, :notes)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':patient_id', $patient_id);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
        exit();
    }
}

// Handle appointment editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $patient_id = $_POST['patient_id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time']; 
    $notes = $_POST['appointment_notes'];

    try {
        // Update the appointment
        $stmt = $conn->prepare("UPDATE Appointments SET patient_id = :patient_id, date = :date, time = :time, description = :notes WHERE appointment_id = :appointment_id AND user_id = :user_id");
        $stmt->bindParam(':appointment_id', $appointment_id);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error updating appointment: " . $e->getMessage()]);
        exit();
    }
}

// Handle appointment deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM Appointments WHERE appointment_id = :appointment_id AND user_id = :user_id");
        $stmt->bindParam(':appointment_id', $appointment_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error deleting appointment: " . $e->getMessage()]);
        exit();
    }
}

// Fetch appointments
try {
    $appointments = fetchAppointments($conn, $user_id);
} catch (PDOException $e) {
    $error_message = "Error fetching appointments: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
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
                <h1 class="mt-4">Create Appointment</h1>
                <div id="messages"></div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-calendar-check mr-1"></i>
                                New Appointment
                            </div>
                            <div class="card-body">
                                <form id="createAppointmentForm" method="POST">
                                    <div class="form-group">
                                        <label for="appointmentDate">Date</label>
                                        <input type="date" class="form-control" id="appointmentDate" name="appointment_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="appointmentTime">Time</label>
                                        <input type="time" class="form-control" id="appointmentTime" name="appointment_time" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="patientSelect">Client Name</label>
                                        <select class="form-control" id="patientSelect" name="patient_id" required>
                                            <?php foreach ($patients as $patient): ?>
                                                <option value="<?php echo $patient['patient_id']; ?>"><?php echo htmlspecialchars($patient['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="appointmentNotes">Notes</label>
                                        <textarea class="form-control" id="appointmentNotes" name="appointment_notes" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="create_appointment">Create Appointment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-info-circle mr-1"></i>
                                Instructions
                            </div>
                            <div class="card-body">
                                <p>Fill out the form to create a new appointment. Ensure that all required fields are filled correctly.</p>
                                <ul>
                                    <li><strong>Date:</strong> Select the date for the appointment.</li>
                                    <li><strong>Time:</strong> Select the time for the appointment.</li>
                                    <li><strong>Client Name:</strong> Enter the client's full name.</li>
                                    <li><strong>Notes:</strong> Add any additional notes or details about the appointment.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="mt-4">Upcoming Appointments</h2>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table mr-1"></i>
                        Appointment List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="appointmentTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Client Name</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                                            <td>
                                                <form method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                                    <button type="submit" name="delete_appointment" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                                <button class="btn btn-secondary btn-sm edit-btn" data-appointment-id="<?php echo $appointment['appointment_id']; ?>">Edit</button>
                                            </td>
                                        </tr>
                                        <tr class="edit-form" data-appointment-id="<?php echo $appointment['appointment_id']; ?>" style="display:none;">
                                            <td colspan="5">
                                                <form method="POST" class="form-inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                                    <div class="form-group mx-sm-3 mb-2">
                                                        <label for="editAppointmentDate-<?php echo $appointment['appointment_id']; ?>" class="sr-only">Date</label>
                                                        <input type="date" class="form-control" id="editAppointmentDate-<?php echo $appointment['appointment_id']; ?>" name="appointment_date" value="<?php echo $appointment['date']; ?>" required>
                                                    </div>
                                                    <div class="form-group mx-sm-3 mb-2">
                                                        <label for="editAppointmentTime-<?php echo $appointment['appointment_id']; ?>" class="sr-only">Time</label>
                                                        <input type="time" class="form-control" id="editAppointmentTime-<?php echo $appointment['appointment_id']; ?>" name="appointment_time" value="<?php echo $appointment['time']; ?>" required>
                                                    </div>
                                                    <div class="form-group mx-sm-3 mb-2">
                                                        <label for="editPatientSelect-<?php echo $appointment['appointment_id']; ?>" class="sr-only">Client Name</label>
                                                        <select class="form-control" id="editPatientSelect-<?php echo $appointment['appointment_id']; ?>" name="patient_id" required>
                                                            <?php foreach ($patients as $patient): ?>
                                                                <option value="<?php echo $patient['patient_id']; ?>" <?php echo $appointment['patient_id'] == $patient['patient_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($patient['name']); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mx-sm-3 mb-2">
                                                        <label for="editAppointmentNotes-<?php echo $appointment['appointment_id']; ?>" class="sr-only">Notes</label>
                                                        <textarea class="form-control" id="editAppointmentNotes-<?php echo $appointment['appointment_id']; ?>" name="appointment_notes" rows="1"><?php echo htmlspecialchars($appointment['description']); ?></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mb-2" name="edit_appointment">Save Changes</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($appointments)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No appointments found</td>
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
            $('.edit-btn').on('click', function() {
                var appointmentId = $(this).data('appointment-id');
                $('tr[data-appointment-id="' + appointmentId + '"]').toggle();
            });
        });
    </script>
</body>
</html>

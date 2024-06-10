<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

$user_id = $_SESSION['user_id'];
$username = "";
$email = "";
$phone_number = "";

// Kullanıcı bilgilerini çekme
try {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['username'];
        $email = $user['email'];
        $phone_number = $user['phone_number'];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Profil güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone_number = $_POST['phone_number'];

    try {
        $stmt = $conn->prepare("UPDATE Users SET username = :username, email = :email, phone_number = :phone_number WHERE user_id = :user_id");
        $stmt->bindParam(':username', $new_username);
        $stmt->bindParam(':email', $new_email);
        $stmt->bindParam(':phone_number', $new_phone_number);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $_SESSION['username'] = $new_username;
        header("Location: myprofile.php?success=profile_updated");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Şifre güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Şifre doğrulama ve güncelleme
    if (password_verify($current_password, $user['password'])) {
        if ($new_password == $confirm_password) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            try {
                $stmt = $conn->prepare("UPDATE Users SET password = :password WHERE user_id = :user_id");
                $stmt->bindParam(':password', $hashed_new_password);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                header("Location: myprofile.php?success=password_changed");
                exit();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
                <h1 class="mt-4">My Profile</h1>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($_GET['success']) && $_GET['success'] == 'profile_updated'): ?>
                    <div class="alert alert-success">
                        Profile updated successfully.
                    </div>
                <?php endif; ?>
                <?php if(isset($_GET['success']) && $_GET['success'] == 'password_changed'): ?>
                    <div class="alert alert-success">
                        Password changed successfully.
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-user mr-1"></i>
                                Profile Details
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="username">Name</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Phone</label>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-key mr-1"></i>
                                Change Password
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
                                </form>
                            </div>
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
</body>
</html>

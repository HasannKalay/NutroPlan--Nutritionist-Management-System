<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
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
                <a href="recipes.php" class="list-group-item ">
                    <i class="fas fa-book"></i> Recipes
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
                <h1 class="mt-4">Recipes</h1>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-bar" placeholder="Search recipes...">
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
                        <i class="fas fa-book mr-1"></i>
                        Recipes List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Recipe ID</th>
                                        <th>Recipe Name</th>
                                        <th>Creation Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Healthy Salad</td>
                                        <td>2024-06-01</td>
                                        <td>
                                            <a href="viewrecipe.html" class="btn btn-info btn-sm" style="background-color: #35424a; border-color: #35424a;">View</a>
                                            <a href="editrecipe.html" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Edit</a>
                                            <a href="downloadrecipe.html" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Download</a>
                                            <a href="deleterecipe.html" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Protein Smoothie</td>
                                        <td>2024-06-01</td>
                                        <td>
                                            <a href="viewrecipe.html" class="btn btn-info btn-sm" style="background-color: #35424a; border-color: #35424a;">View</a>
                                            <a href="editrecipe.html" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Edit</a>
                                            <a href="downloadrecipe.html" class="btn btn-primary btn-sm" style="background-color: #e8491d; border-color: #e8491d;">Download</a>
                                            <a href="deleterecipe.html" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                    <!-- Add more recipes as needed -->
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
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutroPlan - Login</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.svg">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <img src="../images/logo.svg" alt="NutroPlan Logo" class="logo">
                <h1><a href="../index.php" style="text-decoration: none; color: white;"><span style="color: #e8491d;">Nutro</span>Plan</a></h1>
            </div>
            <nav>
                <ul class="nav d-none d-lg-flex">
                    <li class="nav-item"><a class="text-white" href="pricing.php">Pricing</a></li>
                    <li class="nav-item"><a class="text-white" href="about.php">About</a></li>
                    <li class="nav-item"><a class="text-white" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary" href="register.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main style="padding-top: 100px; color: #000;">
        <section class="login-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="text-center mb-4">Login to NutroPlan</h2>
                                <?php
                                session_start();
                                if(isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger">
                                        <?php
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <form action="authenticate.php" method="post">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                    <div class="text-center mt-3">
                                        <a href="forgot-password.html" class="text-secondary">Forgot password?</a>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p>Don't have an account? <a href="register.php" class="text-primary">Register</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="footer bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5>About Us</h5>
                    <p>NutroPlan helps nutrition professionals manage their practice efficiently.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="pricing.php" class="text-white">Pricing</a></li>
                        <li><a href="about.php" class="text-white">About</a></li>
                        <li><a href="about.php" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt"></i> Emniyettepe, Kazım Karabekir Cd. No: 2/13, 34060 Eyüpsultan/İstanbul</li>
                        <li><i class="fas fa-phone"></i> (+90) 534 987 6543</li>
                        <li><i class="fas fa-envelope"></i> info@nutroplan.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com/" class="text-white"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="https://x.com/home" class="text-white"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="https://www.linkedin.com/" class="text-white"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                        <li><a href="https://www.instagram.com/" class="text-white"><i class="fab fa-instagram"></i> Instagram</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; 2024 NutroPlan. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

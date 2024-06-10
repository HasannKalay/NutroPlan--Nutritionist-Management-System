<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['verification_code'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_code = $_POST['verification_code'];
    if ($input_code == $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']);  // Doğrulama kodunu temizle
        
        // Kullanıcı rolüne göre yönlendirme
        if ($_SESSION['role'] == 'admin') {
            header("Location: dashboard/admindashboard.php");
        } else {
            header("Location: dashboard/dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Two-Factor Authentication</h2>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="verification_code">Verification Code</label>
                                <input type="text" class="form-control" id="verification_code" name="verification_code" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Verify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
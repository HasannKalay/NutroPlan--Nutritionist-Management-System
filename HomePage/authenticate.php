<?php
include '../database/db.php';
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // PHPMailer için autoload

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Kullanıcı oturum bilgilerini ayarla
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Doğrulama kodunu oluştur
            $verification_code = rand(100000, 999999);
            $_SESSION['verification_code'] = $verification_code;

            // Doğrulama kodunu e-posta ile gönder
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com'; // SMTP sunucu adresinizi girin
                $mail->SMTPAuth = true;
                $mail->Username = 'nutroplanproject@outlook.com'; // SMTP kullanıcı adınızı girin
                $mail->Password = 'Nutro1234'; // SMTP şifrenizi girin
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Alıcı ve gönderici bilgilerini ayarlayın
                $mail->setFrom('nutroplanproject@outlook.com', 'NutroPlan');
                $mail->addAddress($email, $user['username']);

                // E-posta içeriği
                $mail->isHTML(true);
                $mail->Subject = 'Your NutroPlan Verification Code';
                $mail->Body = "Your verification code is: <b>$verification_code</b>";

                $mail->send();
            } catch (Exception $e) {
                $_SESSION['error'] = 'Verification email could not be sent. Please try again.';
                header("Location: login.php");
                exit();
            }

            // verify.php sayfasına yönlendir
            header("Location: verify.php");
            exit();
        } else {
            $error = "Invalid email or password. Please try again.";
            $_SESSION['error'] = $error;
            header("Location: login.php");
            exit();
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $report_name = $_POST['report_name'];
    $patient_id = $_POST['patient_id'];
    $report_type = $_POST['report_type'];
    $report_date = date('Y-m-d');
    $report_file = $_FILES['report_file'];

    // Check if the file is a valid PDF
    if ($report_file['type'] != 'application/pdf') {
        echo "Only PDF files are allowed.";
        exit();
    }

    // Create a unique file name
    $file_name = uniqid() . '-' . basename($report_file['name']);
    $target_file = "../../uploads/reports/" . $file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($report_file['tmp_name'], $target_file)) {
        try {
            // Insert report into the database
            $stmt = $conn->prepare("INSERT INTO Reports (user_id, patient_id, report_name, report_type, date, file_path) VALUES (:user_id, :patient_id, :report_name, :report_type, :date, :file_path)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':patient_id', $patient_id);
            $stmt->bindParam(':report_name', $report_name);
            $stmt->bindParam(':report_type', $report_type);
            $stmt->bindParam(':date', $report_date);
            $stmt->bindParam(':file_path', $target_file);
            $stmt->execute();

            header("Location: reports.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit();
    }
}
?>

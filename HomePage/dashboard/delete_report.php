<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $_POST['report_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Get the file path before deleting the report
        $stmt = $conn->prepare("SELECT file_path FROM reports WHERE report_id = :report_id AND user_id = :user_id");
        $stmt->bindParam(':report_id', $report_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $report = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($report) {
            // Delete the file from the server
            if (file_exists($report['file_path'])) {
                unlink($report['file_path']);
            }

            // Delete the report from the database
            $stmt = $conn->prepare("DELETE FROM reports WHERE report_id = :report_id AND user_id = :user_id");
            $stmt->bindParam(':report_id', $report_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Report not found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}
?>

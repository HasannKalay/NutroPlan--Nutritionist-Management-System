<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access"]);
    exit();
}

include '../../database/db.php';

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    try {
        $stmt = $conn->prepare("SELECT * FROM BodyMetrics WHERE patient_id = :patient_id");
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->execute();
        $metrics = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($metrics) {
            echo json_encode($metrics);
        } else {
            echo json_encode(["status" => "error", "message" => "No body metrics found for the given patient ID"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error fetching body metrics: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Patient ID not provided"]);
}
?>

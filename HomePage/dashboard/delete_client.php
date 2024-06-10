<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../../database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $patient_id = $_POST['patient_id'];

    try {
        // Begin a transaction
        $conn->beginTransaction();

        // Delete the associated reports
        $stmt = $conn->prepare("DELETE FROM reports WHERE patient_id = :patient_id");
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->execute();

        // Delete the associated body metrics
        $stmt = $conn->prepare("DELETE FROM BodyMetrics WHERE patient_id = :patient_id");
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->execute();

        // Delete the patient
        $stmt = $conn->prepare("DELETE FROM Patients WHERE patient_id = :patient_id AND user_id = :user_id");
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        echo json_encode(["status" => "success"]);
    } catch (PDOException $e) {
        // Roll back the transaction if something failed
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}
?>

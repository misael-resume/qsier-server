<?php
require_once('connectDB.php'); 
$id = isset($_GET["id"]) ? $_GET["id"] : null; 
if ($id) {
    $sql = "DELETE FROM barang WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            $response = [
                "success" => true,
                "message" => "Data deleted successfully."
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "No data found to delete."
            ];
        }
    } catch (PDOException $e) {
        $response = [
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ];
    }
} else {
    $response = [
        "success" => false,
        "message" => "ID report is required."
    ];
}

echo json_encode($response);
$conn = null; 
?>
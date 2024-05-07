<?php
require_once('connectDB.php');

try {
    $stmt = $conn->query('SELECT 1');
    
    if ($stmt) {
        $response = array('message' => "Connected", 'success' => true);
    } else {
        $response = array('message' => "Error: Unable to execute query", 'success' => false);
    }

    echo json_encode($response);
} catch (PDOException $e) {
    $response = array('message' => "Error: " . $e->getMessage(), 'success' => false);
    echo json_encode($response);
}
?>

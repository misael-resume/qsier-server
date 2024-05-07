<?php
require_once('connectDB.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $nama = $_GET["nama"];
    $harga = $_GET["harga"];
    $stok = $_GET["stok"];
    $barcode = $_GET["barcode"];
    $tanggal = time();
            
    try {
        $sql = "INSERT INTO barang (nama, harga, stok, barcode, tanggal) 
                VALUES (:nama, :harga, :stok, :barcode, :tanggal)";
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->execute();
        
        http_response_code(201); // Created
        echo json_encode(array('message' => 'New data has been successfully saved.', 'success' => true));
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array('message' => "Error: " . $e->getMessage(), 'success' => false));
    }
    // Don't set $conn to null here if you plan to use it later.
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('message' => 'Method Not Allowed', 'success' => false));
}
?>
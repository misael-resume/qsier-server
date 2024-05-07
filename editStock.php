<?php
require_once('connectDB.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET["id"];
    $nama = $_GET["nama"];
    $harga = $_GET["harga"];
    $stok = $_GET["stok"];
    $barcode = $_GET["barcode"];
            
    try{
        $sql = "UPDATE barang SET nama=:nama,harga=:harga,stok=:stok,barcode=:barcode WHERE id=:id";
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nama', $nama,PDO::PARAM_STR);
        $stmt->bindParam(':harga', $harga,PDO::PARAM_INT);
        $stmt->bindParam(':stok', $stok,PDO::PARAM_INT);
        $stmt->bindParam(':barcode', $barcode,PDO::PARAM_STR);
        $stmt->execute();
        
        $response = array('message' => 'Data changed successfully.', 'success' => true);
        echo json_encode($response);
    } catch  (PDOException $e) {
        $response = array('message' => "Error: " . $e->getMessage(), 'success' => false);
        echo json_encode($response);
    }
    $conn = null;
}else{
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('message' => 'Method Not Allowed', 'success' => false));

}

?>

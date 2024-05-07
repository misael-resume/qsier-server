<?php
require_once('connectDB.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($data['id']) || !isset($data['jumlah']) || !isset($data["idgroup"])) {
        http_response_code(400); // Bad Request
        echo json_encode(array('message' => 'Invalid input data', 'success' => false));
        exit;
    }
    
    $idbarang = $data['id'];
    $jumlah = $data['jumlah'];
    $idgroup = $data["idgroup"];
    $idorder = $data["idorder"];
    $tanggal = $data["tanggal"];
    $tunai = $data["tunai"];
    $totalharga = 0;
    
    try {
        $count = count($idbarang);
        if ($count != count($jumlah)) {
            http_response_code(400); // Bad Request
            echo json_encode(array('message' => 'Arrays have different lengths', 'success' => false));
            exit;
        }
        
        $conn->beginTransaction(); // Start transaction
        
        // Insert values into 'penjualan' table
        $stmt_penjualan = $conn->prepare("INSERT INTO penjualan (idbarang, idgroup, jumlah) VALUES (:idbarang, :idgroup, :jumlah)");
        $stmt_penjualan->bindParam(':idgroup', $idgroup);
        for ($i = 0; $i < count($idbarang); $i++) {
            $stmt_penjualan->bindParam(':idbarang', $idbarang[$i]);
            $stmt_penjualan->bindParam(':jumlah', $jumlah[$i]);
            $stmt_penjualan->execute();
            
            // Update stock
            $stmt_updatebarang = $conn->prepare("UPDATE barang SET stok = stok - 1 WHERE id = :id");
            $stmt_updatebarang->bindParam(':id', $idbarang[$i]);
            $stmt_updatebarang->execute();
        }
        
        // Calculate total harga
        $stmt_total = $conn->prepare("SELECT SUM(barang.harga * penjualan.jumlah) AS total_harga 
                                      FROM penjualan 
                                      JOIN barang ON penjualan.idbarang = barang.id 
                                      WHERE penjualan.idgroup = :idgroup");
        $stmt_total->bindParam(':idgroup', $idgroup);
        $stmt_total->execute();
        $result = $stmt_total->fetch(PDO::FETCH_ASSOC);
        $totalharga = $result['total_harga'];
    
        // Insert values into 'transaksi' table
        $sql = "INSERT INTO transaksi (idgroup, idorder, tanggal, harga, jumlah, tunai) VALUES (:idgroup, :idorder, :tanggal, :harga, :jumlah, :tunai)";
        $stmt_transaksi = $conn->prepare($sql);
        $stmt_transaksi->bindParam(':idgroup', $idgroup);
        $stmt_transaksi->bindParam(':idorder', $idorder);
        $stmt_transaksi->bindParam(':harga', $totalharga);
        $stmt_transaksi->bindParam(':tanggal', $tanggal);
        $stmt_transaksi->bindParam(':jumlah', $count);
        $stmt_transaksi->bindParam(':tunai', $tunai);
        $stmt_transaksi->execute();
        
        $conn->commit(); // Commit transaction
        
        http_response_code(201); // Created
        echo json_encode(array('message' => 'New Transaction has been successfully saved.', 'success' => true));
    } catch (PDOException $e) {
        $conn->rollBack(); // Rollback transaction on error
        http_response_code(500); // Internal Server Error
        // Log or handle the exception $e appropriately
        echo json_encode(array('message' => 'Internal Server Error', 'success' => false));
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('message' => 'Method Not Allowed', 'success' => false));
}
?>

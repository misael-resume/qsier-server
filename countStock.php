<?php
include('connectDB.php');

try {
    $stmt = $conn->prepare("SELECT SUM(stok) AS jumlah FROM barang");
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $jumlah_barang = $result['jumlah'];

    $response = array('message' => $jumlah_barang, 'success' => true);
    echo json_encode($response);
} catch (PDOException $e) {
    $response = array('message' => 'Terjadi kesalahan: ' . $e->getMessage(), 'success' => false);
    echo json_encode($response);
}
?>

<?php
require_once('connectDB.php');

try {
    $keyword = $_GET['idgroup'];

    $sql = "SELECT penjualan.idbarang, penjualan.idgroup, penjualan.jumlah, barang.nama, barang.harga, barang.barcode
            FROM penjualan
            INNER JOIN barang ON penjualan.idbarang = barang.id
            WHERE penjualan.idgroup=:idgroup";

    $stmt = $conn->prepare($sql); 
    $stmt->bindValue(':idgroup', $keyword); 
    $stmt->execute();
    
    $data = array();

    if ($stmt->rowCount() > 0) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        
        $response = json_encode($data); 

        echo $response;
    } else {

        echo json_encode(array('message' => 'No data found'));
    }
} catch(PDOException $e) {
    echo json_encode(array('error' => $e->getMessage()));
}

$conn = null; 
?>

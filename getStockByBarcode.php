<?php
require_once('connectDB.php');

try {
    $keyword = $_GET['barcode'];


 $sql = "SELECT * FROM barang WHERE barcode=:keyword OR nama=:keyword LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    $stmt->bindparam(":keyword",$keyword, PDO::PARAM_STR);
    $stmt->execute();

    $stock = array();

 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $stock[] = $row;
        
    }


    $response = json_encode($stock);

    echo $response;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
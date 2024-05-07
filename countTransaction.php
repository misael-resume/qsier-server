<?php
require_once('connectDB.php'); 

$currentMonth = date('m');
$currentYear = date('Y');
$startDate = strtotime(date('Y-m-01'));
$endDate = strtotime(date('Y-m-t'));


try {
    $stmt = $conn->prepare("SELECT SUM(harga) AS totaltransaksi FROM transaksi WHERE tanggal BETWEEN :mulai AND :akhir");
    $stmt->bindParam(':mulai', $startDate, PDO::PARAM_INT);
    $stmt->bindParam(':akhir', $endDate, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $totaltransaksi = $result['totaltransaksi'];

    $response = array('message' => $totaltransaksi, 'success' => true);
    echo json_encode($response);
} catch (PDOException $e) {
    $response = array('message' => 'Error: ' . $e->getMessage(), 'success' => false);
    echo json_encode($response);
}



?>

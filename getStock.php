<?php
include('connectDB.php');

try {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

    $offset = ($page - 1) * $limit;

    $sql = "SELECT * FROM barang ORDER BY tanggal Desc LIMIT :offset, :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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

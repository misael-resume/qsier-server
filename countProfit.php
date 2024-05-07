<?php
require_once("connectDB.php");

$limityear = isset($_GET["limityear"]) ? $_GET["limityear"] : date("Y"); 

$sql = "SELECT * FROM transaksi";
$stmt = $conn->prepare($sql);
$stmt->execute();

$pendapatan_per_bulan = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $tanggal = date('F', $row["tanggal"]);
    
    if(date("Y", $row["tanggal"]) == $limityear){
        if (!isset($pendapatan_per_bulan[$tanggal])) {
            $pendapatan_per_bulan[$tanggal] = 0;
        }    
        $pendapatan_barang = $row["harga"];
        $pendapatan_per_bulan[$tanggal] += $pendapatan_barang;
    }

}
$json_array = array();
foreach ($pendapatan_per_bulan as $bulan => $pendapatan) {
    $json_array[] = array("month" => $bulan, "profit" => $pendapatan);
}
echo json_encode($json_array);
$conn = null;
?>

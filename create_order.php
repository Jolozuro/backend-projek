<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['total_bayar'])) {
    echo json_encode(["status" => "error", "message" => "total_bayar tidak dikirim"]);
    http_response_code(400);
    exit();
}

$total = $data['total_bayar'];
$tanggal = date("Y-m-d H:i:s");

// 1. Tambah ke tabel pesanans
$conn->query("INSERT INTO pesanans (tanggal, total_bayar) VALUES ('$tanggal', $total)");
$pesanan_id = $conn->insert_id;

// 2. Ambil data dari keranjangs
$result = $conn->query("SELECT * FROM keranjangs");

while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $jumlah = $row['jumlah'];
    $total_harga = $row['total_harga'];

    $conn->query("INSERT INTO detail_pesanan (pesanan_id, product_id, jumlah, total_harga)
                  VALUES ($pesanan_id, $product_id, $jumlah, $total_harga)");
}

// 3. Kosongkan keranjangs
$conn->query("DELETE FROM keranjangs");

echo json_encode(["status" => "success", "pesanan_id" => $pesanan_id]);
?>

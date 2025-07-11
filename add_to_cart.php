<?php
include 'config.php';

// Ambil data dari frontend
$data = json_decode(file_get_contents("php://input"), true);

$product = $data['product'];
$product_id = (int)$product['id'];
$jumlah = (int)$data['jumlah'];
$total_harga = (int)$data['total_harga'];
$keterangan = mysqli_real_escape_string($conn, $data['keterangan'] ?? '');

// Cek apakah sudah ada item dengan product_id DAN keterangan yang sama
$query = "SELECT * FROM keranjangs WHERE product_id = $product_id AND keterangan = '$keterangan'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Sudah ada, update jumlah dan total_harga
    $row = mysqli_fetch_assoc($result);
    $id = (int)$row['id'];
    $newJumlah = (int)$row['jumlah'] + $jumlah;
    $newTotal = (int)$row['total_harga'] + $total_harga;

    $update = "UPDATE keranjangs 
               SET jumlah = $newJumlah, total_harga = $newTotal 
               WHERE id = $id";
    mysqli_query($conn, $update);
} else {
    // Belum ada, insert data baru ke keranjangs
    $insert = "INSERT INTO keranjangs (product_id, jumlah, total_harga, keterangan)
               VALUES ($product_id, $jumlah, $total_harga, '$keterangan')";
    mysqli_query($conn, $insert);
}

$response = ['message' => 'Success'];
header('Content-Type: application/json');
echo json_encode($response);

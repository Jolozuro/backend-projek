<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
include 'config.php';

// lanjutkan kode...


$result = $mysqli->query("SELECT keranjangs.*, products.nama AS nama, products.harga AS harga, products.kode AS kode
                          FROM keranjangs
                          JOIN products ON keranjangs.product_id = products.id");

$data = [];
while ($row = $result->fetch_assoc()) {
    $row['product'] = [
        'id' => $row['product_id'],
        'nama' => $row['nama'],
        'harga' => $row['harga'],
        'kode' => $row['kode']
    ];
    unset($row['nama'], $row['harga'], $row['kode'], $row['product_id']);
    $data[] = $row;
}

echo json_encode($data);
?>

<?php
header("Access-Control-Allow-Origin: *"); // Izinkan semua origin (React bisa akses)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Izinkan method-method umum
header("Access-Control-Allow-Headers: Content-Type"); // Izinkan header seperti Content-Type
include 'config.php';

// lanjutkan kode...


header("Content-Type: application/json");

$category = isset($_GET['category']) ? $_GET['category'] : null;

if ($category) {
    $stmt = $conn->prepare("
        SELECT products.*, categories.nama AS kategori_nama 
        FROM products 
        JOIN categories ON products.category_id = categories.id 
        WHERE categories.nama = ?
    ");
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare("
        SELECT products.*, categories.nama AS kategori_nama 
        FROM products 
        JOIN categories ON products.category_id = categories.id
    ");
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>

<?php
// Header CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

// Ambil data dari body
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID tidak ditemukan"]);
    exit();
}

$id = intval($data['id']);

try {
    // Cek apakah data dengan ID tersebut ada
    $cek = $conn->prepare("SELECT id FROM keranjangs WHERE id = ?");
    $cek->bind_param("i", $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["error" => "Pesanan tidak ditemukan"]);
        $cek->close();
        exit();
    }
    $cek->close();

    // Lanjut hapus
    $stmt = $conn->prepare("DELETE FROM keranjangs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "message" => "Pesanan berhasil dihapus",
            "id" => $id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Gagal menghapus pesanan"]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Terjadi kesalahan: " . $e->getMessage()]);
}

<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

$query = mysqli_query($conn, "SELECT * FROM categories");
$results = [];

while ($row = mysqli_fetch_assoc($query)) {
  $results[] = $row;
}

echo json_encode($results);
?>

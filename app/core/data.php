<?php
header("Access-Control-Allow-Origin: *");

$data = array("name" => "John", "age" => 30);

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($data);
?>

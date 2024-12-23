<?php
//header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$result;
if($method == 'GET')
{
    $sql = "SELECT * FROM icons";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //echo json_encode($result);
}
?>

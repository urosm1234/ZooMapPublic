<?php

require 'db.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST' && isset($_SESSION['user']))
{
    $id = $_POST['id'];
    $coordinateW = $_POST['coordinatesw'];
    $coordinateH = $_POST['coordinatesh'];
    echo $coordinateH;
    echo " ";
    $sql = "UPDATE icons SET coordinatew = :coordw, coordinateh = :coordh WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":coordw", $coordinateW);
    $stmt->bindParam(":coordh", $coordinateH);
    $result = $stmt->execute();
    echo $result;
    //echo json_encode($result);
}
else
echo "Error";
?>

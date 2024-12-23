<?php

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST')
{
    $user = $_POST['name'];
    $coordinateW = $_POST['coordinatesw'];
    $coordinateH = $_POST['coordinatesh'];
    echo $coordinateH;
    echo " ";
    $sql = "UPDATE icons SET coordinatew = :coordw, coordinateh = :coordh WHERE name = :user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":user", $user, PDO::PARAM_STR);
    $stmt->bindParam(":coordw", $coordinateW, PDO::PARAM_INT);
    $stmt->bindParam(":coordh", $coordinateH, PDO::PARAM_INT);
    $result = $stmt->execute();
    echo $result;
    //echo json_encode($result);
}
?>
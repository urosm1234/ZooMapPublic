<?php

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$result;
if($method == 'GET')
{
    $sql = "SELECT icons.id, icons.name, icons.coordinatew, icons.coordinateh, animal_info.title, animal_info.desc1, animal_info.desc2, animal_info.desc3, animal_info.paragraph FROM `icons` LEFT JOIN (`animal_info`) ON (animal_info.id = icons.animal_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //echo json_encode($result);
}

$PATH = "/ZooProject/ZooMap/";

?>
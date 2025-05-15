<?php

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$result;
if($method == 'GET')
{
    $sql = "SELECT icons.id, icons.name, icons.coordinatew, icons.coordinateh, animal_info.title, animal_info.id as pane_id,animal_info.latin_title, animal_info.red, animal_info.porodica, animal_info.staniste, animal_info.zivotni_vek, animal_info.rasprostranjenost, animal_info.tekst, animal_info.endangered_level, animal_info.klasa FROM `icons` LEFT JOIN (`animal_info`) ON (animal_info.id = icons.animal_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($result, JSON_UNESCAPED_UNICODE);
}

$PATH = "./";

?>

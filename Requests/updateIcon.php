<?php
require "db.php";

session_start();

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST' && isset($_SESSION['user']))
{
    $id = $_POST['icon_id'];
    $animal_id = $_POST['pane_id'];

    $sql = "UPDATE icons SET animal_id =:animal_id WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":animal_id",$animal_id);
    $stmt->bindParam(":id",$id);

    $response = $stmt->execute();
    echo $response;
}
else {
  echo "Fail";
}

?>

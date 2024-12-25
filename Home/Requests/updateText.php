<?php

require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST' && isset($_SESSION['user']))
{
    $id = $_POST['id'];
    $sql = "SELECT animal_id FROM icons where id=:id";
    $stmt1 = $pdo->prepare($sql);
    $stmt1->bindParam(":id",$id, PDO::PARAM_INT);
    $stmt1->execute();
    $animal_id = $stmt1->fetchColumn();

    if($animal_id != null)
    {
        $title = $_POST['title'];
        $desc1 = $_POST['desc1'];
        $desc2 = $_POST['desc2'];
        $desc3 = $_POST['desc3'];
        $paragraph = $_POST['paragraph'];
        
        $sql = "UPDATE animal_info SET title = :title, desc1 = :desc1, desc2 = :desc2, desc3 = :desc3, paragraph = :paragraph WHERE id = :animal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":animal_id", $animal_id, PDO::PARAM_INT);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":desc1", $desc1, PDO::PARAM_STR);
        $stmt->bindParam(":desc2", $desc2, PDO::PARAM_STR);
        $stmt->bindParam(":desc3", $desc3, PDO::PARAM_STR);
        $stmt->bindParam(":paragraph", $paragraph, PDO::PARAM_STR);
        $result = $stmt->execute();
        if($result == 1)
        echo "Success";
        else
        echo "Fail";
    }
    else
    echo "Not Found";

    //echo json_encode($result);
}
?>
<?php

require 'db.php';

session_start();

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
        $latin_title = $_POST['latin_title'];
        $red = $_POST['red'];
        $porodica = $_POST['porodica'];
        $staniste = $_POST['staniste'];
        $zivotni_vek = $_POST['zivotni_vek'];
        $rasprostranjenost = $_POST['rasprostranjenost'];
        $klasa = $_POST['rasprostranjenost'];
        $endangered_level = $_POST['endangered_level'];
        $tekst = $_POST['tekst'];
        
        $sql = "UPDATE animal_info SET title = :title,latin_title = :latin_title, red = :red, porodica = :porodica,  porodica = :porodica, staniste =:staniste, zivotni_vek = :zivotni_vek, rasprostranjenost = :rasprostranjenost, endangered_level = :endangered_level, tekst = :tekst WHERE id = :animal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":animal_id", $animal_id, PDO::PARAM_INT);

        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":latin_title", $latin_title, PDO::PARAM_STR);
        $stmt->bindParam(":red", $red, PDO::PARAM_STR);
        $stmt->bindParam(":porodica", $porodica, PDO::PARAM_STR);
        $stmt->bindParam(":staniste", $staniste, PDO::PARAM_STR);
        $stmt->bindParam(":zivotni_vek", $zivotni_vek, PDO::PARAM_STR);
        $stmt->bindParam(":rasprostranjenost", $rasprostranjenost, PDO::PARAM_STR);
        $stmt->bindParam(":klasa", $klasa, PDO::PARAM_STR);
        $stmt->bindParam(":endangered_level", $endangered_level, PDO::PARAM_STR);
        $stmt->bindParam(":tekst", $tekst, PDO::PARAM_STR);
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

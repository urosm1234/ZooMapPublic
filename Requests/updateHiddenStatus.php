<?php

require "db.php";

session_start();

$method = $_SERVER["REQUEST_METHOD"];

if($method == "POST" && isset($_SESSION['user']))
{
  $id = $_POST['id'];
  $newStatus = $_POST['hidden'];

  $sql = "UPDATE icons SET hidden = :hidden WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(":hidden", $newStatus);
  $stmt->bindParam(":id", $id);

  $res = $stmt->execute();
  
  if($res)
  {
    echo 1;
    return;
  }

  echo 0;
  return;
  
}
else
  echo "Not signed in.";
?>

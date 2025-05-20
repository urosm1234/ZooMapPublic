<?php

require "db.php";

session_start();

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'DELETE' && isset($_SESSION['user'])){

  $id = $_GET['pane_id'];
  $name = $_GET['name'];
  if(!$id)
  {
    echo "Invalid id";
    return;
  }

  if(!$name)
  {
    echo "Invalid name";
    return;
  }

  $sql = "SELECT * FROM icons WHERE animal_id = :animal_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(":animal_id", $id);
  $res =  $stmt->execute();

  if($res && $stmt->fetch(PDO::FETCH_NUM))
  {
    echo "More icons remaining";
    return;
  } 

  $sql = "DELETE FROM animal_info where id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(":id", $id);
  $res = $stmt->execute();

  if($res)
  {
    echo "Deleted Info";
  }
  else
  {
    echo "Deletion query failed";
    return;
  }

  $res = -1;

  if(file_exists("../new_images/animal" . $id . ".jpg"))
    $res = unlink("../new_images/animal" . $id . ".jpg");

  if(file_exists("../new_images/animal" . $id . ".png"))
    $res = unlink("../new_images/animal" . $id . ".png");


  $res = -1;

  if(file_exists("../images/new_icons/" . $name . ".png")) 
  $res = unlink("../images/new_icons/" . $name . ".png");

  if(file_exists(("../images/new_icons/" . $name . ".jpg")))
    $res = unlink("../images/new_icons/" . $name . ".jpg");



  return;

}
else
echo "Not signed in";

?>

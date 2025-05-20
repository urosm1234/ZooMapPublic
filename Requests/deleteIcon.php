<?php

require "db.php";

session_start();
$method = $_SERVER['REQUEST_METHOD'];

if($method == 'DELETE' && isset($_SESSION['user'])){


  $id = $_GET["icon_id"];
  if(!$id)
  {
    echo "Invalid id";
    return;
  }

  $sql = "DELETE FROM icons where id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(":id", $id);
  $res = $stmt->execute();

  if($res)
  {
    echo "Deleted Icon";
  }
  else
  {
    echo "Deletion query failed";
    return;
  }

}
else
echo "Not signed in";

?>

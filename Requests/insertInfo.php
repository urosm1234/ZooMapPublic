<?php
include 'db.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
 

if($method == "POST" && isset($_SESSION['user']))
{
  try{
  $name = $_POST['name'];

  $sql = "INSERT INTO animal_info(`title`) VALUES(:name);";
  $stmt = $pdo -> prepare($sql);
  $stmt->bindParam(":name", $name);
  $result = $stmt->execute();
  if(!$result)
    return "Fail";

  $sth = $pdo->prepare("SELECT MAX(id) FROM animal_info");
  $sth->execute();
  $res = $sth->fetchColumn();
  header('Content-Type: application/json');
  if($res)
  {
    echo json_encode(["id"=>$res]);
    return;
  }
    else return "Critical Fail";
  }
  catch(Exception $e)
  {
    echo $e.getMessage();
  }
}
?>

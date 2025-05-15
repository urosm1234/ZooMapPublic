<?php
require "db.php";

session_start();

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST' && isset($_SESSION['user'])&& isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
{
  $path = $_POST['path'];
  $id = $_POST['id'];

  if($path != "..\\new_images\\" && $path != "..\\images\\new_icons\\")
  {
    echo "Error - invalid PATH";
    echo $path;
    return;
  }

  $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  if($extension != "jpg" && $extension != "png")
  {
    echo "Error - invalid File Type";
    echo $extension;
    return;
  }


  $unique_name;
  if($path == "..\\new_images\\")
    $unique_name = "animal".$id;
  elseif (isset($_POST['name'])) {
    $unique_name = $_POST['name'];
  }
  else
  {
    echo "Error - something went wrong";
    return;
  }

  $target_file = $path .$unique_name.".". $extension; 

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    echo "The file has been uploaded.";
    return 1; 
  } else {
      echo "Sorry, there was an error uploading your file.";
      return 0;
  }
  
}
echo "Error - invalid file";

?>

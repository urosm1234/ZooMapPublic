<?php

require 'db.php';

session_start();

$_SESSION = array();
session_destroy();

header('Location: /ZooProject/ZooMap/Home/Login.php');
exit();

?>

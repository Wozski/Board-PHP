<?php 
  require_once("conn.php");
  session_start();
  session_destroy();
  header("Location: index.php");
  //刪除 token
  /*
  $token = $_COOKIE["token"];
  $sql = sprintf(
  	"delete from tokens where token='%s'",
  	$token
  );
  $conn->query($sql);
  if (!empty($_COOKIE)) {
    setcookie("token", "", time() - 3600 );
    header("Location: index.php");
  }*/
?>
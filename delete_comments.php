<?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();
  if (
    empty($_GET["id"])) {
    header("Location: index.php?errCode=1");
    die("資料不齊全");
  }
  $id = $_GET['id'];
  $username = $_SESSION['username'];
  $user = getUserFromSession($username);
  $sql = "update comments set is_deleted=1 where 
  id=? and username=?";
  if (isAdmin($user)) {
    $sql = "update comments set is_deleted=1 where 
  id=?";
  }
  $stmt = $conn->prepare($sql);
  if (isAdmin($user)) {
    $stmt->bind_param('i', $id);
  } else {
    $stmt->bind_param('sis', $content, $id, $username);    
  }
  $result = $stmt->execute();
  /*if (!empty($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $sql = "update comments set is_deleted=1 where 
    id=? and username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $id, $username);
    $result = $stmt->execute();
  }*/
  if (!$result) {
    die($conn->error);
  }
  header("Location: index.php");
?>
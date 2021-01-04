<?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();
  if (
    empty($_POST["content"])) {
    header("Location: update_comments.php?errCode=1&id=" . $_POST['id']);
    die("資料不齊全");
  }
  $username = $_SESSION['username'];
  $user = getUserFromSession($username);
  $id = $_POST['id'];
  $content = $_POST['content'];

  $sql = "update comments set content=? where id=? and username=?";
  if (isAdmin($user)) {
    $sql = "update comments set content=? where id=?";
  }
  $stmt = $conn->prepare($sql);
  if (isAdmin($user)) {
    $stmt->bind_param('si', $content, $id,);
  } else {
    $stmt->bind_param('sis', $content, $id, $username);    
  }
  $result = $stmt->execute();
  /*if (!empty($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $sql = "update comments set content=? where id=? and username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sis', $content, $id, $username);
    $result = $stmt->execute();
  }*/
  if (!$result) {
    die($conn->error);
  }
  header("Location: index.php");
?>
<?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();

  $username = $_SESSION['username'];
  $role = $_POST['role'];
  if (!empty($_SESSION["username"])) {
    $username = $_POST["username"];
    $sql = "update users set role=? where username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $role, $username);
    $result = $stmt->execute();
  }
  if (!$result) {
    die($conn->error);
  }
  header("Location: admin.php");
?>
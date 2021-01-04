<?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();;
  if (
    empty($_POST["nickname"])) {
    header("Location: index.php?errCode=1");
    die("資料不齊全");
  }
  $username = $_SESSION['username'];
  $nickname = $_POST['nickname'];
  if (!empty($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $sql = "update users set nickname=? where username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $nickname, $username);
    $result = $stmt->execute();
  }
  if (!$result) {
    die($conn->error);
  }
  header("Location: index.php");
?>
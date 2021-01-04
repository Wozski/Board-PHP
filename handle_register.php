<?php
  session_start();
  require_once("conn.php");
  if (
    empty($_POST["username"]) ||
    empty($_POST["password"]) ||
    empty($_POST["nickname"])
    ) 
  {
    header("Location: register.php?errCode=1");
    die ("傻瓜，你有東西忘記輸入了！");
  }
  $nickname = $_POST["nickname"];
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $sql = "insert into users(nickname, username, password) values(?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $nickname, $username, $password);
  $result = $stmt->execute();
  // 帳號重複
  if (!$result) {
    if($conn->errno === 1062) {
      header("Location: register.php?errCode=2");
    }
    die($conn->error);
  }
  $_SESSION['username'] = $username;
  header("Location: index.php");
?>
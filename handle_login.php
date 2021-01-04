<?php 
  require_once("conn.php");
  require_once("utils.php");
  session_start();
  if (
  	empty($_POST["username"]) ||
    empty($_POST["password"])
    ) 
  {
  	header("Location: login.php?errCode=1");
  	die ("傻瓜，你有東西忘記輸入了！");
  }
  $username = $_POST["username"];
  $password = $_POST["password"];
  $sql = "select * from users where username=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $result = $stmt->execute();
  if (!$result) {
  	die($conn->error);
  }
  //真正拿到結果
  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    header("Location: login.php?errCode=2");
    exit();
  }

  //有查到使用者
  $row = $result->fetch_assoc();
  if (password_verify($password, $row['password'])) {
    /* 
    1. 產生 session id(token)
    2. 把 username 寫入檔案
    3. set-cookie: session-id
    */
    $_SESSION["username"] = $username;
    header("Location: index.php");
  } else {
    header("Location: login.php?errCode=2");
  }
    /*產生 token 並儲存
    $token = generateToken();
    $username = $_SESSION["username"];
    print_r($username);
    /*$sql = sprintf(
      "insert into tokens(token, username) values ('%s', '%s')",
      $token,
      $username
    );
    $result = $conn->query($sql);
    if (!$result) {
    die($conn->error);
    }
    //setcookie("token", $token, time() + 3600 );
    //header("Location: index.php");
  } else {
    header("Location: login.php?errCode=2");
  }*/
?>
<?php
  session_start(); 
  require_once("conn.php");
  require_once("utils.php");

  $id = $_GET['id'];
  /* 
  1. 從 Cookie 裡面讀取 PHPSESSID(token)
  2. 從檔案裡面讀取 Session id 的內容
  3. 放到 $_SESSION
  */
  $username = NULL;
  $user = NULL;
  if (!empty($_SESSION["username"])) {
  	$username = $_SESSION["username"];
  	$user = getUserFromSession($username);
  }
  /*
  實作 Session 作法
  if (!empty($_COOKIE["token"])) {
  	$user = getUserFromToken($_COOKIE["token"]);
  	$username = $user["username"];
  	if($username === NULL) {
  		die("資料錯誤");
  	}
  }*/

  //顯示留言（如果放上面會導致 $result 結果被覆蓋無法正常顯示）
  $stmt = $conn->prepare(
  	'select * from comments where id = ?'
  );
  $stmt->bind_param('s', $id);
  $result = $stmt->execute();
  if (!$result) {
  	die ("Error:" . $conn->error);
  }
  //拿出結果
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>留言板</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
	<header class="warning">
		<strong>本網頁只限於練習，所以忽略了資安問題，切勿把真實帳號密碼輸入</strong>
	</header>
		<main class="board">
			<h1 class="board-title">編輯留言</h1>
			<?php 
			if(!empty($_GET["errCode"])) {
				$code = $_GET["errCode"];
				$msg = "Error";
			  if ($code === "1") {
			    $msg = "資料不齊全";
			  }
			  echo "<h2 class='error'>" . $msg . "</h2>";
			  }
			?>
	    <form method="POST" action="handle_update_comments.php">
		  <div>
		    <textarea name="content" rows="5"><?php
		    echo $row['content']; 
		    ?></textarea>
		    <input type="hidden" name="id" value="<?php echo $row['id']?>" />
		  </div>
		  <input type="submit" class="board-btn">
		  <a href="index.php" class="board-btn">返回</a>
		  <div class="board_hr"></div>
		</form>	  
	</main>
</body>
</html>
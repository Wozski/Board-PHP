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
		<div>
		  <a href="index.php" class="board-btn">回到上一頁</a>
		  <?php if (!empty($_COOKIE["username"])) {?>
		  <a href="register.php" class="board-btn">登出</a>
		  <?php } else {?>
		  <a href="register.php" class="board-btn">註冊</a>	
		  <?php } ?>
		</div>
		<h1 class="board-title">登入</h1>
		<?php 
		  if (!empty($_GET["errCode"])) {
		    $code = $_GET["errCode"];
		    $msg = "Error";
		  	if ($code === "1") {
		  		$msg = "資料有缺";
		  	} else if ($code === "2") {
		  		$msg = "查無此會員資料";
		  	}
		  	echo "<h2 class='error'>" . $msg . "</h2>";
		    }
		  	?>
		<form method="POST" action="handle_login.php">
			<div class="login-username">
			  帳號：
			  <input type="text" name="username"> 
			</div>
			<div class="login-username">
			  密碼：
			  <input type="password" name="password"> 
			</div>
			 <input type="submit" name="submit" class="submit-btn"> 
			<div class="board_hr"></div>
		</form>
	</main>
</body>
</html>
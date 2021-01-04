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
		  <a href="login.php" class="board-btn">登入</a>
		</div>
		<h1 class="board-title">註冊</h1>
		<form method="POST" action="handle_register.php">
			<?php if (!empty($_GET["errCode"])) {
				$code = $_GET["errCode"];
				$msg = "Error";
				if($code === "1") {
					$msg = "資料不齊全";
				} else {
					$msg = "帳號已經使用過";
				}
				echo "<h2 class='error'>" . $msg . "</h2>";
			    }
			 ?>
			<div class="login-username">
			  暱稱：
			  <input type="text" name="nickname"> 
			</div>
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
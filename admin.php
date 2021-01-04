 <?php
  require_once("conn.php");
  require_once("utils.php");
  session_start();
  $user = getUserFromSession($_SESSION['username']);
  if ($user['role'] != 2) {
  	header("Location: index.php");
  }
   
  $stmt = $conn->prepare(
    "select id, nickname, username, role from users order by id desc",
  );
  $result = $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  /*$sql = "select * from users where id=?";
  $stmt = $conn->prepare($sql);
  print_r($stmt);
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  print_r($result);
  print_r($row);*/
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
		<div>
		  <a href="index.php" class="board-btn">回到上一頁</a>
		  <a class="update board-btn">修改權限</a>
		  <form method="POST" action="update_admin.php" class="admin_Page">
		  	<div class="hide role_submit">帳號：
			  	<input type="text" name="username" />
			  	權限：
			    <input type="number" name="role" min="0" max="2" />
			    <input type="submit" />
		    </div>
		  </form>		  
		</div>
		<h1 class="board-title">管理者模式</h1>
		<h2>權限介紹：2 管理員, 1 一般用戶, 0 停權用戶</h2>
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
		<form method="POST" action="update_admin.php" class="admin_Page">
			<section>
				<table border>
					<tr>
						<th>id</th>
						<th>role</th>
						<th>nickname</th>
						<th>username</th>
					</tr>
			<?php while ($row = $result->fetch_assoc()) {?>
				<div class="login-username">
					<tr>
						<td><?php echo escape($row['id'])?></td>
						<td><?php echo escape($row['role'])?></td>
						<td><?php echo escape($row['nickname'])?></td>
						<td><?php echo escape($row['username'])?></td>
					</tr>
				</div>
		    <?php } ?>
			<div class="board_hr"></div>
              </table>
			</section>
		</form>
	</main>
	<script type="text/javascript">
		let admin_role = document.querySelector('.update');
		let hide_input = document.querySelector('.hide');
		admin_role.addEventListener('click', (e)=>
			{
				hide_input.classList.toggle('hide');			    
			});
	</script>
</body>
</html>
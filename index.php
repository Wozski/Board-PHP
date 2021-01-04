<?php
  session_start(); 
  require_once("conn.php");
  require_once("utils.php");
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
  $page = 1;
  if (!empty($_GET['page'])) {
  	$page = intval($_GET['page']);
  }
  $items_per_page = 5;
  $offset = ($page - 1) * $items_per_page;
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
  	'select '. 
  	  'Wozski_comments11.id as id, Wozski_comments11.content as content, '.
  	  'Wozski_comments11.create_at as create_at, Wozski_users11.nickname as nickname, Wozski_users11.username as username '.
  	'from comments as Wozski_comments11 '.
  	'left join Wozski_users11 as U on Wozski_comments11.username = Wozski_users11.username '.
  	'where Wozski_comments11.is_deleted is null '.  	
  	'order by Wozski_comments11.id desc '.
  	'limit ? offset ? '
  );
  $stmt->bind_param('ii', $items_per_page, $offset);
  $result = $stmt->execute();
  if (!$result) {
  	die ("Error:" . $conn->error);
  }
  //拿出結果
  $result = $stmt->get_result();
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
			<?php if (!empty($username)) {?>
				<a href="handle_logout.php" class="board-btn">登出</a>
				<span class='submit-btn update_nickname'>編輯暱稱</span>
				<?php if ($user['role'] == 2 ) {?>
				  <a class='board-btn' href="admin.php">管理員權限</a>
				<?php }?>
			<?php } else {?>
			<div>
			  <a href="register.php" class="board-btn">註冊</a>
			  <a href="login.php" class="board-btn">登入</a>
			</div>
		    <?php } ?>
			<h1 class="board-title">Comments</h1>
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
			<?php 
			if (!empty($username)) {?>
				<h2 class='nickname_title'>您好！<?php echo escape($user['nickname']) ?></h2>
				<form method="POST" action="update_user.php">
					<div class="update_user hide">
						<span>新的暱稱：</span>
						<input type="text" name="nickname">
						<input type="submit" class="board-btn">
					</div>
				</form>
				<form method="POST" action="handle_add_comment.php">
				    <div>
				      <?php /*if ($user['role'] != 0) {*/ if ($username && !hasPermission($user, 'create', Null)) {?>
				      	<h3>你已經被停權。</h3>
				      <?php } else if ($username) {?> 
					    <textarea name="content" rows="5"></textarea>
					     <input type="submit" class="board-btn">			
					  <?php }else {?>
					  	<h3>請登入後再留言</h3>
					  <?php }?> 
					</div>
					<div class="board_hr"></div>
					<?php }?>
					<section>
					<?php 
					  while ($row = $result->fetch_assoc()) {
					?>
					  <div class="card">
					    <div class="card__avatar"></div>
						  <div class="card__body">
						    <div class="card__info">
							    <span class="card__author">
								<? echo escape($row["nickname"]); ?> 
								(@<? echo escape($row["username"]); ?>)
								</span>
								<span class="card__time">
								<? echo escape($row["create_at"]); ?>
							    </span>
								<?php if ($username && hasPermission($user, 'update', $row) /*$row['username'] === $username || !empty($username) && $user['role'] == 2*/) {?>
								  <a href="update_comments.php?id=<?php echo $row['id']?>">編輯</a>
								  <a href="delete_comments.php?id=<?php echo $row['id']?>">刪除</a>
								  <?php } ?>
						    </div>
						    <p class="card__content"><? echo escape($row["content"]); ?></p>
						  </div>
						</div>
					<?php }?>
					</section>
					<div class="board_hr"></div>
					<?php 
					  $stmt = $conn->prepare(
					  	'select count(id) as count from comments where is_deleted is null'
					  );
					  $items_per_page = 5;
					  $result = $stmt->execute();
					  $result = $stmt->get_result();
					  $row = $result->fetch_assoc();
					  $count = $row['count'];
					  $total_page = ceil($count / $items_per_page);
					?>
					<div class="page-info">
					  <div>總共有 <?php echo $count ?> 筆資料， 
					  <span><?php echo $page?> / <?php echo $total_page ?></span> 分頁
					  </div>
					  <?php if ($page != 1) { ?>
					  	<a href="index.php?page=1">首頁</a>
					    <a href="index.php?page=<?php echo $page - 1 ?>">上一頁</a>
					  <?php } ?>
					  <?php if ($page != $total_page) {?>
					    <a href="index.php?page=<?php echo $page + 1 ?>">下一頁</a>
					    <a href="index.php?page=<?php echo $total_page ?>">最末頁</a>
					  <?php }?>
					</div>
		        </form>	  
	</main>
	<script type="text/javascript">
		const btn = document.querySelector(".update_nickname");
		const update_nickname = document.querySelector(".hide");
		console.log(update_nickname);
		btn.addEventListener("click", ()=> {
			update_nickname.classList.toggle("hide");
		})
	</script>
</body>
</html>
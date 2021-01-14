<?php
	session_start();
	if(isset($_GET['logout']))
	{
		unset($_SESSION['user']);
	}
	date_default_timezone_set('Asia/Kolkata');
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbname = 'gbook';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error <> 0) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$tip = '';
	if(isset($_GET['del']) && isset($_GET['id']))
	{
		$id = $_GET['id'];
		if($_GET['del'] == '1')
		{
			$sql = "DELETE FROM messages1 WHERE id = {$id}";
			$del = $conn->query($sql);
			if($del)
			{
				$tip = 'Delete successfull.';
			}
		}
	}
	// var_dump($_POST); die;
	if(!empty($_POST))
	{
		if(!isset($_SESSION['user']))
		{
			$tip = "If you want to publish something, please login first.";
		}
		else
		{
			$content = trim($_POST['content']);
			$uid = $_SESSION['user']['id'];
			$addtime = time();
			$sql = "INSERT INTO messages1 (content, uid, add_time) VALUES ('{$content}', {$uid}, '{$addtime}')";
			$add = $conn->query($sql);
		}
	}
	$sql = "SELECT mg.id, mg.content, mg.add_time, us.username FROM messages1 AS mg LEFT JOIN user1 AS us ON us.id = mg.uid ORDER BY mg.id DESC";
	$mysqliRes = $conn->query($sql);
	if($mysqliRes === false)
	{
		echo "E";
		exit;
	}
	$rows = [];
	while($row = $mysqliRes->fetch_array(MYSQLI_ASSOC))
	{
		$rows[] = $row;
	}
	$msgNum = count($rows)
?>
<html>
<head>
<meta charset="utf-8">
		<title>Message Board</title>
		<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="mainframe">
    	<div class="title">Message Board</div>
    	<div class="message">Leaving a message.
				<?php
					echo $tip;
					if(isset($_SESSION['user']))
					{
						echo "Welcome " . $_SESSION['user']['username'] . ' <a href="./index.php?logout=1">Log Out</a> ';
					}
					else
					{
						echo "If you want to publish something, please <a href=./account.php>Log In or Register</a> first.";
					}
				?>
			</div>
    	<div class="info">
    		Where there is a will, there is a way.
    	</div>
        <form action="./index.php" method="post">
		    <textarea class="content" name="content"></textarea>
					<input type="submit" value="publish" onclick="return checkLogin()" class="subbtn">
					<?php
					 	if(!isset($_SESSION['user']))
						{
								echo "If you want to publish something, please login first.";
						}
						else
						{
							echo "You are logged in. Now you can publish.";
						}
					?>
        </form>
				<script type="text/javascript">
					var login = <?php echo isset($_SESSION['user']) ? 1 : 0; ?>;
					function checkLogin()
					{
						if(login == 0)
						{
							alert("If you want to publish something, please login first.");
							return false;
						}
					}
					function deletemsg()
					{
						return confirm("Are you sure?");
					}
				</script>
    	<div class="numofmessage">Number of messages <?php echo $msgNum; ?></div>
    	<!-- message area -->
    	<div class="msgFrame">
    		<?php foreach ($rows as $row): ?>
    	    <div class="content_1">
    	         <div class="mainInfo">
    		         <div class="userId"><a href="#"><?php echo $row['username']; ?></a></div>
    		         <div class="conInfo">
    				       <?php echo $row['content']; ?>
    		            </div>
    		         <div class="time">
									 <?php
									 		echo date('Y-m-d H:i:s', $row['add_time']);
											if(isset($_SESSION['user']))
											{
												if($_SESSION['user']['username'] == $row['username'])
												{
													echo ' <a onclick="deletemsg()" href="./index.php?del=1&id='.$row['id'].'">Delete</a> | <a href="./edit.php?id='.$row['id'].'">Edit</a>';
												}
											}
									 ?>
								 </div>
    		    </div>
    	    </div>
    		<?php endforeach; ?>
    	</div>
    </div>
</body>
</html>

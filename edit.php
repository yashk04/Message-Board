<?php
	session_start();
  if(!$_SESSION['user'])
  {
    header('location:account.php');
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
        $id = $_POST['id'];
  			$sql = "UPDATE messages1 SET content = '{$content}' WHERE id = {$id}";
  			$update = $conn->query($sql);
        header('location:./index.php');
  		}
  	}
    else {
      $id = $_GET['id'];
      $sql = "SELECT * FROM messages1 WHERE id = {$id}";
      $mysqliRes = $conn->query($sql);
      $msgInfo = $mysqliRes->fetch_array(MYSQLI_ASSOC);
    }

?>
<html>
<head>
<meta charset="utf-8">
	<title>Message Board</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="mainframe">
        <div class="title">Message Board</div>
        <form action="./edit.php" method="post">
		    <textarea class="content" name="content"><?php echo $msgInfo['content']; ?></textarea>
          <input type="hidden" name="id" value="<?php echo $msgInfo['id']; ?>">
					<input type="submit" value="update" onclick="return checkLogin()" class="subbtn">
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
    </div>
    <script type="text/javascript">
      var login = <?php echo isset($_SESSION['user']) ? 1 : 0; ?>;
      function checkLogin()
      {
        if(login == 0)
        {
          alert("If you want to update something, please login first.");
          return false;
        }
      }
    </script>
</body>
</html>

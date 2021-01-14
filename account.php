<?php
  session_start();
	date_default_timezone_set('Asia/Kolkata');
	$servername = 'localhost';
	$user = 'root';
	$pwd = '';
	$dbname = 'gbook';
	// Create connection
	$conn = new mysqli($servername, $user, $pwd, $dbname);
	// Check connection
	if ($conn->connect_error <> 0) {
	    die("Connection failed: " . $conn->connect_error);
	}
	// var_dump($_POST); die;
  $tip = '';
	if(!empty($_POST))
	{
    if($_POST['do'] == 'Register')
    {
      $username = trim($_POST['username']);
      $password = md5(trim($_POST['password']));
      $reg_time = time();
      $sql = "INSERT INTO user1 VALUES ('{$username}', '{$password}',{$reg_time})";
  		$addUser = $conn->query($sql);
      if($addUser)
      {
        $tip = 'Successfull account creation, you can login!';
      } else {
      	$tip = 'Account creation failed';
      }
    }
    else
    {
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);
      $sql = "SELECT * FROM user1 WHERE username = '{$username}'";
    	$mysqliRes = $conn->query($sql);
    	if($mysqliRes === false)
    	{
    		echo "something wrong with sql";
    		exit;
    	}
      $userInfo = $mysqliRes->fetch_array(MYSQLI_ASSOC);
      if(!$userInfo)
      {
        $tip = 'User does not exists.';
      }
      else
      {
        if(md5($password) == $userInfo['password'])
        {
          $_SESSION['user'] = $userInfo;
          header('location: ./index.php');
        }
        else
        {
          $tip = 'Wrong password';
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
</head>
<body style="background-color: #246990; height: 100%;">
  <div class="main">
    <h1>Login Here</h1>
    <div class="w3_login">
      <div class="w3_login_module">
        <div class="module form-module">
          <div class="form">
            <?php echo $tip; ?>
            <h2>Account</h2>
            <form action="./account.php" method="post">
              <input type="text" name="username" placeholder="username" required=" ">
              <input type="password" name="password" placeholder="password" required=" ">
              <input type="submit" name="do" value="Login">
            </form>
          </div><!-- 
          <div class="form">
            <h2>Create an account</h2>
            <form action="./account.php" method="post">
              <input type="text" name="username" placeholder="username" required=" ">
              <input type="password" name="password" placeholder="password" required=" ">
              <input type="submit" name="do" value="Register">
            </form>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</body>
</html>

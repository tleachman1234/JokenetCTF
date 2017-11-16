<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/jokeStylez.css" />
	<title>JokeNet Login</title>
</head>
<body>

	<center><h3>Login to JokeNet!</h3></center>
	<form action="" method="POST" class="jokeNetLogin">
		<input type="text" placeholder="Username" name="username">
		<input type="password" placeholder="Password" name="password">
		<input type="submit" name="login" value="Sign In">
		<a href="signup.php">Create Account</a>
	</form>

</body>
</html>

<?php
	if ( !isset($_POST['login']) ) {
		die();
	}

	$username = (array_key_exists('username', $_POST) && is_string($_POST['username']))
	                ? $_POST['username'] : '';
	$password = (array_key_exists('password', $_POST) && is_string($_POST['password']))
	                ? $_POST['password'] : '';
	if (empty($username) || empty($password)) {
    print "<script type=\"text/javascript\">
             alert(\"You didn't fill out the form!\");
           </script>";
    die();
	}

	include "../mysql.php";

	//prepare and bind
	$stmt = $conn->stmt_init();
	if( !$stmt->prepare("SELECT `jokerHash` FROM `jokers` WHERE `jokerName` = ?") ) {
    print "<script type=\"text/javascript\">
             alert(\"Error preparing statment\");
           </script>";
    die();
	}
	$stmt->bind_param("s", $username);

	if (!$stmt->execute()){
		print "<script type=\"text/javascript\">
		         alert(\"Error executing statement\");
		       </script>";
		die();
	}
	$stmt->store_result();

	$stmt->bind_result($passHash);
	$stmt->fetch();

	if (!$stmt->num_rows) {
    print "<script type=\"text/javascript\">
             alert(\"Incorrect Username!\");
           </script>";
    die();
	}
	elseif ( !($passHash === md5($password)) ) {
    print "<script type=\"text/javascript\">
             alert(\"Incorrect Password!\");
           </script>";
    die();
	}

  //Set user state to logged in
  setcookie("logged", 1);
  setcookie("username", $username);
  header("Location: topJokes.php");
  exit();

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/style.css" />
	<link rel="stylesheet" href="/style/navbar.css" />
	<title>Top Jokes - JokeNet</title>
</head>
<body>

	<?php
		include "/mysql.php";
		include "navbar.php";
	?>

	<center><h3>Login</h3></center>
	<form action="authenticate.php" method="POST" class="jokeNetLogin">
		<input type="text" placeholder="Username" name="username">
		<input type="password" placeholder="Password" name="password">
		<input type="submit" value="Sign In">
		<a href="createUser.php?r=login" class="forgot">Create Account</a>
	</form>

</body>
</html>

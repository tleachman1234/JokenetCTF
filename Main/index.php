<?php
	# Use SESSION
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/style.css" />
	<title>Login</title>
</head>
<body>

	<br />
	<br />
	<br />

	<!-- Official Login -->

	<div class="wrapper">
    <form class="form-signin">       
      <h2 class="form-signin-heading">Please login</h2>
      <input type="text" class="form-control" name="username" placeholder="Email Address" required="" autofocus="" />
      <input type="password" class="form-control" name="password" placeholder="Password" required=""/>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   
    </form>
  </div>

	<center><h3>Login</h3></center>
	<form action="authenticate.php" method="POST" class="jokeNetLogin">
	  <input type="text" placeholder="Username" name="username">
	  <input type="password" placeholder="Password" name="password">
	  <input type="submit" value="Sign In">
	  <a href="createUser.php?r=login" class="forgot">Create Account</a>
	</form>

	<!-- Flag Submission -->

	<!-- Flag Hints -->

</body>
</html>

<?php
	
	//check for CTF login
	session_start();
	if (!$_SESSION['logged']) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "You must be logged in to visit that page!";
		header("Location: /Main/authenticate.php");
		die();
	}

	define("AUTH", 1);
	include "../mysql.php";
  include "startTimer.php";

	include "../functions.php";
	checkForLoginBypass();
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/jokeStylez.css" />
	<link rel="stylesheet" href="/style/navbar.css" />
  <noscript><meta http-equiv="refresh" content="0;url=JSdisabled.php"></noscript>
	<title>Post a Joke - JokeNet</title>
</head>
<body>

	<?php
		$highlightButton = 3;
		include "navbar.php";
	?>

	<div class="submitJoke">
		<form action="" method="POST">
			<textarea name="jokeText"></textarea><br />
			<input type="submit" name="post" value="Post to JokeNet">
		</form>
	</div>

</body>
</html>

<?php
	if ( !isset($_POST['post']) ) {
		die();
	}

	$jokeText = (array_key_exists('jokeText', $_POST) && is_string($_POST['jokeText']))
											? $_POST['jokeText'] : '';
	$username = (array_key_exists('username', $_COOKIE) && is_string($_COOKIE['username']))
											? $_COOKIE['username'] : '';

	if (empty($jokeText)) {
		print "<script type=\"text/javascript\">
             alert(\"You didn't write a joke!\");
           </script>";
		die();
	}

	if (($open = strpos($jokeText, '<script')) !== false &&
			($close = strpos($jokeText, '</script>')) !== false &&
			$open < $close
		 ) {
	  print "<script type=\"text/javascript\">
             alert(\"FLAG-70PQNDU11RJ81NSO1NE7S01NK\");
           </script>";
		die();
	}

	if (($open = strpos($username, '<script')) !== false &&
			($close = strpos($username, '>')) !== false &&
			$open < $close
		 ) {
	  print "<script type=\"text/javascript\">
             alert(\"FLAG-9013AOAXYI28DMV0CLD612AKW\");
           </script>";
		die();
	}

	include "../mysql.php";
	$query = "INSERT INTO `jokes`
            (`joke`, `postedBy`, `rating`, `numVotes`, `timeStamp`)
            VALUES
            (?, ?, 0, 0, now())";

	$stmt = $conn->stmt_init();
	if( !$stmt->prepare($query) ) {
		print "<script type=\"text/javascript\">
             alert(\"Problem preparing SQL statement\");
           </script>";
		die();
	}

	$stmt->bind_param("ss", $jokeText, $username);
	if ( !$stmt->execute() ) {
		print "<script type=\"text/javascript\">
             alert(\"Problem executing SQL statement\");
           </script>";
		die();
	}

	print "<script type=\"text/javascript\">
           alert(\"Joke Posted!\");
           window.location.replace(\"topJokes.php\");
         </script>";
?>

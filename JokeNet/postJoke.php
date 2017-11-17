<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/jokeStylez.css" />
	<link rel="stylesheet" href="/style/navbar.css" />
	<title>Top Jokes - JokeNet</title>
</head>
<body>

	<?php
		include "../mysql.php";
		$highlightButton = 3;
		include "navbar.php";
	?>
	<br />
	<br />

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

	if (empty($jokeText)) {
		print "<script type=\"text/javascript\">
             alert(\"You didn't write a joke!\");
           </script>";
		die();
	}

	include "../mysql.php";
	$query = "INSERT INTO `jokes`
            (`joke`, `postedBy`, `rating`, `numVotes`, `timeStamp`)
            VALUES
            (?, ?, 0, 0, now())";

	if( !$stmt->prepare($query) ) {
		print "<script type=\"text/javascript\">
             alert(\"Problem preparing SQL statement\");
           </script>";
		die();
	}

	$stmt->bind_param("ss", $jokeText, $_COOKIE["username"]);
	if ( !$stmt->execute() ) {
		print "<script type=\"text/javascript\">
             alert(\"Problem executing SQL statement\");
           </script>";
		die();
	}
?>

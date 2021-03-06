<?php
	session_start();

	if (!$_SESSION['logged']) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "You must be logged in to visit that page!";
		header("Location: /Main/authenticate.php");
		die();
	}

	include "../functions.php";
	checkForBanner();
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/styling.css" />
	<title>CTF Main Page</title>
</head>
<body>
	<div id="logoutWrapper">
		<a href="/Main/logout.php"><button id="logout"><b>Logout</b></button></a>
	</div>
	<br />
	<br />
	<br />
	<center>
		<?php
			print "<h1 id='lg'><b>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</b></h1>";
			if(substr($_SESSION['username'], 0, 7) === "<script") {
				print "<i>nice try :)</i>";
			}
		?>
	</center>
	<br />

	<!-- Link to JokeNet -->
	<div id="main">
		<h2>Start Hunting</h2>
		<form action="" method="POST">
			<input name="rules" type="submit" value="READ ME!" />
			<input name="jokenetBtn" type="submit" value="Go To JokeNet" />
		</form>
	</div>

	<?php

		if ( isset($_POST['rules']) ) {
			header("Location: /Main/rules.php");
			die();
		}
		if ( isset($_POST['jokenetBtn']) ) {
			header("Location: /JokeNet/index.php");
			die();
		}

	?>

	<!-- Flag Submission -->
	<div id="main">
		<h2>Flag Submission</h2>
		<form action="" method="POST">
			<span></span>
				<input type="text" name="flagCode" placeholder="Flag Code" />
			<input name="flag" type="submit" value="Submit" />
			<a href="hints.php">Need Hints?</a>
		</form>
	</div>

	<!-- Leaderboards -->
	<div id="main">
		<h2>View Leaderboards</h2>
		<form action="leaderboards.php" method="POST">
			<span></span>
				<input name="searchKey" type="text" placeholder="Search by User" />
			<input name="search" type="submit" value="Search" />
			<input name="top" type="submit" value="View Top 10" />
		</form>
	</div>

</body>
</html>

<?php
	//php for flag submission
	if ( !isset($_POST['flag']) ) {
		die();
	}

	include "../mysql.php";

	//prepare and bind
	$stmt = $conn->stmt_init();
	if( !$stmt->prepare("SELECT `flagID`, `flagHash` FROM `flags` WHERE `flagHash` = ?") ) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Error preparing SQL statement";
		header("Location: /Main/main.php");
		die();
	}
	$stmt->bind_param("s", $fh);

	$fh = hash("sha256", $_POST['flagCode']);

	//set variables and execute
	if (!$stmt->execute()){
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Error executing SQL statement";
		header("Location: /Main/main.php");
		die();
	}
	$stmt->store_result();
	$stmt->bind_result($id, $hash);

	if ( !$stmt->num_rows ) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "That FLAG is not valid";
		header("Location: /Main/main.php");
		die();
	}
	$stmt->fetch();

	switch ($id) {
		case 1:
			$query = "UPDATE `users` SET flag1=1 WHERE `username` = ?";
			break;
		case 2:
			$query = "UPDATE `users` SET flag2=1 WHERE `username` = ?";
			break;
		case 3:
			$query = "UPDATE `users` SET flag3=1 WHERE `username` = ?";
			break;
		case 4:
			$query = "UPDATE `users` SET flag4=1 WHERE `username` = ?";
			break;
		case 5:
			$query = "UPDATE `users` SET flag5=1 WHERE `username` = ?";
			break;
		case 6:
			$query = "UPDATE `users` SET flag6=1 WHERE `username` = ?";
			break;
		case 7:
			$query = "UPDATE `users` SET flag7=1 WHERE `username` = ?";
			break;
		case 8:
			$query = "UPDATE `users` SET flag8=1 WHERE `username` = ?";
			break;
		case 9:
			$query = "UPDATE `users` SET flag9=1 WHERE `username` = ?";
			break;
		case 10:
			$query = "UPDATE `users` SET flagE=1 WHERE `username` = ?";
			break;
	}

	if ($query) {
		if( !$stmt->prepare($query) ) {
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "Error preparing SQL statement";
			header("Location: /Main/main.php");
			die();
		}
		$stmt->bind_param("s", $_SESSION['username']);
		if (!$stmt->execute()){
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "Error executing SQL UPDATE statement";
			header("Location: /Main/main.php");
			die();
		}

		$_SESSION['notify'] = 1;
		$_SESSION['msg'] = "Flag code VALID! Nice Job!";

		//check if all 9 flags found
		if( !$stmt->prepare("SELECT  `flag1`, `flag2`, `flag3`, `flag4`, `flag5`,
																 `flag6`, `flag7`, `flag8`, `flag9`, `end`
												 FROM `users` WHERE `username` = ?") ) {
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "Error preparing SQL statement";
			header("Location: /Main/main.php");
			die();
		}
		$stmt->bind_param("s", $_SESSION['username']);
		if (!$stmt->execute()){
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "Error executing SQL UPDATE statement";
			header("Location: /Main/main.php");
			die();
		}
		$stmt->bind_result($f1, $f2, $f3, $f4, $f5, $f6, $f7, $f8, $f9, $end);
		$stmt->fetch();

		if( $f1 && $f2 && $f3 && $f4 && $f5 && $f6 && $f7 && $f8 && $f9 ) {
			if (!$end) {
				if( !$stmt->prepare("UPDATE `users` SET `end` = now() WHERE username = ?") ) {
					$_SESSION['error'] = 1;
					$_SESSION['msg'] = "Problem preparing SQL statement";
					header("Location: /Main/main.php");
					die();
				}
				$stmt->bind_param("s", $_SESSION['username']);
				if (!$stmt->execute()) {
					$_SESSION['error'] = 1;
					$_SESSION['msg'] = "Error executing SQL statement";
					header("Location: /Main/main.php");
					die();
				}
			}
		}

		header("Location: /Main/main.php");
	}

?>
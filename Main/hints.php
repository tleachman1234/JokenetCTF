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

	include "../mysql.php";

	$stmt = $conn->stmt_init();
	$query = "SELECT `flag1`, `flag2`, `flag3`, `flag4`,
                   `flag5`, `flag6`, `flag7`, `flag8`, `flag9`,
                   `hint1`, `hint2`, `hint3`, `hint4`,
                   `hint5`, `hint6`, `hint7`, `hint8`, `hint9`
            FROM `users` WHERE `username` = ?";

	if( !$stmt->prepare($query) ) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Problem preparing SQL statement";
		header("Location: /Main/hints.php");
		die();
	}
	$stmt->bind_param("s", $_SESSION['username']);
	if (!$stmt->execute()) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Error executing SQL statement";
		header("Location: /Main/hints.php");
		die();
	}
	$stmt->store_result();
	if ( !$stmt->num_rows ) {
		$_SESSION['error'] = 1;
		$_SESSION['msg'] = "Fatal Error when retrieving user data from database";
		header("Location: /Main/hints.php");
		die();
	}

	$stmt->bind_result($f1, $f2, $f3, $f4, $f5, $f6, $f7, $f8, $f9, 
										 $h1, $h2, $h3, $h4, $h5, $h6, $h7, $h8, $h9);
	$stmt->fetch();
	
  unset($query);
	if($_POST['Hint1'] && !$f1) $query = "UPDATE `users` SET `hint1` = 1 WHERE `username` = ?";
	elseif($_POST['Hint2'] && !$f2) $query = "UPDATE `users` SET `hint2` = 1 WHERE `username` = ?";
	elseif($_POST['Hint3'] && !$f3) $query = "UPDATE `users` SET `hint3` = 1 WHERE `username` = ?";
	elseif($_POST['Hint4'] && !$f4) $query = "UPDATE `users` SET `hint4` = 1 WHERE `username` = ?";
	elseif($_POST['Hint5'] && !$f5) $query = "UPDATE `users` SET `hint5` = 1 WHERE `username` = ?";
	elseif($_POST['Hint6'] && !$f6) $query = "UPDATE `users` SET `hint6` = 1 WHERE `username` = ?";
	elseif($_POST['Hint7'] && !$f7) $query = "UPDATE `users` SET `hint7` = 1 WHERE `username` = ?";
	elseif($_POST['Hint8'] && !$f8) $query = "UPDATE `users` SET `hint8` = 1 WHERE `username` = ?";
	elseif($_POST['Hint9'] && !$f9) $query = "UPDATE `users` SET `hint9` = 1 WHERE `username` = ?";

	if($query) {
		if( !$stmt->prepare($query) ) {
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "Problem preparing SQL statement";
			header("Location: /Main/hints.php");
			die();
		}
		$stmt->bind_param("s", $_SESSION['username']);
		if (!$stmt->execute()) {
			$_SESSION['error'] = 1;
			$_SESSION['msg'] = "Error executing SQL statement";
			header("Location: /Main/hints.php");
			die();
		}

		$_SESSION['notify'] = 1;
		$_SESSION['msg'] = "Hint Unlocked!";
		header("Location: /Main/hints.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="/style/styling.css" />
	<title>CTF Hints</title>
</head>
<body>

	<div id="logoutWrapper">
		<a href="/Main/logout.php"><button id="logout"><b>Logout</b></button></a>
	</div>
	<div id="homeWrapper">
		<a href="/Main/main.php"><button id="home"><b>Home</b></button></a>
	</div>
	<br />
	<br />
	<br />
	<center>
		<h1 id='lg'><b>Hints</b></h1>
		<div style="color:red;">
			<b>WARNING!</b> For every hint you unlock, 5 points is deducted from your total score!
		</div>
		<div id="rules">
			**Note: There is at least one flag dealing with XSS vulnerabilities. Due to the amount of damage these attacks can cause, I can't actually allow the JavaScript to run, so everything is sanitized and I'm simply checking the input to classify it as XSS. Because of this, it might be a bit harder to recognize where JokeNet might be "vulnerable" to XSS, and my script will only pick up basic JS injection, so using obscure injection strings won't present you with a Flag. <br />
			<br />
			If you find any vulnerabilities that don't seem to lead to a flag, you should let me know! <br />
			There might just be some extra points in it for you...
		</div>
		<br />
	</center>

	<div id="main">
		<form action="hints.php" method="POST">
			<h2>Clue 1:</h2>
			<h3><i>Seems John Doe couldn't figure out how to use prepared statements with the LIKE operator and got lazy...</i></h3>
			<input name="Hint1" type="submit" value="Unlock Hint 1" />
			<?php
				if($f1 || $h1) print "
					<i>
						In SQL, the LIKE operator is used to return <u>multiple rows</u> from a database that partially match some input. So, what is the only place in JokeNet that multiple rows are returned after user input is submitted? <br />
						Remember, when using SQL Injection, you must balance the statement or the query wont run. ' and # should be all you need for balancing. <br />
						Look into the UNION operator. PHP doesn't allow for query chaining with ; so you'll have to be more creative. Also, I'm just going to drop these two strings here and hopefully they'll be useful. <br />
						`jokerHash` & `jokers`
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 2:</h2>
			<h3><i>I like how users can vote on jokes.</i></h3>
			<input name="Hint2" type="submit" value="Unlock Hint 2" />
			<?php
				if($f2 || $h2) print "
					<i>
						HTML manipulation is key to this flag. Remember, HTML code is sent to user machines and run there, meaning an attacker (you in this case) has complete control over it. Mr. Doe has made the mistake of not validating the values from the &lt;select&gt; dropdown, thinking there can only be 6 possible options.
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 3:</h2>
			<h3><i>Not even GOOGLE will find this one!</i></h3>
			<input name="Hint3" type="submit" value="Unlock Hint 3" />
			<?php
				if($f3 || $h3) print "
					<i>
						Google (and many other sites) use bots called webcrawlers to crawl and index pages for their search engine. By convention, these bots check for a robots.txt file in the root folder of every site. This is done to see if there are any folders/files they shouldn't index, by request of the site owner. Be a bot.
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 4:</h2>
			<h3><i>Looks like John Doe has no idea what Persistent XSS is</i></h3>
			<input name="Hint4" type="submit" value="Unlock Hint 4" />
			<?php
				if($f4 || $h4) print "
					<i>
						Persistent XSS is a cross site scripting attack where the injected code is stored into the database and later served up without sanitization. Where in JokeNet is user input being stored and later viewed?
						You should try using &lt;script&gt; tags here to trigger code execution.
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 5:</h2>
			<h3><i>This is why you should only use SESSION for authentication</i></h3>
			<input name="Hint5" type="submit" value="Unlock Hint 5" />
			<?php
				if($f5 || $h5) print "
					<i>
						Cookies are delicious, but they're a terrible choice when it comes to authenticating users. They crumble under the pressure. John doesn't know that though, and he LOVES cookies! Maybe you should try out the Application tab in the Inspect popup, and google what a 'javascript:' url is. Now I've got to get back to work. More documents to write and cookies to eat, documents and cookies, documents & cookies, documents ... cookies, document.cookie ...
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 6:</h2>
			<h3><i>If you're analyizing code, you should inspect it closely</i></h3>
			<input name="Hint6" type="submit" value="Unlock Hint 6" />
			<?php
				if($f6 || $h6) print "
					<i>
						There's not a lot to this flag, literally just inspect the source code. You can right click and hit 'Inspect' if you want, but it might be easier if you choose the 'View Page Source' option.
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 7:</h2>
			<h3><i>Javascript is completely under the client's control</i></h3>
			<input name="Hint7" type="submit" value="Unlock Hint 7" />
			<?php
				if($f7 || $h7) print "
					<i>
						Javascript is run client side, meaning the user is in complete control of the variables, the function calls, and if Javascript is even run at all. Take a look around JokeNet, what do you notice when you do something that isn't allowed? What appears on the screen and why does it appear on the screen? Can you circumvent it?
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 8:</h2>
			<h3><i>This uses the same attack vector as Flag 5</i></h3>
			<input name="Hint8" type="submit" value="Unlock Hint 8" />
			<?php
				if($f8 || $h8) print "
					<i>
						To find this flag you're going to need to create a Reflective XSS attack, do you know what that is? I'm going to assume you do, and ask how you might be able to pull it off using the same vulneribility in flag 5, if you've found it yet :) This one is a bit tricky but I have faith in you.<br />
						You should try using &lt;script&gt; tags here to trigger code execution.
					</i>
				";
			?>
			<hr />
			<br />
			<h2>Clue 9:</h2>
			<h3><i>John Doe has created a secret file...</i></h3>
			<input name="Hint9" type="submit" value="Unlock Hint 9" />
			<?php
				if($f9 || $h9) print "
					<i>
						Here's the file: '/superdupersecretfolder/superdupersecretfile.php' but there's a problem as you'll see. <br />
						Clearly you cant actually be directed here by example.com, so how will you view the file? Do you know the definition of spoofing? What about the definition of referrer? You might need to add an extention or download something to pull this off...
					</i>
				";
			?>
			<hr />
		</form>
	</div>
	<br />

</body>
</html>

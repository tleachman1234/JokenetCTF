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

  if (!$_COOKIE["logged"]) {
    header("Location: login.php");
    die();
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile - JokeNet</title>
  <link rel="stylesheet" type="text/css" href="/style/profile.css" />
  <link rel="stylesheet" type="text/css" href="/style/jokeStylez.css" />
  <link rel="stylesheet" href="/style/navbar.css" />
</head>
<body>
  <?php
    $highlightButton = 4;
    include "navbar.php";

    $user = (array_key_exists('user', $_GET) && is_string($_GET['user']))
                  ? $_GET['user'] : '';
    
    if (empty($user)) {
      header("Location: profile.php?user=" . $_COOKIE['username']);
      die();
    }

    //get user info
    $stmt = $conn->stmt_init();
    if( !$stmt->prepare("SELECT `jokerName`, `email` FROM `jokers` WHERE `jokerName` = ?") ) {
      print "<script type=\"text/javascript\">
               alert(\"Error preparing statment 1\");
             </script>";
      die();
    }
    $stmt->bind_param("s", $user);
    if (!$stmt->execute()) {
      print "<script type=\"text/javascript\">
               alert(\"Error executing statement\");
             </script>";
      die();
    }
    $stmt->bind_result($jokerName, $email);
    $stmt->store_result();
    if ( !$stmt->num_rows ) {
      print "<center><h1>User profile not found</h1></center>";
      die();
    }
    $stmt->fetch()
  ?>

  <div class="n-profile-bar">
    <div class="name">
      <h3><u><?php echo $jokerName; ?></u></h3>
    </div>
    <div class="n-contact">
      <ul>
        <li class="email"><b>Email: <?php echo $email; ?></b></li>
        <li class="num"><b><?php echo $stmt->num_rows; ?> jokes posted</b></li>
      </ul>
    </div>
  </div>

  <h1><center><?php echo $jokerName; ?>'s Jokes:</center></h1>

  <?php
    //get jokes by user
    if( !$stmt->prepare("SELECT * FROM `jokes` WHERE `postedBy` = ? ORDER BY `timeStamp` DESC") ) {
      print "<script type=\"text/javascript\">
               alert(\"Error preparing statment 2\");
             </script>";
      die();
    }
    $stmt->bind_param("s", $user);
    if (!$stmt->execute()){
      print "<script type=\"text/javascript\">
               alert(\"Error executing statement\");
             </script>";
      die();
    }
    $stmt->bind_result($jokeID, $jokeText, $postedBy, $rating, $numVotes, $timeStamp);

    $stmt->store_result();

    include "../functions.php";

    while($stmt->fetch()) {
      printJoke($jokeID, $jokeText, $postedBy, $rating, $timeStamp);
    }

    //AUTH defined above
    include "vote.php";

  ?>
</body>
</html>

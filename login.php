<?php

include '../dbc.php';

$err = array();

foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

if ($_POST['doLogin']=='Login')
{

foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


$user_email = $data['usr_email'];
$pass = $data['pwd'];


if (strpos($user_email,'@') === false) {
    $user_cond = "user_name='$user_email'";
} else {
      $user_cond = "user_email='$user_email'";

}


$result = mysql_query("SELECT `id`,`pwd`,`full_name`,`approved`,`user_level` FROM users WHERE
           $user_cond
			AND `banned` = '0'
			") or die (mysql_error());
$num = mysql_num_rows($result);

    if ( $num > 0 ) {

	list($id,$pwd,$full_name,$approved,$user_level) = mysql_fetch_row($result);

	if(!$approved) {
	$err[] = "Account not activated. Please check your email for activation code";
	 }

	if ($pwd === PwdHash($pass,substr($pwd,0,9))) {
	if(empty($err)){

       session_start();
	   session_regenerate_id (true);

		$_SESSION['user_id']= $id;
		$_SESSION['user_name'] = $full_name;
		$_SESSION['user_level'] = $user_level;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

		$stamp = time();
		$ckey = GenKey();
		mysql_query("update users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysql_error());

	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				   }
		  header("Location: myaccount.php");
		 }
		}
		else
		{
		$err[] = "Invalid Login. Please try again with correct user email and password.";
		}
	} else {
		$err[] = "Error - Invalid login. No such user exists";
	  }
}



?>
<html>
<head>
<title>Journey</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:300' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width; initial-scale=1.0">
  <script>
  $(document).ready(function(){
    $("#logForm").validate();
  });
  </script>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="icon"
      type="image/png"
      href="http://fandombrain.com/Logos/favicon.ico">

</head>

<body>
	<div class="section" id="section1">
  	<div class="login-form">
      <img src="Logos/login.png"/>
      <p id="fb">Journey</p>
      <p id="slogan">Explore Your World!</p>
	  <p><?php

	  if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "$e <br>";
	    }
	  echo "</div>";
	   }
	  ?></p>
	  <div align="center">
      <form action="index.php" method="post" name="logForm" class="form-wrapper-01" >
            <input name="usr_email" type="text" class="inputbox email" placeholder="Email">
            <input name="pwd" type="password" class="inputbox password" placeholder="Password">
            <div align="center">
                <input name="remember" type="checkbox" id="remember" value="1">
                Remember me</div>
       <div align="center">
                <p>
                  <input name="doLogin" type="submit" id="doLogin3" value="Login" class="button">
                </p>
                <p><a href="http://fandombrain.com/about/">learn more</a></p>
                <!--<p><a href="register.php">Sign Up</a>
                  |<a href="forgot.php"> Forgot Password</a>
                  </p>-->
              </div>
      </form>
  </div></div></div>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62014637-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>

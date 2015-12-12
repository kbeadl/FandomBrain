<?php
include 'dbc.php';




if ($_POST['doReset']=='Reset')
{
$err = array();
$msg = array();

foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}
if(!isEmail($data['user_email'])) {
$err[] = "ERROR - Please enter a valid email";
}

$user_email = $data['user_email'];

$rs_check = mysql_query("select id from users where user_email='$user_email'") or die (mysql_error());
$num = mysql_num_rows($rs_check);
    if ( $num <= 0 ) {
	$err[] = "Error - Sorry no such account exists or registered.";
	}


if(empty($err)) {

$new_pwd = GenPwd();
$pwd_reset = PwdHash($new_pwd);
$rs_activ = mysql_query("update users set pwd='$pwd_reset' WHERE
						 user_email='$user_email'") or die(mysql_error());

$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);


$message =
"Here are your new password details ...\n
User Email: $user_email \n
Passwd: $new_pwd \n

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE.
***DO NOT RESPOND TO THIS EMAIL****
";

	mail($user_email, "Reset Password", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion());

$msg[] = "Your account password has been reset and a new password has been sent to your email address.";

 }
}
?>
<html>
<head>
<title>Forgot Password</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:300' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width; initial-scale=1.0">
  <script>
  $(document).ready(function(){
    $("#actForm").validate();
  });
  </script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
      <p>
        <?php
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }
	  ?>
      </p>
      <div class="section">
      <div class="login-form">
        <h1>Lost password?</h1>
        <p>Ok, don't panic. You can recover it here.</p>
      <div>
      <form action="forgot.php" method="post" name="actForm" id="actForm" class="form-wrapper-01" >
          <input name="user_email" type="text" class="required email inputbox" id="txtboxn" size="25" placeholder="Email"></td>
          <div align="center">
                <p>
                  <input name="doReset" type="submit" id="doLogin3" value="Reset" class="button">
                </p>
              </div>
      </form>
    </div>
      <!--<p><a href="login.php">Login</a>
                  |<a href="register.php"> Sign Up</a>
                  </p>-->
    </div>
  </div>
</body>
</html>

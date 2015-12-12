<?php
include '../dbc.php';

$err = array();

if($_POST['doRegister'] == 'Register')
{
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}

if (!isUserID($data['user_name'])) {
$err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";

}
if(!isEmail($data['usr_email'])) {
$err[] = "ERROR - Invalid email address.";

}
if (!checkPwd($data['pwd'],$data['pwd2'])) {
$err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";
}


$sha1pass = PwdHash($data['pwd']);

$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$activ_code = rand(1000,9999);

$rss = 'http://www.hypable.com/feed/';

$usr_email = $data['usr_email'];
$user_name = $data['user_name'];


$rs_duplicate = mysql_query("select count(*) as total from users where user_email='$usr_email' OR user_name='$user_name'") or die(mysql_error());
list($total) = mysql_fetch_row($rs_duplicate);

if ($total > 0)
{
$err[] = "ERROR - The username/email already exists. Please try again with different username and email.";

}


if(empty($err)) {

$sql_insert = "INSERT into `users`
  			(`full_name`,`user_email`,`pwd`,`address`,`tel`,`fax`,`website`,`date`,`users_ip`,`activation_code`,`country`,`user_name`,`rss`
			)
		    VALUES
		    ('$data[full_name]','$usr_email','$sha1pass','$data[address]','$data[tel]','$data[fax]','$data[web]'
			,now(),'$user_ip','$activ_code','$data[country]','$user_name','$rss'
			)
			";

mysql_query($sql_insert,$link) or die("Insertion Failed:" . mysql_error());
$user_id = mysql_insert_id($link);
$md5_id = md5($user_id);
mysql_query("update users set md5_id='$md5_id' where id='$user_id'");

if($user_registration)  {
$a_link = "
*****ACTIVATION LINK*****\n
http://$host$path/activate.php?user=$md5_id&activ_code=$activ_code
";
} else {
$a_link =
"Your account is *PENDING APPROVAL* and will be soon activated the administrator.
";
}

$message =
"Hello \n
Thank you for registering with us. Here are your login details...\n

User ID: $user_name
Email: $usr_email \n
Passwd: $data[pwd] \n

$a_link

Thank You,
FandomBrain
______________________________________________________
THIS IS AN AUTOMATED RESPONSE.
***DO NOT RESPOND TO THIS EMAIL****
";

	mail($usr_email, "FandomBrain Registration", $message,
    "From: \"Member Registration\" <auto-reply@fandombrain.com>\r\n" .
     "X-Mailer: PHP/" . phpversion());

  header("Location: thankyou.php");
  exit();

	 }
 }

?>
<html>
<head>
<title>Sign Up | FandomBrain</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:300' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width; initial-scale=1.0">

  <script>
  $(document).ready(function(){
    $.validator.addMethod("username", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#regForm").validate();
  });
  </script>

<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<p>
	<?php
	 if (isset($_GET['done'])) { ?>
	  <h2>Thank you</h2> Your registration is now complete and you can <a href="login.php">login here</a>";
	 <?php exit();
	  }
	?></p>
	 <?php
	 if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";
	   }
	 ?>
   <div class="section" id="section1">
    <div class="login-form">
      <h1>Sign Up in Seconds!</h1>
      <p>Signup using your Email address</p>
      <div>
      <form action="register.php" method="post" name="regForm" id="regForm" class="form-wrapper-01" >
            <input name="user_name" type="text" id="user_name" class="required username inputbox" minlength="5" placeholder="Username" >
            <p align="center">
              <input name="btnAvailable" class="button" type="button" id="btnAvailable"
			  onclick='$("#checkid").html("Please wait..."); $.get("checkuser.php",{ cmd: "check", user: $("#user_name").val() } ,function(data){  $("#checkid").html(data); });'
			  value="Check Availability">
			    <span style="color:green; font: bold 12px verdana; " id="checkid" ></span></p>
            <input name="usr_email" type="text" id="usr_email3" class="required email inputbox" placeholder="Email">
            <input name="pwd" type="password" class="required password inputbox" minlength="5" id="pwd" placeholder="Password">
            <input name="pwd2"  id="pwd2" class="required password inputbox" type="password" minlength="5" equalto="#pwd" placeholder="Verify Password">
        <p align="center">
          <input name="doRegister" type="submit" id="doRegister" value="Register" class="button">
        </p>
      </form>
    </div>
    <!--<p><a href="login.php">Login</a>
                  |<a href="forgot.php"> Forgot Password</a>
                  </p>-->
  </div>
</div>

</body>
</html>

<?php
include '../dbc.php';
include 'src/Instagram.php';
use MetzWeb\Instagram\Instagram;
include '../initialize.php';
page_protect();

$err = array();
$msg = array();

if($_POST['SUBMIT_BUTTON_NAME'] == 'Update')
{
  $addsite = ($_POST['site']);
  mysql_query("update users set rss=CONCAT('$addsite',',',rss) where id='$_SESSION[user_id]'");
}

if($_POST['doUpdate'] == 'Update')
{


$rs_pwd = mysql_query("select pwd from users where id='$_SESSION[user_id]'");
list($old) = mysql_fetch_row($rs_pwd);
$old_salt = substr($old,0,9);

  if($old === PwdHash($_POST['pwd_old'],$old_salt))
  {
  $newsha1 = PwdHash($_POST['pwd_new']);
  mysql_query("update users set pwd='$newsha1' where id='$_SESSION[user_id]'");
  $msg[] = "Your new password is updated";
  } else
  {
   $err[] = "Your old password is invalid";
  }

}

if($_POST['doSave'] == 'Save')
{
foreach($_POST as $key => $value) {
  $data[$key] = filter($value);
}

mysql_query("UPDATE users SET
       WHERE id='$_SESSION[user_id]'
      ") or die(mysql_error());

$msg[] = "Profile Sucessfully saved";
 }

$rs_settings = mysql_query("select * from users where id='$_SESSION[user_id]'");

$instagram = new Instagram(array(
  'apiKey'      => $apikey,
  'apiSecret'   => $apisecret,
  'apiCallback' => $callback 
));
$loginUrl = $instagram->getLoginUrl();
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="icon"
      type="image/png"
      href="/Logos/favicon.ico">
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

    <title>Journey</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <script src="js/javascript.js" type="text/javascript"></script>
    <link href='http://fonts.googleapis.com/css?family=Comfortaa:300' rel='stylesheet' type='text/css'>
    <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
  </head>
  <body style="background-color:#3498db;">
      <div id="header">
        <div id="home"><a href="myaccount.php"><img src="menu/home.png"/></a></div>
        <div id="cd-nav">
    <a href="#overlay" class="cd-nav-trigger" id="open-overlay"><img src="menu/hubs.png" id="hubs"/></a>
  </div>
        <div id="profile"><a href="mysettings.php"><img src="menu/profile.png"/></a></div>
        <div id="write"><img src="menu/write.png"/></div>
        <div id="logo">
        <img src="Logos/brain.png"/>
        </div>
      </div>
    <a href="#"><div id="overlay">
    <div style="height:20%"></div>
    <a href="<?php echo $loginUrl ?>"><img class="displayed" src="Logos/social/instagram.png"/></a>

</div></a>
<div id="content" class="settings">
  <div id="rss">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
<h3 class="titlehdr">My Account - Settings</h3>
      <p>
        <?php
  if(!empty($err))  {
     echo "<div class=\"msg\">";
    foreach ($err as $e) {
      echo "* Error - $e <br>";
      }
    echo "</div>";
     }
     if(!empty($msg))  {
      echo "<div class=\"msg\">" . $msg[0] . "</div>";

     }
    ?>
      </p>
      <p>Here you can make changes to your profile. Please note that you will
        not be able to change your email which has been already registered.</p>
    <?php while ($row_settings = mysql_fetch_array($rs_settings)) {?>
      <form action="myaccount.php" method="post" name="myform" id="myform">
        <table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr>
            <td>User Name</td>
            <td><input name="user_name" type="text" id="web2" disabled value=<?php echo $row_settings['user_name'];?>
          </tr>
          <tr>
            <td>Email</td>
            <td><input name="user_email" type="text" id="web3"  value=<?php echo $row_settings['user_email']; ?>></td>
          </tr>
        </table>
        <p align="center">
          <input name="doSave" type="submit" id="doSave" value="Save">
        </p>
      </form>
        </table>
        <p align="center">
          <input name="doSave" type="submit" id="doSave" value="Save">
        </p>
      </form>
    <?php } ?>
      <h3 class="titlehdr">Change Password</h3>
      <p>If you want to change your password, please input your old and new password
        to make changes.</p>
      <form name="pform" id="pform" method="post" action="">
        <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr>
            <td width="31%">Old Password</td>
            <td width="69%"><input name="pwd_old" type="password" class="required password"  id="pwd_old"></td>
          </tr>
          <tr>
            <td>New Password</td>
            <td><input name="pwd_new" type="password" id="pwd_new" class="required password"  ></td>
          </tr>
        </table>
        <p align="center">
          <input name="doUpdate" type="submit" id="doUpdate" value="Update">
        </p>
              <h3 class="titlehdr">Websites</h3>
      <p>Add and change the websites that you follow.</p>
      <form method="post" name="myform" id="myform">
        <table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="forms">
          <tr>
            <td>RSS Link</td>
            <td><input name="site" type="text" id="web2"</td>
            <p align="center">
          <input name="SUBMIT_BUTTON_NAME" type="submit" id="sites" value="Update">
        </p>
          </tr>
        <p>&nbsp; </p>
        <p align="center">List of Sites</p>
        <?php
          $total_sites=count($array);
        ?>
      </form>
</table></div></div>
</html>

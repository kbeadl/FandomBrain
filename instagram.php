<?php

/**
 * Instagram PHP API
 *
 * @link https://github.com/cosenary/Instagram-PHP-API
 * @author Christian Metz
 * @since 01.10.2013
 */

require 'src/Instagram.php';
use MetzWeb\Instagram\Instagram;

require '../initialize.php';

// initialize class
$instagram = new Instagram(array(
  'apiKey'      => $apikey,
  'apiSecret'   => $apisecret,
  'apiCallback' => $callback // must point to success.php
));

// receive OAuth code parameter
$code = $_GET['code'];

// check whether the user has granted access
if (isset($code)) {

  // receive OAuth token object
  $data = $instagram->getOAuthToken($code);
  $username = $username = $data->user->username;

  // store user access token
  $instagram->setAccessToken($data);

  // now you have access to all authenticated user methods
  $result = $instagram->getUserFeed();

  $loginUrl = $instagram->getLoginUrl();

} else {

  // check whether an error occurred
  if (isset($_GET['error'])) {
    echo 'An error occurred: ' . $_GET['error_description'];
  }

}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />

    <title>Journey</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/javascript.js" type="text/javascript"></script>
    <link href="css/insta.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Comfortaa:300' rel='stylesheet' type='text/css'>
    <link type="text/css" rel="stylesheet" href="css/jquery.mmenu.all.css" />
    <script src="https://vjs.zencdn.net/4.2/video.js"></script>
<link href="https://vjs.zencdn.net/4.2/video-js.css" rel="stylesheet">
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
<br><br>
    <div class="container">
      <div class="main">
        <?php
          // display all user likes
          foreach ($result->data as $media) {
            $content = "<div id='rss'>";
            $id = $media->id;
            // output media
            if ($media->type === 'video') {
              // video
              $poster = $media->images->low_resolution->url;
              $source = $media->videos->standard_resolution->url;
              $content .= "<video class=\"media video-js vjs-default-skin\" width=\"250\" height=\"250\" poster=\"{$poster}\"
                           data-setup='{\"controls\":true, \"preload\": \"auto\"}'>
                             <source src=\"{$source}\" type=\"video/mp4\" />
                           </video>";
            } else {
              // image
              $image = $media->images->low_resolution->url;
              $content .= "<img class=\"media\" src=\"{$image}\"/>";
            }

            // create meta section
            $avatar = $media->user->profile_picture;
            $username = $media->user->username;
            $comment = $media->caption->text;
            $content .= "<div class=\"content\">
                           <div class=\"avatar\" style=\"background-image: url({$avatar})\"></div>
                           <p>{$username}</p>
                           <div class=\"comment\">{$comment}</div>
                         </div><br><br>";
            // output media
            echo $content . "</div>";
          }
        ?>
        <!-- GitHub project -->
      </div>
    </div>
  </body>
</html>

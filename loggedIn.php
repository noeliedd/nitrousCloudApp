<?php
  error_reporting(E_ALL); 
  session_start();
  require_once("dbConnect.php");
  require_once('facebook/src/HttpClients/FacebookCurl.php');
  require_once('facebook/src/HttpClients/FacebookHttpable.php');
  require_once('facebook/src/HttpClients/FacebookCurlHttpClient.php' );
  require_once('facebook/src/HttpClients/FacebookStreamHttpClient.php');   
  require_once('facebook/src/HttpClients/FacebookStream.php');
  require_once('facebook/src/Entities/AccessToken.php');
  require_once('facebook/src/FacebookSession.php');
  require_once('facebook/src/FacebookRequest.php');
  require_once('facebook/src/FacebookResponse.php');
  require_once('facebook/src/FacebookSDKException.php');
  require_once('facebook/src/FacebookRequestException.php');
  require_once('facebook/src/FacebookRedirectLoginHelper.php');
  require_once('facebook/src/FacebookAuthorizationException.php');
  require_once('facebook/src/GraphObject.php');
  require_once('facebook/src/GraphUser.php');
  require_once('facebook/src/GraphLocation.php');  
  require_once('facebook/src/GraphSessionInfo.php');

  use facebook\FacebookSession;
  use facebook\FacebookRequest;
  use facebook\GraphUser;

  $db = new dbConnect(); 
  $id = '';
  $fullName ='';
  
  $config = parse_ini_file('nitrous_config.ini');

   FacebookSession::setDefaultApplication($config['nitrous_app_id'],$config['nitrous_app_secret']);
   $session = new FacebookSession($_SESSION['fb_token']);

   if(empty($_SESSION['fb_token']))
   {
     header("Location: index.php");        
   }
   else
   {
     $request = new FacebookRequest($session, 'GET', '/me');
     $response = $request->execute();
     $graph = $response->getGraphObject(GraphUser::classname());
     $name = $graph->getFirstName();
     $fullName = $graph->getName();
     $id = $graph->getId();
     $pic = 'https://graph.facebook.com/'.$id.'/picture';        
     $db->insertUser($fullName,$id);
   }
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Cloud Mashup App</title>
		<meta name="description" content="Cloud Mashup Apllication">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="assets/css/custom.css" rel="stylesheet" type ="text/css">		
	</head>  
 
  <body>
		<div class="navbar navbar-fixed-top navbar-inverse">
		  <div class="navbar-inner">
			<div class="container">
			  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </a>        
			  <a class="brand">Mashup App</a>
			  <div class="nav-collapse">
				<ul class="nav pull-right">
				  <li class="divider-vertical"></li>
          <li><img src="<?= $pic ?>"/></li>
				  <li><a href="logout.php">Log Out</a></li>
				</ul>
			  </div>
			</div>
		  </div>
		</div>
		<div class="container hero-unit">
      <h4>Hello <?php echo htmlentities($name, ENT_QUOTES, 'UTF-8'); ?>, You have been signed in via Facebook</h4>        
      <p id ="routeDistance">Total Distance Km :</p>	             
      <div id ="map-container">   
        <div id ="map">	
		    </div>
        <div id ="clearMapBtn">
         <button onclick ="clearMap()" type="submit" name ="clearMap" class="btn btn-primary btn-sm">Clear Entire Map</button>
          </div>
        <div id ="clearLastBtn">        
         <button onclick ="clearLastMarker()" type="submit" name ="clearLast" class="btn btn-primary btn-sm">Clear Last Marker</button>	
         </div>	
		  </div>
	  </div>
     <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/custom.js"></script>
	</body>
</html>

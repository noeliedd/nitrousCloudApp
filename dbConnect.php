<?php  
  $error = "Error connecting to the database"; 
  $config = parse_ini_file('azure_config.ini'); 
  global $mysqli; 
  $mysqli = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']) or die ($error); 

  class dbConnect
  {
    function insertUser($name,$id)
    {      
      global $mysqli;
      $query = mysqli_query( $mysqli,"INSERT INTO Users (Name, Fb_Id) VALUES ('".$name."', '".$id."') ");
    }    
  }
?>
 
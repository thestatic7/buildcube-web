<?php
define('DBSERVER2', 'localhost'); // Database server
define('DBUSERNAME2', 'u1675024_default'); // Database username
define('DBPASSWORD2', 'mvOOe0oTlD0Vb74C'); // Database password
define('DBNAME2', 'u1675024_buildcube'); // Database name
 
/* connect to MySQL database */
$db = mysqli_connect(DBSERVER2, DBUSERNAME2, DBPASSWORD2, DBNAME2);
 
// Check db connection
if($db === false){
    die("Error: connection error. " . mysqli_connect_error());
}
?>
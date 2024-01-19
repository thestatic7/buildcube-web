<?php
define('DBALTSERVER', 'mysql2.joinserver.xyz:3306'); // Database server
define('DBALTUSERNAME', 'u82189_PsvqG52LpG'); // Database username
define('DBALTPASSWORD', '@Ve.bzb9Rm63p5e0n^TdV.57'); // Database password
define('DBALTNAME', 's82189_BuildCube'); // Database name
 
/* connect to MySQL database */
$conn = mysqli_connect(DBALTSERVER, DBALTUSERNAME, DBALTPASSWORD, DBALTNAME);
 
// Check db connection
if($conn === false){
    die("Error: connection error. " . mysqli_connect_error());
}
?>
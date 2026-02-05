<?php  date_default_timezone_set("Africa/Lagos");
$cons=mysqli_connect("localhost","root","","socialnet");
if(mysqli_connect_errno()){
	echo 'Failed to connect.phpto the database';
}
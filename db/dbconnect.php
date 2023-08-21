<?php

/* Connection to database */
if($conn = mysqli_connect("localhost","root","","FinalProject")) {
/* Check connection */
	if(mysqli_connect_error()) {
		echo "Connection failed";
		printf("Error : %s",mysqli_connect_error());
	}
}else {
	header('Location: 404.php');
}
	
	
?>

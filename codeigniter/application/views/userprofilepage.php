<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<?php 
	if (isset($alert)){
		echo $alert;
		echo "<br><br>";
	}
?>

<h1>Welcome <?php echo "$username"; ?></h1>
Name: <?php echo "$name"; ?> <br>
Address: <?php echo "$address"; ?> <br>
Birthday: <?php echo "$birthday"; ?> <br>

<button onclick="location.href='<?php echo base_url();?>profile/onlineusers'">View Online</button>
<button onclick="location.href='<?php echo base_url();?>profile/viewbirthdays'">View User Birthdays</button>
<button onclick="location.href='<?php echo base_url();?>profile/editself'">Edit Info</button>
<button onclick="location.href='<?php echo base_url();?>profile/logout'">Log Out</button>

</body>
</html>

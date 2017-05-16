<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Welcome <?php echo "$username"; ?></h1>
Name: <?php echo "$name"; ?> <br>
Address: <?php echo "$address"; ?> <br>
Birthday: <?php echo "$birthday"; ?> <br>

<button onclick="location.href='profile/onlineusers'">View Online</button>
<button onclick="location.href='profile/viewbirthdays'">View User Birthdays</button>
<button onclick="location.href='profile/logout'">Edit Info</button>
<button onclick="location.href='profile/logout'">Log Out</button>

</body>
</html>

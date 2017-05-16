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
<button onclick="location.href='profile/logout'">Edit Info</button>
<button onclick="location.href='profile/superview?username=<?php echo $username;?>'">View Users</button>
<button onclick="location.href='profile/superview'">Create User</button>
<button onclick="location.href='profile/superview'">Update User</button>
<button onclick="location.href='profile/superview'">Edit User</button>
<button onclick="location.href='profile/superview'">Delete User</button>
<button onclick="location.href='profile/logout'">Log Out</button>
</body>
</html>

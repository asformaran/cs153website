<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<?php 
	if ($this->session->flashdata('alert')){
		echo $this->session->flashdata('alert');
		echo "<br><br>";
	}
?>

<h1>Welcome <?php echo "$username"; ?></h1>
Name: <?php echo "$name"; ?> <br>
Address: <?php echo "$address"; ?> <br>
Birthday: <?php echo "$birthday"; ?> <br>

<button onclick="location.href='<?php echo base_url();?>profile/onlineusers'">View Online</button>
<button onclick="location.href='<?php echo base_url();?>profile/editself'">Edit Info</button>
<button onclick="location.href='<?php echo base_url();?>profile/superview'">View Users</button>
<button onclick="location.href='<?php echo base_url();?>profile/superadd'">Create User</button>
<button onclick="location.href='<?php echo base_url();?>profile/superview'">Update User</button>
<button onclick="location.href='<?php echo base_url();?>profile/superview'">Edit User</button>
<button onclick="location.href='<?php echo base_url();?>profile/superview'">Delete User</button>
<button onclick="location.href='<?php echo base_url();?>profile/logout'">Log Out</button>

</body>
</html>

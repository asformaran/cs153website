<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Add User</h1>

<?php echo validation_errors(); ?>
<?php 
	if (isset($error)){
		echo "$error";
	}
?>

<?php echo form_open('profile/superadd'); ?>

	Username: <input type="text" name="username"><br>
	Password: <input type="password" name="password"><br> 
	Name: <input type="text" name="name"><br>
	Address: <input type="text" name="address"><br> 
	Birthday: <input type='date' name='birthday'><br>
	<input type="submit" name='submitted' value="Create">
	<input type="submit" name='cancelled' value="Back">

<?php echo form_close(); ?>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Change Temporary Credentials</h1>

<?php echo validation_errors(); ?>
<?php 
	if (isset($error)){
		echo "$error";
	}
?>

<?php echo form_open('login/notroot'); ?>

	Username: <input type="text" name="username"><br>
	Password: <input type="password" name="password"><br> 
	Name: <input type="text" name="name"><br>
	Address: <input type="text" name="address"><br> 
	Birthday: <input type='date' name='birthday'><br>
	<input type="submit" name='submitted' value="Create">

<?php echo form_close(); ?>

</body>
</html>

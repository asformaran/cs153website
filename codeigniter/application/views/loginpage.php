<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Login</h1>

<?php echo validation_errors(); ?>
<?php 
	if (isset($error)){
		echo "$error";
	}
?>

<?php echo form_open('login'); ?>

	Username: <input type="text" name="username"><br>
	Password: <input type="password" name="password"><br> 
	<input type="submit" value="Login">

<?php echo form_close(); ?>

</body>
</html>

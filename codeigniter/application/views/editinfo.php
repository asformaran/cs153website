<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Edit Info</h1>

<?php echo validation_errors(); ?>
<?php 
	if (isset($error)){
		echo "$error";
	}
?>

<?php echo form_open('profile/editself'); ?>

	Name: <input type="text" name="name" value = "<?php echo $name;?>"><br>
	Address: <input type="text" name="address" value = "<?php echo $address; ?>"><br> 
	Birthday: <input type='date' name='birthday' value = "<?php echo $birthday; ?>"><br>
	<input type="submit" value="Update">

<?php echo form_close(); ?>

</body>
</html>

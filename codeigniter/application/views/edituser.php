<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Edit User</h1>

<?php echo validation_errors(); ?>
<?php 
	if (isset($error)){
		echo "$error";
	}
?>

<?php echo form_open('profile/superedit'); ?>

	<?php 
		
		foreach ($users as $user){
			echo '<input type="radio" name="chosen" value="'.$user['username'].'" '.set_radio('chosen', $user['username']).'>';
			echo $user['username'];
			echo '<br>';
			
		}
	?>
	Name: <input type="text" name="name" value=<?php if (isset($name)) {echo "'".$name."'";}?> ><br>
	Address: <input type="text" name="address" value=<?php if (isset($address)) {echo "'".$address."'";}?>><br> 
	Birthday: <input type='date' name='birthday' value=<?php if (isset($birthday)) {echo "'".$birthday."'";}?>><br>
	<input type="submit" name='view' value="View">
	<input type="submit" name='updated' value="Update">
	<input type="submit" name='cancelled' value="Back">

<?php echo form_close(); ?>

</body>
</html>

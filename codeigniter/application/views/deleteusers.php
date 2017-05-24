<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Delete Users</h1>

<?php echo form_open('profile/superdelete'); ?>

	<?php

		foreach ($users as $user){
			echo '<input type="checkbox" name="deletearr[]" value="'.$user['username'].'">';
			echo $user['username'].', '.$user['name'];
			echo '<br>';
		}
	?>
	<input type="submit" name='deleted' value="Delete">
	<input type="submit" name='cancelled' value="Back">

<?php echo form_close(); ?>

</body>
</html>

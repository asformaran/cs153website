<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>Change Privilege</h1>

<?php echo form_open('profile/superpriv'); ?>

	<?php

		foreach ($users as $user){
			echo '<input type="checkbox" name="privlevel[]" value="'.$user['username'].'">';
			if ($user['superuser'] == 1) {
				$superuser = 'superuser';
			}
			else {
				$superuser = 'user';
			}
			echo $user['username'].', '.$superuser;
			echo '<br>';
		}
	?>
	<input type="submit" name='changed' value="Change">
	<input type="submit" name='cancelled' value="Back">

<?php echo form_close(); ?>

</body>
</html>

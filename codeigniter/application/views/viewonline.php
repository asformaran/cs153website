<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>All Online Users</h1>

<ul>
    <?php foreach ($online as $user):?>

        <li><?php echo $user['username'];?></li>

    <?php endforeach;?>
</ul>
<button onclick="location.href='<?php echo base_url();?>profile'">Back</button>

</body>
</html>

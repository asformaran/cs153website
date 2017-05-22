<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>All Users</h1>

<ul>
    <?php foreach ($users as $user):?>

        <li><?php echo $user['name'] . ", " . $user['address'] . ", " . $user['birthday'];?></li>

    <?php endforeach;?>
</ul>
<button onclick="location.href='<?php echo base_url();?>profile'">Back</button>

</body>
</html>

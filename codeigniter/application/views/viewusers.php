<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>All Users</h1>

<ul>
    <?php foreach ($users as $user):?>

        <li><?php echo $user['name'] . " " . $user['address'] . " " . $user['birthday'];?></li>

    <?php endforeach;?>
</ul>

</body>
</html>

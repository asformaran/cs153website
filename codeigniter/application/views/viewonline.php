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

</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to our Website</title>
</head>
<body>

<h1>All User Birthdays</h1>

<ul>
    <?php foreach ($users as $user):?>

        <li><?php echo $user['username'] . "," . $user['name'] . "," . $user['birthday'];?></li>

    <?php endforeach;?>
</ul>

</body>
</html>

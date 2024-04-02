
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../public/assets/css/style.css">
</head>
<body>
<?php
include "../app/core/functions.php";
include "../app/core/config.php";
include "../app/pages/includes/header.php";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $values = [];
    $values['username'] = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(empty($password)) {
        message("Password is required");
    } else {
        $query = "select * from users where username = :username limit 1";
        $row = db_query_one($query,$values);

        if(!empty($row) && $password == $row['password'])
        {
            authenticate($row);
            message("Login successful");
            redirect('admin');  				
        }
        else
        {
            message("Wrong username or password"); 
        }
    }
}
?>
	<section class="content">
 
		<div class="login-holder">

		<?php if(message()):?>
			<div class="alert"><?=message('',true)?></div>
		<?php endif;?>

			<form method="post">
				<h2>Login</h2>
				<input value="<?=set_value('username')?>" class="my-1 form-control" type="text" name="username" placeholder="Username">
				<input value="<?=set_value('password')?>" class="my-1 form-control" type="password" name="password" placeholder="Password" >
				<button class="my-1 btn " style="background-color:#5755FE;  border-radius: 5rem; color:#f7f7f7">Login</button>
			</form>
		</div>
	</section>
    <?php include "../app/pages/includes/footer.php";?>
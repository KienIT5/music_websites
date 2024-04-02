<?php
include "../app/core/functions.php";
include "../app/core/config.php";
require page('includes/header');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {

  $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE username = :username";
  $result_username = db_query_one($query, ['username' => $username]);

  if (!empty($result_username)) {
      message('Tên đăng nhập đã tồn tại.');
  }

  $query = "SELECT * FROM users WHERE email = :email";
  $result_email = db_query_one($query, ['email' => $email]);

  if (!empty($result_email)) {
      message('Email đã được sử dụng.');
  }

  if (empty($result_username) && empty($result_email)) {
      $values = [
          'username' => $username,
          'email' => $email,
          'password' => $password,
      ];

      $query = "INSERT INTO users (" . implode(',', array_keys($values)) . ", role) VALUES (:" . implode(', :', array_keys($values)) . ", 'user')";
      $row_count = db_query_insert($query, $values);

    if ($row_count !== false && $row_count > 0) {
        message('<span style="background-color: green;color: white">Sign up successful, you are preparing to redirect to the login page!</span>');
        echo '<script>
            setTimeout(function() {
                window.location.href = "login.php";
            }, 3000);
        </script>';
    } else {
        message('Sign up failed. Please try again.');
    }
  }
}

?>

<section class="content">
  <div class="signup-holder">
    <?php if (message()): ?>
      <div class="alert"><?= message('', true) ?></div>
    <?php endif; ?>

    <form method="post">
      <link rel="stylesheet" href="../public/assets/css/style.css">
      <center><img src="assets/images/logo.jpg" style="width: 100%;border-radius: 50%;border: solid thin #f7f7f7;"></center>
      <h2>Sign Up</h2>
      <input value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" class="my-1 form-control" type="text" name="username" placeholder="Username">
      <input class="my-1 form-control" type="password" name="password" placeholder="Password">
      <input value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" class="my-1 form-control" type="email" name="email" placeholder="Email">
      <button class="my-1 btn" style="background-color: #5755FE; color: aliceblue; border-radius: 5rem">Sign Up</button>    </form>
  </div>
</section>

<?php require page('includes/footer'); ?>

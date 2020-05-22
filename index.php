<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
  <body class="content">
<?php
if (
  empty($_SERVER['PHP_AUTH_USER']) ||
  empty($_SERVER['PHP_AUTH_PW']) ||
  $_SERVER['PHP_AUTH_USER'] != 'admin' ||
  md5($_SERVER['PHP_AUTH_PW']) != md5('111')
) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

if (isset($_GET['do']) && $_GET['do'] == 'rm_users') {
  header('Location: del.php');
}

header('Content-Type: text/html; charset=UTF-8');
include('db.php');

try {
  $db = connectToDB();
  $stmt = $db->prepare("SELECT * FROM user ORDER BY id");
  $stmt->execute();
  $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  print $e->getMessage();
}

print('<h1>Admin Page</h1>');
?>


  <?php foreach ($response as $user) { ?>
    <div name="<?= 'user_' . $user['id'] ?>">
      <!-- при нажатии на крестик скрываем карточку юзера и заносим его ID в куку, чтобы потом удалить из БД -->
      <p  class="boba" name="<?= $user['id'] ?>" onclick="
        document.getElementsByName(`user_<?= $user['id'] ?>`)[0].style.display=`none`;
        document.cookie = 'rm_user_<?= $user['id'] ?> = <?= $user['id'] ?>; path=/b6/';
      ">&#215;</p>

      <?php foreach ($user as $key => $value) {
        if ($key == 'pass_hash') {
          continue;
        }
        if ($key == 'id') {
          print '<h3>USER ID: ' . $value . '</h3>';
          continue;
        }
        if (strstr($key, 'skill_')) {
          $key = substr($key, 6);
          $value = ($value == 1) ? 'Yes' : 'No';
        }
      ?>
      <!--Выводим данные пользователя из базы данных -->
        <div class="vova"><?= '<b>' . $key . '</b>' . ': ' . $value ?></div>
      <?php } ?>
    </div></br>
  <?php }

  //подтверждение удаления
  print '<p><button onclick="document.location.replace(`index.php?do=rm_users`)">Update</button></p>';
  ?>

</body>
</html>

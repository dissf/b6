<?php
include('db.php');
// ID пользователей для удаления из БД
$rm_users = [];
foreach ($_COOKIE as $key => $value) {
  if (strstr($key, 'rm_user_')) {
    $rm_users[] = $value;
  }
}
if (empty($rm_users)) {
  header('Location: index.php');
} else {
  // очищаем куки
  foreach ($_COOKIE as $key => $value) {
    if (strstr($key, 'rm_user_')) {
      setcookie($key, '', 1, "/b6/");
    }
  }
  // удаляем выбранных пользователей из базы данных
  try {
    $query = "DELETE FROM user WHERE ";
    foreach ($rm_users as $id) {
      if ($id == end($rm_users)) {
        $query .= 'id = ' . $id;
      } else {
        $query .= 'id = ' . $id . ' OR ';
      }
    }
    $db = connectToDB();
    $stmt = $db->prepare($query);
    $stmt->execute();
  } catch (PDOException $e) {
    print $e->getMessage();
  }
  header('Location: index.php');
}

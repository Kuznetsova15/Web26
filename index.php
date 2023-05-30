<?php
$user = 'u53002';
$pass = '8089091';
$db = new PDO('mysql:host=localhost;dbname=u53002', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
$pass_hash=array();
try{
  $get=$db->prepare("select pass from admin where user=?");
  $get->execute(array('admin'));
  $pass_hash=$get->fetchAll()[0][0];
}
catch(PDOException $e){
  print('Error: '.$e->getMessage());
}
if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      $_SERVER['PHP_AUTH_USER'] != 'admin' ||
      md5($_SERVER['PHP_AUTH_PW']) != $pass_hash) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Unauthorized (Требуется авторизация)</h1>');
    exit();
}
if(empty($_GET['edit_id'])){
  header('Location: admin.php');
}
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    setcookie('name_value', '', 100000);
    setcookie('email_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('pol_value', '', 100000);
    setcookie('limb_value', '', 100000);
    setcookie('bio_value', '', 100000);
    setcookie('inv_value', '', 100000);
    setcookie('walk_value', '', 100000);
    setcookie('fly_value', '', 100000);
    setcookie('check_value', '', 100000);
  }
  
  $errors = array();
  $error=FALSE;
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['radio-1'] = !empty($_COOKIE['pol_error']);
  $errors['radio-2'] = !empty($_COOKIE['limb_error']);
  $errors['super'] = !empty($_COOKIE['super_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['check-1'] = !empty($_COOKIE['check_error']);
  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя или у него неверный формат (only English)</div>';
    $error=TRUE;
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните имейл или у него неверный формат</div>';
    $error=TRUE;
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Выберите год.</div>';
    $error=TRUE;
  }
  if ($errors['radio-1']) {
    setcookie('pol_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
    $error=TRUE;
  }
  if ($errors['radio-2']) {
    setcookie('limb_error', '', 100000);
    $messages[] = '<div class="error">Укажите кол-во конечностей.</div>';
    $error=TRUE;
  }
  if ($errors['super']) {
    setcookie('super_error', '', 100000);
    $messages[] = '<div class="error">Выберите суперспособности(хотя бы одну).</div>';
    $error=TRUE;
  }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию или у неё неверный формат (only English)</div>';
    $error=TRUE;
  }
  $values = array();
  $values['inv'] = 0;
  $values['walk'] = 0;
  $values['fly'] = 0;
  

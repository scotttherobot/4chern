<?php
require_once("Chern.class.php");
$user = $_POST['user'];
$pass = $_POST['pass'];
$admin = $_POST['admin'];
$is_lead = 0;

$chern = new Chern();
$chern->createUser($user, $pass, $admin);
?>

<html>
<? $chern->urlredir('admin.php');?>
</html>
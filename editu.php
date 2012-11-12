<?php
require_once("header.php");
$user = $_POST['user'];
$admin = $_POST['admin'];

$chern = new Chern();
$chern->editUser($user, $admin);
?>

<html>
<? $chern->urlredir('profile.php?user='.$user);?>
</html>
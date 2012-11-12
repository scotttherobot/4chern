<?php
require_once("Chern.class.php");
$user = $_POST['user'];

$chern = new Chern();
$chern->deleteUser($user);
?>

<html>
<? $chern->urlredir('admin.php');?>
</html>
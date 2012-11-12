<?php
$thread = $_GET['thread'];
require_once("header.php");
$thread_name = $chern->threadName($thread);

?>
<html>
<h1> <? echo($thread_name);?> </h1>

<?
$chern->leadPost();
$chern->remainingPosts();
?>

<br><br>
<? $chern->makePostForm($thread); ?>
</html>
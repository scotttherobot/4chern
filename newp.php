<?php
require_once("Chern.class.php");
$thread_id = $_GET['thread'];
$author_id = $_POST['auth'];
$body = $_POST['body'];
$image = $_POST['image'];
$user = $_POST['user'];
$pass = $_POST['pass'];
$is_lead = 0;

$chern = new Chern();
$chern->createPost($author_id, $thread_id, $body, $is_lead, $image);
$thread_name = $chern->threadName($thread_id);
?>

<html>
<? $chern->redir($thread_id);?>
</html>
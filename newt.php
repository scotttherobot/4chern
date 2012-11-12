<?php
require_once("Chern.class.php");
$thread_name = $_POST['name'];
$tags = $_POST['tags'];
$author_id = $_POST['auth'];
$body = $_POST['body'];
$user = $_POST['user'];
$pass = $_POST['pass'];
$image = $_POST['image'];

$chern = new Chern();
$thread_id = $chern->createThread($thread_name, $tags, $author_id, $body, $image);
$thread_name = $chern->threadName($thread_id);
?>

<html>
<? $chern->redir($thread_id); ?>
</html>
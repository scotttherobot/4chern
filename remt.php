<?php
require_once("Chern.class.php");

$thread_id = $_POST['thread'];
$post_id = $_POST['post'];

require_once("header.php");
?>

<html>
<?

if(!$thread_id && $post_id){
	echo('post_id received. <br><br>');
	$chern->printPost($post_id);
	echo('<b>Attempting to delete post '.$post_id.'<b><br>');
	$chern->deletePost($post_id);
}
else if(!$post_id && $thread_id){
	echo('thread_id received. <br><br>');
	$chern->printLead($thread_id);
	echo('Attempting to delete entire thread '.$thread_id.'<br>');
	$chern->deleteThread($thread_id);
}
else if($post_id && $thread_id){
	echo('Only one parameter may be set at a time. <br>');
	echo('Nothing was deleted. ');
}
else{
	echo('You must set one parameter. <br>');
	echo('Nothign was deleted.');
}

?>
</html>
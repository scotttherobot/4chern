<?php
require_once("header.php");
$profile = $_GET['user'];

if($profile){
} else{
	$profile = $chern->username;
}

?>
<html>

<? 

if($chern->is_admin){
	$chern->adminOptions($profile);
}

?>


<h1> <? echo($profile); ?>'s posts </h1>

<? $chern->printPostBy($profile);?>

</html>
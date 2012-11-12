<?php

require_once("header.php");
if(!$chern->loggedin){
	echo('wat.');
	die;
}
?>

<html>
<h3> Admin Console </h3>

<div style="width:300px; height:350px; border:3px solid red; float:left;">
	<center>
	<font color=red>Destroy Things </font>
		<div style="width:250px; border:1px solid red">
			<font color=red>Delete a post: </font>
			<? $chern->deletePostForm();?>
		</div>
		<div style="width:250px; border:1px solid red">
			<font color=red>Delete a thread: </font>
			<? $chern->deleteThreadForm();?>
		</div>
		<div style="width:250px; border:1px solid red">
			<font color=red>Delete a User: </font>
			<? $chern->deleteUserForm();?>
		</div>
	</center>
</div>

<div style="width:300px; height:200px; border:3px solid green; float:left;">
	<center>
	<font color=green>Create Things </font>
		<div style="width:250px; border:1px solid green">
			<font color=green>Add a User: </font>
			<? $chern->newUserForm();?>
		</div>
	</center>
</div>

<div style="width:300px; height:200px; border:3px solid blue; float:left;">
	<center>
	<font color=blue>View Things </font>
		<div style="width:250px; border:1px solid blue">
			<font color=blue>Registered Users: <br></font>
			<? $chern->printUsers();?>
		</div>
	</center>
</div>

<br><br>
</html>
<?php

require_once("Database.class.php");

class Chern
	{
	
	var $db;
	var $thread_id;
	var $post_id;
	var $lead_post_id;
	var $username = 'anon';
	var $is_admin = 0;
	var $pass;
	var $loginfailed;
	var $loggedin = false;
	
	function __construct(){
		$this->db = new Database();
	}
	
///// The header for all pages
	function printheader($thread_id = null){
		echo('<font face="Comic Sans MS">');
		echo('<head><title>4ChErN</title></head>');
		echo('<a href="/4chern" style="text-decoration:none; color:#000000;">');
		echo('<div><font size=5>');
		echo('4ChErN');
		echo('</font></div>');
		echo('</a>');
		echo('<div style="position:absolute;
						  top:0;
						  right:5;">');
		if($this->loginfailed){
				echo('<font size=5 color=red>Sorry, login failed.</font>');
		}
		else if($this->loggedin && $this->is_admin == 1){
			echo('<font size=5>Welcome, '.$this->username.'</font><br>');
			echo('<font size=1><a href="index.php?logout=1" style="text-decoration:none; color:#000000;">Logout</a>');
			echo('<a href="profile.php" style="text-decoration:none; color:#000000;">  My Posts</a>');
			echo('<a href="/4chern" style="text-decoration:none; color:#000000;">  Home</a>');
			echo('<a href="admin.php" style="text-decoration:none; color:#000000;">  Admin Panel</a></font>');
		}
		else if($this->loggedin){
			echo('<font size=5>Welcome, '.$this->username.'</font><br>');
			echo('<font size=1><a href="index.php?logout=1" style="text-decoration:none; color:#000000;">Logout</a>');
			echo('<a href="profile.php" style="text-decoration:none; color:#000000;">  My Posts</a>');
			echo('<a href="/4chern" style="text-decoration:none; color:#000000;">  Home</a></font>');
		}
		else{
			$this->loginForm($thread_id);
		}
		echo('</div><hr>');
	}
	
///// Thread Listing
	function printThreads(){
		$db = $this->db;
		$sql = "SELECT * FROM threads ORDER BY thread_id DESC";
		$db->query($sql);
		while($db->nextRecord()){
				echo('<a href="post.php?thread=' . $db->Record['thread_id'] . '">' . $db->Record['thread_name'] .' </a><br> ');
		}
	}
	function printUsers(){
		$db = $this->db;
		$sql = "SELECT * FROM users ORDER BY username ASC";
		$db->query($sql);
		while($db->nextRecord()){
				echo('<a href="profile.php?user=' . $db->Record['username'] . '">' . $db->Record['username'] .' </a><br> ');
		}
	}

///// Displaying a Thread	
	function threadName($thread_id){
		$db = $this->db;
		$sql = "SELECT * FROM threads";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['thread_id'] == $thread_id){
				$this->thread_id = $thread_id;
				$this->lead_post_id = $db->Record['lead_post_id'];
				return($db->Record['thread_name']);
			}
		}
	}
	function leadPost(){
		$db = $this->db;
		$sql = "SELECT * FROM posts";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['thread_id'] == $this->thread_id && $db->Record['is_lead'] && $db->Record['author_id'] == $this->username && $this->username != 'anon'){
				//echo('post '. $db->Record['post_id'] . ' by ');
				echo('<font style="BACKGROUND-COLOR: red"><b>OP SEZ:</b></font>');
				echo('<font style="BACKGROUND-COLOR: green">');
				echo(' by <a href="profile.php?user=' . $db->Record['author_id'] . '" style="text-decoration:none; color:#000000;">'.$db->Record['author_id'].' </a>');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deleteThreadButton($db->Record['thread_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=300>');
				echo('<i> ' . $db->Record['body'] . ' </i><br><br>~~~~~~~<br>');
			}
			else if($db->Record['thread_id'] == $this->thread_id && $db->Record['is_lead'] && $this->is_admin == 1){
				//echo('post '. $db->Record['post_id'] . ' by ');
				echo('<font style="BACKGROUND-COLOR: red"><b>OP SEZ:</b></font>');
				echo('<font style="BACKGROUND-COLOR: green">');
				echo(' by <a href="profile.php?user=' . $db->Record['author_id'] . '" style="text-decoration:none; color:#000000;">'.$db->Record['author_id'].' </a>');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deleteThreadButton($db->Record['thread_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=300>');
				echo('<i> ' . $db->Record['body'] . ' </i><br><br>~~~~~~~<br>');
			}
			else if($db->Record['thread_id'] == $this->thread_id && $db->Record['is_lead'] ){
				//echo('post '. $db->Record['post_id'] . ' by ');
				echo('<font style="BACKGROUND-COLOR: red"><b>OP SEZ:</b></font>');
				echo('<font style="BACKGROUND-COLOR: green">');
				echo(' by <a href="profile.php?user=' . $db->Record['author_id'] . '" style="text-decoration:none; color:#000000;">'.$db->Record['author_id'].' </a>');
				echo('at ' . $db->Record['date'] . '</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=300>');
				echo('<i> ' . $db->Record['body'] . ' </i><br><br>~~~~~~~<br>');
			}
		}
	}
	function remainingPosts(){
		$db = $this->db;
		$sql = "SELECT * FROM posts ORDER BY post_id ASC";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['thread_id'] == $this->thread_id && $db->Record['is_lead'] == 0 && $this->is_admin == 1){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo(' by <a href="profile.php?user=' . $db->Record['author_id'] . '" style="text-decoration:none; color:#000000;">'.$db->Record['author_id'].' </a>');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deletePostButton($db->Record['post_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['thread_id'] == $this->thread_id && $db->Record['is_lead'] == 0 && $db->Record['author_id'] == $this->username && $this->username != 'anon'){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo(' by <a href="profile.php?user=' . $db->Record['author_id'] . '" style="text-decoration:none; color:#000000;">'.$db->Record['author_id'].' </a>');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deletePostButton($db->Record['post_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['thread_id'] == $this->thread_id && $db->Record['is_lead'] == 0){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo(' by <a href="profile.php?user=' . $db->Record['author_id'] . '" style="text-decoration:none; color:#000000;">'.$db->Record['author_id'].' </a>');
				echo('at ' . $db->Record['date'] . ' </font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
		}
	}
///// Displaying specific things
	function printPost($post_id){
		$db = $this->db;
		$sql = "SELECT * FROM posts";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['author_id'] == $username && $db->Record['author_id'] == $this->username && $this->username != 'anon'){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deletePostButton($db->Record['post_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['author_id'] == $username){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . '  </font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
		}
	}
	function printPostBy($username){
		$db = $this->db;
		$sql = "SELECT * FROM posts ORDER BY post_id ASC";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['author_id'] == $username && $this->is_admin == 1 && $db->Record['is_lead'] == '1'){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deleteThreadButton($db->Record['thread_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['author_id'] == $username && $this->is_admin == 1){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deletePostButton($db->Record['post_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['author_id'] == $username && $db->Record['author_id'] == $this->username && $this->username != 'anon' && $db->Record['is_lead'] == '1'){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deleteThreadButton($db->Record['thread_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['author_id'] == $username && $db->Record['author_id'] == $this->username && $this->username != 'anon'){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . ' ');
				$this->deletePostButton($db->Record['post_id']);
				echo('</font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
			else if($db->Record['author_id'] == $username){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . ' in <a href="post.php?thread='. $db->Record['thread_id'] . '" style="text-decoration:none; color:#000000;"> thread '. $db->Record['thread_id'] .'<a> ');
				echo('at ' . $db->Record['date'] . '  </font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=200>');
				echo('<i> ' . $db->Record['body'] . ' </i><br>');
			}
		}
	}
	function printLead($thread_id){
		$db = $this->db;
		$sql = "SELECT * FROM posts";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['thread_id'] == $thread_id && $db->Record['is_lead']){
				//echo('post '. $db->Record['post_id'] . ' by ');
				echo('<font style="BACKGROUND-COLOR: red"><b>OP SEZ:</b></font>');
				echo('<font style="BACKGROUND-COLOR: green">');
				echo(' by <a href="profile.php?user="' . $db->Record['author_id'] . '">'.$db->Record['author_id'].'</a>');
				echo(' at ' . $db->Record['date'] . '  </font><br>');
				echo('<img src="' . $db->Record['image'] . '" width=300>');
				echo('<i> ' . $db->Record['body'] . ' </i><br><br>~~~~~~~<br>');
			}
		}
	}
	
	
///// Crudely dumping a Thread
	function printThread($thread_id){
		$db = $this->db;
		$sql = "SELECT * FROM threads";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['thread_id'] == $thread_id){
				$this->thread_id = $thread_id;
				$this->post_id = $db->Record['lead_post_id'];
				echo('<b>' . $db->Record['thread_name'] . ' </b><br>  ');
			}
		}
		$this->printComments();
	}	
	function printComments(){
		$db = $this->db;
		$sql = "SELECT * FROM posts ORDER BY post_id DESC";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['thread_id'] == $this->thread_id){
				echo('<font style="BACKGROUND-COLOR: yellow">post '. $db->Record['post_id'] . '  ');
				echo('by '. $db->Record['author_id'] . '  ');
				echo('at ' . $db->Record['date'] . '  </font><br>');
				echo('<i>' . $db->Record['body'] . ' </i><br>');
			}
		}
	}
	
///// Creating and Deleting Threads and Posts
	function createThread($thread_name, $tags, $author_id, $body, $image){
		// (thread_name, tags, author_id)
		$db = $this->db;
		$esname = $db->mysql_escape_mimic($thread_name);
		$sql = "INSERT INTO threads (thread_name, tags, author_id) VALUES ('".$esname."','".$tags."','".$author_id."')";
		$db->query($sql);
		$thread_id = $db->lastId();
		$this->createPost($author_id, $thread_id, $body, 1, $image);
		return($thread_id);
	
	}
	function createPost($author_id, $thread_id, $body, $is_lead, $image){
		// (author_id, thread_id, body, is_lead)
		if(!$is_lead){
			$is_lead = 0;
		}
		$db = $this->db;
		$esbody = $db->mysql_escape_mimic($body);
		$esimg = $db->mysql_escape_mimic($image);
		$sql = "INSERT INTO posts (author_id, thread_id, body, is_lead, image) VALUES ('".$author_id."','".$thread_id."','".$esbody."','".$is_lead."','".$esimg."')";
		$db->query($sql);
	
	}
	function deleteThread($thread_id){
		$db = $this->db;
		$sql = "DELETE FROM threads
				WHERE thread_id='".$thread_id."'";
		$db->query($sql);
		$sql = "DELETE FROM posts
				WHERE thread_id='".$thread_id."'";
		$db->query($sql);
		echo('Entire thread ' .$thread_id. ' was successfully deleted.<br>');
		
	}
	function deletePost($post_id){
		$db = $this->db;
		$sql = "DELETE FROM posts
				WHERE post_id='".$post_id."'";
		$db->query($sql);
		echo('Single post ' .$thread_id. ' was successfully deleted.');
	}
	
///// Redirection methods.
	function redir($thread_id){
		echo('<meta http-equiv="REFRESH" content="0;url=post.php?thread=' .$thread_id.'">');
	}
	function urlredir($url){
		echo('<meta http-equiv="REFRESH" content="0;url=' .$url.'">');
	}
	
///// UI Forms
	function makeThreadForm(){
			//(name, tags, auth, body, image)
			echo('<FORM action="newt.php" method="post">');
			echo('<P>');
			echo('<i>New Thread</i><br>');
			echo('<LABEL for="auth">Thread Name: </LABEL>');
			echo('<INPUT type="text" id="name" name ="name"><BR>');
			echo('<LABEL for="auth">Author: </LABEL>');
			echo('<INPUT type="text" id="auth" name ="auth" value="'.$this->username.'" readonly="readonly"><BR>');
			echo('<LABEL for="body">Post: </LABEL>');
			echo('<textarea cols="50" rows="4" id="body"  name ="body"></textarea><BR>');
			echo('<LABEL for="image">Image: </LABEL>');
			echo('<INPUT type="text" id="image"  name ="image"><BR>');
			echo('<LABEL for="image">Tags: </LABEL>');
			echo('<INPUT type="text" id="tags"  name ="tags"><BR>');
			echo('<INPUT type="submit" value="Send"> <INPUT type="reset">');
			echo('</P>');
			echo('</FORM>');
	}
	function makePostForm($thread_id){
		// (author, body, image)
		echo('<FORM action="newp.php?thread='.$thread_id.'" method="post">');
		echo('<P>');
		echo('<LABEL for="auth">Author: </LABEL>');
		echo('<INPUT type="text" id="auth" name ="auth" value="'.$this->username.'" readonly="readonly"><BR>');
		echo('<LABEL for="body">Post: </LABEL>');
		echo('<textarea cols="50" rows="4" id="body"  name ="body"></textarea><BR>');
		echo('<LABEL for="image">Image: </LABEL>');
		echo('<INPUT type="text" id="image"  name ="image"><BR>');
		echo('<INPUT type="submit" value="Send"> <INPUT type="reset">');
		echo('</P>');
		echo('</FORM>');
	}
	function deletePostForm(){
		// (author, body, image)
		echo('<FORM action="remt.php" method="post">');
		echo('<P>');
		echo('<LABEL for="post">Post ID: </LABEL>');
		echo('<INPUT type="text" id="post" name ="post"><BR>');
		echo('<INPUT type="submit" value="Delete it!"> <INPUT type="reset">');
		echo('</P>');
		echo('</FORM>');
	}
	function deleteThreadForm(){
		// (author, body, image)
		echo('<FORM action="remt.php" method="post">');
		echo('<P>');
		echo('<LABEL for="thread">Thread ID: </LABEL>');
		echo('<INPUT type="text" id="thread" name ="thread"><BR>');
		echo('<INPUT type="submit" value="Delete it!"> <INPUT type="reset">');
		echo('</P>');
		echo('</FORM>');
	}
	function loginForm($thread_id){
		// (author, body, image)
		if($thread_id){
			echo('<FORM action='. $_SERVER['PHP_SELF'] .'?thread=' . $thread_id .' method="post">');
		} else {
			echo('<FORM action='. $_SERVER['PHP_SELF'] .' method="post">');
		}
			echo('<P>');
			echo('<INPUT type="text" id="user" name ="user" placeholder="Username">');
			echo('<INPUT type="password" id="pass"  name ="pass" placeholder="Password">');
			echo('<INPUT type="submit" value="login">');
			echo('</P>');
			echo('</FORM>');
		
	}
///// Buttons
	function deletePostButton($post_id){
		// (author, body, image)
		echo('<FORM style="display:inline;" action="remt.php" method="post">');
		echo('<INPUT type="hidden" id="post" name ="post" value="'.$post_id.'">');
		echo('<INPUT type="submit" value="Delete">');
		echo('</FORM>');
	}
	function deleteThreadButton($thread_id){
		// (author, body, image)
		echo('<FORM style="display:inline;" action="remt.php" method="post">');
		echo('<INPUT type="hidden" id="thread" name ="thread" value="'.$thread_id.'">');
		echo('<INPUT type="submit" value="Delete Thread">');
		echo('</FORM>');
	}
	function deleteUserButton($username){
		// (author, body, image)
		echo('<FORM style="display:inline;" action="remu.php" method="post">');
		echo('<INPUT type="hidden" id="user" name="user" value="'.$username.'"><BR>');
		echo('<INPUT type="submit" value="Delete This User">');
		echo('</FORM>');
	}
	function makeAdminButton($username){
		// (author, body, image)
		echo('<FORM style="display:inline;" action="editu.php" method="post">');
		echo('<INPUT type="hidden" id="user" name="user" value="'.$username.'">');
		echo('<INPUT type="hidden" id="admin" name="admin" value="1">');
		echo('<INPUT type="submit" value="Promote to Admin">');
		echo('</FORM>');
	}
	function demoteAdminButton($username){
		// (author, body, image)
		echo('<FORM style="display:inline;" action="editu.php" method="post">');
		echo('<INPUT type="hidden" id="user" name="user" value="'.$username.'">');
		echo('<INPUT type="hidden" id="admin" name="admin" value="0">');
		echo('<INPUT type="submit" value="Revoke Admin Status">');
		echo('</FORM>');
	}
///// Admin Options
	function adminOptions($user){
		if($this->username != $user && $user != 'anon'){
			$this->deleteUserButton($user);
			$this->makeAdminButton($user);
			$this->demoteAdminButton($user);
		}
		else {
			echo('[There are no administrative options for '.$user.']');
		}
	}
	
	
///// User Management
	function authUser($username, $password){
		$auth = $this->checkCredentials($username, $password);
		if($auth){
			$this->username = $username;
			$this->loginfailed = false;
			$this->loggedin = true;
			$this->checkAdmin();
		}
		else{
			$this->loginfailed = true;
			return false;
		}
		return $username;
	}
	function checkCredentials($username, $password){
		$db = $this->db;
		$sql = "SELECT * FROM users";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['username'] == $username && $db->Record['password'] == $password){
				return true;
				break;
			}
		}
	}
	function checkAdmin(){
		$db = $this->db;
		$sql = "SELECT * FROM users";
		$db->query($sql);
		while($db->nextRecord()){
			if($db->Record['username'] == $this->username){
				if($db->Record['is_admin'] == 1){
					$this->is_admin = 1;
				}
				break;
			}
		}
	}
	function createUser($username, $password, $admin){
		$db = $this->db;
		if($admin == 'on'){
			$sql = "INSERT INTO users (username, password, is_admin) VALUES ('".$username."','".$password."','1')";
		}
		else $sql = "INSERT INTO users (username, password, is_admin) VALUES ('".$username."','".$password."','0')";
		$db->query($sql);
	}
	
	function newUserForm(){
		// (author, body, image)
		echo('<FORM action="newu.php" method="post">');
		echo('<P>');
		echo('<LABEL for="user">Username: </LABEL>');
		echo('<INPUT type="text" id="user" name ="user"><BR>');
		echo('<LABEL for="pass">Password: </LABEL>');
		echo('<INPUT type="password" id="pass"  name ="pass"><BR>');
		echo('<LABEL for="admin">Make Admin: </LABEL>');
		echo('<INPUT type="checkbox" id="admin"  name ="admin"><BR>');
		echo('<INPUT type="submit" value="Submit"> <INPUT type="reset">');
		echo('</P>');
		echo('</FORM>');
	}
	function deleteUserForm(){
		// (author, body, image)
		echo('<FORM action="remu.php" method="post">');
		echo('<P>');
		echo('<LABEL for="user">Username: </LABEL>');
		echo('<INPUT type="text" id="user" name="user"><BR>');
		echo('<INPUT type="submit" value="Delete it!"> <INPUT type="reset">');
		echo('</P>');
		echo('</FORM>');
	}
	
	function deleteUser($username){
		$db = $this->db;
		$sql = "DELETE FROM users
				WHERE username='".$username."'";
		$db->query($sql);
		echo('User ' .$username. ' was successfully deleted.');
	}
	function editUser($username, $admin){
		$db = $this->db;
		$sql = "UPDATE users
				SET is_admin='".$admin."' 
				WHERE username='".$username."'";
		$db->query($sql);
		echo('User ' .$username. ' was successfully modified.');
	}
}
?>
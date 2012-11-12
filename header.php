<?
require_once("Chern.class.php");
require_once("Cookie.class.php");
$user = $_POST['user'];
$pass = $_POST['pass'];
$logout = $_GET['logout'];
$chern = new Chern();
$cookie = new Cookie();
$cookie->setName('chern');
$chern->thread_id = $thread;

if($logout){
	$cookie->delete();
}
// check for cookie here
else if($cookie->get()){
	$user = $cookie->get();
	$chern->username = $user;
	$chern->loggedin = true;
	$chern->checkAdmin();
}
// set cookie here
else if($user || $pass){
	if($chern->authUser($user, $pass)){
		$cookie->setName('chern');
		$cookie->setValue($user);
		// Set cookie expiration time
		$cookie->setTime("+1 hour");
		$cookie->create();
		$chern->username = $user;
		$chern->loggedin = true;
		$chern->checkAdmin();
	}
}


$chern->printheader($thread);



?>
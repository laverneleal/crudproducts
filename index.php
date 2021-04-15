<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>

<?php
include 'functions.php';
// Your PHP code here.

// Home Page template below.
?>

<?=template_header('Home')?>


<div class="content">
	<h2><?php echo 'Welcome ' . $_SESSION['name'] . '!'; ?></h2>
	
</div>

<?=template_footer()?>


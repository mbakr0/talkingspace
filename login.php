<?php require ('core/init.php'); ?>

<?php
$user = new User();

if (isset($_POST['do_login'])) {

		$username = $_POST['username'];
		$password = md5($_POST['password']);


		if ($user->login($username,$password)) {
				
			redirect("index.php","You have logged in ","success");
		} else {

			redirect("index.php","Login Error","error");
		}
	} else {
		redirect("index.php");
	}
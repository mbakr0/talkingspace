<?php require ('core/init.php'); ?>

<?php

$topic = new Topic();
$user = new User();
$validate = new validator();


$field_array = array('name','email','username','password','password2');

if (isset($_POST['register'])) {
	
	$data = array();
	$data['name'] = $_POST['name'];
	$data['email'] = $_POST['email'];
	$data['username'] = $_POST['username'];
	$data['password'] = md5($_POST['password']);
	$data['password2'] = md5($_POST['password2']);
	$data['about'] = $_POST['about'];

	if ($validate->isRequired($field_array)) {
		if ($validate->isValidEmail($data['email'])) {
			if ($validate->passwordMatch($data['password'],$data['password2'])) {
				
			} else {
				redirect('register.php','Your Passsword did not match','error');
			}
		} else {
			redirect('register.php','Please Use a vaild email address','error');
		}
		
	} else {
		redirect('register.php','Please fill in all required field','error');
	}

	//Uploade avatar Image
	if ($user->uploadAvatar()) {
		
		$data['avatar'] = $_FILES['avatar']['name'];
	} else {
		$data['avatar'] = "noimage.png";
	}

	//Register user
	if ($user->register($data)) {
		redirect('index.php','You are registered and can now log in ','Success');
	} else {
		redirect('index.php','Something went wrong with registeration ','error');
	}
}
$template = new Template('templates/register.php');
$template->totalTopics = $topic->getTotalTopics();

echo $template;
echo $_POST["register"];
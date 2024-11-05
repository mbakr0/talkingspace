<?php require ('core/init.php'); ?>

<?php
//Get Template
$topic = new Topic();
if (isset($_POST['do_create'])) {
	 	
	$validate = new Validator();
	$field_array = array("title","body","category");

	 $data = array();
	 $data['title'] = $_POST['title'];
	 $data['body']  = $_POST['body'];
	 $data['category_id'] = $_POST['category'];
	 $data['user_id'] = getUser()['user_id'];

	 if ($validate->isRequired($field_array)) {
	 	if ($topic->create($data)) {
	 		redirect('index.php','Your topic has been added','success');
	 	} else {
	 		redirect('create.php','Your topic has not added','error');
	 	}
	 } else {
	 	redirect('create.php','Please fill in all required field','error');
	 }


}
$template = new Template('templates/create.php');
$template->totalTopics = $topic->getTotalTopics();

echo $template;
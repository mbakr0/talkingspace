<?php require ('core/init.php'); ?>

<?php

$topic = new Topic();
$template = new Template('templates/topic.php');


$topic_id = isset($_GET["id"]) ? $_GET["id"] : 0  ;

if (isset($_POST["do_reply"])) {

	$data = array();
	$data["topic_id"] = $_GET['id'];
	$data["body"] = $_POST['body'];
	$data["user_id"] = getUser()['user_id'];

	$valdiate = new Validator();

	$field_array = array('body');

	if ($valdiate->isRequired($field_array)) {

		if ($topic->reply($data)) {
			redirect('topic.php?id='.$data['topic_id'],'your replay has been posted','success');
		} else {
			redirect('topic.php?id='.$data['topic_id'],'your replay has not been posted','error');
		}
	} else {
		redirect('topic.php?id='.$data['topic_id'],'your replay is blank','error');
	}


}

$template->topic = $topic->getTopic($topic_id);
$template->replies = $topic->getReplies($topic_id);
$template->title = $topic->getTopic($topic_id)['title']; 
$template->totalTopics = $topic->getTotalTopics();

echo $template;


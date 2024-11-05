<?php require ('core/init.php'); ?>

<?php
//Get Template
$topic = new topic();

$category = isset($_GET["category"]) ? $_GET["category"] : null;

$user = isset($_GET["user"]) ? $_GET["user"] : null;


$template = new Template('templates/topics.php');



if (isset($category)) {

	$template->topics = $topic->getByCategory($category);
	$template->title = 'Posts In "'.$topic->getCategory($category)['name'].'"';
}

if (isset($user)) {

	$template->topics = $topic->getByUser($user);
	//$template->title = 'Posts In "'.$topic->getUser($user)['name'].'"';
}




if (!isset($category) && !isset($user) ){

	$template->topics = $topic->getAllTopics();
}



$template->totalTopics = $topic->getTotalTopics();
$template->totalCategories= $topic->getTotalCategories();
echo $template;
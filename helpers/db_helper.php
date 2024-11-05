<?php
/*
 *
 */

 function replyCount($topic_id){

  	$db = new Database();
 	$db->query("SELECT * FROM replies WHERE topic_id = :topic_id");
 	$db->bind(':topic_id',$topic_id);
 	$db->resultset();
  
 	return $db->rowCount(); 	
 }

 function getCategories(){

 	$db = new Database();
 	$db->query("SELECT * FROM Categories");
 	
 	$results = $db->resultset();

 	return $results;
 }

 function userPostCount($id){
 	$db = new Database();

 	$db->query("SELECT * FROM topics WHERE user_id = :id");
 	$db->bind(":id",$id);

 	$row = $db->resultset();
 	$topic_count = $db->rowCount();


 	$db->query("SELECT * FROM replies WHERE user_id = :id");
 	$db->bind(":id",$id);

 	$row = $db->resultset();
 	$reply_count = $db->rowCount();

 	return $reply_count + $topic_count;

 }
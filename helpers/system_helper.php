<?php

function redirect($page = FALSE , $message = null , $message_type = null){


		if (is_string($page)) {
			$location = $page;
		} else {
			$location = $_SERVER['SCRIPT_NAME'];
		}


		//Check for massegaes
		if ($message != null) {
			$_SESSION['message'] = $message;
		}

		//Check for type
		if ($message_type != null) {
			$_SESSION['message_type'] = $message_type;
		}

		//Redirect
		header("Location: ".$location);
		exit;

}

function displayMessage(){

	if (!empty($_SESSION["message"])) {
		
		//Assign Message
		$message = $_SESSION['message'];

		if(!empty($_SESSION['message_type'])){

			//Assign Type 
			$message_type = $_SESSION['message_type'];

			//Create Output
			if ($message_type == "error") {
				echo "<div class='alert alert-danger'>". $message. "</div>";
			} else {
				echo "<div class='alert alert-success'>". $message."</div>";
			}

		}
		//Unset messages
		unset($_SESSION['message']);
		unset($_SESSION['message_type']);
		} else {
			echo "";
		}


}


	function isLoggedIn(){
		if (isset($_SESSION['is_logged_in'])) {
			return true;
		} else {
			return false;
		}
	}

	function getUser(){
		$userArrary = array();
		$userArrary["user_id"] = $_SESSION["user_id"];
 		$userArrary["name"] = $_SESSION["name"];
		$userArrary["username"] = $_SESSION["username"];
		return $userArrary;
	}


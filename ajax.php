<?php
include_once 'Action.class.php';

if(isset($_GET['action'])){
	switch($_GET['action']){
		
		case 'next_url':{
			$action = new Action();
			$next_url = false;
			
			if(isset($_SESSION['next_url'])) $next_url = $_SESSION['next_url'];
			if(isset($_REQUEST['next_url'])) $next_url = $_REQUEST['next_url'];
				
			if($next_url){
				$data = file_get_contents($next_url);
				if($data === false){
					echo 'NO_DATA';
				}else{
					$data = $action->getNextPhotos($data);
					echo json_encode($data);
				}
			}else{
				echo 'NO_DATA';
			}
		}
		
	}
}
<?php
session_start();
require_once 'Imagegen.class.php';

//$COLLAGES_PATH = '/var/www/instamodule/collages/';
$COLLAGES_PATH = '/home/idposter/public_html/instagram/collages/';
$IMAGE_WIDTH = '640';
$IMAGE_HEIGHT = '640';
$BORDER_WIDTH = '10';
$BORDER_HEIGHT = '10';

if(isset($_POST['data'])){
	
	//print_r($_POST);
	//print_r($_SESSION['data']);
	if(!$_SESSION['data']->user->id){
		echo json_encode(array('status' => 'ERROR','message' => 'SESSION_OUT'));
		die();
	}
	$user_dir = $COLLAGES_PATH . $_SESSION['data']->user->id;
	
	if(!is_dir($user_dir)){
		mkdir($user_dir, 0777);
	}
	
	$date = new DateTime();
	$sub = $date->format('U');
	
	$user_dir_collage = $user_dir . '/' . $sub;
	mkdir($user_dir_collage, 0777);

	$user_dir_collage_origs = $user_dir_collage . '/origs';
	mkdir($user_dir_collage_origs, 0777);
	
	//echo $user_dir_collage_origs;
	
	$file_paths = array();
	foreach($_POST['data']['images'] as $url){
		$path_info = pathinfo($url);
		//echo $path_info;

		$file_path = $user_dir_collage_origs.'/'.$path_info['basename'];
		
		$f = file_put_contents($file_path, fopen($url, 'r'));
		if($f){
			$file_paths[] = $file_path;
		}else{
			echo json_encode(array('status' => 'ERROR','message' => 'ERROR_UPLOADING_FILE'));
            //echo json_encode(array('status' => 'ERROR','message' => $user_dir_collage));
			die();			
		}
	}

       if(isset($_POST['data']['collage'])){

      // echo $_POST['data']['collage'];
		
		switch($_POST['data']['collage']){
			case '3x2':
			case '4x3':
			case '6x4':
			case '8x6':
			case '2x3':
			case '3x4':
			case '4x6':
			case '6x8':
			case '1x1':
			case '2x2':
			case '3x3':
			case '1x3':{ 
				$linear_arr = explode('x', $_POST['data']['collage']);
				$image = new Imagegen();
				if($_POST['data']['position'] == 'horizontal'){
					$image->genLinearCollage($linear_arr[0], $linear_arr[1], $IMAGE_WIDTH, $IMAGE_HEIGHT, $BORDER_WIDTH, $BORDER_HEIGHT, $_POST['data']['background_color'], $file_paths);
				}else{
					$image->genLinearCollage($linear_arr[1], $linear_arr[0], $IMAGE_WIDTH, $IMAGE_HEIGHT, $BORDER_WIDTH, $BORDER_HEIGHT, $_POST['data']['background_color'], $file_paths);
				}
				$collage_file_path = $user_dir_collage.'/collage.jpg';
				$saved = $image->save($collage_file_path);
				if($saved){
					echo json_encode(array('status' => 'SUCCESS','collage_id' => $sub));
				}else{
					echo json_encode(array('status' => 'ERROR','message' => 'ERROR_SAVING_FILE'));
					die();
				}
					
			}
		}
		
	}
}
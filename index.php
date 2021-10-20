<?php
	session_start();
	$root_path = '/home/idposter/public_html/';
	
	$page_action = 'index';
	if(isset($_GET['action'])){

		if($_GET['action'] == 'logout'){
			unset($_SESSION['data']);
			header('Location: http://idposter.com/Instagram_Print/');
			die();
		}
		if($_GET['action'] == 'view'){
			if(isset($_GET['collage'])){
				if($_SESSION['data']->user->id){
					$page_action = 'view';
					$collage_file_path = '/instagram/collages/'.$_SESSION['data']->user->id.'/'.$_GET['collage'].'/collage.jpg';
					$collage_files_path = '/instagram/collages/'.$_SESSION['data']->user->id.'/'.$_GET['collage'].'/origs/';
					$origs_files_dir = $root_path.$collage_files_path;
					$origs_files_list = array_diff(scandir($origs_files_dir), array('..', '.'));

                    $path = '.'.$collage_file_path;
                    $extensions = array('jpeg', 'jpg', 'png', 'gif');
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                    if(in_array($ext, $extensions))
                    {
                       if(isset($_SESSION['custom']))
                       {
                          if(!is_array($_SESSION['custom']))
                          {
                            $_SESSION['custom'] = array();
                          }
                       }

                       $size=getimagesize($path);
                       switch($size[2])
                       {
                              case 1: $src = @imagecreatefromgif($path); break;
                              case 2: $src = @imagecreatefromjpeg($path); break;
                              case 3: $src = @imagecreatefrompng($path); break;
                              case 6: $src = @imagecreatefromwbmp($path); break;
                       }

                       $max_h = '400';
                       $max_w = '400';
                       $iw=$size[0];
                       $ih=$size[1];

                       if($iw>$ih)
                       {
                          $new_w=$max_w;
                          $new_h=($max_h*$ih)/$iw;
                       }
                       else if($iw<$ih)
                       {
                          $new_h=$max_h;
                          $new_w=($max_w*$iw)/$ih;
                       }
                       else
                       {
                          $new_h=$max_h;
                          $new_w=$max_w;
                       }
                       $new_h =(int)$new_h;
                       $new_w =(int)$new_w;

                       $dst=ImageCreateTrueColor($new_w, $new_h);
                       ImageCopyResampled ($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $iw, $ih);
                       $thumb = './instagram/collages/'.$_SESSION['data']->user->id.'/'.$_GET['collage'].'/small-'.$_GET['collage'].'.jpg';
                       $thumb_path = '/instagram/collages/'.$_SESSION['data']->user->id.'/'.$_GET['collage'].'/small-'.$_GET['collage'].'.jpg';
                       ImageJPEG ($dst, $thumb, 100);
                       imagedestroy($src);

                       $_SESSION['custom'][]=array($thumb_path,$ih,$iw,$_GET['collage'],'instagram');
                    }

				}else{
					unset($_SESSION['data']);
					header('Location: http://idposter.com/Instagram_Print/');
					die();
				}
			}			
		}
	}
	require 'Instagram-PHP-API/src/Instagram.php';
	require 'Action.class.php';
	
	$instagram = new Instagram(array(
		'apiKey'      => 'c7c8427a87be475d87e4ce54e020917d',
		'apiSecret'   => '28b8b8f6f61a4fdcaac130cb89164aae',
		'apiCallback' => 'http://idposter.com/Instagram_Print/login/' // must point to success.php
	));
	$action = new Action();

    //echo $instagram->getApiCallback();

	$loginUrl = null;
	$loggedIn = false;
	
	if(isset($_SESSION['data']->access_token)){
      	$instagram->setAccessToken($_SESSION['data']->access_token);
		$loggedIn = true;
		$user_dir = $root_path.'collages/'.$_SESSION['data']->user->id.'/';
		if(!is_dir($user_dir)){
			@mkdir($user_dir, 0777);
		}
	}else{
		$loginUrl = $instagram->getLoginUrl();
	}

    $title = 'Print Instagram Photos on Posters, T-Shirts, Puzzles, Mousepads, Magnets, Pillows';

    $tm = 'instagram.php';
?>
<?php
	session_start();
	require_once 'Instagram-PHP-API/src/Instagram.php';

    $instagram = new Instagram(array(
		'apiKey'      => 'c7c8427a87be475d87e4ce54e020917d',
		'apiSecret'   => '28b8b8f6f61a4fdcaac130cb89164aae',
		'apiCallback' => 'http://idposter.com/Instagram_Print/login/' // must point to success.php
    ));
    
	if(isset($_GET['code'])){
    	$data = $instagram->getOAuthToken($_GET['code']);
    	$_SESSION['data'] = $data;
	}
	
	header('Location: http://idposter.com/Instagram_Print/');
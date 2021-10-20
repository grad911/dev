<?php

	class Imagegen{
		
		private $image;
	
		public function __construct(){
			
		}
		
		//WxH
		public function genLinearCollage($count_w, $count_h, $images_w, $images_h, $borders_w, $borders_h, $background_color, $paths){
			//echo 'lol';
			
			$realWidth = ($images_w * $count_w) + ($borders_w * ($count_w + 1));
			$realHeight = ($images_h * $count_h) + ($borders_h * ($count_h + 1));
			$this->image = imagecreatetruecolor($realWidth, $realHeight);
			imagealphablending($this->image, true);
			
			$rgb = $this->hexToRgbColor($background_color);
			$background_color = imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]);
			imagefill($this->image, 0, 0, $background_color);
			
			$x = 0;
			$y = 0;
			foreach($paths as $key => $path){
			//echo $path;
				$im = imagecreatefromjpeg($path);
				imagealphablending($im, false);
				imagesavealpha($im, true);
			
				$pos_x = ($images_w * $x) + ($borders_w * $x) + $borders_w;
				$pos_y = ($images_h * $y) + ($borders_h * $y) + $borders_h;
			
				imagecopy($this->image, $im, $pos_x, $pos_y, 0, 0, imagesx($im), imagesy($im));
			
				$x++;
				if($x == $count_w){
					$x = 0;
					$y++;
				}
				
			}
			
		}
		
		
		
		
		public function hexToRgbColor($hex){
			$hex = str_replace("#", "", $hex);
			if(strlen($hex) == 3) {
				$r = hexdec(substr($hex,0,1).substr($hex,0,1));
				$g = hexdec(substr($hex,1,1).substr($hex,1,1));
				$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
				$r = hexdec(substr($hex,0,2));
				$g = hexdec(substr($hex,2,2));
				$b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);
			return $rgb;
		}
		
		public function __destruct(){
			imagedestroy($this->image);
		}
	
		public function display(){
			header ("Content-type: image/jpeg");
			imagejpeg($this->image);
		}
		
		public function save($path){
			return imagejpeg($this->image, $path);
		}
		
	}
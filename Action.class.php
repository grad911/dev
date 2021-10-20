<?php
session_start();

class Action{
	
	public function getNextPhotos($data){

		if(is_string($data)){
			$data = json_decode($data,true);			
		}
		
		$returned = array(
			'html' => $this->getPhotosHtmlList($data)
		);
		if(is_object($data)){
			if(isset($data->pagination->next_url)){
				$returned['next_url'] = $data->pagination->next_url;
				$_SESSION['next_url'] = $data->pagination->next_url;
			}else{
				unset($_SESSION['next_url']);
			}
		} else if(is_array($data)){
			if(isset($data['pagination']['next_url'])){
				$returned['next_url'] = $data['pagination']['next_url'];
				$_SESSION['next_url'] = $data['pagination']['next_url'];
			}else{
				unset($_SESSION['next_url']);
			}			
		}
		
		return $returned;
	}
	
	public function getPhotosHtmlList($data){
		$html = '';
		
		if(is_object($data)){
			foreach($data->data as $media){
				$html .= '<span id="'.$media->id.'" class="image_block">';
					$html .= '<input id="select_'.$media->id.'" class="image_select" type="checkbox" name="selected[]" value="'.$media->images->standard_resolution->url.'" />';
					$html .= '<img src="'.$media->images->thumbnail->url.'" class="img-thumbnail" onClick="setImage(\''.$media->id.'\', \''.$media->images->standard_resolution->url.'\');"/>';
				$html .= '</span>';
			}
		}
		
		if(is_array($data)){
			foreach($data['data'] as $key => $media){
				$html .= '<span id="'.$media['id'].'" class="image_block">';
					$html .= '<input id="select_'.$media['id'].'" class="image_select" type="checkbox" name="selected[]" value="'.$media['images']['standard_resolution']['url'].'" />';
					$html .= '<img src="'.$media['images']['thumbnail']['url'].'" class="img-thumbnail" onClick="setImage(\''.$media['id'].'\', \''.$media['images']['standard_resolution']['url'].'\');"/>';
				$html .= '</span>';
			}
		}
		
		//echo $html;
		
		return $html;
	}
	
	public function getOrigsPhotosHtmlList($data, $collage_files_path){
		$html = '';
		
		if(is_array($data)){
			foreach($data as $key => $filename){
				$html .= '<span id="orig_'.$key.'" class="image_block">';
					$html .= '<a href="'.$collage_files_path.$filename.'" target="_blank"><img src="'.$collage_files_path.$filename.'" class="img-thumbnail" /></a>';
				$html .= '</span>';
			}
		}

		return $html;
	}
	
}
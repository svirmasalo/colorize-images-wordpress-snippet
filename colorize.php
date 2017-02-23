<?php
	/**
	* Version 1.0.0
	* Author Sampo Virmasalo
	* Released 23 Feb 2017
	*/  
	function colorizeImage($image){
		/**
		* Location variables
		*/
		$domain = 'YOUR DOMAIN';
		$localPath  = $_SERVER['DOCUMENT_ROOT'];
		$fileNameExtension = '_colorized.';
		$colorScheme = [255,0,0,1];
		/**
		* function expects $url to be either a plain url or 
		* wordpress image object.
		*/ 
		$isArray = is_array($image);
		if($isArray){
			$originalUrl = $image["url"];
		}else{
			$originalUrl = $image;
		}
		/**
		* Transfer url to local path
		*/
		$originalPath = str_replace($domain, $localPath, $originalUrl);
		$newPath = str_replace('.',$fileNameExtension,$originalPath );
		$newDomain = str_replace($localPath, $domain, $newPath);

		/**
		* If the file already exists, I'm not needed
		*/ 
		if(file_exists($newPath)){
			return $newDomain;
		}

		/**
		* Check filetype
		*/ 
		$fileInfo = new finfo(FILEINFO_MIME);
		$mimeType = $fileInfo->buffer(file_get_contents($originalPath));
		$mimeType = explode(';', $mimeType);

		switch ($mimeType[0]) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($originalPath);
				break;
			case 'image/png':
				$image = imagecreatefrompng($originalPath);
				break;
			default:
				echo 'Image has to be either jpg or png.';
				break;
				die;
		}
		/**
		* Turn into grayscale
		*/
		if($image && imagefilter($image, IMG_FILTER_GRAYSCALE)){
			$newImage = str_replace('.', $fileNameExtension, $originalPath);
			if($mimeType[0] == 'image/jpeg'){
				imagejpeg($image, $newPath);
			}else{
				imagepng($image,$newPath);
			}
		}else{
			exit('Can`t convert image to grayscale');
		}
		/**
		* Colorize image
		*/
		if($image && imagefilter($image, IMG_FILTER_COLORIZE,$colorScheme[0],$colorScheme[1],$colorScheme[2],$colorScheme[3])){
			if($mimeType[0] == 'image/jpeg'){
				imagejpeg($image, $newPath);
			}else{
				imagepng($image,$newPath);
			}	
		}else{
			exit('Can`t colorize image');
		}
		imagedestroy($image);
		
		return $newDomain;

	}
?>
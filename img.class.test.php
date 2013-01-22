<?php
class img {


	function img($source_image,$user_id=''){
		$this->source_image = $source_image;
		
			// List of our known photo types
		$this->known_photo_types = array( 
					'image/pjpeg' => 'jpg',
					'image/jpeg' => 'jpg',
					'image/gif' => 'gif',
					'image/x-png' => 'png'
		);
		
		$this->types = array(
			1 => 'gif',
			2 => 'jpg',
			3 => 'png',
			4 => 'SWF',
			5 => 'PSD',
			6 => 'BMP',
			7 => 'TIFF(intel byte order)',
			8 => 'TIFF(motorola byte order)',
			9 => 'JPC',
			10 => 'JP2',
			11 => 'JPX',
			12 => 'JB2',
			13 => 'SWC',
			14 => 'IFF',
			15 => 'WBMP',
			16 => 'XBM'
    	);
		
		
		list($this->width_orig, $this->height_orig,$this->img_type_index) = getimagesize($this->source_image);
		$this->square_max_size = SQUARE_MAX_SIZE;
		
		if($this->width_orig > $this->height_orig){
			$this->iswide = 1;
		}else{
			$this->iswide = 0;
		}
		
		//$this->imgfiletype = $this->source_image['type'];
		$this->imgfiletype = $this->types[$this->img_type_index];
		
		switch ($this->imgfiletype) {
			case 'jpg': $this->function_to_read = "ImageCreateFromJPEG"; $this->function_to_write = "imagejpeg"; break;
			case 'png': $this->function_to_read = "ImageCreateFromPNG"; $this->function_to_write = "imagepng"; break;
			case 'gif': $this->function_to_read = "ImageCreateFromGIF"; $this->function_to_write = "imagegif"; break;
			//default:     imagepng($img);  break;
		}
		
		
		$this->extention =$this->imgfiletype;
		if(!empty($user_id)){
			$this->basefilename = $user_id.".".gen_rand_string(time());
			$this->filename = $this->basefilename.".".$this->extention;
			$this->largefilename = $this->basefilename."_l.".$this->extention;
			$this->medfilename = $this->basefilename."_m.".$this->extention;
			$this->smfilename = $this->basefilename."_s.".$this->extention;
			$this->squarefilename = $this->basefilename."_sq.".$this->extention;
			$this->type = $this->types[$this->img_type_index];
		}
		$orig_name = $this->source_image['name'];
		echo "<h3>is wide:".$this->iswide."</h3><br>".$this->squarefilename."<br>\n";
		
	}
/*------------------square image -----------------------*/
	function get_orig_width(){
		return $this->width_orig;
	}
	function get_orig_height(){
		return $this->height_orig;
	}
	function is_img_wide(){
		return $this->iswide;
	}
	function get_square_filename(){
		return $this->squarefilename;
	}
	function get_img_storage_size(){
		return sprintf("%uk ", filesize($this->source_image)/1024);
	}
	function get_file_type(){
		return $this->imgfiletype;
	}
	function square_img($square_max_size){
		$this->square_max_size = $square_max_size;
				//if its wide and its height is at least as tall as the square
				if($iswide == 1 && $height_orig >= $this->square_max_size){
					$bigger_than_sq = 1;
				//if its tall and its width is as wide as the square
				}elseif($iswide == 0 && $width_orig >= $this->square_max_size){
					$bigger_than_sq = 1;
				}else {
					$bigger_than_sq = 0;
				}
				
				if($bigger_than_sq == 1){
					$thumb = new Thumbnail;
					$thumb->quality = 100;
					$thumb->fileName = $image_location;
					$thumb->init();
					$thumb->percent = 0;
					if($this->iswide){
						//is wide so make as tall as $this->square_max_size
						$thumb->maxHeight = $this->square_max_size;
					}else{
						$thumb->maxWidth = $this->square_max_size;
					}
					//$thumb->maxHeight = $this->square_max_size;
					//$thumb->resize();
					if(function_exists("ImageCreateTrueColor")) {
						$this->workingImage = ImageCreateTrueColor($this->cropSize,$this->cropSize);
					}else {
						$this->workingImage = ImageCreate($this->cropSize,$this->cropSize);
					}
					if(function_exists("imagecopyresampled")){
					  imagecopyresampled($new_dest_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}else{
					  imagecopyresized  ($new_dest_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}
					
					
					$this->cropSize = $this->square_max_size;
					$this->cropX = ($thumb->getCurrentWidth()/2)-($this->cropSize/2);
					$this->cropY = ($thumb->getCurrentHeight()/2)-($this->cropSize/2);
					
					imagecopy(
						$this->workingImage, //Destination image link resource
						$this->source_image,     //Source image link resource
						0,
						0,
						$this->cropX,
						$this->cropY,
						$this->cropSize,
						$this->cropSize
					);
					
	
					$thumb->save($images_dir."/".$squarefilename);
					//$thumb->destruct();
					
				}elseif($bigger_than_sq == 0){ //the image is narrower than square size
					
					//$bg_image  = imagecreate($this->square_max_size, $this->square_max_size);
					$bg_image  = imagecreatetruecolor($this->square_max_size, $this->square_max_size);
					$bgcolor     = imagecolorallocate($bg_image, 255, 255, 255);					
					
					$sq_dest_x = $this->square_max_size/2 - $width_orig/2;
					$sq_dest_y = $this->square_max_size/2 - $height_orig/2;
					imagecopy($bg_image,$source_handle,$sq_dest_x,$sq_dest_y,0,0,$width_orig,$height_orig);
					imagefill($bg_image, 0, 0, $bgcolor);
					
					// Ok kids it time to draw a border
					// save that thing and we outta here
					$cColor=imagecolorallocate($bg_image,233,233,233);
					
						// first coodinates are in the first half of image
						$x0coordonate = 0;
						$y0coordonate = 0;
						// second coodinates are in the second half of image
						$x1coordonate = 0;
						$y1coordonate = $this->square_max_size;
						imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
						
						$x0coordonate = 0;
						$y0coordonate = $this->square_max_size-1;
						// second coodinates are in the second half of image
						$x1coordonate = $this->square_max_size-1;
						$y1coordonate = $this->square_max_size-1;
						imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
					
						$x0coordonate = $this->square_max_size-1;
						$y0coordonate = $this->square_max_size-1;
						// second coodinates are in the second half of image
						$x1coordonate = $this->square_max_size-1;
						$y1coordonate = 0;
						imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );
						
						$x0coordonate = $this->square_max_size-1;
						$y0coordonate = 0;
						// second coodinates are in the second half of image
						$x1coordonate = 0;
						$y1coordonate = 0;
						imageline ($bg_image, $x0coordonate, $y0coordonate, $x1coordonate, $y1coordonate,$cColor );	
					
					$sqfile_loc = $images_dir."/".$squarefilename;
					/*switch ($extention) {
						case 'jpg': imagejpeg($bg_image,$sqfile_loc); break;
						case 'png':  imagepng($bg_image,$sqfile_loc);  break;
						case 'gif':  imagegif($bg_image,$sqfile_loc);  break;
						//default:     imagepng($img);  break;
					}*/
					imagedestroy($bg_image);
				}
	} // end of square_img()*/
	
	##########################################
	#----- Image Display/Save Functions -----#
	##########################################
	function save($name) {
		$this->show($name);
	}
	
	function show($name='') {
		
		switch($this->imgfiletype) {
			case "gif":
				if($name != '') {
					ImageGif($this->newImage,$name);
				}
				else {
					header("Content-type: image/gif");
					ImageGif($this->newImage);
				}
				break;
			case "jpg":
				if($name != '') {
					ImageJpeg($this->newImage,$name,$this->quality);
				}
				else {
					header("Content-type: image/jpeg");
					ImageJpeg($this->newImage,'',$this->quality);
				}
				break;
			case "png":
				if($name != '') {
					ImagePng($this->newImage,$name);
				}
				else {
					header("Content-type: image/png");
					ImagePng($this->newImage,$name);
				}
				break;
		}
	}
		
}
				
?>
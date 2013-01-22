<?php
include('../includes/config.php'); 
session_start(); 
if(!isset($HTTP_SESSION_VARS['user_id'])) {
	echo "Login";
	exit;
}

function upload_photo() {
// initialization
	$result_final = "";
	$counter = 0;
	global $dbname, $dbhost, $dbuser, $dbpasswd, $lang, $upload_error;
	$images_dir = MEMBER_IMG_DIR_PATH;
	// List of our known photo types
	$known_photo_types = array( 
						'image/pjpeg' => 'jpg',
						'image/jpeg' => 'jpg',
						'image/gif' => 'gif',
						//'image/bmp' => 'bmp',
						//'image/x-png' => 'png'
					);
	
	// GD Function List
	$gd_function_suffix = array( 
						'image/pjpeg' => 'JPEG',
						'image/jpeg' => 'JPEG',
						'image/gif' => 'GIF',
						//'image/bmp' => 'WBMP',
						//'image/x-png' => 'PNG'
					);

	// Fetch the photo array sent by the form
	$photos_uploaded = $_FILES['photo_filename'];
	// Fetch the photo caption array
	$photo_caption = $_POST['photo_caption'];
//echo count($photos_uploaded);
	while( $counter <= count($photos_uploaded) ) {
		if($photos_uploaded['size'][$counter] > 0) {
		//echo $photos_uploaded['type'][$counter] . "<br>";
			if(!array_key_exists($photos_uploaded['type'][$counter], $known_photo_types)){
			// we will return an array where the first item $result_final[0] will be the status of the operation
			$result_final .= $lang['file']." ".($counter+1). " ".$lang['is_not_a_photo'].".  <a href=\"upload_iframe.php\">".$lang['try_again']."</a>";
			$result[$counter] = array(0,$result_final);
				
			}
			else {
				
				//---------------db save -----------------------------
				$filetype = $photos_uploaded['type'][$counter];
				$extention = $known_photo_types[$filetype];
				$filename = $_SESSION['user_id'].".".time().".".$extention;
				$orig_name = $photos_uploaded['name'][$counter];
				
				mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Could not connect: " . mysql_error());
				mysql_select_db($dbname);
				//check if profile pic exists allready
				$sql = "SELECT * from " . USERS_GALLERY_TABLE
				. " WHERE photo_uid =".$_SESSION['user_id']." and use_in_profile=1";
				//if it does delete it - we will replace it with the current one.
				$profile_pic_result = mysql_query($sql);
				$profile_pic_rows = mysql_num_rows($profile_pic_result);
				if($profile_pic_rows){
					for($i=0;$i<$profile_pic_rows;$i++){
						$profile_pic_row = mysql_fetch_assoc($profile_pic_result);
						unlink($images_dir."/".$profile_pic_row['photo_filename']);
						unlink($images_dir."/tb_".$profile_pic_row['photo_filename']);
						$sql = "DELETE from " . USERS_GALLERY_TABLE . " where photo_id=".$profile_pic_row['photo_id'];
						mysql_query($sql);
					}
				}
				
				$sql = "INSERT INTO " . USERS_GALLERY_TABLE 
				." (photo_uid, photo_filename, photo_caption, photo_category, orig_filename,use_in_profile)"
				." VALUES('".$_SESSION['user_id']."', '".addslashes($filename)."', '"
				.addslashes($photo_caption[$counter])."', 0, '".$orig_name."',1)" ;
				
				mysql_query($sql);
				
				$new_id = mysql_insert_id();
				
				
				//mysql_query( "UPDATE " . USERS_GALLERY_TABLE . " SET photo_filename='".addslashes($filename)."' WHERE photo_id='".addslashes($new_id)."'" );
				//---------------db save end -----------------------------
				
				// Store the orignal file with predefined maximun dimensions
				$width = IMG_MAX_WIDTH;
				$height = IMG_MAX_HEIGHT;
				
				list($width_orig, $height_orig) = getimagesize($photos_uploaded["tmp_name"][$counter]);
				
				// is it bigger than our set max width and max height?
				// else keep the same dimention
				if($width_orig > $width || $height_orig > $height) {
					if ($width && ($width_orig < $height_orig)) {
					  $width = ($height / $height_orig) * $width_orig;
					} else {
					  $height = ($width / $width_orig) * $height_orig;
					}
				} else {
					$width = $width_orig;
					$height = $height_orig;
				}
				//echo "w:".$width."<br>h:".$height."<br>";
				// Build Thumbnail with GD 1.x.x, you can use the other described methods too
				$function_suffix = $gd_function_suffix[$filetype];
				$function_to_read = "ImageCreateFrom".$function_suffix;
				$function_to_write = "Image".$function_suffix;
				$image_location = $images_dir."/".$filename;
				$thumb_name = "tb_".$filename;
				$thumb_img_location = $images_dir."/".$thumb_name;
				
				// Read and write for owner, read for everybody else
				//chmod("/somedir/somefile", 0644);
				
				// Read the source file
				$source_handle = $function_to_read ($photos_uploaded['tmp_name'][$counter]); 
				
				//copy($photos_uploaded['tmp_name'][$counter], $image_location);
				
				if($source_handle)
				{
					// Let's create an blank image for the thumbnail
					if(GD_VERSION >= 2 && $filetype != 'image/gif') {
						$destination_handle = imagecreatetruecolor($width,$height);
					}else{
				     	$destination_handle = imagecreate($width,$height);
					}
				
					// Now we resize it
					if(GD_VERSION >= 2) {
						//echo 'using imagecopyresampled<br>';
					  imagecopyresampled($destination_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}else{
						//echo 'old imagecopyresized<br>';
			      	  imagecopyresized($destination_handle,$source_handle,0,0,0,0,$width,$height,$width_orig,$height_orig);
					}
				}
				// Let's save the thumbnail
				$function_to_write( $destination_handle, $image_location);
				ImageDestroy($destination_handle );
				chmod($image_location, 0666);
				
				
				//-----------------------thumbnail-------------------------
				// Get new dimensions
				//ist($width_orig, $height_orig) = getimagesize($photos_uploaded["tmp_name"][$counter]);
				$thumbnail_width = THUMB_MAX_WIDTH;
				$thumbnail_height = THUMB_MAX_HEIGHT;	
							
				
				if ($thumbnail_width && ($width_orig < $height_orig)) {
				  $thumbnail_width = ($thumbnail_height / $height_orig) * $width_orig;
				} else {
				  $thumbnail_height = ($thumbnail_width / $width_orig) * $height_orig;
				}

				
				
				if($source_handle)
				{
					// Let's create an blank image for the thumbnail
					if(GD_VERSION >= 2 && $filetype != 'image/gif') {
						$destination_handle = imagecreatetruecolor($thumbnail_width,$thumbnail_height);
					}else{
				     	$destination_handle = imagecreate($thumbnail_width,$thumbnail_height);
					}
				
					// Now we resize it
					if(GD_VERSION >= 2) {
						//echo 'using imagecopyresampled<br>';
					  imagecopyresampled($destination_handle,$source_handle,0,0,0,0,$thumbnail_width,$thumbnail_height,$width_orig,$height_orig);
					}else{
						//echo 'old imagecopyresized<br>';
			      	  imagecopyresized($destination_handle,$source_handle,0,0,0,0,$thumbnail_width,$thumbnail_height,$width_orig,$height_orig);
					}
				}
				// Let's save the thumbnail
				$function_to_write( $destination_handle, $thumb_img_location );
				ImageDestroy($destination_handle );	
				chmod($thumb_img_location, 0666);			
				
				
				$result_final .= "<img src='". MEMBER_IMG_DIR_URL ."/".$thumb_name."' width=".$thumbnail_width." />".$lang['image']." ".($counter+1)." ".$lang['added_fem']."<br />";
				$result[$counter] = array(1,$result_final);
			}
		}
	$counter++;
	}
	return $result;
}// end of upload photo

//is it a valid image resource?
function is_gd_handle($var) {
   ob_start();
       imagecolorallocate($var, 255, 255, 255);
       $error = ob_get_contents();
   ob_end_clean();
   if(preg_match('/not a valid Image resource/',$error)) {
       return false;
   } else {
       return true;
   }
}

$mode = ( isset($_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
if ($mode == 'upload'){
		$result = upload_photo();
}

$ftmp = $_FILES['image']['tmp_name'];
$oname = $_FILES['image']['name'];
$fname = 'upload/'.$_FILES['image']['name'];
/*if(move_uploaded_file($ftmp, $fname)){*/
if($result){
		foreach($result as $key => $resultrow) {
			if($resultrow[0] > 0) { //success
	?>
	<html><head><script>
	var par = window.parent.document;
	var images = par.getElementById('images');
	//var imgdiv = images.getElementsByTagName('div')[<?=(int)$_POST['imgnum']?>];
	var imgdiv = images.getElementsByTagName('div')[0];
	var image = imgdiv.getElementsByTagName('img')[0];
	imgdiv.removeChild(image);
	var image_new = par.createElement('img');
	//image_new.src = 'resize.php?pic=<?=$oname?>';
	image_new.src = '<?=get_profile_photo($_SESSION['user_id'])?>';
	image_new.className = 'loaded';
	imgdiv.appendChild(image_new);
	
	//hide the field
	parent.Element.show('change_link');
	parent.Element.hide('photo_fields');
	</script></head>
	</html>
	<?php
	//exit();
}}}
?><html><head>
<script language="javascript">
function upload(){
	// hide old iframe
/*	var par = window.parent.document;
	var num = par.getElementsByTagName('iframe').length - 1;
	var iframe = par.getElementsByTagName('iframe')[num];
	iframe.className = 'hidden';
	
	// create new iframe
	var new_iframe = par.createElement('iframe');
	new_iframe.src = 'upload.php';
	new_iframe.frameBorder = '0';
	par.getElementById('iframe').appendChild(new_iframe);
	
	// add image progress
	var images = par.getElementById('images');
	var new_div = par.createElement('div');
	var new_img = par.createElement('img');
	new_img.src = 'indicator.gif';
	new_img.className = 'load';
	new_div.appendChild(new_img);
	images.appendChild(new_div);*/
	
	// send
	//var imgnum = images.getElementsByTagName('div').length - 1;
	//document.iform.imgnum.value = imgnum;
	document.iform.submit();
}
</script>
<? //<link rel="stylesheet" href="../style_std.css" type="text/css"> ?>
<style>
body{
margin:0 0;
padding:0 0;
background-color:#FFFFFF;
}
form{
	padding:0;
	margin:0;
}
#file {
	width: 300px;
	margin:0px;
	padding:0px;
}

.simulatedLink {
	color:#0000FF;
	text-decoration:underline;
	font-family: Tahoma;
}
</style>
<head><body>
<?
	if($result){
		foreach($result as $key => $resultrow) {
			if($resultrow[0] > 0) { //success
				//echo $resultrow[1];
				$upload_error = 0;
			}else { 
				echo "<p class=\"error\">".$resultrow[1]."</p>"; //failure
				$upload_error = 1;
				}
		}
	}
	
	if($upload_error != 1){
?>
<form name="iform" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="imgnum" />
<input type="hidden" name="mode" value="upload">
<input id="file" type="file" name="photo_filename[]" onChange="upload()" /> 
<a class="simulatedLink" id="change_link" href="#" onClick="parent.Element.show('change_link');parent.Element.hide('photo_fields');"><?=$lang['cancel']?></a>
	
</form>
<?
	}
?>
</html>
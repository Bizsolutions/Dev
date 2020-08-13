<?php
// File and new size
$filename = 'mmr_images/logos/logo_18996.jpg';
$percent = 0.25;

// Content type
header('Content-Type: image/jpeg');

// Get new sizes
list($width, $height) = getimagesize($filename);
$newwidth = $width * $percent;
$newheight = $height * $percent;

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($filename);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagejpeg($thumb,"logo_189961.jpg");
/*echo $source;
copy($thumb,'/mmr_images/logos/');*/
$org_image="logo_189961.jpg";
$destination="/mmr_images/logos/";

$img_name=basename($org_image);

if( rename( $org_image , $destination.'/'.$img_name )){
 echo 'moved!';
} else {
 echo 'failed';
}
?>
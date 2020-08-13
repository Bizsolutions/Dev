<?php
/*require_once('core/database.class.php');*/
// File and new size
/*$filename = 'mmr_images/logos/';*/
$percent = 0.25;
/*$sql="select id from companies limit 0,1000";
$query=mysql_query($sql);
while($res=mysql_fetch_assoc($query)){*/
for($i=15001;$i<=25000;$i++){
 $filename="mmr_images/logos/logo_$i.jpg";
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
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

$outputname="logo_$i.jpg";
// Output
imagejpeg($thumb,$outputname);
/*echo $source;
copy($thumb,'/mmr_images/logos/');*/
$org_image=$outputname;
$destination="mmr_images/smalllogos";

$img_name=basename($org_image);

if( rename( $org_image , $destination.'/'.$org_image )){
 echo 'moved!';
} else {
 echo 'failed';
}}
?>
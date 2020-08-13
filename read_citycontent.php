<?php
require 'core/database.class.php';

$n=1;
while($myFile = fopen('http://www.topmovingreviews.com/city_content/City-state-moving-companies-reviews-looking-for-the_V'.$n.'.txt','r'))

{
$id=++$n;
$id=-1;
while (!feof ($myFile)){
$my = fgets($myFile);
$id=++$id;
$rem=$id%4;
if($rem==2){
$my=mysql_real_escape_string($my);
 $result = mysql_query("Insert into city_content (content) values('$my')");
 

/*if (!$result) {
    die("Could not load. " . mysql_error());
}
else {"Data Inserted successfully";}*/

}}}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>

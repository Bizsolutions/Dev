<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>

<?php
require 'core/database.class.php';
$i=0;
$sql="SELECT *  FROM  companies where logo is null limit 0,1000";
$query=mysql_query($sql);
while($res=mysql_fetch_array($query))
{

 $id=$res['id'];
$file = 'mmr_images/logos/logo_no.jpg';
 $newfile = "mmr_images/logos/logo_$id.jpg";

if (copy($file, $newfile)) {
$update=mysql_query("update  companies  set logo ='https://www.topmovingreviews.com/mmr_images/logos/logo_$id.jpg' where id='$id'");
$i++;
}
else
{
    echo "failed to copy";
}
}
echo $i;
?>
</body>
</html>

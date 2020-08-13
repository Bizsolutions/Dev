<?php	 	 	 	
session_start();

require_once('../core/database.class.php');
$to ="bishara7@gmail.com";

//$to = "form@topmovingreviews.com";
//$to = "alokronline@gmail.com";
//$to = "leads@prosmovers.com";
//include("autoreply.php");


//$to = "bishara7@gmail.com";
$Name = $_POST['name'];
$Phnumber = $_POST['phnumber'];
$Email = $_POST['txtEmail'] ;
$FromZip =$_POST['from_zip'];
$ToZip =$_POST['to_zip'] ;
$MoveDate =$_POST['calendarHere'];
$MoveSize =$_POST['moving_size'];
$Comment =$_POST['txtComments'];


if(substr($FromZip,0,1)==0){ $FromZip=substr($FromZip, 1);}

$sql=mysql_query("SELECT city,state FROM zipcodes where zipcode = '$FromZip'"); 
$info=mysql_fetch_array($sql);
 if(strlen($FromZip)==4){ $FromZip="0".$FromZip;} else {$FromZip=$FromZip;}
$FromCity=$info['city'];
$FromState=$info['state'];
if(strlen($ToState)==2){
$sql2=mysql_query("SELECT city,county,state,zipcode FROM zipcodes where zipcode = '$ToZip'"); 
$info2=mysql_fetch_array($sql2);
}
else {
if(substr($ToState,0,1)==0){ $ToState=substr($ToState, 1);}
$sql2=mysql_query("SELECT city,county,state,zipcode FROM zipcodes where zipcode = '$ToZip'"); 
$info2=mysql_fetch_array($sql2);
}

 $ToCity=$info2['city'];
$ToState=$info2['state'];
//$ToZip=$info2['zipcode'];
 if(strlen($ToZip)==4){ $ToZip="0".$ToZip;} else {$ToZip=$ToZip;}

//"$dis ".$FromCity.", ".$FromState." to ".$ToCity.", ".$ToState." from www.jersey-citymovers.com";There is a '.$dis.' Car Transport <br/>  Make   '.$make1.'<br/>  Model  '.$model1.'<br/>  Year   '.$year1.'<br/>   Run. Cond. '.$cond1.'<br/>
if($ToState==$FromState){$dis="Local Distance Moving Lead from" ;} else {$dis="Long Distance Moving Lead from" ;}
$subject = "Lead from topmovingreviews.com";

$message = '<p> www.topmovingreviews.com</p>
<p> Name = '.$Name.'<br/>
  Email = '.$Email.'<br />
  Contact No. (Home) = '.$Phnumber.'<br/>
  From_City = '.$FromCity.'<br />
  From_State = '.$FromState.'<br />
  From_Zip = '.$FromZip.'<br />
  To_City = '.$ToCity.' <br/>
  To_State = '.$ToState.' <br />
  To_Zip = '.$ToZip.'<br/>
  Move Date = '.$MoveDate.'<br/>
  Type = '.$MoveSize.'<br/>
 
  Comments =  '.$Comment.'
</p>
';
 
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
// More headers
$headers .= "From: form@topmovingreviews.com" .  "\r\n";

$sent = mail($to, $subject, $message, $headers,'-freturn@topmovingreviews.com') ;
//autoreply($Email,$Fname,$Lname,$to);

if($sent)
{
//include("granot.php");
echo '<script>alert("Thank you for contacting Top Moving Reviews!!!")</script>';

echo '<meta http-equiv="refresh" content="0; url=https://www.topmovingreviews.com/" />';

//Header("Location: thanks.html");
}
else
{print "We encountered an error sending your mail"; }
?>
<?php session_start();

require_once('core/database.class.php');

	 	 	 	
if(isset($_POST['Submit'])){
// code for check server side validation
if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){  
$msg="<span style='color:red'>Wrong Captcha</span>";// Captcha verification is incorrect.
}else{// Captcha verification is Correct. Final Code Execute here!
$msg="<span style='color:green'>The Validation code has been matched.</span>";

$Name = $_POST['name'];
$Phnumber = $_POST['phone'];
$Email = $_POST['email']; 
$FromZip =$_POST['from_zip'];
$ToZip =$_POST['to_zip'] ;
$MoveDate =$_POST['calendarHere'];
$MoveSize =$_POST['moving_size'];
$Comment =$_POST['txtComments'];
$company_email=$_REQUEST['company_email'];
$business_name=$_REQUEST['business_name'];

/*$to ="$Email , $company_email , bishara7@gmail.com";
*/
$to ="richiebash@yandex.com";


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
<p> Business Name = '.$business_name.'<br/>
Name = '.$Name.'<br/>
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


$sql="INSERT INTO `contact_this_mover` (`id`, `company_id`, `moving_size`, `moving_date`, `from_zip`, `to_zip`, `full_name`, `phone`, `email`, `message`) VALUES ('', '$_REQUEST[company_id]', '$MoveSize', '$MoveDate', '$FromZip', '$ToZip', '$Name', '$Phnumber', '$Email', '$Comment')";
mysql_query($sql);

//include("granot.php");
echo '<meta http-equiv="refresh" content="0; url=https://www.topmovingreviews.com/movercontact.php?msg1=1&comp_name='.$business_name.'" />';
}
else
{print "We encountered an error sending your mail"; }

}

}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Top Moving Reviews</title>
<link rel=stylesheet type="text/css" href="https://www.topmovingreviews.com/css/style.css">
 <link rel=stylesheet type="text/css" href="https://www.topmovingreviews.com/css/dhtmlxcalendar.css"/>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js"></script>
<script language="javascript">var today=new Date();var dd=today.getDate();var mm=today.getMonth()+1;var yyyy=today.getFullYear();if(dd<10)
{dd='0'+dd;}
if(mm<10)
{mm='0'+mm;}
today=mm+'-'+dd+'-'+yyyy;var myCalendar;function doOnLoad(){myCalendar=new dhtmlXCalendarObject("calendarHere");myCalendar.hideTime();myCalendar.setDate(getdate());}
function setFrom(){myCalendar.setSensitiveRange(today,null);}




function validate()
{
 
		var stringEmail = document.getElementById('email').value;
      if ( document.getElementById('moving_size').value == '' )
        {
                alert('Please Select Moving Size!');
				document.getElementById('moving_size').focus();
                return false;				
        }
			else if ( document.getElementById('calendarHere').value == '' )
        {
        
                alert('Please Enter Moving Date!');
				document.getElementById('calendarHere').focus();
                return false;				
        }
		  else if ( document.getElementById('from_zip').value == '' )
        {
                alert('Please Enter Valid Zip Code Where  you are Moving from!');
				document.getElementById('from_zip').focus();
                return false;				
        }
	  else if ( document.getElementById('to_zip').value == '' )
        {
                alert('Please Enter Valid Zip Code Where  you are Moving to!');
				document.getElementById('to_zip').focus();
                return false;	
		}
							
          else if ( document.getElementById('name').value == '' )
        {
                alert('Please Enter Your Full Name!');
				document.getElementById('name').focus();
                return false;	
		}
		    else if ( document.getElementById('phone').value == '' )
        {
                alert('Please Enter Your Phone Number!');
				document.getElementById('phone').focus();
                return false;	
		}
		     else if ( document.getElementById('email').value == '' )
        {
                alert('Enter your Email!');
				document.getElementById('email').focus();
                return false;				
        }
       
        
		 else  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(stringEmail)))
        {
            
            alert('Enter Valid Email Id'); 
			document.getElementById('email').focus();           
            return false;
        } 
		
		else
		{
		document.getElementById("frm1").submit();	
		return true;
		}
}



function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}


</script>








<style>
.inputbox{
border: 1px solid #ccc;
border-radius: 4px;
width:150px;
height:25px;
}
.selectbox
{
border: 1px solid #ccc;
border-radius: 4px;
width:150px;
height:27px;
}
.textareabox{border: 1px solid #ccc;
border-radius: 4px;
width:200px;
height:150px;
}

.button{
background:#E79C1C;
border: 1px solid #ccc;
border-radius: 6px;
height:30px; 
width:200px;
color:#FFFFFF;
font-size:14px;
font-weight:bold;
}
.fontbold
{ font-weight:bold;
}
  
  .column {
    float: left;
    width: 50%;
	text-align:center;
}
  
/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
</style>

</head>

<body onLoad="doOnLoad();">

<?php if($_REQUEST['msg1']==1){



echo "<br><span style=\"color:#3f4773; text-align:justify;\">";



echo "<h3 align=\"center\" >Thank you for contacting $_REQUEST[comp_name].</h3>";

echo "<h3 align=\"center\" >Based on the information that you have submitted, three professional moving companies have received your contact information and should be contacting you soon to provide quotes and estimates for the service that you have inquired about. </h3>";

echo "<p align=\"center\" style=\"cursore:pointer; color:red;\" ><a  onclick='javascript:window.close();'>Close  </a></p>";

echo "</span>";

} else {

?>


<h3 align="center">Message this moving company</h3><br />
<div class="row">
  <?php
          $mmrimg = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$_REQUEST['company_id'].".jpg";
          $compimg = "https://www.topmovingreviews.com/company/logos/logo_".$_REQUEST['company_id'].".jpg";
            if(@getimagesize($mmrimg) != '')
              {
                  $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$_REQUEST['company_id'].".jpg";
              }
              else if(@getimagesize($compimg) != '')
              {
                  $logo_image = "https://www.topmovingreviews.com/company/logos/logo_".$_REQUEST['company_id'].".jpg";
              }else if(stristr($_REQUEST["image"], "mymovingreviews.com")){
                  $logo_image = $_REQUEST["image"];
              }
              else{
                  $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
              }
              
  ?>
  <div class="column"><img src="<?php echo $logo_image;?>" style="box-shadow: 0 0 3px 3px #ccc;border-radius: 3px;"  /></div>
  <div class="column" style="font-size:16px; font-weight:bold;"><?php echo $_REQUEST['title'];?><br /><span style="font-size:14px; font-weight:300;">Overall rating:
 
 
  <p class=stars>
<span class="fa fa-star  <?php if($_REQUEST['rating']>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if($_REQUEST['rating']>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if($_REQUEST['rating']>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if($_REQUEST['rating']>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if($_REQUEST['rating']>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

</p></span>
</div>
</div>
    
        
      </div><br />
	  <form  id="frm1" name="frm1" method="post" action="" autocomplete="off" onsubmit="return validate();">
<table width="607" border="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" style="color:#E79C1C ; font-weight:bold; font-size:16px;">Contact <?php echo $_REQUEST['title'];?> :</td>
  </tr>
  <tr>
    <td width="129">&nbsp;</td>
    <td width="190">&nbsp;</td>
	
    <td width="282"></td>
  </tr>
  <tr>
    <td height="33" class="fontbold"><div align="right">Moving Size:</div></td>
    <td >
      <select name="moving_size" id="moving_size" class="selectbox">
	  
	 
											<option value="">Select Size</option>
											<option value="4+ Bedroom Home">4+ Bedroom Home</option>
											<option value="3 Bedroom Home">3 Bedroom Home</option>
											<option value="2 Bedroom Home">2 Bedroom Home</option>
											<option value="1 Bedroom Home">1 Bedroom Home</option>
											<option value="Studio">Studio</option>
											<option value="Partial Move">Partial Move</option>
											<option value="Commercial">Commercial</option>
      </select>    </td>
    <td class="fontbold" style="padding-left:10px;" valign="top">Your Message:</td>
  </tr>
  <tr>
    <td  class="fontbold"><div align="right">Moving Date:</div></td>
    <td  >
      <input type="text" id="calendarHere" class="inputbox" name="calendarHere" onClick="setFrom();" readonly="" />    </td>
    <td rowspan="5" align="left" valign="top">
      <textarea name="txtComments" id="txtComments" class="textareabox"></textarea>    </td>
  </tr>
  <tr>
    <td  class="fontbold"><div align="right">From Zip:</div></td>
    <td ><input name="company_email" id="company_email" type="hidden" value="<?php echo $_REQUEST['email'];?> " />
      <input name="from_zip" id="from_zip" type="text" class="inputbox" />    </td>
  </tr>
<!--  <tr>
    <td class="fontbold"><div align="right">Going To:</div></td>
    <td ><select name="select2" class="selectbox">
    </select></td>
  </tr>-->
  <tr>
    <td class="fontbold"><div align="right">Going To:</div></td>
    <td ><input name="to_zip" id="to_zip" type="text" class="inputbox" /></td>
  </tr>
  <tr>
    <td  class="fontbold"><div align="right">Full Name:</div></td>
    <td ><input name="name" id="name" type="text" class="inputbox" /></td>
  </tr>
  <tr>
    <td  class="fontbold"><div align="right">Phone:</div></td>
    <td ><input name="phone" id="phone" type="text" class="inputbox" /></td>
  </tr><input name="business_name" type="hidden" value="<?php echo $_REQUEST['title'];?>" />
  <tr>
    <td class="fontbold" valign="top"><div align="right">E-Mail:</div></td>
    <td valign="top"><input name="email" id="email" type="text" class="inputbox" />
	 <?php if(isset($msg)){ echo $msg; }?> 	</td>
    <td align="left"><img src="phpcaptcha/captcha.php?rand=<?php echo rand();?>" id='captchaimg'><a href='javascript: refreshCaptcha();'><img src="images/refresh.jpg" /></a><br />
	<label for='message'>Enter the code above here :</label>
	<input id="captcha_code" name="captcha_code" type="text" class="inputbox">
        
       	</td>
  </tr>
  <tr>
    <td class="fontbold">&nbsp;</td>
    <td ><input name="Submit" type="submit" class="button" value="Send Message" /></td>
    <td align="left">         </td>
  </tr>
</table>
</form>
<?php } ?>

</body>
</html>

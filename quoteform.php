<?php session_start();



header('Content-Type: text/html; charset=ISO-8859-1');

require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();

$db_link = $bd->db_connect();



$moving_from_zipcode=$_REQUEST[MovingFrom];

$moving_to_zipcode=explode(",",$_REQUEST[MovingTo]);


if(count($moving_to_zipcode)==2) {

$sql_zip="select ZipCode from zipcodes where City='$moving_to_zipcode[0]' and State='$moving_to_zipcode[1]' limit 0,1";
$res_zip=mysql_fetch_array(mysql_query($sql_zip));

$moving_to_zipcode=$res_zip['ZipCode'];
}

else if(count($moving_to_zipcode)==3) {
$moving_to_zipcode=$moving_to_zipcode[2];
}

else

{

$moving_to_zipcode=$_REQUEST[MovingTo];
}



if(isset($_POST['publish'])){
?>

<?php
// code for check server side validation
if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
{  
    $msg="<span style='color:red'>Wrong Captcha</span>";// Captcha verification is incorrect.
}
else
{





    // Captcha verification is Correct. Final Code Execute here!
$msg="<span style='color:green'>The Validation code has been matched.</span>";
$to ="richiebash@yandex.com";
//$to ="srikantadora86@gmail.com";
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

if(strlen($ToState)==2)
{
$sql2=mysql_query("SELECT city,county,state,zipcode FROM zipcodes where zipcode = '$ToZip'"); 
$info2=mysql_fetch_array($sql2);
}

else 
{
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

/*echo '<script>alert("Thank you for contacting Top Moving Reviews!!!")</script>';*/



echo '<meta http-equiv="refresh" content="0; url=https://www.topmovingreviews.com/quoteform.php?msg1=1" />';



//Header("Location: thanks.html");

}

else

{print "We encountered an error sending your mail"; }



}

} 

?>







<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"

    "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>Get Quote for Top Moving Reviews</title>



<meta name=viewport content="width=device-width, initial-scale=1.0">

<meta http-equiv=Content-Type content="text/html; charset=UTF-8">

<link href=favicon.ico rel=icon type="image/x-icon">

<meta name="description" content="Quote for Top Moving Reviews">

<meta name=keywords content="Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving"><link href="css/fo.css" rel=stylesheet type="text/css" >

<link rel=stylesheet type="text/css" href="css/style.css">





<meta name=dc.language content=US>

<meta name=dc.subject content="NY Movers">

<meta name=DC.identifier content="/meta-tags/dublin/">

<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">

<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>

<meta name=HandheldFriendly content=true>

<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>



	   <!--calendar for submission form dated 12012018-->

	  <link rel=stylesheet type="text/css" href="css/dhtmlxcalendar.css"/>

<script src="js/dhtmlxcalendar.js"></script>

<script language="javascript">
var today=new Date();
var dd=today.getDate();
var mm=today.getMonth()+1;
var yyyy=today.getFullYear();
if(dd<10)
{dd='0'+dd;}

if(mm<10)

{mm='0'+mm;}

today=mm+'-'+dd+'-'+yyyy;
var myCalendar;
function doOnLoad(){
    myCalendar=new dhtmlXCalendarObject("calendarHere");
    myCalendar.hideTime();
    myCalendar.setDate(getdate());}

function setFrom(){
    myCalendar.setSensitiveRange(today,null);
    var date1=Date();
    console.log(date1);
}



function validate()

{
 var stringEmail = document.getElementById('txtEmail').value;
 if ( document.getElementById('name').value == '' )

        {

        

                alert('Enter Your Name!');

				document.getElementById('name').focus();

                return false;				

        }

		

	



         else if ( document.getElementById('phnumber').value.length != 10 || (isNaN(document.getElementById('phnumber').value)) )

        {

                alert('Enter Your Valid Phone Number!');

				document.getElementById('phnumber').focus();

                return false;				

        }

          

         else if ( document.getElementById('txtEmail').value == '' )

        {

                alert('Enter your Email!');

				document.getElementById('txtEmail').focus();

                return false;				

        }

       

        

		 else  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(stringEmail)))

        {

            

            alert('Enter Valid Email Id'); 

			document.getElementById('txtEmail').focus();           

            return false;

        } 

          else if ( document.getElementById('from_zip').value == '' )

        {

                alert('Enter From Zip!');

				document.getElementById('from_zip').focus();

                return false;				

        }

            else if ( document.getElementById('to_zip').value == '' )

        {

                alert('Enter To Zip!');

				document.getElementById('to_zip').focus();

                return false;				

        }

		

		

            else if ( document.getElementById('moving_size').value == '' )

        {

                alert('Enter Move Size');

				document.getElementById('moving_size').focus();

                return false;				

        }

		

		else

		{

		document.getElementById("frm11").submit();

		return true;

		}

}



function refreshCaptcha(){

	var img = document.images['captchaimg'];

	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;

}













</script>

	   	<style>

	

/* new review rating stars */

.rating {

    display: inline-block;

    font-size: 0;

    position: relative;

    vertical-align: middle;

}

.rating-input {

    float: right;

    width: 0px;

    height: 16px;

    padding: 0;

    margin: 0 0 0 -16px;

    opacity: 0;

}

/*.rating:hover .rating-star:hover,

.rating:hover .rating-star:hover ~ .rating-star,

.rating-input.checked ~ .rating-star {

    background: url('../images/write-review-hover.png') no-repeat 0 0;

    cursor: pointer;

}

.rating-star,

.rating:hover .rating-star {

    position: relative;

    float: right;

    display: block;

    width: 38px;

    height: 32px;

    margin: 0;

    background: url('../images/write-review-hover.png') no-repeat 0 -33px;

}*/

/* Colour update on the cool hover rating effect */

.rating:hover .rating-star:hover,

.rating:hover .rating-star:hover ~ .rating-star,

.rating-input.checked ~ .rating-star {

	background: url(https://www.mymovingreviews.com/images/spr_onestar.svg);

	background-position: 0 -408px;

	background-repeat: no-repeat;

	background-size: 100%;

	cursor: pointer;

}

.rating-star,

.rating:hover .rating-star {

	position: relative;

	float: right;

	display: block;

	width: 38px;

	height: 38px;

	margin: 0;

	background: url(https://www.mymovingreviews.com/images/spr_onestar.svg);

	background-position: 0 -408px;

	background-size: 100%;

	background-repeat: no-repeat;

}

.rating-star, .rating:hover .rating-star { background: url(https://www.mymovingreviews.com/images/spr_onestar.svg); background-position: 0 -391.5px; background-repeat: no-repeat; background-size: 100%; height: 38px; }

.rating:hover .rating-star:nth-of-type(5):hover, .rating:hover .rating-star:nth-of-type(5):hover ~ .rating-star, .rating-input:nth-of-type(5).checked ~ .rating-star { background-position: 0 -313px; }	/* 1 Star Rating */

.rating:hover .rating-star:nth-of-type(4):hover, .rating:hover .rating-star:nth-of-type(4):hover ~ .rating-star, .rating-input:nth-of-type(4).checked ~ .rating-star { background-position: 0 -234px; }	/* 2 Star Rating */

.rating:hover .rating-star:nth-of-type(3):hover, .rating:hover .rating-star:nth-of-type(3):hover ~ .rating-star, .rating-input:nth-of-type(3).checked ~ .rating-star { background-position: 0 -155px; }	/* 3 Star Rating */

.rating:hover .rating-star:nth-of-type(2):hover, .rating:hover .rating-star:nth-of-type(2):hover ~ .rating-star, .rating-input:nth-of-type(2).checked ~ .rating-star { background-position: 0 -76px; }	/* 4 Star Rating */

.rating:hover .rating-star:nth-of-type(1):hover, .rating:hover .rating-star:nth-of-type(1):hover ~ .rating-star, .rating-input:nth-of-type(1).checked ~ .rating-star { background-position: 0 0px; }	/* 5 Star Rating */

/* Base styles for the element that has a tooltip */

[data-tooltip] {

  position: relative;

  cursor: pointer;

}



/* Base styles for the entire tooltip */

[data-tooltip]:before,

[data-tooltip]:after {

  position: absolute;

  visibility: hidden;

  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";

  filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=0);

  opacity: 0;

  -webkit-transition: 

      opacity 0.2s ease-in-out,

        visibility 0.2s ease-in-out,

        -webkit-transform 0.2s cubic-bezier(0.71, 1.7, 0.77, 1.24);

    -moz-transition:    

        opacity 0.2s ease-in-out,

        visibility 0.2s ease-in-out,

        -moz-transform 0.2s cubic-bezier(0.71, 1.7, 0.77, 1.24);

    transition:         

        opacity 0.2s ease-in-out,

        visibility 0.2s ease-in-out,

        transform 0.2s cubic-bezier(0.71, 1.7, 0.77, 1.24);

  -webkit-transform: translate3d(0, 0, 0);

  -moz-transform:    translate3d(0, 0, 0);

  transform:         translate3d(0, 0, 0);

  pointer-events: none;

  border-radius: 3px;

  text-align: center;

}



/* Show the entire tooltip on hover and focus */

[data-tooltip]:hover:before,

[data-tooltip]:hover:after,

[data-tooltip]:focus:before,

[data-tooltip]:focus:after {

  visibility: visible;

  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";

  filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);

  opacity: 1;

}



/* Base styles for the tooltip's directional arrow */

[data-tooltip]:before {

  z-index: 1001;

  border: 7px solid transparent;

  background: transparent;

  content: "";

}



/* Base styles for the tooltip's content area */

[data-tooltip]:after {

  z-index: 1000;

  padding: 8px;

  width: 160px;

  background-color: #000;

  background-color: hsla(0, 0%, 20%, 0.9);

  color: #fff;

  content: attr(data-tooltip);

  font-size: 14px;

  line-height: 1.2;

}



/* Directions */



/* Top (default) */

[data-tooltip]:before,

[data-tooltip]:after {

  bottom: 100%;

  left: 50%;

}



[data-tooltip]:before {

  margin-left: -6px;

  margin-bottom: -12px;

  border-top-color: #000;

  border-top-color: hsla(0, 0%, 20%, 0.9);

}



/* Horizontally align top/bottom tooltips */

[data-tooltip]:after {

  margin-left: -80px;

}



[data-tooltip]:hover:before,

[data-tooltip]:hover:after,

[data-tooltip]:focus:before,

[data-tooltip]:focus:after {

  -webkit-transform: translateY(-12px);

  -moz-transform:    translateY(-12px);

  transform:         translateY(-12px); 

}

	input[type=radio]

	{margin-left:0px;

	width:0px;

	}Important!;



</style>

</head>

<body onLoad="doOnLoad();">

<div >

<?php include 'newheaderwithoutbanner.php'; ?>


<?php if($_REQUEST['msg1']==1){

echo "<br><span style=\"color:#3f4773; text-align:justify;\">";

echo "<h3 align=\"center\" >Thank you for contacting Top Moving Reviews</h3>";
echo "<h3 align=\"center\" >Based on the information that you have submitted, three professional moving companies have received your contact information and should be contacting you soon to provide quotes and estimates for the service that you have inquired about. </h3>";
echo "<p align=\"center\" ><a href=\"index.php\">Back to Home  </a></p>";
echo "</span>";
} else {
?>




<div class="container">

    <div class="row">

<div class="col-md-3"></div>

<div class="col-md-6 shadow-lg p-3 mb-5 bg-light rounded">

<form name="frm11" method="post" action="" autocomplete="off" onsubmit="passData_new1();">

<input id="ip" name="ip" type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
    


									<table  border="0" cellspacing="0" width="100%" >



    

  <tr height="40" >

    <td colspan="3"><h3>Your Information</h3>

      </td>

    </tr>

  <tr height="50">

    <td colspan="2">First Name</td>

    <td><input type="text" name="name" id="name" class="form-control" required></td>    

  </tr>

  <tr height="50">

    <td colspan="2">Last Name</td>

    <td><input type="text" name="lname" id="lname" class="form-control" required></td>    

  </tr>


  <tr height="50">

    <td colspan="2">Phone Number </td>

    <td><input type="text" name="phnumber" id="phnumber" class="form-control" required></td>   

  </tr>

  <tr height="50">

    <td colspan="2" >Email</td>

    <td><input type="email" name="txtEmail" id="txtEmail" class="form-control" required></td>

  </tr>

  <tr height="20">

  </tr>

  <tr height="40">

    <td colspan="3"><h3>Moving Information</h3></td>

    </tr>

  <tr height="50">
    <td colspan="2">From Zip</td>
    <td><input type="text" name="from_zip" id="from_zip" value="<?php echo $moving_from_zipcode; ?>" class="form-control" maxlength="5" required></td>
  </tr>
  <tr height="30" class="ftooltip" style="display:none;">
      <td colspan="2">&nbsp;</td>
      <td><div class="invalid-tooltip" id="fzip-error" style="position:unset !Important; margin-bottom:15px; "> Please provide a valid zip.</div></td>
  </tr>
  <tr height="50">
    <td colspan="2">From City</td>
    <td><input type="text" id="fcity" name="from_city"  class="form-control" disabled="disabled"></td>
  </tr>
  <tr height="50">
    <td colspan="2">From State</td>
    <td><input type="text" id="fstate" name="from_state" class="form-control" disabled="disabled" ></td>
  </tr>



  <tr height="50">
    <td colspan="2">To Zip</td>
    <td><input type="text" name="to_zip" id="to_zip" value="<?php echo $moving_to_zipcode; ?>" class="form-control" maxlength="5" required></td>
  </tr>
  <tr height="30" class="ttooltip" style="display:none;">
      <td colspan="2">&nbsp;</td>
      <td><div class="invalid-tooltip" id="tzip-error" style="position:unset !Important; margin-bottom:15px; "> Please provide a valid zip.</div></td>
  </tr>
  
  <tr height="50">
    <td colspan="2">To City</td>
    <td><input type="text" id="tcity" name="to_city"  class="form-control" disabled="disabled" ></td>
  </tr>
  <tr height="50">
    <td colspan="2">To State</td>
    <td><input type="text" id="tstate" name="to_state" class="form-control" disabled="disabled" ></td>
  </tr>

  <tr height="50">

    <td colspan="2">Move Date</td>
    <td><input id=calendarHere  name=calendarHere onClick="setFrom();"  value="<?php echo $_REQUEST[calendarHere]; ?>" readonly="" class="form-control" required></td>

   

  </tr>

  <tr height="50">

    <td colspan="2">Move Size </td>

    <td><select id="moving_size" name="moving_size" data-validation="required" class="form-control" required>
      <option value="">Select Move Size</option>
      <option value="5 Bedrooms">4+ Bedroom Home</option>
      <option value="4 Bedrooms">4 Bedroom Home</option>
      <option value="3 Bedrooms">3 Bedroom Home</option>
      <option value="2 Bedrooms">2 Bedroom Home</option>
      <option value="1 Bedroom">1 Bedroom Home</option>
      <option value="" disabled="" style="letter-spacing:-2px;">------------------------------------</option>
      <option value="Studio">Studio</option>
      <option value="1 Bedroom">Partial Move</option>
      <option value="Studio">Office Move</option>
      <option value="Studio">Commercial Move</option>
    </select></td>

   

  </tr>

   <tr height="50">

    <td colspan="2">Comments</td>

    <td><textarea id="txtComments" name="txtComments" class="form-control"></textarea></td>

   

  </tr>

  

   <tr>

     <td colspan="2"> <?php if(isset($msg)){ echo $msg; }?></td>

     <td><img src="phpcaptcha/captcha.php?rand=<?php echo rand();?>" id='captchaimg'><a href='javascript: refreshCaptcha();'><img src="images/refresh.jpg" /></a><br />

	<label for='message'>Enter the code above here :</label>

	<input id="captcha_code" name="captcha_code" type="text" class="inputbox"></td>


   </tr>

   <tr>

    <td colspan="2"></td>

    <td><br/><input value="Get Quotes" name="publish" type="submit" class="btn btn-block btn-primary" ></td>

  </tr>

</table>



									

									

									

									

    </form>

</div>



<div class="col-md-3"></div>

</div>

</div>

<h1><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
function is_int(value) {
  if ((parseFloat(value) == parseInt(value)) && !isNaN(value)) {
    return true;
  } else {
    return false;
  }
}

//$(".fancy-form div > div").hide();
$("#from_zip").on('keypress, keydown, keyup',function() {
  // Cache
  var el = $(this);
  console.log(el.val().length);
  // Did they type five integers?
  if ((is_int(el.val())) || (el.val().length == 5)) 
  {
    // Call Ziptastic for information
    $.ajax({
      url: "https://zip.getziptastic.com/v2/US/" + el.val(),
      cache: false,
      dataType: "json",
      type: "GET",
      success: function(result, success) 
      {
        //$(".zip-error, .instructions").slideUp(200);
        $("#fcity").val(result.city);
        $("#fstate").val(result.state_short);
        $("#fzip-error").hide();
        $("#from_zip").css("color","black");
        $("#fzip-error").slideUp(500);      
        $(".ftooltip").slideUp(500);        

      },
      error: function(result, success) {
        $("#from_zip").css("color","red");
        $("#fzip-error").slideDown(500);        
        $("#fcity").val(null);
        $("#fstate").val(null);
        $(".ftooltip").slideDown(500);        
      }
    });
  }
  else if (el.val().length < 5) 
  {
    //$(".zip-error").slideUp(200);
  };
});
$("#to_zip").on('keypress, keydown, keyup',function() {
  // Cache
  var el = $(this);
  // Did they type five integers?
  if ((is_int(el.val())) || (el.val().length == 5)) 
  {
    // Call Ziptastic for information
    $.ajax({
      url: "https://zip.getziptastic.com/v2/US/" + el.val(),
      cache: false,
      dataType: "json",
      type: "GET",
      success: function(result, success) 
      {
        $("#tcity").val(result.city);
        $("#tstate").val(result.state_short);
        $("#tzip-error").hide();
        $("#to_zip").css("color","black");
        $("#tzip-error").slideUp(500);        
        $(".ttooltip").slideUp(500);        
      },
      error: function(result, success) {
        $("#to_zip").css("color","red");
        $("#tzip-error").slideDown(500);
        $("#tcity").val(null);
        $("#tstate").val(null);
        $(".ttooltip").slideDown(500);        
      }
    });
  }
  else if (el.val().length < 5) 
  {
    //$(".zip-error").slideUp(200);
  };
});








$(document).ready(function(){
var el1 = $("#from_zip");
if ((is_int(el1.val())) || (el1.val().length == 5)) 
  {
    // Call Ziptastic for information
    $.ajax({
      url: "https://zip.getziptastic.com/v2/US/" + el1.val(),
      cache: false,
      dataType: "json",
      type: "GET",
      success: function(result, success) 
      {
        //$(".zip-error, .instructions").slideUp(200);
        $("#fcity").val(result.city);
        $("#fstate").val(result.state_short);
        $("#fzip-error").hide();
        $("#from_zip").css("color","black");
        $("#fzip-error").slideUp(500);      
        $(".ftooltip").slideUp(500);        

      },
      error: function(result, success) {
        $("#from_zip").css("color","red");
        $("#fzip-error").slideDown(500);        
        $("#fcity").val(null);
        $("#fstate").val(null);
        $(".ftooltip").slideDown(500);        
      }
    });
  }
  else if (el1.val().length < 5) 
  {
        $("#from_zip").css("color","red");
        $("#fzip-error").slideDown(500);        
        $("#fcity").val(null);
        $("#fstate").val(null);
        $(".ftooltip").slideDown(500); 
  };   
  
  




var el2 = $("#to_zip");
if ((is_int(el2.val())) || (el2.val().length == 5)) 
  {
    // Call Ziptastic for information
    $.ajax({
      url: "https://zip.getziptastic.com/v2/US/" + el2.val(),
      cache: false,
      dataType: "json",
      type: "GET",
      success: function(result, success) 
      {
        //$(".zip-error, .instructions").slideUp(200);
        $("#tcity").val(result.city);
        $("#tstate").val(result.state_short);
        $("#tzip-error").hide();
        $("#to_zip").css("color","black");
        $("#tzip-error").slideUp(500);      
        $(".ttooltip").slideUp(500);        

      },
      error: function(result, success) {
        $("#to_zip").css("color","red");
        $("#tzip-error").slideDown(500);        
        $("#tcity").val(null);
        $("#tstate").val(null);
        $(".ttooltip").slideDown(500);        
      }
    });
  }
  else if (el2.val().length < 5) 
  {
        $("#to_zip").css("color","red");
        $("#tzip-error").slideDown(500);        
        $("#tcity").val(null);
        $("#tstate").val(null);
        $(".ttooltip").slideDown(500); 
  };  
  
    
});


</script>


</h1>
<script>

function passData_new1() 
{
	     
	     var fname =document.getElementById("name").value;
	     var lname =document.getElementById("lname").value;
	     var phnumber =document.getElementById("phnumber").value;	     
	     var txtEmail =document.getElementById("txtEmail").value;	     

	     var from_zip =document.getElementById("from_zip").value;	 
	     var from_city =document.getElementById("fcity").value;	 
	     var from_state =document.getElementById("fstate").value;	 

	     
	     var to_zip =document.getElementById("to_zip").value;	     
	     var to_city =document.getElementById("tcity").value;	 
	     var to_state =document.getElementById("tstate").value;	 


	     var calendarHere =document.getElementById("calendarHere").value;	 
	     var moving_size =document.getElementById("moving_size").value;
	     var txtComments =document.getElementById("txtComments").value;
	     
	     var ip =document.getElementById("ip").value;	 	     

	       

		var alldata = fname + '^' + lname+ '^' + phnumber+ '^' + txtEmail+ '^' + from_zip+ '^' + from_city+ '^' + from_state+ '^'+ to_zip+ '^' + to_city+ '^' + to_state+ '^' + calendarHere+ '^' + moving_size+ '^' + txtComments + '^' +ip;

		var dataString = 'data_to_be_pass=' + alldata;
			if (alldata == '') 
			{
					
			} 
			else 
			{
				// AJAX code to submit form.
				$.ajax({
						type: "POST",
						url: "lead.php",
						data: dataString,
						cache: false
						});
			}
			

	return false;
	}	
</script>


<?php } include 'footer.php'; ?>

</body>

</html>


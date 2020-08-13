<?php header('Content-Type: text/html; charset=ISO-8859-1');
require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();
$db_link = $bd->db_connect();
$company_id=54;
$sql_company="SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";
$query_company=mysql_query($sql_company);
$res_company=mysql_fetch_array($query_company);
$compnay_address=explode(",",$res_company['address']);
$countarray=count($compnay_address);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Review Submitted</title>

<meta name=viewport content="width=device-width, initial-scale=1.0">
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<link href=favicon.ico rel=icon type="image/x-icon">
<meta name="description" content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title'].",".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2).",".substr($res_company['text'],0,30);?>...">
<meta name=keywords content="<?php echo $res_company['title'];?>,Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving"><link href="../css/fo.css" rel=stylesheet type="text/css" >
<link rel=stylesheet type="text/css" href="../css/style.css">
<meta property=og:title content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2);?>">
<meta property=og:description content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2).",".substr($res_company['text'],0,30);?>...">
<meta property=og:image content="http://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg">
<meta property=og:site_name content="http://topmovingreviews.com">

<meta name="twitter:url" content="http://www.topmovingreviews.com/Garland-moving-group_dynamic.php" />
<meta name="twitter:title" content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2);?>" />
<meta name="twitter:image" content="http://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg"/>
<meta name="twitter:description" content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title'].",".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2).",".substr($res_company['text'],0,30);?>..." />
<meta name="twitter:site" content="@topmovingreviews" />


<meta name=dc.language content=US>
<meta name=dc.subject content="NY Movers">
<meta name=DC.identifier content="/meta-tags/dublin/">
<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">
<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
<meta name=HandheldFriendly content=true>
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <link rel=stylesheet href="https://www.topmovingreviews.com/css/dhtmlxcalendar.css"/>
<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js"></script>
<script>var today=new Date();var dd=today.getDate();var mm=today.getMonth()+1;var yyyy=today.getFullYear();if(dd<10)
{dd='0'+dd;}
if(mm<10)
{mm='0'+mm;}
today=mm+'-'+dd+'-'+yyyy;var myCalendar;function doOnLoad(){myCalendar=new dhtmlXCalendarObject("calendarHere");myCalendar.hideTime();myCalendar.setDate(getdate());}
function setFrom(){myCalendar.setSensitiveRange(today,null);}

function validate()
{
 
			 if ( document.getElementById('calendarHere').value == '' )
        {
        
                alert('Select Moving Date!');
				document.getElementById('calendarHere').focus();
                return false;				
        }
     else if ( document.getElementById('MovingFrom').value == '' || (isNaN(document.getElementById('MovingFrom').value)))
        {
                alert('Enter Valid Zip Code Where  you are Moving From!');
				document.getElementById('MovingFrom').focus();
                return false;				
        }
		
		  else if ( document.getElementById('MovingTo').value == '' )
        {
                alert('Enter Valid Zip Code Where  you are Moving To!');
				document.getElementById('MovingTo').focus();
                return false;				
        }
	
		
		else
		{
		document.getElementById("frm1").submit();	
		return true;
		}
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
<body onLoad="doOnLoad();"onLoad="doOnLoad();">
<div class=wrapper>
<?php include 'header-without-form.php'; ?>
<div class=content>
<div class=block-left>

<br>
<section id="content-1" class="large-12 small-12 columns">

<style>

.form {
    margin: 10px;
    border: 1px solid #f2f2f2;
    background-color: #FAFAFA;
	height:130px;
	padding-top:30px;
}
#review-wrap {
    width: 688px;
    float: left;
	margin-left:200px;
	
	
}
</style>


<div id="review-wrap">
<h1 style="size:24px;">Review Submitted</h1>
<div class="form" style="text-align:center;">
		<h2 style="color:#F00">Please activate your review!</h2>
		<p><strong>We have sent you a confirmation email at <span class="user_mail"><?php  echo $_REQUEST['email'];?></span>. Please click on the link in your email to activate the review.</strong></p>
		<!--<p><a class="btn_light" href="http://gmail.com" rel="nofollow" target="_blank">Go to activate your review</a></p>-->
		<p><strong>If you can't find the email, please <span style="color: #ff0000">check your spam directory</span>.</strong></p>
	</div>
	
	<p>Thank you for your review. The review you have submitted will help others decide which moving company to use based on moving reviews like this.</p>
	
</div>

				  </section>
</div>

<div class=block-right>
<div class=cstm_get_quote>
<h6><i>Get Moving Quotes</i></h6>
<form id="frm1" name="frm1" method="post" action=https://www.topmovingreviews.com/quoteform.php autocomplete="off">
<div class=cstm_frm>
<label> <input  class=newinput  value="Move date" onfocus="this.value=''"  id="calendarHere"  name="calendarHere" onClick="setFrom();" readonly=""></label>
<label><input class=newinput  value="Where are you moving from Zip Code?" onfocus="this.value=''" name="MovingFrom" id="MovingFrom" ></label>
<label><input class=newinput  value="Where are you moving to Zip Code? " onfocus="this.value=''" name="MovingTo" id="MovingTo"></label>
<div class=submit-new><a class=cstm_btn onclick="javascript:return validate()" style="cursor:pointer;">Continue</a> </div>
</div>
</form>
</div>
<?php 

$sql_dot="select power_units,usdot_number,mc,safety_url from company_dot_data where  company_name= 'BEST MOVING SERVICE- AMERICAN PRIDE MOVING SERVICE'";

$query_dot=mysql_query($sql_dot);

$res_dot=mysql_fetch_assoc($query_dot);

?>


<div class=nxt><h1>Read up before you make your next move</h1></div>
<div class=nxt_move>
<button class=accordion><h4>Hire the best moving company</h4></button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
<button class=accordion><h4>Moving Checklist: how to prepare for the moving day</h4></button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
<button class=accordion><h4>Tips to find the best affordable movers near you</h4></button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
</div>
<button class=accordion><h4>6 things to consider before the relocation</h4></button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

<?php header('Content-Type: text/html; charset=ISO-8859-1');
require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();
$db_link = $bd->db_connect();
$company_id=$_REQUEST['company_id'];
$sql_company="SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";
$query_company=mysql_query($sql_company);
$res_company=mysql_fetch_array($query_company);
$compnay_address=explode(",",$res_company['address']);
$countarray=count($compnay_address);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    
<title><?php echo $res_company['count(*)'] ." Top Moving for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2);?></title>

<meta name=viewport content="width=device-width, initial-scale=1.0">
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 
<meta name="description" content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title'].",".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2).",".substr($res_company['text'],0,30);?>...">
<meta name=keywords content="<?php echo $res_company['title'];?>,Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving"><link href="../css/fo.css" rel=stylesheet type="text/css" >

<meta property=og:title content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2);?>">
<meta property=og:description content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2).",".substr($res_company['text'],0,30);?>...">
<meta property=og:image content="http://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg">
<meta property=og:site_name content="http://topmovingreviews.com">

<meta name="twitter:url" content="http://www.topmovingreviews.com/Garland-moving-group_dynamic.php" />
<meta name="twitter:title" content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title']."-".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2);?>" />
<meta name="twitter:image" content="http://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg"/>
<meta name="twitter:description" content="<?php echo $res_company['count(*)'] ." Top Moving Reviews for " .$res_company['title'].",".$compnay_address[$countarray-3].",".substr($compnay_address[$countarray-2],1,2).",".substr($res_company['text'],0,30);?>..." />
<meta name="twitter:site" content="@topmovingreviews" />

<link href="https://www.topmovingreviews.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name=dc.language content=US>
<meta name=dc.subject content="NY Movers">
<meta name=DC.identifier content="/meta-tags/dublin/">
<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">
<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
<meta name=HandheldFriendly content=true>
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script language="javascript" type="text/javascript">								
        

			$(document).ready(function() {

							$('.rating-input').change(function() {
					if ($(this).is(':checked')) {		
						$('.rating-input').removeClass('checked');
						$(this).addClass('checked');
					}
				});
				
			});


function validate()

{

 var stringEmail = document.getElementById('email').value;

 if ( document.getElementById('review_subject').value == '' )

        {
                alert('Enter Company Review Subject!');
				document.getElementById('review_subject').focus();
                return false;				

        }

 				else if ( document.getElementById('your_review').value == '' )

        {

                alert('Enter Your Review about the Company!');
				document.getElementById('your_review').focus();
                return false;				

        }
		      
			   else if ( document.getElementById('moving_size').value == '' )

        {

                alert('Enter Move Size!');
				document.getElementById('moving_size').focus();
                return false;				

        }
		
				   else if ( document.getElementById('order_id').value == '' )

        {

        

                alert('Enter Quote or Order ID!');
				document.getElementById('order_id').focus();
                return false;				

        }
		
				   else if ( document.getElementById('origin_country').value == '' )

        {

                alert('Select Pick up Country!');
				document.getElementById('origin_country').focus();
                return false;				

        }
				   else if ( document.getElementById('origin_state').value == '' )

        {      

                alert('Select Pick up State!');
				document.getElementById('origin_state').focus();
                return false;				

        }
				 else if ( document.getElementById('origin_city').value == '' )

        {
                alert('Enter Pick up City!');
				document.getElementById('origin_city').focus();
                return false;				

        }		
					  else if ( document.getElementById('destination_country').value == '' )

        {     

                alert('Select Destination Country!');
				document.getElementById('destination_country').focus();
                return false;				

        }
				   else if ( document.getElementById('destination_state').value == '' )

        {    

                alert('Select Destination State!');
				document.getElementById('destination_state').focus();
                return false;				

        }
				 else if ( document.getElementById('destination_city').value == '' )

        {     

                alert('Enter Destination City!');
				document.getElementById('destination_city').focus();
                return false;				

        }	
       
					 else if ( document.getElementById('name').value == '' )

        {     

                alert('Enter Your Name!');
				document.getElementById('name').focus();
                return false;				

        }	
			 else if ( document.getElementById('email').value == '' )

        {     

                alert('Enter Your Email!');
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
		return true;

		}

}
			
	  </script>
	  
	  
	     <script>

    $(document).ready(function () {



        var showChar = 200;

        var ellipsestext = "...";

        var moretext = "Read More";

        var lesstext = "Less";

        $('.more').each(function () {

            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);

                var h = content.substr(showChar - 1, content.length - showChar);



                var html = c + '<span class="moreelipses">' + ellipsestext +

                    '</span><span class="morecontent"><span style="display:none;">' + h +

                    '</span>&nbsp;&nbsp;\n<a href="" class="morelink">' + moretext + '</a></span>';



                $(this).html(html);

            }



        });



        $(".morelink").click(function () {

            if ($(this).hasClass("less")) {

                $(this).removeClass("less");

                $(this).html(moretext);

            } else {

                $(this).addClass("less");

                $(this).html(lesstext);

            }

            $(this).parent().prev().toggle();

            $(this).prev().toggle();

            return false;

        });



    });



    //for after filter

    function reload_more() {

        // alert();

        var showChar = 200;

        var ellipsestext = "...";

        var moretext = "Read More";

        var lesstext = "Less";

        $('.more').each(function () {

            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);

                var h = content.substr(showChar - 1, content.length - showChar);



                var html = c + '<span class="moreelipses">' + ellipsestext +

                    '</span>&nbsp;<span class="morecontent"><span style="display:none;">' + h +

                    '</span>&nbsp;&nbsp;\n<a href="" class="morelink">' + moretext + '</a></span>';



                $(this).html(html);

            }

        });



        $(".morelink").click(function () {

            if ($(this).hasClass("less")) {

                $(this).removeClass("less");

                $(this).html(moretext);

            } else {

                $(this).addClass("less");

                $(this).html(lesstext);

            }

            $(this).parent().prev().toggle();

            $(this).prev().toggle();

            return false;

        });

    }

</script> 
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	   	<style>
	
	.rating { 
  border: none;
  float: left;
}

.rating > input { display: none; } 
.rating > label:before { 
  margin: 5px;
  font-size: 1.25em;
  font-family: FontAwesome;
  display: inline-block;
  content: "\f005";
}

.rating > .half:before { 
  content: "\f089";
  position: absolute;
}

.rating > label { 
  color: #ddd; 
 float: right; 
}

/***** CSS Magic to Highlight Stars on Hover *****/

.rating > input:checked ~ label, /* show gold star when clicked */
.rating:not(:checked) > label:hover, /* hover current star */
.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */

.rating > input:checked + label:hover, /* hover current star when changing rating */
.rating > input:checked ~ label:hover,
.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
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
.rating:hover .rating-star:nth-of-type(1):hover, .rating:hover .rating-star:nth-of-type(1):hover ~ .rating-star, .rating-input:nth-of-type(1).checked ~ .rating-star { }	/* 5 Star Rating */
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
	}
.block-left {
    width: 66.5%!important;
    float: left;
    margin-right: 2.5%;
}
@media only screen and (max-width: 700px) {
.block-left {
    width: 100% !important;
    float: none;
    margin-right: 2.5%;
}
}
/*.fa-star{color:grey!important;}*/
.fa-star:hover{
     color: red!important
}
.btn {
    display: inline-block;
    font-weight: 400;
    color: #212529;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    
    border: 1px solid #f1f1f1 !important;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
</style>
<script>	jQuery(document).ready(function($){
	    
	$(".btnrating").on('click',(function(e) {
	
	var previous_value = $("#selected_rating").val();
	
	var selected_value = $(this).attr("data-attr");
	$("#selected_rating").val(selected_value);
	
	$(".selected-rating").empty();
	$(".selected-rating").html(selected_value);
	
	for (i = 1; i <= selected_value; ++i) {
	$("#rating-star-"+i).toggleClass('btn-warning');
	$("#rating-star-"+i).toggleClass('btn-default');
	}
	
	for (ix = 1; ix <= previous_value; ++ix) {
	$("#rating-star-"+ix).toggleClass('btn-warning');
	$("#rating-star-"+ix).toggleClass('btn-default');
	}
	
	}));
	
		
});
</script>
</head>
<body>
<div class=wrapper>
<?php include 'newheaderwithoutbanner.php'; ?>
<div class="container">
<div class="content">
<div class=block-left>
<div class=jet>
<h2 class=colorclass><i>Now reviewing:  <?php echo  $res_company['title'];?></i> </h2>
<div class=jet-left>

<img src="../../mmr_images/logos/logo_<?php echo $company_id;?>.jpg" class=jt-height alt="New York">
</div>
<div class=jet-right>




</div>



</div>
<br>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
<form name="frm11"  action="process/add_user_review.php" method="post"  enctype="multipart/form-data" >
   <h2> Your overall rating of this company</h2>
   <div class="form-group" id="rating-ability-wrapper">
	    <label class="control-label" for="rating">
	    <span class="field-label-header"></span><br>
	    <span class="field-label-info"></span>
	    <input type="hidden" id="selected_rating" name="selected_rating" value="" required="required">
	    </label>
	    <h6 class="bold rating-header" style="">
	    <span class="selected-rating">0</span><small> / 5</small>
	    </h6>
	    <button type="button" class="btnrating btn btn-default btn-lg light-back" data-attr="1" id="rating-star-1"  data-toggle="tooltip" title="Poor">
	        <i class="fa fa-star" aria-hidden="true"></i>
	    </button>

	    <button type="button" class="btnrating btn btn-default btn-lg light-back" data-attr="2" id="rating-star-2" data-toggle="tooltip" title="Unsatisfactory">
	        <i class="fa fa-star" aria-hidden="true"></i>
	    </button>
	    <button type="button" class="btnrating btn btn-default btn-lg light-back" data-attr="3" id="rating-star-3" data-toggle="tooltip" title="Average">
	        <i class="fa fa-star" aria-hidden="true"></i>
	    </button>
	    <button type="button" class="btnrating btn btn-default btn-lg light-back" data-attr="4" id="rating-star-4" data-toggle="tooltip" title="Good">
	        <i class="fa fa-star" aria-hidden="true"></i>
	    </button>
	    <button type="button" class="btnrating btn btn-default btn-lg light-back" data-attr="5" id="rating-star-5" data-toggle="tooltip" title="Excellent">
	        <i class="fa fa-star" aria-hidden="true"></i>
	    </button>
	</div>
									<table width="100%" border="0" cellspacing="0">

  <tr><input name="company_id" type="hidden" value="<?php echo $company_id;?>" class="form-control"class="form-control" >
    <td>
				
<div class="add-review-right"  >

				<!--	<div class="add_review"  >
						<span class="rating has-success" style="float:right; important!" >
							<input data-validation="required" data-validation-error-msg-required="Please enter overall rating" id="write-rating-5" name="rating_value" type="radio" value="5" class="rating-input valid">
							<label data-tooltip="Excellent" for="write-rating-5" class="rating-star"></label>
							<input data-validation="required" id="write-rating-4" name="rating_value" type="radio" value="4" class="rating-input valid">
							<label data-tooltip="Good" for="write-rating-4" class="rating-star"></label>
							<input data-validation="required" id="write-rating-3" name="rating_value" type="radio" value="3" class="rating-input  valid">
							<label data-tooltip="Average" for="write-rating-3" class="rating-star"></label>
							<input data-validation="required" id="write-rating-2" name="rating_value" type="radio" value="2" class="rating-input valid">
							<label data-tooltip="Unsatisfactory" for="write-rating-2" class="rating-star"></label>
							<input data-validation="required" id="write-rating-1" name="rating_value" type="radio" value="1" class="rating-input valid">
							<label data-tooltip="Poor" for="write-rating-1" class="rating-star"></label>
						</span>
						Click to rate.					</div>-->
						</div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Review subject</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td  colspan="2">
      <input type="text" name="review_subject" id="review_subject"  class="form-control" required="">
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr >
    <td>Your review</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td colspan="2" >
      <textarea name="your_review" id="your_review"  class="form-control"></textarea>
   </td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td>Move size</td>
    <td><select id="moving_size" name="moving_size" data-validation="required" class="form-control" >
					<option value="">Select Move Size</option>
					<option value="4+ Bedroom Home">4+ Bedroom Home</option>
					<option value="4 Bedroom Home">4 Bedroom Home</option>
					<option value="3 Bedroom Home">3 Bedroom Home</option>
					<option value="2 Bedroom Home">2 Bedroom Home</option>
					<option value="1 Bedroom Home">1 Bedroom Home</option>
					<option value="Studio">Studio</option>
					<option value="Partial Move">Partial Move</option>
					<option value="" disabled="" style="letter-spacing:-2px;">------------------------------------</option>
					<option value="Office Move">Office Move</option>
					<option value="Commercial Move">Commercial Move</option>
				</select></td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td>Quote or order ID</td>
    <td><input type="text" name="order_id" id="order_id" class="form-control"></td>
    <td>&nbsp;</td>
  </tr>
   <tr height="50">
    <td>Initial Estimate </td>
    <td><input type="text" name="initial_estimate" id="initial_estimate"class="form-control" ></td>
    <td>&nbsp;</td>
  </tr>
   <tr height="50">
    <td>Final Bill</td>
    <td><input type="text" name="final_bill" id="final_bill"class="form-control" ></td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td style="font-weight:bold; font-size:18px;">Moving Route</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Pickup location</td>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" >
      <tr >
        <td style="padding:3px;"><select name="origin_country" id="origin_country" class="form-control"><option value="">Select Country</option>
		<option value="USA">USA</option>
        </select></td>
        <td style="padding:3px;"><select name="origin_state" id="origin_state" class="form-control">
		<option value="">Select State</option>
		<?php
								   	  $string = "SELECT * FROM us_state_list";
									  $query = mysql_query($string);
									  while($result = mysql_fetch_array($query)) {
								   ?>
                                   		<option value="<?= $result['State_Code']; ?>"><?= $result['State_Name']; ?></option>
                                   <?php } ?>
        </select></td>
        <td style="padding:3px;"><input type="text" name="origin_city" id="origin_city" class="form-control"></td>
      </tr>
    </table></td>
    </tr>
  <tr height="50">
    <td>Delivery location</td>
    <td colspan="2"><table width="100%" border="0" cellspacing="0" >
      <tr>
        <td style="padding:3px;"><select name="destination_country" id="destination_country" class="form-control"> <option value="">Select Country</option>
		<option value="USA">USA</option>  </select></td>
        <td style="padding:3px;" ><select name="destination_state" id="destination_state" class="form-control">
		<option value="">Select State</option>
		<?php
								   	  $string = "SELECT * FROM us_state_list";
									  $query = mysql_query($string);
									  while($result = mysql_fetch_array($query)) {
								   ?>
                                   		<option value="<?= $result['State_Code']; ?>"><?= $result['State_Name']; ?></option>
                                   <?php } ?> </select></td>
        <td style="padding:3px;"><input type="text" name="destination_city" id="destination_city" class="form-control"></td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td  style="font-weight:bold; font-size:18px;">Your Information</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Your name</td>
    <td><input type="text" name="name" id="name" class="form-control"></td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td>Your email address</td>
    <td><input type="text" name="email" id="email" class="form-control"></td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td>Allow visitors to contact you:</td>
    <td align="left"><label>
      <input type="checkbox" name="allow" id="allow" value="checkbox">
    </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr height="50">
    <td>Attach files</td>
    <td><input name="photo" id="photo" type="file" class="form-control"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td><br/><input value="Publish Review" name="publish" type="submit" class="btn btn-block btn-primary"   onClick="javascript:return validate();"></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

									
									
									
									
			 </form>
</div>

<div style="width:340px;float:left;text-align: left;padding: 10px 0 22px 22px;" >
<h3>Recent reviews on this company </h3>
<div style="height: 1240px; overflow: auto;line-height: 150%;">

<?php 
$sql_reviews="SELECT author,rating,text,date,wants_to_contact  FROM   reviews    where company_id=$company_id ORDER BY STR_TO_DATE(date, '%M %d,%Y') desc limit 0,3";
$query_reviews=mysql_query($sql_reviews);
while($res_reviews=mysql_fetch_array($query_reviews))
{

?>

<div>
<p class=stars>
<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
  <span style="font-weight:bold;">&nbsp&nbsp<?php echo $res_reviews['author']; ?></span></p>
<p class="more "><?php echo $res_reviews['text']; ?></p>
</div><br>
<?php } ?>
</div>
<?php /*?><div class=cstm_get_quote>
<h6><i>Get Moving Quotes</i></h6>
<div class=cstm_frm>
<label> <input class=newinput name="" value="Move date"></label>
<label><input class=newinput name="" value="Where are you moving from?"></label>
<label><input class=newinput name="" value="Where are you moving to? "></label>
<div class=submit-new><a class=cstm_btn href="#">Continue</a> </div>
</div>
</div>
<?php 

$sql_dot="select power_units,usdot_number,mc,safety_url from company_dot_data where  company_name= 'BEST MOVING SERVICE- AMERICAN PRIDE MOVING SERVICE'";

$query_dot=mysql_query($sql_dot);

$res_dot=mysql_fetch_assoc($query_dot);

?>

<div class=mover-details>
<h3>Mover Details</h3>
<strong>Moving Company Business Info</strong><br><br>
<table style="width:100%">
<tr>
<td class="newtd new-pad">In Business Since</td><td class="newtd new-pad">2013</td>
</tr>
<tr>
<td class=" new-pad">Number of Trucks</td><td class=new-pad><?php echo $res_dot['power_units'];?></td>
</tr>
<tr>
<td class="newtd new-pad">USDOT#</td><td class="newtd new-pad"><a href="<?php echo $res_dot['safety_url'];?>" target="_blank" style="color:#0000FF;"><?php echo $res_dot['usdot_number'];?></a></td>
</tr>
<tr>
<td class=new-pad>ICC MC Number</td><td class=new-pad><a href="<?php echo $res_dot['safety_url'];?>" target="_blank" style="color:#0000FF;"><?php echo $res_dot['mc'];?></a></td>
</tr>
</table>
</div>


<div class=ppl_viewed>
<h2>People who viewed this also viewed</h2>
<div>
<?php 

$company = new company();

$sql_pplview="select Comany_ID,company_name,city,state from company where viewed=1 order by Rand() limit 4";

$query_pplview=mysql_query($sql_pplview);

while($res_pplview=mysql_fetch_assoc($query_pplview)){

$average = $company->get_average_reviews_count($res_pplview["Comany_ID"]);

$reviews_count = $company->get_company_reviews_count($res_pplview["Comany_ID"]);

?>
<div class=ppl_view>
<div class=ppl_view_left>
<img src="images/logo1.png" alt=Garland moving group>
</div>
<div class=ppl_view_right>
<div class=ppl_lft style="width:160px;">
<span><?= $res_pplview["company_name"]; ?></span>
<p><?=$res_pplview["city"].",".$res_pplview["state"];   ?>, USA</p>
</div>
<div class=ppl_ryt>
<p class=stars>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
</p>
<span style="font-size:13px;"><?php echo $reviews_count;?> Reviews</span>
</div>
</div>
</div>
<?php  }  ?>
</div>
</div>
<div class=cmprev>
<p>Write a Moving Company Review Here</p>
<h4>Company Name</h4>
<div class=search-box>
<input class=seach22 value="Looking For..." name=search>
<button type=submit><i class="fa fa-search"></i>&nbsp;Search</button>
</div>
</div>
<div class=nxt><h1>Read up before you make your next move</h1></div>
<div class=nxt_move>
<button class=accordion>Hire the best moving company</button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
<button class=accordion>Moving Checklist: how to prepare for the moving day</button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
<button class=accordion>Tips to find the best affordable overs near you</button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
</div>
<button class=accordion>6 things to consider before the relocation</button>
<div class=panel>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
</div><?php */?>
</div>
</div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

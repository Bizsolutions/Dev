<?php header('Content-Type: text/html; charset=windows-1252');
require_once('core/database.class.php');



require_once('core/company.class.php');



$bd = new db_class();

$db_link = $bd->db_connect();

$company_id = isset($_GET['id']) ? $_GET['id'] : 54;



 //$sql_company   = "SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email,companies.logo,companies.city,companies.state,companies.zipcode  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";
$sql_company    = "SELECT companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email,companies.logo,companies.city,companies.state,companies.zipcode  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";

$query_company  = mysql_query($sql_company);

$res_company    = mysql_fetch_array($query_company);
//$res_company    = mysql_fetch_row($query_company);

$compnay_address= explode(",",$res_company['address']);

$countarray     = count($compnay_address);



// Getting the review for the company, to display a proper value

$sql = "SELECT * FROM reviews WHERE company_id='".$company_id."'";

$query = mysql_query($sql);



$company_rating = 0;
$company_review_number = mysql_num_rows($query);
if($company_review_number  > 0)

{

	

	$review_total = 0;

	while ($obj = mysql_fetch_object($query)) 

	{

		$review_total+=$obj->rating;

	}



	$company_rating = number_format($review_total/$company_review_number,1);

}

?>

<!DOCTYPE HTML >

<html lang="en">

<head>

<title><?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?></title>

<link href='https://fonts.googleapis.com/css?family=Montserrat:400&display=swap'; rel='stylesheet'>
<!--<link href='fonts.googleapis.com/css?family=Montserrat:400'; rel='stylesheet' type='text/css'>-->
<!--<link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?display=swap">-->




 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 

<meta name="description" content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>">

<meta name=keywords content="<?php echo $res_company['title'];?>">

   <meta http-equiv="X-UA-Compatible" content="IE=edge" />

      <meta name="HandheldFriendly" content="true">

      <meta property=og:title content="<?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?>">

      <meta property=og:description content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>">

      <!--<meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">-->

      <meta property=og:url content=":https://www.topmovingreviews.com/movers/Changing-Spaces-Moving-3616">

      <meta name=dc.language content=US>

      <meta name=dc.subject content="NY Movers">

      <meta name=DC.identifier content="/meta-tags/dublin/">

      <meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>

      <meta name=HandheldFriendly content=true>



      <meta name="viewport" content="width=device-width, initial-scale=1.0">



<!--<link rel="stylesheet" type="text/css" href="https://www.topmovingreviews.com/css/style.css">-->
<link rel="stylesheet" type="text/css" href="css/stylez.css">



<meta property=og:title content="<?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?>">

<meta property=og:description content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>">

<meta property=og:image content="https://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg">

<meta property=og:site_name content="https://topmovingreviews.com">



<meta name="twitter:url" content="https://www.topmovingreviews.com/moving.php" />

<meta name="twitter:title" content="<?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?>" />

<meta name="twitter:image" content="https://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg"/>

<meta name="twitter:description" content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>" />

<meta name="twitter:site" content="@topmovingreviews" />



<script async type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"https://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>

<style>

@font-face {
    font-family: "Montserrat";
    
    /*src: url("https://www.topmovingreviews.com/fontz/Montserrat-Regular.ttf");*/
    /*src: url("CustomFont.woff") format("woff"),
    url("CustomFont.otf") format("opentype"),
    url("CustomFont.svg#filename") format("svg");*/
}


body,
html {
	/*font-family: 'Montserrat',arial;*/
	font-family: 'Montserrat' !important;
	color: #000;
	font-size: 15px;
	font-weight: 400;
	line-height: 25px;
	font-style: normal;
	overflow-x: hidden
}



.morelink{







            color:#0188cc!important;







        }



.pagination1 {

    float: left;

    padding: 8px 16px;

    line-height: 20px;

    text-decoration: none;

    background-color: #fff;

	border:none !important;   

    border-left-width: 0;

	 font-weight:bold;

}



.pagination

{

padding-left:400px;



}



.pagination ul>li {

    display: inline;

	float:left;

	

	

}.pagination a {

    color: #000;

    float: left;

    padding: 8px 16px;

    text-decoration: none;

   	border:none !important;

    

}





@media (max-width: 481px)  {.hiderseach{font-size:18px;}}

</style>



<script>var today=new Date();var dd=today.getDate();var mm=today.getMonth()+1;var yyyy=today.getFullYear();if(dd<10)

{dd='0'+dd;}

if(mm<10)

{mm='0'+mm;}

today=mm+'-'+dd+'-'+yyyy;var myCalendar;
function doOnLoad(){
    myCalendar=new dhtmlXCalendarObject("calendarHere");
    myCalendar.hideTime();
    myCalendar.setDate(getdate());
}

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

</head>

<body onLoad="doOnLoad();">

<div class=wrapper>

<?php require_once('newheaderwithoutbannernewz.php'); ?>

<div class="container m-top-35">

    

        

    

<div class="jet jet-1">

    <div class="jeft-newleft">

        <div class="row">

        <?php

          if($res_company["logo"] != NULL)

          {

            if(is_file("https://www.topmovingreviews.com/mmr_images/logos/logo_".$company_id.".jpg"))

            {

              $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$company_id.".jpg";

            }

            else if(is_file("https://www.topmovingreviews.com/company/logos/logo_".$company_id.".jpg"))

            {

              $logo_image = "https://www.topmovingreviews.com/company/logos/logo_".$company_id.".jpg";

            }

            else

            {

              $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";

            }

          }

          else

          {

            $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";

          }

        ?>

<div class="col-md-3 text-left"><img src="<?php echo $logo_image;?>"  alt="<?php echo  $res_company['title'];?> - <?php echo $res_company['state'];?> -<?php echo  $res_company['city'];?> - Reviews">

<div class="btn btn-orange" style="cursor:pointer; margin-top:15px" onClick="window.open('https://www.topmovingreviews.com/movercontact.php?company_id=<?php echo $company_id;?>&title=<?php echo  $res_company['title'];?>&rating=<?php echo $res_company['rating'];?>&email=<?php echo $res_company['email'];?>','popUpWindow','height=550,width=550,left=400,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">

<!--<i class="fa fa-envelope-o"></i>-->Contact Mover

</div></div>

<div class="col-md-9 text-left m-top-35">



<h1 class="colorclass" style="text-align:left!important; "><i><?php echo  $res_company['title'];?></i> </h1>



<p class=stars>

<span class="fa fa-star  <?php if(round($company_rating)>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($company_rating)>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($company_rating)>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($company_rating)>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($company_rating)>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class=rating><strong style="color:#000;"><?php echo  $company_rating;?> </strong> (<?php echo  $company_review_number;?> Reviews)</span>&emsp;

<!--<span ><a class="btn btn-orange" href="https://www.topmovingreviews.com/add_reviews.php?company_id=<?php echo $res_company['id'];?>"><i class="fa fa-star"></i>&nbsp;Write a Review</a></span>
-->
</p>

<!--<div  class=jet-share>

<a href="#"><i class="fa fa-share"></i> Share</a>

</div>-->

</div></div>

<div class=>



<div class=htag>



</div>

<div class=info>
<?php if($res_company['address']<>"") {?>
<p><div class=location></div> 

<?php echo  $res_company['address'];?> | <a rel="nofollow" href="https://www.topmovingreviews.com/map.php?query=<?php echo substr(str_replace(",","%2C",str_replace(" ","+",$res_company['address'])),0,-4); ?>" target="_blank">View On Map</a></p><?php } ?>
<?php if($res_company['phone']<>"") {?>
<p><div class=headphone></div> Toll Free:  <?php echo  $res_company['phone'];?> | <div class=call></div> Phone:  <?php  echo  $res_company['phone'];?> </p><?php } ?>

<p><?php if($res_company['email']<>"") {?><div class=mail></div> <?php echo  $res_company['email'];?> | <?php } if($res_company['website']<>"") { ?> <div class=web></div><?php echo  $res_company['website']; }?></p>

</div>



</div></div>



<div class="jeft-right">

    <div class=cstm_get_quote>

<h6><i>The quick and easy money saver</i></h6>

<form id="frm1" name="frm1" method="post" action=https://www.topmovingreviews.com/quoteform.php autocomplete="off">

<div class=cstm_frm>

<label> <input  class=newinput  value="Move date" onFocus="this.value=''"  id="calendarHere"  name="calendarHere" onClick="setFrom();" readonly=""></label>

<label><input class=newinput  value="Where are you moving from Zip Code?" onFocus="this.value=''" name="MovingFrom" id="MovingFrom" ></label>

<label><input class=newinput  value="Where are you moving to Zip Code? " onFocus="this.value=''" name="MovingTo" id="MovingTo"></label>

<div class=submit-new><a class=cstm_btn onClick="javascript:return validate()" style="cursor:pointer;">Continue</a> </div>

</div>

</form>

</div>

    

</div>

</div><!---end-left--->

<div><hr/></div>

<div class=block-left>

    <div style="margin-top:3px; font-size:13px;"><b><a href="https://www.topmovingreviews.com" style="color:#999999;" >

<i class="fa fa-home" aria-hidden="true"></i>

</a>, <a href="https://www.topmovingreviews.com/moving-company.php" style="color:#999999;" >

Movers</a>, <a  style="color:#999999;"><?php echo  str_replace('-', ' ',$res_company['title']);?></a></b></div>

<div class="row " style="margin-top:20px">

<!--Review Summary 18-05-2020-->
    <?php 
    $query_summary = "SELECT id,rating FROM reviews where  company_id=$company_id";
    $result_summary = mysql_query($query_summary);
    // $res_summary=mysql_fetch_assoc($result_summary);
    $res_sumarray = [];
    while($res_summary=mysql_fetch_assoc($result_summary)){
      $res_sumarray[] = $res_summary;
    }
    $counts = array_count_values(array_column($res_sumarray, 'rating'));
        $max = 0;
        $p_one = "";
        $p_two = "";
        $p_three = "";
        $p_four = "";
        $p_five = "";
        foreach ($counts as $key => $value) {
            
            
            switch ($key) {
                case 1:
                    $p_one = $value;
                case 2:
                    $p_two = $value;
                case 3:
                    $p_three = $value;
                case 4:
                    $p_four = $value;
                //case 5:
                default:
                    $p_five = $value;
            }
        }
        
    $round = 1;
    $num = count($res_sumarray);
    $p_oneavg = "";
    $p_twoavg = "";
    $p_threeavg = "";
    $p_fouravg ="";
    $p_fiveavg ="";
    if($num > 0){
        $p_oneavg = round($p_one/$num*100, $round);
        $p_twoavg = round($p_two/$num*100, $round);
        $p_threeavg = round($p_three/$num*100, $round);
        $p_fouravg = round($p_four/$num*100, $round);
        $p_fiveavg = round($p_five/$num*100, $round);
    }
    // $max = 0;
    foreach ($res_sumarray as $rate => $count) {
            $max = $max+$count['rating'];
    }
    
    $avg_rating = '';
    if($num > 0) $Average_rating = floatval($max / $num);
    
    $avg_rating =  number_format((float)$Average_rating, 1, '.', '');
    
    ?>
<!--Review Summary 18-05-2020-->
    <div class="col-md-6 reviiie">

        <h3>Review Summary

</h3>







<div class="progress-custom">

    

      <div class="progress-value">

        5

    </div>

      <div class="progress"> 

      

  <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_fiveavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>



</div>

</div>



   <div class="progress-custom">

    

      <div class="progress-value">

        4

    </div>

      <div class="progress"> 

      

  <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_fouravg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>



</div>

</div>

<div class="progress-custom">

    

      <div class="progress-value">

        3

    </div>

      <div class="progress"> 

      

  <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_threeavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>



</div>

</div>

<div class="progress-custom">

    

      <div class="progress-value">

        2

    </div>

      <div class="progress"> 

      

  <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_twoavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>



</div>

</div>

<div class="progress-custom">

    

      <div class="progress-value">

        1

    </div>

      <div class="progress"> 

      

  <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_oneavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>



</div>

</div>





</div>



<div class="col-md-6">

  <a class="btn btn-grey pull-right " href="https://www.topmovingreviews.com/add_reviews.php?company_id=<?php echo $res_company['id'];?>">WRITE A REVIEW</a>

    <div class="totals-wrap text-align:center">

                        <div class="average">

                            <?php echo $avg_rating ?>

                        </div>

                        <div class="count">

                            <?php echo $num; ?> reviews                        </div>

                    </div>

    

</div>

</div>

<p class="more-info text-center"><strong>Your trust is our top concern</strong>, so businesses can't pay to alter or remove their reviews.</p>







<div class=popular-listing-reviews style="margin-top:40px">

<!--<h3 class=popular-listing-title>

<span class=write-1 style="font-size:26px;"><a href="https://www.topmovingreviews.com/add_reviews.php?company_id=<?php echo $res_company['id'];?>">WRITE A REVIEW</a></span> About <strong><?php echo  $res_company['title'];?></strong>

</h3>-->

</div>

<div class="row"><div class="col-md-6"><b>(<?php echo  $res_company['count(*)'];?> Reviews) for  <?php echo  $res_company['title'];?></b></div>

<div class=" col-md-6">

<div style=text-align:right>

<div class=filter_by1>

<label>Sort by

<select style="border:0; background:none; cursor:pointer;color: #3b65a7; padding: 0 5px 0 0;font-weight: bold;font-size:14px;">

	<option value="newest">Newest Reviews</option>

	<option value="oldest">Oldest Reviews</option>

	<option value="best">Highest Rated</option>

	<option value="worst">Lowest Rated</option>

</select></label>

</div></div>

</div>

</div>





<?php 

 

$adjacents = 3;

$tbl_name='reviews';

$query = "SELECT COUNT(*) as num FROM $tbl_name where  company_id=$company_id";

$total_pages = mysql_fetch_array(mysql_query($query));

$total_pages = $total_pages['num'];



/* Setup vars for query. */

$targetpage = "movers.php"; 	//your file name  (the name of this file)

$limit = 10; 	

 $getpage=split("_",$_GET['compname'],2);	

 $countarraypage=count($getpage);

$page=$getpage[$countarraypage-1];





						//how many items to show per page

/*$page = isset($_GET['page'])?$_GET['page']:1;

*/

if(is_numeric($page)){

 $page = $page;

 $compname=$getpage[0];

 }

else{$page=1;$compname=$_GET['compname'];}

if($page) 

	$start = ($page - 1) * $limit; 			//first item to display on this page

else

	$start = 0;								//if no page var is given, set start to 0



/* Get data. */

$sql = "SELECT * FROM $tbl_name  where  company_id=$company_id order by str_to_date(date,'%b %d, %Y') desc LIMIT $start, $limit";

$result = mysql_query($sql);



/* Setup page vars for display. */

if ($page == 0) $page = 1;					//if no page var is given, default to 1.

$prev = $page - 1;							//previous page is page - 1

$next = $page + 1;							//next page is page + 1

$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.

$lpm1 = $lastpage - 1;						//last page minus 1



/* 

	Now we apply our rules and draw the pagination object. 

	We're actually saving the code to a variable in case we want to draw it more than once.

*/

$pagination = "";

if($lastpage > 1)

{	

	$pagination .= "<div class=\"pagination\"  ><ul>";

	//previous button

	if ($page > 1) {

		$pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname"."_"."$prev-$_GET[id]/\">Prev 10</a></li>";

	

	



}

		$pagination.= "<li> <span class=\"pagination1\">$company_review_number"." Reviews</span></li>";

	

			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

			{

				/*if ($counter == $page)

					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname_$counter-$_GET[id]/\">$counter</a></li>";	*/				

			}



	//next button

	if ($page < $counter - 1) 

		$pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname"."_"."$next-$_GET[id]/\">Next 10</a></li>";

	/*else

		$pagination.= "<li><span class=\"pagination1\">-></span></li>";*/

	$pagination.= "</ul></div>\n";		

}



while($res_reviews=mysql_fetch_array($result))

{



?>



<div class="reviewbox reviewbox-wid newreviewbox" style="margin-top:0px;">

<div class=review-tag>

    <div class="row">

        <div class="col-md-8"><div class=review-tag-left>

<!--<i class="fa fa-user"></i>

--><p class=review-tag-name><b><?php echo $res_reviews['author']; ?></b><br/>

<span style="font-size:12px"><?php echo date('M d,Y',strtotime($res_reviews['date'])); ?></span>

</p>





</div></div>

<div class="col-md-4 text-right"><p class=stars>

<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_reviews["rating"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

</p></div>

    </div>







<div class=review-tag-right>



</div>

</div>

<p class=review-place>

<!--<div class=truck></div>--><?php if(strlen($res_reviews['move_location_text']) > 0){echo $res_reviews['move_location_text'];}?>

</p>

<div class="row m-top-35">

    <div class="col-md-12"><p class="review-para review-para-1 more ">

<?php echo $res_reviews['text']; ?> </p>

<div class=revbtn>

<p class=sharebtn style="cursor:pointer;" onClick="window.open('https://www.topmovingreviews.com/share.php?comp_name=<?php echo  $res_company['title'];?>&id=<?php echo  $res_company['id'];?>','popUpWindow','height=220,width=520,left=400,top=200,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">

<i class="fa fa-share"></i> Share

</p>

<!--<span class="form-control col-md-2 pull-right text-center m-bttom">

<?php echo date('M d,Y',strtotime($res_reviews['date'])); ?></span>-->

<?php /*?><?php if($res_reviews['wants_to_contact']==1) { ?><?php */?>

<!--<p class=cntbtn style="cursor:pointer;" onClick="window.open('https://www.topmovingreviews.com/contact_user.php?author=<?php echo $res_reviews['author']; ?>','popUpWindow','height=460,width=620,left=400,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">

<i class="fa fa-envelope-o"></i> Contact

</p>--><?php /*?><?php } ?><?php */?>

</div></div>

</div>

</div>



<?php } ?>



<!--<div class=load_more_cls><a href="#">Load More</a>&ensp;<i class="fa fa-angle-down"></i></div>-->

<?=$pagination?>

<!--<div  style="float: left;font-size: 13px; margin: 10px 14px 0;">Page 1 of 1</div>

<div  class="pagination" style="float: right; width: auto; padding-top:13px;">

		<ul style="list-style-type: none;" >

		<li ><span style="background:#F4F4F4; padding:6px 6px 0px 6px; font-weight:bold;" >1</span></li>

		<li ><span style="background:#F4F4F4; padding:6px 6px 0px 6px; font-weight:bold;" >2</span></li>

		</ul>

	</div>-->



</div>

<div class=block-right>



<?php 



$sql_dot="select power_units,usdot_number,mc,safety_url,date_format(granted_date,\"%m-%d-%Y\") as granted_date from company_dot_data where  company_name= '$res_company[title]'";



$query_dot=mysql_query($sql_dot);



$res_dot=mysql_fetch_assoc($query_dot);



?>



<div class=mover-details>

<h3>Mover Details Moving Company Business Info</h3><br><br>

<table style="width:100%">

<tr>

<td class="newtd new-pad"><b>In Business Since</b></td><td class="newtd new-pad"><b><?php echo $res_dot['granted_date'];?></b></td>

</tr>



<tr>

<td class="newtd new-pad"><b>USDOT#</b></td><td class="newtd new-pad"><a href="<?php echo $res_dot['safety_url'];?>" target="_blank" style="color:#0000FF;"><b><?php echo $res_dot['usdot_number'];?></b></a></td>

</tr>

<tr>

<td class=new-pad><b>ICC MC Number</b></td><td class=new-pad><a href="<?php echo $res_dot['safety_url'];?>" target="_blank" style="color:#0000FF;"><b><?php echo $res_dot['mc'];?></b></a></td>

</tr>

</table>

</div>





<div class=ppl_viewed>

<h3>Other Movers Nearby</h3>

<div>

<?php 



$company = new company();

$cityname=$res_company['city'];

$statename=$res_company['state'];

//select id,company_name,city,state from companies where viewed=1 order by Rand() limit 4  i have  removed viewed for temporary on 18022018

  /*address like '%$cityname%' and address like '%$statename%'  and title!='$res_company[title]'*/

 $sql_pplview="select id,title,address,logo,city,state from companies where rating>3 and state ='$statename' order by Rand() limit 4";



$query_pplview=mysql_query($sql_pplview);



while($res_pplview=mysql_fetch_assoc($query_pplview)){

$compnay_address1=explode(",",$res_pplview['address']);

$countarray1=count($compnay_address1);



$average = $company->get_average_reviews_count($res_pplview["id"]);



$reviews_count = $company->get_company_reviews_count($res_pplview["id"]);

$comp_name = str_replace(' ', '-', $res_pplview['title']);

?>

<?php /*?><div class=ppl_view>

<div class=ppl_view_left><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/">

<img src="https://www.topmovingreviews.com/mmr_images/smalllogos/logo_<?php if ($res_pplview["logo"]==NULL){echo "no";} else echo $res_pplview["id"];?>.jpg"  alt="<?php echo $res_pplview["id"];?>" style="width:47px !important; height:25px !important;"></a>

</div>

<div class=ppl_view_right>

<div class=ppl_lft style="width:160px;">

<h4><span><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/"> <?= $res_pplview["title"]; ?></a></span></h4>

<p><?=$res_pplview['city'].", ".$res_pplview['state'];   ?>, USA</p>

</div>

<div class=ppl_ryt>

<p class=stars>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

</p>

<span style="font-size:13px;"><?php echo $reviews_count;?> Reviews</span>

</div>

</div>

</div><?php */?>





<div class="wrap">

    <div class="left"><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/">

<img src="https://www.topmovingreviews.com/mmr_images/smalllogos/logo_<?php if ($res_pplview["logo"]==NULL){echo "no";} else echo $res_pplview["id"];?>.jpg"  alt="" style="width:47px !important; height:25px !important;"></a></div>

    <div class="right"><p class="stars star-1">

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($average["avg(rating)"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

</p>

<span style="font-size:13px;"><?php echo $reviews_count;?> Reviews</span></div>

    <div class="center"><h4><span><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/"> <?= $res_pplview["title"]; ?></a></span></h4><p><?=$res_pplview['city'].", ".$res_pplview['state'];   ?></p></div>

</div>





<?php  }  ?>

</div>



</div>

<div style="margin-top:10px;" >

<?php 

$state_code=$res_company['state'];



$sql_state=mysql_query("select name from states where state_code='$state_code' and usa_state=1");

$res_state=mysql_fetch_array($sql_state);

?>

See more <a href="https://www.topmovingreviews.com/moving-companies/<?php echo trim($res_company['city']).str_replace(' ', '-',$compnay_address[$countarray-2]); ?>/" style="font-weight:bold; color:#839AEB;">movers in <?php echo  $res_company['city'];?>, <?php echo $state_code; ?></a><br>Explore more

<a href="https://www.topmovingreviews.com/usa/<?php echo $res_state['name']."-movers-".$state_code; ?>/" style="font-weight:bold; color:#839AEB;;"><?php echo $res_state['name'];?> Movers</a></div>

<div class=cmprev>

<h2>Search Moving Company</h2><br>

<div class="search-box"><form name="frm_search" action="https://www.topmovingreviews.com/searchcompany.php" method="post" class="seach22">

<input class="seach22" value="" name=company_search>

<button type=submit><i class="fa fa-search"></i>&nbsp;<span class="hiderseach">Search</span></button>

</form>

</div>

</div>

<div class=nxt><h3>Read before hiring</h3></div>

<div class="row newrow">

<div class="col-md-12">

  <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company-1.jpg"/>

          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/moving-checklist-how-to-prepare-for-the-moving-day/"><h4>How to Prepare for the Moving Day</h4></a></div>

          <p>Most people are aware on how stressful the moving day can be. To help you lessen the load, we created a comprehensive ... </p>

            

</div>     

</div>

<!--end1-->

<div class="row newrow">

<div class="col-md-12">

 <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company.jpg"/>

          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/tips-on-how-do-you-find-a-moving-company/"><h4>Tips on how do you find a moving company.</h4></a></div>

          <p>Moving is a very stressful and also pricey experience, as well as if you do not take precaution it can promptly become ... </p>

            

</div>     

</div>

<!--end1-->

<div class="row newrow">

<div class="col-md-12">

<img class="img-left" src="https://www.topmovingreviews.com/images/packing-guide.jpg"/>

          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/essential-tips-when-packing-your-closet-3/"><h4>Packing Guide</h4></a></div>

          <p>Packing can be an intensely challenging step when preparing to move. It is during this time that most damages to goods...</p>

            

</div>     

</div>

<!--end1-->

<div class="row newrow">

<div class="col-md-12">

 <img class="img-left" src="https://www.topmovingreviews.com/images/change.jpg"/>

          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/moving-checklist-when-changing-your-address-3/"><h4>Change Your Address When You Move</h4></a></div>

          <p>Before you can settle in, you have to ensure that your mail is updated to continue receiving your regular mail without any...</p>

            

</div>     

</div>



</div>

</div><div></div>

</div>



<?php include 'footer.php'; ?>

</div>



<script>







 $(document).ready(function () {















        var showChar = 950;







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







        var showChar = 950;







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
<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://www.topmovingreviews.com/responsive/popper.min.js"></script>

  <script src="https://www.topmovingreviews.com/responsive/bootstrap-datetimepicker.min.js"></script>

  <script src="https://www.topmovingreviews.com/responsive/bootstrap.min.js"></script>


<script src="https://www.topmovingreviews.com/responsive/demo.js"></script>
<!--<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js" async></script>-->
<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js" ></script>
<!-- font awesome for starts--->





</body>

</html>


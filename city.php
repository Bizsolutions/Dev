<?php header('Content-Type: text/html; charset=windows-1252');



require 'core/database.class.php';



if(isset($_GET['cityname']))



$cityname=str_replace("-"," ",$_GET['cityname']);



if(isset($_GET['stateshrtname']))



$stateshrtname=$_GET['stateshrtname'];







if(isset($_GET['zipcode']))



$zipcode=$_GET['zipcode'];





$sql_state_name="select name from states where state_code='$stateshrtname'";



$mysql_query_state=mysql_query($sql_state_name);



$res_state_name=mysql_fetch_array($mysql_query_state);



$state_name=$res_state_name['name'];



?>





<!DOCTYPE HTML>



<html lang="en">



    <head>



        <title>Moving  Reviews  for  Top  Rated  <?php echo ucfirst($cityname);  ?> ,  <?php echo strtoupper($stateshrtname);  ?>  Movers    </title>



        <meta name="description" content="Looking  for  Top  <?php echo ucfirst($cityname);  ?>    Movers.  Compare  and  get  an  estimate  by  recommended  Moving  Companies,  Movers  Reviews  by  real  customers  pre-screened  and  fully-insured.  "/>



        <meta name="keywords" content="Moving Companies, Movers" />



     



       <meta http-equiv="X-UA-Compatible" content="IE=edge" />



      <meta name="HandheldFriendly" content="true">



      <meta property=og:title content="Top Movers Reviews - Moving Companies ">



      <meta property=og:description content="Research  for  Top   Moving  Companies  for  your  Upcoming  Move.  Read  Top  Movers  Reviews  and  Rating.  Choose  Licensed  and  insured  Movers  to  service  your  Moving  needs.">



      <meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">



      <meta property=og:url content=":https://www.topmovingreviews.com/moving-companies/Denver-CO-80264">



      <meta name=dc.language content=US>



      <meta name=dc.subject content="NY Movers">



      <meta name=DC.identifier content="/meta-tags/dublin/">



      <meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>



      <meta name=HandheldFriendly content=true>







      <meta name="viewport" content="width=device-width, initial-scale=1.0">







        <link rel="stylesheet" type="text/css" href="https://topmovingreviews.com/css/style.css">



<link href="https://topmovingreviews.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">



         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


	  <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>



<script>





$(document).ready(function(){




	$("#MovingTo").keyup(function(){



		$.ajax({



		type: "POST",



		url: "../../process/readService.php",



		data:'MovingTo='+$(this).val(),



		beforeSend: function(){



			$("#MovingTo").css("background","#FFF no-repeat 165px");



		},



		success: function(data){



			$("#suggesstion-box1").show();



			$("#suggesstion-box1").html(data);



			$("#MovingTo").css("background","#FFF");



		}



		});



	});



});







function selectCountry1(val) {



$("#MovingTo").val(val);



$("#suggesstion-box1").hide();



}







</script>



<script>
function myFunction() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>









		<script type="application/ld+json">{"@context":"https://schema.org","@type":"Organization","url":"https://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>

<style>
.pagination1 {
    float: left;padding: 8px 16px;line-height: 20px;text-decoration: none;background-color: #fff;border:none !important;border-left-width: 0;font-weight:bold;}
.pagination li {display: inline;float:left;}
.pagination a {
    color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;}
  .pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}
</style>

    </head>



    <body onLoad="doOnLoad();">



        <div class="wrapper">



            <?php include 'newheaderwithoutbanner.php'; ?>



            <div class="content">



                <div class="block-left">



                    <div class="bread-crumbs">



                        <span class="bread-name"><a href="https://www.topmovingreviews.com/index.php">Home</a> <span class="arrow">></span> <a href="https://www.topmovingreviews.com/moving-company.php">Movers </a><span class="arrow">></span> </span><span class="current"> <a href="https://www.topmovingreviews.com/moving-companies/<?php echo ucfirst($cityname)."-".strtoupper($stateshrtname)."-".$zipcode; ?>/"><?php echo ucwords($cityname); ?> Movers</a></span>



                    </div>



                    <div class="popular-listing ">



                        <div>



                        <div class="popular-left">



<?php

if(file_exists('https://www.topmovingreviews.com/mmr_images/city_min_images/'.str_replace(" ","_",ucfirst($cityname))."_".strtoupper($stateshrtname).'.jpg')) {

?>

                           <img src="https://www.topmovingreviews.com/mmr_images/city_min_images/<?php echo str_replace(" ","_",ucfirst($cityname))."_".strtoupper($stateshrtname); ?>.jpg" alt="<?php echo str_replace(" ","-",ucfirst($cityname))." ".strtoupper($stateshrtname); ?> Movers Moving Companies">

<?php } else { ?> <img src="https://www.topmovingreviews.com/mmr_images/city_min_images/Denver_CO.jpg" alt="<?php echo str_replace(" ","-",ucfirst($cityname))." ".strtoupper($stateshrtname); ?> Movers Moving Companies"><?php }  ?>

                        </div>



                        <div class="popular-right" style="margin-top:70px !important;">



						<h1 class="popular-listing-title" style="text-align:left !important;">


2020 Top  movers in <?php echo ucwords($cityname).", ".strtoupper($stateshrtname); ?>
                        



                        </h1>



                        </div>



						</div>



                    </div>







              










<hr/>
                    <div class="row">

<div class="col-md-8">

                 	<div class="popular-listing popular-listing-1">



                        <h2 class="popular-listing-title" style="margin-top:0px !important;" >



                          Best  <?php  echo ucwords($cityname); ?> Movers Reviews



                        </h2>



                    </div>

</div>

<div class="col-md-4 text-right"><form  name="frm_filter" action='https://www.topmovingreviews.com/moving-companies/<?php echo $cityname."-".$stateshrtname."-".$zipcode; ?>/' method='post'>



                            <div class="filter_by1" >



                                <label>Sort by



                                    <select name="filter" style="border:0; background:none; cursor:pointer;color: #3b65a7; padding: 0 5px 0 0;font-weight: bold;font-size:14px;" onChange="document.frm_filter.submit();"><option>Select</option>



                                        <option value="date_saved asc" <?php if ($_REQUEST['filter']=="date_saved asc") {?> selected="selected" <?php  } ?>>Newest Reviews</option>



                                        <option value="date_saved desc" <?php if ($_REQUEST['filter']=="date_saved desc") {?>  selected="selected"  <?php  } ?>>Oldest Reviews</option>



                                        <option value="rating desc" <?php if ($_REQUEST['filter']=="rating desc") { ?>  selected="selected" <?php  } ?>>Highest Rated</option>



                                        <option value="rating asc" <?php if ($_REQUEST['filter']=="rating asc") { ?>  selected="selected" <?php  } ?>>Lowest Rated</option>



                                    </select></label>



                            </div></form></div>

   			



                    </div>







<?php 







$adjacents = 3;



$tbl_name='company_state';



$query = mysql_query("SELECT *  FROM    companies  , reviews    where companies.id=reviews.company_id and city in ('$cityname') group by companies.id"); 



$total_pages = mysql_num_rows($query);







/* Setup vars for query. */



$limit = 10; 								//how many items to show per page
$arr = explode("?",$_SERVER['REQUEST_URI']);
$isID = false; 
$Ispage_num = 0;

if(isset($arr[1])){
    $isID =  true;
  $pCount=  explode("=",$arr[1]);
  $Ispage_num = $pCount[1];
}

if($isID)

 $page =$Ispage_num;

else


$page=1;
if($page) 

	$start = ($page - 1) * $limit; 			//first item to display on this page

else

	$start = 0;								//if no page var is given, set start to 0
/* Get data. */

if(isset($_REQUEST['filter']))
{

$filter=$_REQUEST['filter'];
$sql = "SELECT title,address,id,rating,phone,logo  FROM  companies      where   city in ('$cityname') group by companies.id order by $filter LIMIT $start, $limit";

} else {
  $sql ="SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and city in ('$cityname')  group by companies.id order by   companies.rating desc,count(*) desc  LIMIT $start, $limit";
 //$sql = "SELECT title,address,id,rating,phone  FROM  companies      where   address like '% $cityname,%' LIMIT $start, $limit";
}

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
$cityname = str_replace(' ', '-', $cityname);


$zipcode = isset($_GET['zipcode'])?$_GET['zipcode']:'';
if($lastpage > 1) {	
	$pagination .= "<div class=\"pagination\"  ><ul>";
	
	//previous button
	if ($page > 1) 
		$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$zipcode?page=$prev\">Prev</a></li>"; 
	else
		$pagination.= "<li><span class=\"pagination1\">Prev</span></li>";	

	//pages	



	if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
    {	

		for ($counter = 1; $counter <= $lastpage; $counter++)

		{
			if ($counter == $page)
				$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
			else
				$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$zipcode?page=$counter\">$counter</a></li>";					
		}
	}



	elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some



	{



		//close to beginning; only hide later pages



		if($page < 1 + ($adjacents * 2))		



		{



			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)



			{



				if ($counter == $page)



					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";



				else



					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$zipcode/\">$counter</a></li>";					



			}



			$pagination.= "<li><span>...</span>";



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$lpm1/\">$lpm1</a></li>";



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$lastpage/\">$lastpage</a></li>";		



		}



		//in middle; hide some front and some back



		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))



		{



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-1/\">1</a></li>";



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-2/\">2</a></li>";



			$pagination.= "<li><span>...</span></li>";



			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)



			{



				if ($counter == $page)



					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";



				else



					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$zipcode/\">$counter</a></li>";					



			}



			$pagination.= "<li><span>...</span></li>";



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$lpm1/\">$lpm1</a></li>";



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$lastpage/\">$lastpage</a></li>";		



		}



		//close to end; only hide early pages



		else



		{



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-1/\">1</a></li>";



			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-2/\">2</a></li>";



			$pagination.= "<li><span>...</span></li>";



			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)



			{



				if ($counter == $page)



					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";



				else



					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$zipcode/\">$counter</a></li>";					



			}



		}



	}



	



	//next button



	if ($page < $counter - 1) 



		$pagination.= "<li><a href=\"https://www.topmovingreviews.com/moving-companies/$cityname-$stateshrtname-$zipcode?page=$next\">Next</a></li>";



	else



		$pagination.= "<li><span class=\"pagination1\">Next</span></li>";



	$pagination.= "</ul></div>\n";		



}







while($res_comp_city=mysql_fetch_array($result))



{









$sql_reviewcount=mysql_query("select * from reviews where company_id= '$res_comp_city[id]'");



$res_reviewcount=mysql_num_rows($sql_reviewcount);



$compnay_address=explode(",",$res_comp_city['address']);



$countarray=count($compnay_address);



$compnay_address_zip=explode(" ",$compnay_address[$countarray-2]);



$comp_name = str_replace('/','-',str_replace(' ', '-', $res_comp_city["title"]));







?>



				<div class="row" style="padding-top: 40px; " onClick="window.location.href='https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_comp_city["id"]; ?>/'">



<div class="col-md-3">

	<?php

// 		if($res_comp_city["logo"]!=NULL)

// 		{

// 			if(getimagesize("https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_comp_city["id"].".jpg"))

// 			{

// 				$image_file = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_comp_city["id"].".jpg";

// 			}

// 			else  if(getimagesize("https://www.topmovingreviews.com/company/logos/logo_".$res_comp_city["id"].".jpg"))

// 			{

// 				$image_file = "https://www.topmovingreviews.com/company/logos/logo_".$res_comp_city["id"].".jpg";

// 			}

// 			else

// 			{

// 				$image_file = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";

// 			}

// 		}

// 		else

// 		{

// 			$image_file = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";

// 		}




/*
$mmr_images = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_comp_city["id"].".jpg";

$comp_images = "https://www.topmovingreviews.com/company/logos/logo_".$res_comp_city["id"].".jpg";

$no_images = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";



// echo $data = getimagesize($mmr_images).'hi';

  if($res_comp_city["logo"]!=NULL)

    {

      if($data = getimagesize($mmr_images))

      {

        $image_file = $mmr_images;

      }

      else  if($data = getimagesize($comp_images))

      {

        $image_file = $comp_images;

      }

      else

      {

        $image_file = $no_images;

      }

    }

    else

    {

      $image_file = $no_images;

    }*/
		  $img =  $res_comp_city["logo"];
          $mmrimg = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_comp_city["id"].".jpg";
          $compimg = "https://www.topmovingreviews.com/company/logos/logo_".$res_comp_city["id"].".jpg";
      
          if($res_comp_city["logo"] != NULL){
              
              if(@getimagesize($mmrimg) != '' && stristr($res_comp_city["logo"], "topmovingreviews.com"))
              {
                  
                  $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_comp_city["id"].".jpg";
                  
              }
              
              else if(@getimagesize($compimg) != '' && stristr($res_comp_city["logo"], "topmovingreviews.com"))
              {
                  
                  $logo_image = "https://www.topmovingreviews.com/company/logos/logo_".$res_comp_city["id"].".jpg";
                  
              }else if(stristr($res_comp_city["logo"], "mymovingreviews.com"))
              {
                  $logo_image = $img;
                  
              }
               
              else{
                  $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
              }
              
          }
          else {
              $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
              
          }

	
	
	

	?>

	<img src="<?php echo $logo_image;?>" height="80" width="190" ></div>



<div class="col-md-9" >



<h4  style="text-align:left!important;"><a style="color:#000000;" href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_comp_city["id"]; ?>/"><?php echo $res_comp_city["title"]; ?></a></h4>



<p class=stars>



<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



</p>



<span style="color:#000000" >(<?php echo $res_reviewcount; ?> Reviews)</span><br>



<span style="color:#000000"><?php echo $res_comp_city["address"]; ?></span>

<div style="clear: both; padding-top: 8px;">



<?php  /*$sql_review="select * from reviews where company_id=$res_comp_city[id]";



	  $query_review=mysql_query($sql_review);	



	  $res_review=mysql_fetch_assoc($query_review);*/



	  echo substr($res_comp_city["text"],0,140)."...";



 ?>



		



</div>





</div>



<div style="clear:both"></div>





</div>	



					



					







<?php }

require 'core/functions.php';

$cityname = str_replace('-', ' ', $cityname);


$result = getNearbyMoversByCity($cityname,$stateshrtname,30,200,10);



/*var_dump($result);*/

	



	

	for($i=0;$i<count($result);$i++){

/*$i=0;*/

foreach ($result[$i] as $key => $value)

{

if($key=='id')

 $company_id=$value;

if($key=='address')

 $address=$value;

if($key=='title')

 $company_name=$value;

if($key=='logo')

 $logo=$value;

if($key=='rating')

 $rating=$value;

if($key=='text')

$text=$value;				

			

	}			

				$sql_reviewcount=mysql_query("select * from reviews where company_id= '$company_id'");



$res_reviewcount=mysql_num_rows($sql_reviewcount);



$compnay_address=explode(",",$address);



$countarray=count($compnay_address);



$compnay_address_zip=explode(" ",$compnay_address[$countarray-2]);



$comp_name = str_replace('/','-',str_replace(' ', '-', $company_name));







?>



				<div class="row" style="padding-top: 40px; margin-bottom:60px;" onClick="window.location.href='https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $company_id; ?>/'">



<div class="col-md-3">

	<?php

 	

 		    $img1 =   $logo;
            $mmrimg1 = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$company_id.".jpg";
            $compimg1 = "https://www.topmovingreviews.com/company/logos/logo_".$company_id.".jpg";
      
          if($logo != NULL){
              
              if(@getimagesize($mmrimg1) != '' && stristr( $logo, "topmovingreviews.com"))
              {
                  
                  $logo_image1 = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$company_id.".jpg";
                  
              }
              
              else if(@getimagesize($compimg1) != '' && stristr( $logo, "topmovingreviews.com"))
              {
                  
                  $logo_image1 = "https://www.topmovingreviews.com/company/logos/logo_".$company_id.".jpg";
                  
              }else if(stristr( $logo, "mymovingreviews.com"))
              {
                  $logo_image1 = $img1;
                  
              }
               
              else{
                  $logo_image1 = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
              }
              
          }
          else {
              $logo_image1 = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
              
          }



	?>

	<img src="<?php echo $logo_image1;?>" height="80" width="190" ></div>



<div class="col-md-9" >



<h4  style="text-align:left!important;"><a style="color:#000000;" href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $company_id; ?>/"><?php echo $company_name; ?></a></h4>



<p class=stars>



<span class="fa fa-star  <?php if(round($rating)>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($rating)>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($rating)>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($rating)>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



<span class="fa fa-star  <?php if(round($rating)>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>



</p>



<span style="color:#000000" >(<?php echo $res_reviewcount; ?> Reviews)</span><br>



<span style="color:#000000"><?php echo $address; ?></span>



</div>



<div style="clear:both"></div>



<div style="clear: both; padding-top: 8px;">



<?php  



	  echo substr($text,0,140)."...";



 ?>



		



</div>



</div>	



					





				<?php

				



}		

 ?>







					<?=$pagination?>



					



					<div class="bottomtext">



					



					<?php



							$random=mt_rand(1,5000);



							$sql_get_content=mysql_query("select content from city_content where id='$random' limit 0,1");



							$res_get_content=mysql_fetch_array($sql_get_content);



						 	$city_statecode= "<strong>".ucfirst($cityname).",".strtoupper($stateshrtname)."</strong>";



						 	$moving_companies="<strong>"."Moving Companies"."</strong>";



						 	$movers_in="<strong>"."cheap movers in"."</strong>";



							$cheap_local_moves="<strong>"."local moves"."</strong>";



							$long_distance_movers="<strong>"."long distance movers"."</strong>";



							$top="<strong>"."top"."</strong>";



						?>



						



                            <p ><?php echo str_replace("long distance movers",$long_distance_movers,str_replace("local moves",$local_moves,str_replace("top",$top,str_replace("cheap movers in",$cheap_movers_in,str_replace("Moving Companies",$moving_companies,str_replace("(City), (State)",$city_statecode,$res_get_content['content'])))))) ; ?>



							</p>



							</div>





                </div>



                <div class="block-right">

<div class=cstm_get_quote>

<h6><i>The quick and easy money saver</i></h6>

<form id="frm1" name="frm1" method="post" action=https://www.topmovingreviews.com/quoteform.php autocomplete="off">

<div class=cstm_frm>

<label> <input  class=newinput  placeholder="Moving Date" onFocus="this.value=''"  id="calendarHere"  name="calendarHere" onClick="setFrom();" readonly=""></label>

<label><input class=newinput  placeholder="From Zip" onFocus="this.value=''" name="MovingFrom" id="MovingFrom" maxlength="5" ></label>

<label><input class=newinput  placeholder="To Zip or City, State" onFocus="this.value=''" name="MovingTo" id="MovingTo"></label>
<div id="suggesstion-box1"></div>

<div class=submit-new><a  onClick="javascript:return validate()" style="cursor:pointer;">Continue</a> </div>

</div>

</form>

</div>

<?php 



$sql_dot="select power_units,usdot_number,mc,safety_url,date_format(granted_date,\"%m-%d-%Y\") as granted_date from company_dot_data where  company_name= '$res_company[title]'";



$query_dot=mysql_query($sql_dot);



$res_dot=mysql_fetch_assoc($query_dot);



?>



                    <div class="callfor"><h3>Are  you  a  Moving  Company  </h3><br>



					<span style="font-size:15px;">Find  out  how  you  can  be  in  our  <a href="">Moving List</a></span>.



                        <!--<img src="images/300x600.jpg" width="300px" height="600" alt="city page add"/>-->



                    </div>


<div  class="callfor">
                            <h2>Best movers nearby</h2>
                     <div class="input-group "><form name="frm_zip" action="https://www.topmovingreviews.com/searchzipcode.php" method="post">
	<input type="text" class="form-control" placeholder="Find movers by zip" name="zipcode_search" ></form>
      <span class="input-group-btn">
        <input class="btn btn-search newbtn" name="bt" type="submit" value="Find"  onClick="javascript:document.frm_zip.submit();">
      </span>
</div>
                        </div>




                   <!-- <div class="cmprev">



                        <p>Write a Moving Company Review Here</p>



                        <h4>Company Name</h4>



                        <div class="search-box">



                            <input type="text" placeholder="Looking For..." name="search">



                            <button type="submit"><i class="fa fa-search"></i>&nbsp;Search</button>



                        </div>	







                    </div>-->















                    <div class="cities">



                        <h2 style="line-height:24px;">Movers near <? echo ucwords($cityname);?> by City    </h2>



                       <!-- <div class="search-box">



                            <input type="text" placeholder="Search California's Cities" name="search">



                            <button type="submit"><i class="fa fa-search"></i>&nbsp;Search</button>



                        </div>	-->



                        <div class="city">







                        	    <?php



			 /*  echo "Srikana". $ip = $_REQUEST['REMOTE_ADDR']; 



			    $query = @unserialize(file_get_contents('https://ip-api.com/php/' . $ip));



			    



			    if ($query && $query['status'] == 'success') {



				



				$region = $query['region']; */



				



				$sql_lat="select * from cities_extended where city='$cityname' and state_code='$stateshrtname' limit 0,1";



				$query_lat=mysql_query($sql_lat);



				$res_lat=mysql_fetch_array($query_lat);



				$lat = $res_lat['latitude'];;



				$lon = $res_lat['longitude'];

			

				 $get_city = "SELECT *, ((ACOS(SIN($lat * 3.14 / 180) * SIN(latitude * 3.14 / 180) + COS($lat * 3.14 / 180) * COS(latitude * 3.14 / 180) * COS(($lon - longitude) * 3.14 / 180)) * 180 / 3.14) * 60 * 1.1515) AS distance FROM cities_extended where state_code = '$stateshrtname' and city<>'$cityname' GROUP BY city HAVING distance<='1000' ORDER BY distance ASC limit 0,6";


				if ($result = mysql_query($get_city)) {



				    while ($row = mysql_fetch_assoc($result)) {



					



					 $sql_state_zip="select zipcode,city,state  from companies  where city='$cityname' limit 0,1";



					$query_state_zip=mysql_query($sql_state_zip);



					$res_state_zip=mysql_fetch_array($query_state_zip); 

					?>

					  <div class='city-box' >


		  <a href="https://www.topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",$row['city'])."-".$row['state_code']."-".$row['zip']; ?>/" style='text-transform: capitalize;' title="<?php echo str_replace("-", " ", $row['city']);?>">



		  



		<?php echo round($row['distance'],0)."<span style=font-size:11px>mi</span>"."   ". substr(str_replace("-", " ", $row['city']),0,13); ?></a>



			



			</div>



					



					<?php


					
				    }

				}

			    /*} else {

				echo 'Unable to get location';

			    }*/

			    ?>



							<div class="city-anchr">



                             <?php /*?>   <a href="https://www.topmovingreviews.com/state-city.php?state_code=<?php echo $stateshrtname; ?>" style='text-transform: capitalize;' title="<?php echo str_replace("-", " ", $row['city']);?>">



                                    <u>See All Cities</u>&ensp;<i class="fa fa-angle-right" aria-hidden="true"></i>



                                </a><?php */?>



                            </div>




<div class="row">
  <div class="col-md-12">
      
    <div class="collapse multi-collapse" id="multiCollapseExample1">
      <div class='city-box' >

		  
		   <?php
		  
		  
		   $get_city = "SELECT *, ((ACOS(SIN($lat * 3.14 / 180) * SIN(latitude * 3.14 / 180) + COS($lat * 3.14 / 180) * COS(latitude * 3.14 / 180) * COS(($lon - longitude) * 3.14 / 180)) * 180 / 3.14) * 60 * 1.1515) AS distance FROM cities_extended where state_code = '$stateshrtname' and city<>'$cityname' GROUP BY city HAVING distance<='1000' ORDER BY distance ASC limit 6,10";


				if ($result = mysql_query($get_city)) {



				    while ($row = mysql_fetch_assoc($result)) {



					



					 $sql_state_zip="select zipcode,city,state  from companies  where city='$cityname' limit 0,1";



					$query_state_zip=mysql_query($sql_state_zip);



					$res_state_zip=mysql_fetch_array($query_state_zip); 

					?>

					  <div class='city-box' >


		  <a href="https://www.topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",$row['city'])."-".$row['state_code']."-".$row['zip']; ?>/" style='text-transform: capitalize;' title="<?php echo str_replace("-", " ", $row['city']);?>">



		  



		<?php echo round($row['distance'],0)."<span style=font-size:11px>mi</span>"."   ". substr(str_replace("-", " ", $row['city']),0,13); ?></a>



			



			</div>



					



					<?php


					
				    }

				} ?>

			</div>
    </div>
  </div>

</div>
<p>
  <a class="hidden-button" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1"><u>See More</u>&ensp;<i class="fa fa-angle-right" aria-hidden="true"></i></a>
  </p>




                        </div>



					



					</div>







                    <!--<div class="find">



                        <h4>Find Movers near me</h4>



                        <div class="search-box">



                            <input type="text" placeholder="Enter Zip" name="search">



                            <button type="submit"><i class="fa fa-search"></i>&nbsp;Find</button>



                        </div>	



                    </div>-->





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



            </div>



        </div>







        <?php include 'footer.php'; ?>



        <script>



            window.onscroll = function () {



                myFunction()



            };







            var navtop = document.getElementById("myHeader");



            var sticky = navtop.offsetTop;







            function myFunction() {



                if (window.pageYOffset >= sticky) {



                    navtop.classList.add("sticky");



                } else {



                    navtop.classList.remove("sticky");



                }



            }



        </script>







    </body>



</html>
<?php ob_start();
header('Content-Type: text/html; charset=windows-1252');
require 'core/database.class.php';

if(isset($_GET['statename']))
 $statename=str_replace("-"," ",$_GET['statename']);


if(isset($_GET['stateshrtname']))
 $stateshrtname=$_GET['stateshrtname'];



$sql_state=mysqli_query($link,"select id,state_code from states where name='$statename' and usa_state=1");
$res_state=mysqli_fetch_array($sql_state);
$state_id=$res_state['id'];
$state_code=$res_state['state_code'];

/*$sql_company="SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";
$query_company=mysqli_query($sql_company);
$res_company=mysqli_fetch_array($query_company);
$compnay_address=explode(",",$res_company['address']);
$countarray=count($compnay_address);
*/

?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Top <?php echo ucwords($statename);?> Movers Reviews - <?php echo strtoupper($stateshrtname);?> Moving Companies</title>
        <meta name="description" content="Research  for  Top  <?php echo ucwords($statename);?>  Moving  Companies  for  your  Upcoming  Move.  Read  Top  <?php echo strtoupper($stateshrtname);?>  Movers  Reviews  and  Rating.  Choose  Licensed  and  insured  Movers  to  service  your  Moving  needs."/>
        <meta name="keywords" content="Moving Companies, Movers, Reviews" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="HandheldFriendly" content="true">
      <meta property=og:title content="Top Movers Reviews - Moving Companies ">
      <meta property=og:description content="Research  for  Top   Moving  Companies  for  your  Upcoming  Move.  Read  Top  Movers  Reviews  and  Rating.  Choose  Licensed  and  insured  Movers  to  service  your  Moving  needs.">
      <meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">
      <meta property=og:url content=":https://www.topmovingreviews.com/usa/Alabama-movers-AL">
      <meta name=dc.language content=US>
      <meta name=dc.subject content="NY Movers">
      <meta name=DC.identifier content="/meta-tags/dublin/">
      <meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
      <meta name=HandheldFriendly content=true>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
  .fa.fa-pull-left, .fa.pull-left { margin-right: .3em }.fa, .fa-stack { display: inline-block }.fa-fw, .fa-li { text-align: center }
@font-face { font-family: FontAwesome; src: url(../fonts/fontawesome-webfont.eot); src: url(../fonts/fontawesome-webfont.eot) format('embedded-opentype'), url(../fonts/fontwesoame-webfont.ttf) format('woff2'), url(../fontwesoame-webfont.ttf) format('ttf'), url(../fonts/fontwesoame-webfont.ttf) format('truetype'), url(../fonts/fontawesome-webfont.svg?v=4.7.0#fontawesomeregular) format('svg'); font-weight: 400; font-style: normal }

.sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0 }
.sr-only-focusable:active, .sr-only-focusable:focus { position: static; width: auto; height: auto; margin: 0; overflow: visible; clip: auto }
            .block-left {
                width: 70%;
                float: left;
                margin-right: 2.5%;
            }

            .block-right {
                width: 26%;
                float: left;
            }
@media (max-width: 481px)  {.block-left {
                width: 100%;
                float: left;
                margin-right: 2.5%;
            }

            .block-right {
                width: 100%;
                float: left;
             }
             .m-top73 {
    margin-top: 73px;
    font-size:20px !important;
}
}


        </style>
		
		
		
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"https://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>
<style>
#more {display: none;}
</style>
    </head>
    <body onLoad="doOnLoad();">
       <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus imperdiet, nulla et dictum interdum, nisi lorem egestas vitae scel<span id="dots">...</span><span id="more">erisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. In at libero sed nunc venenatis imperdiet sed ornare turpis. Donec vitae dui eget tellus gravida venenatis. Integer fringilla congue eros non fermentum. Sed dapibus pulvinar nibh tempor porta.</span></p>
<button onclick="myFunction()" id="myBtn">See more</button>-->

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
        <div class="wrapper">
            <?php include 'newheaderwithoutbanner.php'; ?>
            <div class="content">
                <div class="block-left">
                    <div class="bread-crumbs">
                        <span class="bread-name"><a href="https://www.topmovingreviews.com/index.php">Home </a><span class="arrow">></span><a href="https://www.topmovingreviews.com/moving-company.php"> Movers </a><span class="arrow">></span> </span><span class="current"><a href=""><?php echo ucwords($statename); ?> Movers</a></span>
                    </div>
                    <div class="popular-listing ">
					<?php
					$sql_topbottom_text="select top_heading,top_text,bottom_text,name from states where state_code='$state_code' and usa_state=1";
					$query_topbottom_text=mysqli_query($link,$sql_topbottom_text);
					$res_topbottom_text= mysqli_fetch_array($query_topbottom_text);
					?>
                        <h1 class="popular-listing-title " style="text-align:left !important;" >
                            2020 Top 10 <?php echo $res_topbottom_text['name']; ?> Movers Reviews & <?php echo $state_code; ?> Moving Companies
                        </h1>
                        <div class="popular-left">
                            <img src="https://www.topmovingreviews.com/mmr_images/state_min_images/<?php echo strtolower($statename); ?>.jpeg" alt="Top <?php echo ucwords($statename);?> Movers Reviews &ndash; <?php echo strtoupper($stateshrtname);?> Moving Companies" >
                        </div>
                        <div class="popular-right">
						
						<?php /*?><?php
							$random=mt_rand(1,5000);
							$sql_get_content=mysqli_query("select content from city_content where id='$random' limit 0,1");
							$res_get_content=mysqli_fetch_array($sql_get_content);
						 	$city_statecode= "<strong>".ucfirst($cityname).",".strtoupper($stateshrtname)."</strong>";
						 	$moving_companies="<strong>"."Moving Companies"."</strong>";
						 	$movers_in="<strong>"."movers in"."</strong>";
							$local_moves="<strong>"."local moves"."</strong>";
							$long_distance_movers="<strong>"."long distance movers"."</strong>";
						?><?php */?>
						
                            <p ><?php
							
							echo $res_topbottom_text['top_text'];
							
							
							 /*echo str_replace("long distance movers",$long_distance_movers,str_replace("local moves",$local_moves,str_replace("movers in",$movers_in,str_replace("Moving Companies",$moving_companies,str_replace("(City), (State)",$city_statecode,$res_get_content['content']))))) ;*/ ?>
							</p>
                           <!-- <p>Having a <strong>reputable <?php echo ucwords($statename); ?> moving company</strong> handling your belongings locally or interstate is something everyone is looking for before the moving day. We have selected the best movers with real <?php echo ucwords($statename); ?> movers reviews so you can make your decision in advance. Just <strong>fill our free moving quote form </strong>on the left if you want to estimate your moving costs and find out the best moving rates in your area. We carefully select the best moving companies for a smooth moving experience. Whether you are moving in <span>Los Angeles</span>, <span>San Diego</span>, <span>San Francisco</span> or <span>Long Beach</span> or other cities like <span>San Jose</span>, <span>Sacramento</span> or <span>Fresno</span> - find the best reliable movers online today.</p>-->
                        </div>
                    </div>

            <div class="row">
                <div class="col-md-8">
        <div class="popular-listing popular-listing-1">
                        <h2 class="popular-listing-title" >
                          Best  <?php echo ucwords($statename); ?> <strong>Movers Reviews</strong>
                        </h2>
                    </div></div>
                 <div class="col-md-4">       <div class="">
                        <div style="text-align:right">
                            <form  name="frm_filter" action='https://www.topmovingreviews.com/usa/<?php echo $statename."-movers-".$stateshrtname; ?>/' method='post'>
                            <div class="filter_by1">
                                <label>Sort by
                                    <select name="filter" style="border:0; background:none; cursor:pointer;color: #3b65a7; font-weight: bold;font-size:14px;" onChange="document.frm_filter.submit();"><option>Select</option>
                                        <option value="companies.date_saved asc" <?php if ($_REQUEST['filter']=="companies.date_saved asc") {?> selected="selected" <?php  } ?>>Newest Reviews</option>
                                        <option value="companies.date_saved desc" <?php if ($_REQUEST['filter']=="companies.date_saved desc") {?>  selected="selected"  <?php  } ?>>Oldest Reviews</option>
                                        <option value="rating desc" <?php if ($_REQUEST['filter']=="rating desc") { ?>  selected="selected" <?php  } ?>>Highest Rated</option>
                                        <option value="rating asc" <?php if ($_REQUEST['filter']=="rating asc") { ?>  selected="selected" <?php  } ?>>Lowest Rated</option>
                                    </select></label>
                            </div></form></div>
                    </div></div>
            </div>



             


<?php 



$adjacents = 3;



$tbl_name='company_state';



$query = mysqli_query($link,"SELECT *  FROM    companies  , reviews    where companies.id=reviews.company_id and city in ('$cityname') group by companies.id"); 



$total_pages = mysqli_num_rows($query);







/* Setup vars for query. */
$arr = explode("?",$_SERVER['REQUEST_URI']);
$isID = false; 
$Ispage_num = 0;

if(isset($arr[1])){
    $isID =  true;
  $pCount=  explode("=",$arr[1]);
  $Ispage_num = $pCount[1];
}
$limit = 10; 								//how many items to show per page

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
$sql = "SELECT title,address,id,rating,phone,logo  FROM  companies      where   state='$state_code' group by companies.id order by $filter LIMIT $start, $limit";


}



else



{





$sql = "SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and state='$state_code'  group by companies.id order by   companies.rating desc,count(*) desc  LIMIT $start, $limit";



 //$sql = "SELECT title,address,id,rating,phone  FROM  companies      where   address like '% $cityname,%' LIMIT $start, $limit";



}











$result = mysqli_query($link,$sql);







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

$statename1=str_replace(' ','-',$statename);
	$pagination .= "<div class=\"pagination\"  ><ul>";
	//previous button
	if ($page > 1) 
		$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$prev\"><-</a></li>";
	else
		$pagination.= "<li><span class=\"pagination1\"><-</span></li>";	
	
	//pages	
	if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
	{	
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $page)
				$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
			else
				$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$counter\">$counter</a></li>";					
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
					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$counter\">$counter</a></li>";					
			}
			$pagination.= "<li><span>...</span>";
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$lpm1\">$lpm1</a></li>";
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$lastpage\">$lastpage</a></li>";		
		}
		//in middle; hide some front and some back
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=1\">1</a></li>";
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=2\">2</a></li>";
			$pagination.= "<li><span>...</span></li>";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
				else
					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$counter\">$counter</a></li>";					
			}
			$pagination.= "<li><span>...</span></li>";
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$lpm1\">$lpm1</a></li>";
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$lastpage\">$lastpage</a></li>";		
		}
		//close to end; only hide early pages
		else
		{
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=1\">1</a></li>";
			$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=2\">2</a></li>";
			$pagination.= "<li><span>...</span></li>";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
				else
					$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$counter\">$counter</a></li>";					
			}
		}
	}
	
	//next button
	if ($page < $counter - 1) 
		$pagination.= "<li><a href=\"https://www.topmovingreviews.com/usa/$statename1-movers-$stateshrtname?page=$next\">-></a></li>";
	else
		$pagination.= "<li><span class=\"pagination1\">-></span></li>";
	$pagination.= "</ul></div>\n";		
}







while($res_comp_city=mysqli_fetch_array($result))



{









$sql_reviewcount=mysqli_query($link,"select * from reviews where company_id= '$res_comp_city[id]'");



$res_reviewcount=mysqli_num_rows($sql_reviewcount);



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



<div class="col-md-9" style="cursor:pointer;" >
 


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



	  $query_review=mysqli_query($sql_review);	



	  $res_review=mysqli_fetch_assoc($query_review);*/



	  echo substr($res_comp_city["text"],0,250);



 ?>

<span style="color:#354EF4">...read more</span>

		



</div>





</div>



<div style="clear:both"></div>





</div>





<?php   } ?>
                   

                   <!--  <div class="cent">
                        <div class="pagination">
						
						
						
                           <a href="#">&laquo;</a>
                            <a href="#">1</a>
                            <a href="#" class="active">2</a>
                            <a href="#">3</a>
                            <a href="#">4</a>
                            <a href="#">5</a>
                            <a href="#">&raquo;</a>
                        </div>
                    </div>-->
					
					<?=$pagination?>
                    <div class="bottomtext">
					
					<?php
							
							echo $res_topbottom_text['bottom_text'];
							
							
							?>
					
                        <?php /*?><h2><?php echo ucwords($statename); ?>  Movers</h2>
							<?php
							$random=mt_rand(1,5000);
							$sql_get_content=mysqli_query("select content from city_content where id='$random' limit 0,1");
							$res_get_content=mysqli_fetch_array($sql_get_content);
						 	$city_statecode= "<strong>".ucfirst($cityname).",".strtoupper($stateshrtname)."</strong>";
						 	$moving_companies="<strong>"."Moving Companies"."</strong>";
						 	$movers_in="<strong>"."movers in"."</strong>";
							$local_moves="<strong>"."local moves"."</strong>";
							$long_distance_movers="<strong>"."long distance movers"."</strong>";
						?>
						<p ><?php echo str_replace("long distance movers",$long_distance_movers,str_replace("local moves",$local_moves,str_replace("movers in",$movers_in,str_replace("Moving Companies",$moving_companies,str_replace("(City), (State)",$city_statecode,$res_get_content['content']))))) ; ?>
							</p><?php */?>
						
                       <?php /*?> <p><?php echo ucwords($statename); ?> is a state packed with places to visit and things to do. From the wine tasting adventure at the Napa Valley to the outdoor journey at the Yosemite Valley, the beauty of this place is truly undisputable. If you want to enjoy the awe-inspiring scenery of the state, you might want to consider moving to the large cities in <?php echo ucwords($statename); ?> such as Los Angeles, and San Francisco, or at the charming city of Oakland and Long Beach. With the assistance of a trustworthy <strong><?php echo ucwords($statename); ?> Moving companies, </strong> you can guarantee that all your items will reach your destination in a secured manner. Whether you need the support of local or interstate <strong> movers in <?php echo ucwords($statename); ?>, </strong> there are experts here that specialize in various types of moves.</p><?php */?>
                       
                       graph 
                       
                       
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

<div class=submit-new><a class=cstm_btn onClick="javascript:return validate()" style="cursor:pointer;">Continue</a> </div>
</div>
</form>
</div>
<?php 

$sql_dot="select power_units,usdot_number,mc,safety_url,date_format(granted_date,\"%m-%d-%Y\") as granted_date from company_dot_data where  company_name= '$res_company[title]'";

$query_dot=mysqli_query($link,$sql_dot);

$res_dot=mysqli_fetch_assoc($query_dot);

?>

                    <div class="callfor">
<h3>Are  you  a  Moving  Company</h3><br>Find out how you can be in our <a href="">Moving List</a>.  
                        <!--<img src="images/300x600.jpg"/>-->
                    </div>

                   <!-- <div class="cmprev">
                        <p>Write a Moving Company Review Here</p>
                        <div class="search-box">
                            <input type="text" placeholder="Looking For..." name="search">
                            <button type="submit"><i class="fa fa-search"></i>&nbsp;Search</button>
                        </div>	

                    </div>-->

                    <div class="cities">
                        <h3>Check Movers in Nearby States</h3>
                        <ul id="output"></ul>
                        <div class="city">


<?php
/* Code for getting near by states from current location */
/*$ip = $_REQUEST['REMOTE_ADDR']; 
$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
if ($query && $query['status'] == 'success') {*/

	 $sql_lat="select * from  states_nearby where name='$_GET[statename]' limit 0,1";
	 $query_lat=mysqli_query($link,$sql_lat);
	 $res_lat=mysqli_fetch_array($query_lat);
	 $lat = $res_lat['lat'];
	 $lon = $res_lat['lon'];
	  $get_state = "SELECT *, ((ACOS(SIN($lat * 3.14 / 180) * SIN(lat * 3.14 / 180) + COS($lat * 3.14 / 180) * COS(lat * 3.14 / 180) * COS(($lon - lon) * 3.14 / 180)) * 180 / 3.14) * 60 * 1.1515) AS distance FROM states_nearby where name <> '$_GET[statename]' HAVING distance<='2000' ORDER BY distance ASC limit 0,6";
	if ($result = mysqli_query($link,$get_state)) {
		  if(mysqli_num_rows($result)>1){
		while ($row = mysqli_fetch_assoc($result)) {
		
		  $query_state_shrtname=mysqli_query($link,"select state_code from states where name='$row[name]'");
		  $res_state_shrtname=mysqli_fetch_array($query_state_shrtname);
		 
		  ?>
		  
		  <div class='city-box'>
		  
		  <a href="https://www.topmovingreviews.com/usa/<?php echo str_replace(" ","-",$row['name'])."-movers-".$res_state_shrtname['state_code'] ; ?>/" style='text-transform: capitalize;'>
		  
		<?php echo str_replace("-", " ", $row['name']); ?></a>
			
			</div>
			<?php }
		}
		else { echo "<span href='' style='color: #FF0000;'>"."No Movers in your nearby States"."</span>"; }
	}
/*} else {
	echo 'Unable to get location';
}*/
?>
                       


                           <!-- <div class="city-anchr">
                                <a>
                                    <u>See All States</u>&ensp;<i class="fa fa-angle-right" aria-hidden="true"></i>
                                </a>
                            </div>-->

                        </div>
                    </div>

                    <div class="cities">
                        <h3>Top Moving Reviews <br>In <?php echo ucwords($statename);?> Cities</h3>

                        <div class="city">
 <?php   //select distinct(SUBSTRING_INDEX(SUBSTRING_INDEX(address,\",\",-3),\",\",1)) as city ,id from companies where address like '% $stateshrtname %'
$sql_city="SELECT  city,state_name,zips FROM `us_city_population` where state_code='$state_code' order by population DESC limit 0,6";
 $query_city=mysqli_query($link,$sql_city);
 while($res_city=mysqli_fetch_assoc($query_city))
 {
//address like '% $res_city[city]%' and address like '% $stateshrtname %'
/*$sql_state_zip="select SUBSTRING_INDEX(SUBSTRING_INDEX(address,\",\",-2),\",\",1) as state_zipcode from companies where address like '% $stateshrtname %'";
$query_state_zip=mysqli_query($sql_state_zip);

$res_state_zip=mysqli_fetch_array($query_state_zip); */

if(strlen($res_city['zips'])==4)
$res_city['zips']="0".$res_city['zips'];
if(strlen($res_city['zips'])==3)
$res_city['zips']="00".$res_city['zips'];
 ?>
 
                            <div class="city-box">
<a href="https://www.topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",ltrim($res_city['city']))."-".$stateshrtname."-".$res_city['zips']; ?>/">									<?php echo $res_city['city']; ?></a>
                            </div>
							
	<?php						
			}
				
	?>						
							                        

<div class="row">
  <div class="col-md-12">
    <div class="collapse multi-collapse" id="multiCollapseExample1">
      <div class='city-box' >

		  
		    <?php   //select distinct(SUBSTRING_INDEX(SUBSTRING_INDEX(address,\",\",-3),\",\",1)) as city ,id from companies where address like '% $stateshrtname %'
$sql_city="SELECT  city,state_name,zips FROM `us_city_population` where state_code='$state_code' order by population DESC limit 6,7";
 $query_city=mysqli_query($link,$sql_city);
 while($res_city=mysqli_fetch_assoc($query_city))
 {
//address like '% $res_city[city]%' and address like '% $stateshrtname %'
/*$sql_state_zip="select SUBSTRING_INDEX(SUBSTRING_INDEX(address,\",\",-2),\",\",1) as state_zipcode from companies where address like '% $stateshrtname %'";
$query_state_zip=mysqli_query($sql_state_zip);

$res_state_zip=mysqli_fetch_array($query_state_zip); */

if(strlen($res_city['zips'])==4)
$res_city['zips']="0".$res_city['zips'];
if(strlen($res_city['zips'])==3)
$res_city['zips']="00".$res_city['zips'];
 ?>
 
                            <div class="city-box">
<a href="https://www.topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",ltrim($res_city['city']))."-".$stateshrtname."-".$res_city['zips']; ?>/">									<?php echo $res_city['city']; ?></a>

			</div>


					<?php


					
				    }

				 ?>

		


			</div>
			
	
			
			
    </div>
	
	
	
	
  </div>

</div>
<p>
  <a  data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1"><u>See More</u>&ensp;<i class="fa fa-angle-right" aria-hidden="true"></i></a>
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
          <div class="content-heading"><a href="https://www.topmovingreviews.com/Move/moving-checklist-how-to-prepare-for-the-moving-day/"><h4>How to Prepare for the Moving Day</h4></a></div>
          <p>Most people are aware on how stressful the moving day can be. To help you lessen the load, we created a comprehensive ... </p>
            
</div>     
</div>
<!--end1-->
<div class="row newrow">
<div class="col-md-12">
 <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company.jpg"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/Move/tips-on-how-do-you-find-a-moving-company/"><h4>Tips on how do you find a moving company.</h4></a></div>
          <p>Moving is a very stressful and also pricey experience, as well as if you do not take precaution it can promptly become ... </p>
            
</div>     
</div>
<!--end1-->
<div class="row newrow">
<div class="col-md-12">
<img class="img-left" src="https://www.topmovingreviews.com/images/packing-guide.jpg"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/Move/essential-tips-when-packing-your-closet-3/"><h4>Packing Guide</h4></a></div>
          <p>Packing can be an intensely challenging step when preparing to move. It is during this time that most damages to goods...</p>
            
</div>     
</div>
<!--end1-->
<div class="row newrow">
<div class="col-md-12">
 <img class="img-left" src="https://www.topmovingreviews.com/images/change.jpg"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/Move/moving-checklist-when-changing-your-address-3/"><h4>Change Your Address When You Move</h4></a></div>
          <p>Before you can settle in, you have to ensure that your mail is updated to continue receiving your regular mail without any...</p>
            
</div>     
</div>       
</div>
				
            </div>
			
        </div>
        <?php include 'footer.php'; ?>
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
</script>
    </body>
    
</html>

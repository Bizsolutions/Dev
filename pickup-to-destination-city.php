<?php header('Content-Type: text/html; charset=windows-1252');
require 'core/database.class.php';

if(isset($_GET['pickupcity']))
	$pickupcity=str_replace("-"," ",$_GET['pickupcity']);

if(isset($_GET['destinationcity']))
 	$destinationcity=str_replace("-"," ",$_GET['destinationcity']);



$sql1="select text_city from pickup_destination_city where pickup_city='$pickupcity' and destination_city='$destinationcity'";
$res1=mysql_fetch_array(mysql_query($sql1));


?>

<!DOCTYPE html>
<html lang="en">
   <head>
<title>Popular City to City Moving Destinations</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name=viewport content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link href=favicon.ico rel=icon type="image/x-icon">
<meta name="description" content="Quote for Top Moving Reviews">
<meta name=keywords content="Moving, Company">
<!--<link href="../css/fo.css" rel=stylesheet type="text/css" >-->
<link rel=stylesheet type="text/css" href="https://www.topmovingreviews.com/css/style.css">

<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
 <meta property=og:title content="National Moving Companies ">
      <meta property=og:description content="Our review of moving companies highlights 6 of the best options whether you need a van line or a moving container.">
      <meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">
      <meta property=og:url content=":https://www.topmovingreviews.com">
<meta name=dc.language content=US>
<meta name=dc.subject content="NY Movers">
<meta name=DC.identifier content="/meta-tags/dublin/">
<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
<meta name=HandheldFriendly content=true>
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>
<style>
.moving-routes h1 {
    font-size: 22px;
    font-weight: normal;
    margin: 15px 0 5px;
    text-align: left;
}
h3.local-location {
    color: #f27208;
    font-size: 1.2em;
    margin: 10px 0;
}
.moving-routes .hleft {
    float: left;
    width: 60%;
}
.moving-routes .hright {
    float: right;
    width: 40%;
    padding-left: 170px;
}
.local-bullets li {
    list-style: none;
}
 

  .column {

    float: left;

	margin: 0 -2px 0 0;

	padding: 12px 14px;

	overflow: hidden;

	

    }

  

/* Clear floats after the columns */

.row {

    content: "";

    display: table;

    clear: both;

	padding:5px;

	border-bottom: 1px solid rgb(238, 238, 238);

	width:900px;

}

.row:hover { 

	box-shadow: 0 0 4px #CCCCCC inset;

	background: #f7f7f7;

	cursor:pointer;

}

.row:hover .column img{

	box-shadow: 0 1px 4px rgba(0, 105, 214, 0.25);

	border-color: rgb(158, 192, 104);

	transition: all 0.2s ease 0s;

	

}

.column img {

	border: 1px solid #CCCCCC;

	border-radius: 4px 4px 4px 4px;

	box-shadow: 0 0 1px 0 #CCCCCC;

	padding: 2px;

	float: left;

	margin: 0 8px 0 0;

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

.pagination li {

    display: inline;

	float:left;

	

	

}.pagination a {

    color: #000;

    float: left;

    padding: 8px 16px;

    text-decoration: none;

   	border:none!important;

    

}





</style>

	
</head>
<body onLoad="doOnLoad();">
<div class="wrapper">
<?php include 'header.php'; ?>
<div class="content">
<div class="moving-routes">
		<div class="hleft">
    <h1>Moving from <?php  echo "$pickupcity"; ?> to <?php  echo "$destinationcity"; ?></h1>

					<p style="text-align:justify">
					
					
					<?php  echo $res1['text_city']; ?>
					
					</p>
				<?php	
				
				
				
	$adjacents = 3;

$tbl_name='company_state';

$query = mysql_query("SELECT *  FROM    companies  , reviews    where companies.id=reviews.company_id and city in ('$pickupcity','$destinationcity') group by companies.id"); 

 $total_pages = mysql_num_rows($query);



/* Setup vars for query. */

$limit = 20; 								//how many items to show per page



if(strlen($_GET['zipcode'])<3)

 $page =$_GET['zipcode'];

else

$page=1;



if($page) 

	$start = ($page - 1) * $limit; 			//first item to display on this page

else

	$start = 0;								//if no page var is given, set start to 0



/* Get data. */


	$sql ="SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and city in ('$pickupcity','$destinationcity')  group by companies.id order by   companies.rating desc,count(*) desc  LIMIT $start, $limit";
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

	if ($page > 1) 

		$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$prev/\">Prev</a></li>";

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

				$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$counter/\">$counter</a></li>";					

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

					$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$counter/\">$counter</a></li>";					

			}

			$pagination.= "<li><span>...</span>";

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$lpm1/\">$lpm1</a></li>";

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$lastpage/\">$lastpage</a></li>";		

		}

		//in middle; hide some front and some back

		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))

		{

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-1/\">1</a></li>";

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-2/\">2</a></li>";

			$pagination.= "<li><span>...</span></li>";

			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)

			{

				if ($counter == $page)

					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$counter/\">$counter</a></li>";					

			}

			$pagination.= "<li><span>...</span></li>";

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$lpm1/\">$lpm1</a></li>";

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$lastpage/\">$lastpage</a></li>";		

		}

		//close to end; only hide early pages

		else

		{

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-1/\">1</a></li>";

			$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-2/\">2</a></li>";

			$pagination.= "<li><span>...</span></li>";

			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

			{

				if ($counter == $page)

					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$counter/\">$counter</a></li>";					

			}


		}

	}
	//next button

	if ($page < $counter - 1) 

		$pagination.= "<li><a href=\"http://www.topmovingreviews.com/$pickupcity-to-$destinationcity-movers-$next/\">Next</a></li>";

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

$comp_name = str_replace(' ', '-', $res_comp_city["title"]);



?>
					
					
					<div class="row" style="padding-top: 40px; margin-bottom:60px;" onClick="window.location.href='http://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_comp_city["id"]; ?>/'">

<div class="column"><img src="https://www.topmovingreviews.com/mmr_images/logos/logo_<?php if ($res_comp_city["logo"]==NULL){echo "no";} else echo $res_comp_city["id"];?>.jpg" alt="<?php echo $res_comp_city['title'];?>" height="80" width="190" ></div>

<div class="column" >

<h4  style="text-align:left!important;"><a style="color:#000000;" href="http://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_comp_city["id"]; ?>/"><?php echo $res_comp_city["title"]; ?></a></h4>

<p class=stars>

<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_comp_city["rating"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

</p>

<span style="color:#000000" >(<?php echo $res_reviewcount; ?> Reviews)</span><br>

<span style="color:#000000"><?php echo $res_comp_city["address"]; ?></span>

</div>

<div style="clear:both"></div>

<div style="clear: both; padding-top: 8px;">

<?php  /*$sql_review="select * from reviews where company_id=$res_comp_city[id]";

	  $query_review=mysql_query($sql_review);	

	  $res_review=mysql_fetch_assoc($query_review);*/

	  echo substr($res_comp_city["text"],0,240)."...";

 ?>

		

</div>

</div>
					
<?php } ?>
					
					<?=$pagination?>
					

				</div>

             <div class="hright">
             	<h3 class="local-location">Destinations from Los Angeles, CA</h3>
             	
             	<ul class="local-bullets">
			<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-new-york-moving">Movers to New York, NY</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/moving-from-la-to-austin">Movers to Austin, TX</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/relocating-from-los-angeles-to-dallas">Movers to Dallas, TX</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/la-to-atlanta-move">Movers to Atlanta, GA</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-maui-movers">Movers to Kahului, HI</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-nashville-moving-companies">Movers to Nashville, TN</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-las-vegas-moving-companies">Movers to Las Vegas, NV</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-denver-moving-companies">Movers to Denver, CO</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-seattle-moving-companies">Movers to Seattle, WA</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-raleigh-movers">Movers to Raleigh, NC</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-san-diego-movers">Movers to San Diego, CA</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-portland-movers">Movers to Portland, OR</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-phoenix-movers">Movers to Phoenix, AZ</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-chicago-movers">Movers to Chicago, IL</a></li>
				<li><a href="https://www.topmovingreviews.com/moving-destinations/los-angeles-to-san-francisco-movers">Movers to San Francisco, CA</a></li>
		
	</ul>
			</div>
</div>

</div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

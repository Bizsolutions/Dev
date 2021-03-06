<?php header('Content-Type: text/html; ');

require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();

$db_link = $bd->db_connect();



?>



<!DOCTYPE html>

<html lang="en">

   <head>

<title>Moving Companies in America | List of Movers in USA Statewise</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<meta name=viewport content="width=device-width, initial-scale=1.0">

     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 

<meta name="description" content="Quote for Top Moving Reviews">

<meta name=keywords content="Moving, Company">



<link href="https://topmovingreviews.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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

.newstyless{    line-height: 27px;

    font-size: 14px;

    text-align: left !important;}

.map_wr2 h2 {

    color: #FFF;

    font-weight: bold;

    border-radius: 15px;

    background:#3F4773;

    padding: 10px;font-size:20px;

    text-align:center;

}



	.map_list {



    columns: 3;

	

	}

	.map_wr2 .map_list ul {

    float: left;

    margin: 0px; padding: 0px 40px;

	

}

.map_wr2 .map_list ul li {

    margin: 0px;

    padding: 0px;

    list-style-type: square;

    color:#CCD02B;

    font-size: 16px;

    line-height: 26px;

	 

}

.map_wr2 .map_list ul li a {

color: #333333;

font-weight: bold;

text-decoration: none;

line-height: 36px;

font-size: 13px;

font-family: arial;

 



}

.card-row-title {

    font-size: 26px;

    margin: 0px 0px 20px 0px;

    padding: 0px;

    border-bottom: 2px solid #009f8b;

    padding-bottom: 10px;

    font-weight: bold;

}

.map_wr2{width:100%;}

</style>





	

</head>

<body onLoad="doOnLoad();">

<div class="wrapper">

<?php include 'newheaderwithoutbanner.php'; ?>

<div class="container">

<div class="row shadow-lg p-3 mb-5 bg-white rounde">

    <h3 class="card-row-title">List of Movers in America by City - Find a <strong>Moving Company</strong> Near You</h3>



                    <div class="card-row-content">



					<p style="text-align:justify">Make movers comparison based on moving companies reviews and consumer reports reviews. Find new movers, piano mover, pool table mover or trailer movers with recommendations. Find the best mover services by reading local movers reviews. Select the best mover price and the best local movers ratings. The best piano mover, pool table mover is here. As we are expanding our index, we are planning to include storage facilities around the country.</p>



					<h1 class="newstyless">By selecing your city you can read authentic local moving company reviews based on customer testimonials, consumer reports, ratings and testimonials.</h1>

                    <br><br>

					</div>

			<div class="map_wr2">



        	<h2>Select Your City Below:</h2>

<br><br>

             <div class="col-sm-12 ">



            <div class="map_list">



			



			 <ul>



			 <?php 



			 



			 $sql = mysql_query( "SELECT distinct city,state_code,zip FROM cities_extended where state_code='$_REQUEST[state_code]' order by city asc");



			while($row = mysql_fetch_array($sql))



			



			{



				$city=$row['city'];

				$state_code=$row['state_code'];

				$zips=$row['zip'];



				 /*$sname = strtolower($row['name']);*/



/*$stname = str_replace(' ', '-', $sname);

$stname=$sname;

$sql1 = mysql_query("SELECT * FROM companies WHERE city='$city'");

$r = mysql_num_rows($sql1);*/



			 ?>



			 



			 <li><a  href="http://topmovingreviews.com/moving-companies/<?php echo str_replace(" ","-",$city); ?>-<?php echo str_replace(" ","-",$state_code); ?>-<?php echo $zips; ?>/"> 

			 

			 <?php echo $city; ?>

			 

			  </a>

			  

			  </li>



			 



			 <?php 



			}



			 ?>



			 </ul>



			 



            </div>



			</div>



        </div>





</div>





</div>

</div>

<?php include 'footer.php'; ?>

</body>

</html>


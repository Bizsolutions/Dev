<?php header('Content-Type: text/html; ');

require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();

$db_link = $bd->db_connect();

?>



<!DOCTYPE html>

<html lang="en">

   <head>

<title>Top Moving Routes and Destinations in USA</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<meta name=viewport content="width=device-width, initial-scale=1.0">

     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 

<meta name="description" content="Top Moving Routes and Destinations in USA">

<meta name=keywords content="Moving, Company">







<link href="https://topmovingreviews.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">

 <meta property=og:title content="National Moving Companies ">

      <meta property=og:description content="Our review of moving companies highlights 6 of the best options whether you need a van line or a moving container.">

      <meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">

      <meta property=og:url content="https://www.topmovingreviews.com">

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

</style>





	

</head>

<body onLoad="doOnLoad();">

<div class="wrapper">

<?php include 'newheaderwithoutbanner.php'; ?>

<br/>

<div class="container" >

<div>

    <h3 class="card-row-title">Top Moving Routes and Destinations in USA</h3>



                    <div class="card-row-content">



					<p style="text-align:justify">We know that there are specific routes between some of the largest cities that are very popular in the USA. It is the reason why we've made the top moving route pages (city to city). You can check out movers that support an exact route and will be well-prepared when the day of moving comes. </p>



					<p style="text-align:justify">All moving companies that are listed are ranked based on customer reviews, testimonials, and ratings. The customers wrote reviews that have used specific routes.</p>





					</div>

			<div class="map_wr2">



        	<h2>Domestic Routes:</h2>

<br><br>

             <div class="col-sm-12 ">



            <div class="map_list">



			



			 

			 <ul>

			 

			 <?php 

							$sql1="select pickup_city,destination_city from pickup_destination_city";

							$query1=mysql_query($sql1);

							while($res1=mysql_fetch_array($query1))

								{						



			?>

									<li><a href="https://www.topmovingreviews.com/routes/<?php echo str_replace(" ","-",$res1['pickup_city'])."-to-".str_replace(" ","-",$res1['destination_city']); ?>-movers/">Moving from <?php echo $res1['pickup_city']." to ".$res1['destination_city']; ?></a></li>			

		

					 <?php 

					 		}

					 ?>

		</ul>



		



			 



            </div>



			</div>



        </div>





</div>

<br/>



</div>

</div>

<?php include 'footer.php'; ?>

</body>

</html>


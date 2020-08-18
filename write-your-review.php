<?php header('Content-Type: text/html; charset=ISO-8859-1');

require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();

$db_link = $bd->db_connect();

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"

    "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>Write Review for Top Moving Reviews</title>



<meta name=viewport content="width=device-width, initial-scale=1.0">

<meta http-equiv=Content-Type content="text/html; charset=UTF-8">

 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 

<meta name="description" content="Quote for Top Moving Reviews">

<meta name=keywords content="Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving">







<meta name=dc.language content=US>

<meta name=dc.subject content="NY Movers">

<meta name=DC.identifier content="/meta-tags/dublin/">

<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">

<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>

<meta name=HandheldFriendly content=true>

<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>



	<script language="javascript">

	function validate() 

	{

	

	if (document.getElementById('review_search').value== '')

	

	{

	

	alert("Please Enter Company Name to Write Review");

	document.getElementById('review_search').focus();

	return false;

	}

	

	else

		{

		document.getElementById("review_frm").submit();	

		return true;

		}

	}

	

	</script>   

	<!--srikanta datd 15092018-->





</head>

<body onLoad="doOnLoad();">

<div class=wrapper>

<?php include 'newheaderwithoutbanner.php'; ?>

<div class=container>

<div class=" shadow p-3 mb-5 bg-white rounded">

<br>

<h2>Write Your Review</h2><br>



<form name="review_frm" id="review_frm" action="write-your-review.php" method="post" >

<div class="input-group mb-3">

  <input type="text" class="form-control" id="review_search"  name="review_search"  placeholder=" " aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $_REQUEST['review_search'];?>">

  <div class="input-group-append">



    <button class=" btn btn-primary" type="submit" onclick="javascript:return validate()">Write A Rewiew</button>

  </div>

</div>    

    

</form>



<?php

if(isset($_REQUEST['review_search']))

{

echo "<br>";

echo "<h2>";

echo "Did You Mean :";

echo "</h2>";

echo "<br>";

echo "<hr>";

echo "<br>";

$sql = "SELECT * FROM companies  where title like '%$_REQUEST[review_search]%' order by rating desc";

$result = mysqli_query($link,$sql);



while($res_company=mysqli_fetch_array($result))

{

$comp_name = str_replace(' ', '-', $res_company["title"]);

?>



<br>



<br><br>

<div class="row" onClick="window.location.href='add_reviews.php?company_id=<?php echo $res_company["id"]; ?>'">

<div class="column"><img src="https://www.topmovingreviews.com/mmr_images/logos/logo_<?php echo $res_company["id"];?>.jpg" ></div>

<div class="column" >

<h4 style="color:#3b65a7;"><a href="add_reviews.php?company_id=<?php echo $res_company["id"]; ?>"><?php echo $res_company["title"]; ?></a></h4>

<p class=stars>

<span class="fa fa-star  <?php if(round($res_company["rating"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_company["rating"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_company["rating"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_company["rating"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

<span class="fa fa-star  <?php if(round($res_company["rating"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

</p>

<span style="color:#3b65a7;"><?php echo $res_company["address"]; ?></span>

</div>

<div style="clear:both"></div>

<div style="clear: both; padding-top: 8px;">

<?php  $sql_review="select * from reviews where company_id=$res_company[id]";

	  $query_review=mysqli_query($link,$sql_review);	

	  $res_review=mysqli_fetch_assoc($query_review);

	  echo substr($res_review["text"],0,500)."...";

 ?>

		

</div>

</div>

<?php } }?>



<div class="cust" style=" margin-bottom:30px; line-height:40px;">



			<p style="color:#666666; font-size:16px; margin-top:20px">Still hesitant to write your moving review? Find out why you ought to do it here</p>

		

			<h3>By posting a review you understand and agree that:</h3>



			<ol >



			<li>You must have been a customer of the company you are reviewing.</li>



			<li>You should submit only one review per move/transportation per company. Do not post "play-by-play" reviews.</li>



			<li>Companies and their employees may NOT submit reviews under any circumstances, even by request of a customer.</li>



			<li>You, as the poster of the review, are responsible for the review content. False info may result in review filtration.</li>



			<li>You accept our terms of service.</li>



			</ol>



			</div>

</div>





</div>



<?php include 'footer.php'; ?>

</body>

</html>


<?php header('Content-Type: text/html; charset=ISO-8859-1');

require_once('core/database.class.php');



require_once('core/company.class.php');



$bd = new db_class();

$db_link = $bd->db_connect();







/*$sql_company="SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";

$query_company=mysql_query($sql_company);

$res_company=mysql_fetch_array($query_company);

$compnay_address=explode(",",$res_company['address']);

$countarray=count($compnay_address);*/

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"

    "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>TopMovingReviews.com - Movers rated by real customers.</title>



<meta name=viewport content="width=device-width, initial-scale=1.0">

<meta http-equiv=Content-Type content="text/html; charset=UTF-8">

 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 

<meta name="description" content="Moving Reviews and Movers Ratings. Moving Companies ratings, Movers Comments and Moving Companies Testimonials. Real customers ratings by state by My Moving Reviews">
<meta name=dc.language content=US>

<meta name=dc.subject content="NY Movers">

<meta name=DC.identifier content="/meta-tags/dublin/">

<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">

<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>

<meta name=HandheldFriendly content=true>

<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>


<script type="text/javascript">
function showCity(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("DivCity").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","city_search.php?q="+str,true);
xmlhttp.send();
}

function showCity_State(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("DivCity").innerHTML=xmlhttp.responseText;
	document.getElementById("wait").innerHTML='Please Wait';
    }
}
xmlhttp.open("GET","city_search.php?city="+str+"&q="+document.getElementById('selStateZip').value,true);
xmlhttp.send();
}
//function showFind(str)
//{
//if (str=="")
//  {
//  document.getElementById("txtHint").innerHTML="";
//  return;
//  }
//if (window.XMLHttpRequest)
//  {// code for IE7+, Firefox, Chrome, Opera, Safari
//  xmlhttp=new XMLHttpRequest();
//  }
//else
//  {// code for IE6, IE5
//  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//  }
//xmlhttp.onreadystatechange=function()
//  {
//  if (xmlhttp.readyState==4 && xmlhttp.status==200)
//    {
//    document.getElementById("DivCity").innerHTML=xmlhttp.responseText;
//	document.getElementById("wait").innerHTML='Please Wait';
//    }
//}
//xmlhttp.open("GET","city_search.php?city="+str+"&q="+document.getElementById('selStateZip').value,true)"&f="+"find";
//xmlhttp.send();
//}

</script>		
</head>

<body>
<?php require_once('newheaderwithoutbanner.php'); ?>

<div class="container">

         <div class="row">
                <div class="col-md-4">
                    <div class="shadow-lg p-3 mb-5 bg-white rounde"> <form name="frm_search1" action="https://www.topmovingreviews.com/searchcompany.php" method="post">
                            <div class="">
                                <strong>Write a Moving Company Review Here</strong><br/>
							 <br>
                             
                                <div class="">
                              
                                    <input type="text" value="Search Company Name" name="company_search" onFocus="javascript:document.frm_search1.company_search.value='';" class="form-control form-control-sm"> 
                                    <button type="submit" class="btn btn-primary">
                                        <!--<img src="images/ico4.jpg">-->
                                        &nbsp;Search
                                    </button>
                                </div>
                            </div>
                        </form></div></div>
                         <div class="col-md-4">
                    <div class="shadow-lg p-3 mb-5 bg-white rounded"> <form name="frm_zip" action="https://www.topmovingreviews.com/searchzipcode.php" method="post">
                            <div class="">
                                <strong>Find Movers near me</strong><br/>
							 <br>
                                <div class="">
                                    <input class="form-control form-control-sm" type="text" value="Enter Zip" name="zipcode_search" onFocus="javascript:document.frm_zip.zipcode_search.value='';"> 
                                    <button type="submit" class="btn btn-primary">
                                        <!--<img src="images/ico4.jpg">-->
                                        &nbsp;Find
                                    </button>
                                </div>
                            </div>
                        </form></div></div>
                         <div class="col-md-4">
                    <div class="shadow-lg p-3 mb-5 bg-white rounded">		 <strong>Find Movers by City</strong><br/>
							 <br>
							 <div class="">
							 <select onChange="return showCity(this.value)"  name="selStateZip" id="selStateZip" class="form-control form-control-sm">
							 <option>Select State</option>
							 <?php 	 $sql_state = mysqli_query($link, "SELECT state_code,name  FROM states where usa_state=1 order by name");

									while($row_state = mysqli_fetch_array($sql_state))

									{ ?>
							 <option value="<?php echo $row_state['state_code'] ;?>"><?php echo $row_state['name'] ;?> </option>
							 
							 <?php } ?>
							 
							 </select>
							 </div>
							 
                                <div class="" id="DivCity">
                                    <select disabled="disabled" class="form-control form-control-sm">
									<option value="">Select City</option>
									</select>
                                 <!--   <button >
                                      
                                        &nbsp;Find
                                    </button>-->
									<div id="wait">
									</div>
									
                                </div></div></div>
                
            </div>

<div class="content cust-sdearch-br">





<br>

<h2>Search for Moving Companies and Moving Tips</h2>


<p>Use the search box below to look up your moving company or explore our article base before moving.</p>

<form name="frm_search" action="searchzipcode.php" method="post">

<table width="100%" border="0" cellspacing="4	">

  <tr>

    <td><input  name="zipcode_search" type="text" class="form-control" value="<?php echo $_REQUEST['zipcode_search'];?>">

</td>

    <td><input name="" value="Search!" type="submit" class="btn  btn-primary"></td>

  </tr>

</table></form>

<br>

<?php 

 

$adjacents = 3;

$tbl_name='companies';

if(isset($_REQUEST['zipcode_search']))

$query = "SELECT COUNT(*) as num FROM $tbl_name where  zipcode like '%$_REQUEST[zipcode_search]%'";



$total_pages = mysqli_fetch_array(mysqli_query($link,$query));

$total_pages = $total_pages['num'];



/* Setup vars for query. */

$targetpage = "searchzipcode.php"; 	//your file name  (the name of this file)

$limit = 20; 								//how many items to show per page

$page = isset($_GET['page'])?$_GET['page']:1;

if($page) 

	$start = ($page - 1) * $limit; 			//first item to display on this page

else

	$start = 0;								//if no page var is given, set start to 0



/* Get data. */

if(isset($_REQUEST['zipcode_search']))

$sql = "SELECT * FROM $tbl_name  where zipcode like '%$_REQUEST[zipcode_search]%' order by rating desc LIMIT $start, $limit";

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

	$pagination .= "<div  ><ul  class=\"pagination\">";

	//previous button

	if ($page > 1) 

		$pagination.= "<li class=\"page-item\"><a href=\"$targetpage?page=$prev&zipcode_search=$_REQUEST[zipcode_search]\" class=\"page-link\">Previous</a></li>";

	else

		$pagination.= "<li class=\"page-item disabled\"><span class=\"page-link\">Previous</span></li>";	

	

	//pages	

	if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up

	{	

		for ($counter = 1; $counter <= $lastpage; $counter++)

		{

			if ($counter == $page)

					$pagination.= "<li  class=\"page-item active\"><span class=\"page-link \">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"$targetpage?page=$counter&zipcode_search=$_REQUEST[zipcode_search]\">$counter</a></li>";										

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

					$pagination.= "<li  class=\"page-item active\"><span class=\"page-link \">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"$targetpage?page=$counter&zipcode_search=$_REQUEST[zipcode_search]\">$counter</a></li>";					

			}

			$pagination.= "<li><span>...</span>";

			$pagination.= "<li><a href=\"$targetpage?page=$lpm1&zipcode_search=$_REQUEST[zipcode_search]\">$lpm1</a></li>";

			$pagination.= "<li><a href=\"$targetpage?page=$lastpage&zipcode_search=$_REQUEST[zipcode_search]\">$lastpage</a></li>";		

		}

		//in middle; hide some front and some back

		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))

		{

			$pagination.= "<li><a href=\"$targetpage?page=1&zipcode_search=$_REQUEST[zipcode_search]\">1</a></li>";

			$pagination.= "<li><a href=\"$targetpage?page=2&zipcode_search=$_REQUEST[zipcode_search]\">2</a></li>";

			$pagination.= "<li><span>...</span></li>";

			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)

			{

				if ($counter == $page)

					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"$targetpage?page=$counter&zipcode_search=$_REQUEST[zipcode_search]\">$counter</a></li>";					

			}

			$pagination.= "<li><span>...</span></li>";

			$pagination.= "<li><a href=\"$targetpage?page=$lpm1&zipcode_search=$_REQUEST[zipcode_search]\">$lpm1</a></li>";

			$pagination.= "<li><a href=\"$targetpage?page=$lastpage&zipcode_search=$_REQUEST[zipcode_search]\">$lastpage</a></li>";		

		}

		//close to end; only hide early pages

		else

		{

			$pagination.= "<li><a href=\"$targetpage?page=1&zipcode_search=$_REQUEST[zipcode_search]\">1</a></li>";

			$pagination.= "<li><a href=\"$targetpage?page=2&zipcode_search=$_REQUEST[zipcode_search]\">2</a></li>";

			$pagination.= "<li><span>...</span></li>";

			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

			{

				if ($counter == $page)

					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";

				else

					$pagination.= "<li><a href=\"$targetpage?page=$counter&zipcode_search=$_REQUEST[zipcode_search]\">$counter</a></li>";					

			}

		}

	}

	

	//next button

	if ($page < $counter - 1) 

		$pagination.= "<li><a href=\"$targetpage?page=$next&zipcode_search=$_REQUEST[zipcode_search]\">Next</a></li>";

	else

		$pagination.= "<li><span class=\"pagination1\">Next</span></li>";

	$pagination.= "</ul></div>\n";		

}



while($res_company=mysqli_fetch_array($result))

{

$comp_name = str_replace('/','-',str_replace(' ', '-', $res_company["title"]));

?>







<div class="row c-bottom" onClick="window.location.href='http://topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_company["id"]; ?>'">

<div class="column col-md-3"><img src="https://topmovingreviews.com/mmr_images/logos/logo_<?php if ($res_company["logo"]==NULL){echo "no";} else echo $res_company["id"];?>.jpg" ></div>

<div class="column col-md-9 text-left" >

<h4 style="color:#3b65a7; text-align:left!important"><a href="http://topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_company["id"]; ?>"><?php echo $res_company["title"]; ?></a></h4>

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

	  echo substr($res_review["text"],0,140)."...";

 ?>

		

</div>

</div>

<?php } ?>



</div>

<?=$pagination?>


</div>



<?php include 'footer.php'; ?>

</body>

</html>


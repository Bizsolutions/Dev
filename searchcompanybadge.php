<?php header('Content-Type: text/html; charset=ISO-8859-1');
require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();
$db_link = $bd->db_connect();

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>TopMovingReviews.com - Movers rated by real customers.</title>

<meta name=viewport content="width=device-width, initial-scale=1.0">
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 
<meta name="description" content="Moving Reviews and Movers Ratings. Moving Companies ratings, Movers Comments and Moving Companies Testimonials. Real customers ratings by state by My Moving Reviews">
 <link rel="stylesheet" type="text/css" href="https://www.topmovingreviews.com/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



<meta name=dc.language content=US>
<meta name=dc.subject content="NY Movers">
<meta name=DC.identifier content="/meta-tags/dublin/">
<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">
<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
<meta name=HandheldFriendly content=true>
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>




</head>
<body>
<div class=wrapper>
<?php include 'newheaderwithoutbanner.php'; ?>
<div class=content style="text-align:center;">

<!--srikanta datd 15092018-->

<!--<p>Use the search box below to look up your moving company badge.</p>
-->

<style>
    
.container-table {
    display: table;
}
.vertical-center-row {
    display: table-cell;
    vertical-align: middle;
}
    
</style>

<form name="frm_search" action="searchcompanybadge.php" method="post">
<!--    <div class="container" container-table >
        <div class="row vertical-center-row" style="background:#E1E1E1 ; border-radius:5px; padding:20px;">-->
    <div class="container" >
        <div class="row " style="background:#E1E1E1 ; border-radius:5px; padding:20px;">
            <!--<div class="col-md-3">-->
            <!--<div style="min-height:356px; " class="col-xs-4 col-xs-offset-4">-->
            <div style="min-height:356px;margin-left:8%;" class="col-xs-4 col-xs-offset-4">

                            <img style="max-width: 100%;height: auto;" src="https://www.topmovingreviews.com/images/badge.png">

                            <h3 class="text-center">Let new customers know you have existing customers rooting for you, with our badges.</h3>
                            <p class="text-center" style="color:#FF0000;font-size: 16px;margin-top:20px;">Do you want to have this badge in your website?</p>
                            <p  style="text-align:center; font-weight:bold; font-size:18px; color:#0000FF; cursor:pointer;" onClick="javascript:document.getElementById('a').style.visibility ='visible'">Start Here</p>
                    
            
        <!--</div>-->
            
                   <br/><br/>
                   <div class="table-responsive">          
                        <table class="table">
                          <tr>
                            <td>Use the search box below to look up your moving company badge.
                                <br>
                                <input  name="company_search" type="text" 
                                        style=" background-color:#FFFFFF;width:50%; border:0px solid #000000;border-radius: 3px; height:46px;box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);font-size: 18px; color:#777;" 
                                        value="<?php echo $_REQUEST['company_search'];?>">
                        </td>
                            <td><br><input  name="" value="Search!" type="submit" style="background:#E79C1C;border: 1px solid #ccc;border-radius: 4px;height:30px;width:90px; height:50px;color:#FFFFFF;font-size:18px;font-weight:bold;"></td>
                          </tr>
                        </table>
                   </div>
                   </div>
            </div>
    </div>
</form>
<br>
<?php 
 
$adjacents = 3;
$tbl_name='companies';
if(isset($_REQUEST['company_search'])){
$query = "SELECT COUNT(*) as num FROM $tbl_name where  title like '%$_REQUEST[company_search]%'";

$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages['num'];
}
/* Setup vars for query. */
$targetpage = "searchcompany.php"; 	//your file name  (the name of this file)
$limit = 20; 								//how many items to show per page
$page = isset($_GET['page'])?$_GET['page']:1;
if($page) 
	$start = ($page - 1) * $limit; 			//first item to display on this page
else
	$start = 0;								//if no page var is given, set start to 0

/* Get data. */
if(isset($_REQUEST['company_search']))
$sql = "SELECT * FROM $tbl_name  where title like '%$_REQUEST[company_search]%' order by rating desc LIMIT $start, $limit";
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
		$pagination.= "<li><a href=\"$targetpage?page=$prev&company_search=$_REQUEST[company_search]\"><-</a></li>";
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
				$pagination.= "<li><a href=\"$targetpage?page=$counter&company_search=$_REQUEST[company_search]\">$counter</a></li>";					
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
					$pagination.= "<li><a href=\"$targetpage?page=$counter&company_search=$_REQUEST[company_search]\">$counter</a></li>";					
			}
			$pagination.= "<li><span>...</span>";
			$pagination.= "<li><a href=\"$targetpage?page=$lpm1&company_search=$_REQUEST[company_search]\">$lpm1</a></li>";
			$pagination.= "<li><a href=\"$targetpage?page=$lastpage&company_search=$_REQUEST[company_search]\">$lastpage</a></li>";		
		}
		//in middle; hide some front and some back
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
			$pagination.= "<li><a href=\"$targetpage?page=1&company_search=$_REQUEST[company_search]\">1</a></li>";
			$pagination.= "<li><a href=\"$targetpage?page=2&company_search=$_REQUEST[company_search]\">2</a></li>";
			$pagination.= "<li><span>...</span></li>";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
				else
					$pagination.= "<li><a href=\"$targetpage?page=$counter&company_search=$_REQUEST[company_search]\">$counter</a></li>";					
			}
			$pagination.= "<li><span>...</span></li>";
			$pagination.= "<li><a href=\"$targetpage?page=$lpm1&company_search=$_REQUEST[company_search]\">$lpm1</a></li>";
			$pagination.= "<li><a href=\"$targetpage?page=$lastpage&company_search=$_REQUEST[company_search]\">$lastpage</a></li>";		
		}
		//close to end; only hide early pages
		else
		{
			$pagination.= "<li><a href=\"$targetpage?page=1&company_search=$_REQUEST[company_search]\">1</a></li>";
			$pagination.= "<li><a href=\"$targetpage?page=2&company_search=$_REQUEST[company_search]\">2</a></li>";
			$pagination.= "<li><span>...</span></li>";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
				else
					$pagination.= "<li><a href=\"$targetpage?page=$counter&company_search=$_REQUEST[company_search]\">$counter</a></li>";					
			}
		}
	}
	
	//next button
	if ($page < $counter - 1) 
		$pagination.= "<li><a href=\"$targetpage?page=$next&company_search=$_REQUEST[company_search]\">-></a></li>";
	else
		$pagination.= "<li><span class=\"pagination1\">-></span></li>";
	$pagination.= "</ul></div>\n";		
}
if(isset($_REQUEST['company_search']))
{
while($res_company=mysql_fetch_array($result))
{
$comp_name = str_replace(' ', '-', $res_company["title"]);
?>



<div class="row" onClick="window.open('https://www.topmovingreviews.com/companybadge.php?company_id=<?php echo $res_company['id'];?>&title=<?php echo  urlencode(str_replace(' ', '-',$res_company['title']));?>&rating=<?php echo $res_company['rating'];?>&email=<?php echo $res_company['email'];?>','popUpWindow','height=600,width=610,left=300,top=80,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
<div class="column"><img src="https://www.topmovingreviews.com/mmr_images/logos/logo_<?php if ($res_company["logo"]==NULL){echo "no";} else echo $res_company["id"];?>.jpg" ></div>
<div class="column" >
<h4 style="color:#3b65a7; text-align:left!important;"><a onClick="window.open('https://www.topmovingreviews.com/companybadge.php?company_id=<?php echo $res_company['id'];?>&title=<?php echo  str_replace(' ', '-',$res_company['title']);?>&rating=<?php echo $res_company['rating'];?>&email=<?php echo $res_company['email'];?>','popUpWindow','height=600,width=610,left=300,top=80,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');"><?php echo $res_company["title"]; ?></a></h4>
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
	  $query_review=mysql_query($sql_review);	
	  $res_review=mysql_fetch_assoc($query_review);
	  echo substr($res_review["text"],0,140)."...";
 ?>
		
</div>
</div>
<?php }} ?>

</div>
<?=$pagination?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

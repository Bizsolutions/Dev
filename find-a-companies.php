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
<title>Find A Company</title>

<meta name=viewport content="width=device-width, initial-scale=1.0">
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 
<meta name="description" content="Quote for Top Moving Reviews">
<meta name=keywords content="Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving">
<!--<link href="../css/fo.css" rel=stylesheet type="text/css" >-->


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

<div class=>
<div class="">
<br>

       
       <div class="container">
               <div class="row">
                <div class="col-md-4">
                    <div class="shadow-lg p-3 mb-5 bg-white rounde"> <form name="frm_search" action="https://www.topmovingreviews.com/searchcompany.php" method="post">
                            <div class="">
                                <strong>Write a Moving Company Review Here</strong><br/>
							 <br>
                             
                                <div class="">
                              
                                    <input type="text" value="Search Company Name" name="company_search" onFocus="javascript:document.frm_search.company_search.value='';" class="form-control form-control-sm"> 
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
							 <?php 	 $sql_state = mysql_query( "SELECT state_code,name  FROM states where usa_state=1");

									while($row_state = mysql_fetch_array($sql_state))

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
           
       </div>

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
$result = mysql_query($sql);

while($res_company=mysql_fetch_array($result))
{
$comp_name = str_replace(' ', '-', $res_company["title"]);
?>

<br>

<br><br>
<div class="row" onClick="window.location.href='add_reviews.php?company_id=<?php echo $res_company["id"]; ?>'">
<?php
	if(is_file("https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_company["id"].".jpg"))
	{
		$logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_".$res_company["id"].".jpg";
	}
	else
	{
		$logo_image = "https://www.topmovingreviews.com/company/logos/logo_".$res_company["id"].".jpg";
	}
?>
<div class="column"><img src="<?php echo $logo_image;?>" ></div>
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
	  $query_review=mysql_query($sql_review);	
	  $res_review=mysql_fetch_assoc($query_review);
	  echo substr($res_review["text"],0,500)."...";
 ?>
		
</div>
</div>
<?php } }?>


</div>


</div>

<?php include 'footer.php'; ?>
</body>
</html>

<?php 
require_once('core/database.class.php');

require_once('core/company.class.php');

$bd = new db_class();

$db_link = $bd->db_connect();
$sql_company="SELECT count(*),companies.title,companies.address,reviews.text  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=54";
$query_company=mysql_query($sql_company);
$res_company=mysql_fetch_array($query_company);
$compnay_address=explode(",",$res_company['address']);
$countarray=count($compnay_address);

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>5 Top Moving for Garland Moving Group Manhattan NY</title>

<meta name=viewport content="width=device-width, initial-scale=1.0">
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<link href=favicon.ico rel=icon type="image/x-icon">
<meta name="description" content="5 Top Moving Reviews for Garland Moving Group, Manhattan, NY.I just wanted to express my gratitude and appreciation to Garland Movers.">


<meta name=keywords content="Garland Moving Group,Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving">
<link href="css/fo.css" rel=stylesheet type="text/css" >
<link rel=stylesheet type="text/css" href="css/style.css">
<meta property=og:title content="5 Top Moving for Garland Moving Group Manhattan NY">
<meta property=og:description content="5 Top Moving Reviews for Garland Moving Group, Manhattan, NY.I just wanted to express my gratitude and appreciation to Garland Movers.">
<meta property=og:image content="http://www.topmovingreviews.com/garland-moving-group-logo.jpg">
<meta property=og:site_name content="topmovingreviews.com">

<meta name="twitter:url" content="http://www.topmovingreviews.com/Garland-moving-group.php" />
<meta name="twitter:title" content="5 Top Moving for Garland Moving Group Manhattan NY" />
<meta name="twitter:image" content="http://www.topmovingreviews.com/images/garland-moving-group-logo.jpg"/>
<meta name="twitter:description" content="5 Top Moving Reviews for Garland Moving Group, Manhattan, NY.I just wanted to express my gratitude and appreciation to Garland Movers." />
<meta name="twitter:site" content="@topmovingreviews" />


<meta name=dc.language content=US>
<meta name=dc.subject content="NY Movers">
<meta name=DC.identifier content="/meta-tags/dublin/">
<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">
<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
<meta name=HandheldFriendly content=true>
<link href="../css/custom-resp.css" rel="stylesheet" type="text/css">
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>
<!--srikanta start on 06.09.2018 for modal window-->
<link rel="stylesheet" href="modal/bootstrap.min.css">
<script src="modal/jquery.min.js"></script>
<script src="modal/bootstrap.min.js"></script>
<style type="text/css">
  
  .column {
    float: left;
    width: 50%;
	text-align:center;
}
  .column1 {
    float: left;
    width: 33%;
	text-align:center;
}
/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
</style> 
<script type="text/javascript">
function validate()

{

 var stringEmail = document.getElementById('contact_email').value;

 if ( document.getElementById('contact_name').value == '' )

        {

        

                alert('Enter Your Name!');
				document.getElementById('contact_name').focus();
                return false;				

        }

		      

         else if ( document.getElementById('contact_email').value == '' )

        {

                alert('Enter your Email!');
				document.getElementById('contact_email').focus();
                return false;				

        }

       

        

		 else  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(stringEmail)))

        {

            

            alert('Enter Valid Email Id');            
			document.getElementById('contact_email').focus();
            return false;

        } 
		
		else

		{
		document.contact_frm.submit();
		return true;

		}
}
</script>

 
<!--srikanta end on 06.09.2018 for modal window-->

</head>
<body>
<div class=wrapper>
<?php include 'header-without-form.php'; ?>
<div class=content>
<div class=block-left>
<div class=jet>
<h2 class=colorclass><i>Garland Moving Group</i> </h2>
<div class=jet-left>

<img src="images/garland-moving-group-logo.jpg" class=jt-height alt="New York">
<div class=con data-toggle="modal" data-target="#myModal2" >
<i class="fa fa-envelope-o"></i>&nbsp;Contact this Movers
</div>
<div class=jet-share>
<a href="#"><i class="fa fa-share"></i> Share</a>
</div>
</div>
<div class=jet-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
<span class=rating><strong style="color:#000;">4.0 </strong> (7 Reviews)</span>&emsp;
<span class=write><a href="#"><i class="fa fa-star"></i>&nbsp;Write a Review</a></span>
</p>
<div class=htag>
<hr>
</div>
<div class=info>
<p><div class=location></div> 
77 Water Street, 8th Floor, Manhattan, New York, 10005 | <a rel="nofollow" href="https://goo.gl/maps/qEMX6HQgJJB2">View On Map</a></p>
<p><div class=headphone></div> Toll Free: (800) 219-4008 | <div class=call></div> Phone: (800) 219-4008 </p>
<p><div class=mail></div> info@garlandmovinggroup.com |<div class=web></div>www.garlandmovinggroup.com</p>
</div>
</div>
</div>
<div class=popular-listing-reviews>
<h1 class=popular-listing-title>
<span class=write-1><a href="#">WRITE A REVIEW</a></span> About <strong>Garland Moving Group</strong>
</h1>
</div>
<div class=sortedclass>
<div style=text-align:right>
<div class=filter_by1 style="width:25%;">
<label>Sort by
<select style="border:0; background:none; cursor:pointer;color: #3b65a7; padding: 0 5px 0 0;font-weight: bold;font-size:14px;">
	<option value="newest">Newest Reviews</option>
	<option value="oldest">Oldest Reviews</option>
	<option value="best">Highest Rated</option>
	<option value="worst">Lowest Rated</option>
</select></label>
</div></div>
</div>
<div class="reviewbox reviewbox-wid">
<div class=review-tag>
<div class=review-tag-left>
<i class="fa fa-user"></i>
<p class=review-tag-name>Maxwel Dockery</p>
<span class=review-tag-date>
May 17, 2018
</span>
</div>
<div class=review-tag-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
</p>
</div>
</div>
<p class=review-place>
<div class=truck></div>Interstate move from <span>New York</span> to <span>Florida</span>
</p>
<p class="review-para review-para-1">
Honestly, I am grateful. I am really thankful for the services I was accorded last. Through the help of Garland Movers crew, I was able to relocate to my current residence in Iowa. They ensure that it was done in the perfect of ways without even a single flaw being evident. <!-- Normally, so...<a href="#">Full review&ensp;<i class="fa fa-angle-right"></i></a>--></p>
<div class=revbtn >
<p class=sharebtn style="cursor:pointer;" data-toggle="modal" data-target="#myModal"><i class="fa fa-share" ></i> Share
</p>
<p class=cntbtn style="cursor:pointer;" data-toggle="modal" data-target="#myModal1">
<i class="fa fa-envelope-o"></i> Contact
</p>
</div>
</div>
<div class="reviewbox reviewbox-wid">
<div class=review-tag>
<div class=review-tag-left>
<i class="fa fa-user"></i>
<p class=review-tag-name>Maxwel Dockery</p>
<span class=review-tag-date>
May 17, 2018
</span>
</div>
<div class=review-tag-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
</p>
</div>
</div>
<p class=review-place>
<div class=truck></div>Interstate move from <span>New York</span> to <span>Florida</span>
</p>
<p class="review-para review-para-1">
Honestly, I am grateful. I am really thankful for the services I was accorded last. Through the help of Garland Movers crew, I was able to relocate to my current residence in Iowa. They ensure that it was done in the perfect of ways without even a single flaw being evident. <!-- Normally, so...<a href="#">Full review&ensp;<i class="fa fa-angle-right"></i></a>--></p>
<div class=revbtn>
<p class=sharebtn>
<a href="#"><i class="fa fa-share"></i> Share</a>
</p>
<p class=cntbtn>
<a href="#"><i class="fa fa-envelope-o"></i> Contact</a>
</p>
</div>
</div>
<div class="reviewbox reviewbox-wid">
<div class=review-tag>
<div class=review-tag-left>
<i class="fa fa-user"></i>
<p class=review-tag-name>Maxwel Dockery</p>
<span class=review-tag-date>
May 17, 2018
</span>
</div>
<div class=review-tag-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
</p>
</div>
</div>
<p class=review-place>
<div class=truck></div>Interstate move from <span>New York</span> to <span>Florida</span>
</p>
<p class="review-para review-para-1">
Honestly, I am grateful. I am really thankful for the services I was accorded last. Through the help of Garland Movers crew, I was able to relocate to my current residence in Iowa. They ensure that it was done in the perfect of ways without even a single flaw being evident. <!-- Normally, so...<a href="#">Full review&ensp;<i class="fa fa-angle-right"></i></a>--></p>
<div class=revbtn>
<p class=sharebtn>
<a href="#"><i class="fa fa-share"></i> Share</a>
</p>
<p class=cntbtn>
<a href="#"><i class="fa fa-envelope"></i> Contact</a>
</p>
</div>
</div>
<div class="reviewbox reviewbox-wid">
<div class=review-tag>
<div class=review-tag-left>
<i class="fa fa-user"></i>
<p class=review-tag-name>Maxwel Dockery</p>
<span class=review-tag-date>
May 17, 2018
</span>
</div>
<div class=review-tag-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
</p>
</div>
</div>
<p class=review-place>
<div class=truck></div>Interstate move from <span>New York</span> to <span>Florida</span>
</p>
<p class="review-para review-para-1">
Honestly, I am grateful. I am really thankful for the services I was accorded last. Through the help of Garland Movers crew, I was able to relocate to my current residence in Iowa. They ensure that it was done in the perfect of ways without even a single flaw being evident. <!-- Normally, so...<a href="#">Full review&ensp;<i class="fa fa-angle-right"></i></a>--></p>
<div class=revbtn>
<p class=sharebtn>
<a href="#"><i class="fa fa-share"></i> Share</a>
</p>
<p class=cntbtn>
<a href="#"><i class="fa fa-envelope-o"></i> Contact</a>
</p>
</div>
</div>
<div class="reviewbox reviewbox-wid">
<div class=review-tag>
<div class=review-tag-left>
<i class="fa fa-user"></i>
<p class=review-tag-name>Maxwel Dockery</p>
<span class=review-tag-date>
May 17, 2018
</span>
</div>
<div class=review-tag-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
</p>
</div>
</div>
<p class=review-place>
<div class=truck></div>Interstate move from <span>New York</span> to <span>Florida</span>
</p>
<p class="review-para review-para-1">
Honestly, I am grateful. I am really thankful for the services I was accorded last. Through the help of Garland Movers crew, I was able to relocate to my current residence in Iowa. They ensure that it was done in the perfect of ways without even a single flaw being evident. <!-- Normally, so...<a href="#">Full review&ensp;<i class="fa fa-angle-right"></i></a>--></p>
<div class=revbtn>
<p class=sharebtn>
<a href="#"><i class="fa fa-share"></i> Share</a>
</p>
<p class=cntbtn>
<a href="#"><i class="fa fa-envelope-o"></i> Contact</a>
</p>
</div>
</div>
<div class="reviewbox reviewbox-wid">
<div class=review-tag>
<div class=review-tag-left>
<i class="fa fa-user"></i>
<p class=review-tag-name>Maxwel Dockery</p>
<span class=review-tag-date>
May 17, 2018
</span>
</div>
<div class=review-tag-right>
<p class=stars>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checked"></span>
<span class="fa fa-star checkednot"></span>
</p>
</div>
</div>
<p class=review-place>
<div class=truck></div>Interstate move from <span>New York</span> to <span>Florida</span>
</p>
<p class="review-para review-para-1">
Honestly, I am grateful. I am really thankful for the services I was accorded last. Through the help of Garland Movers crew, I was able to relocate to my current residence in Iowa. They ensure that it was done in the perfect of ways without even a single flaw being evident.<!-- Normally, so...<a href="#">Full review&ensp;<i class="fa fa-angle-right"></i></a>--></p>
<div class=revbtn>
<p class=sharebtn>
<a href="#"><i class="fa fa-share"></i> Share</a>
</p>
<p class=cntbtn>
<a href="#"><i class="fa fa-envelope-o"></i> Contact</a>
</p>
</div>
</div>
<div class=load_more_cls><a href="#">Load More</a>&ensp;<i class="fa fa-angle-down"></i></div>
</div>
<div class=block-right>
<div class=cstm_get_quote>
<h6><i>Get Moving Quotes</i></h6>
<div class=cstm_frm>
<label> <input class=newinput name="" value="Move date"></label>
<label><input class=newinput name="" value="Where are you moving from?"></label>
<label><input class=newinput name="" value="Where are you moving to? "></label>
<div class=submit-new><a class=cstm_btn href="#">Continue</a> </div>
</div>
</div>
<?php 

$sql_dot="select power_units,usdot_number,mc,safety_url from company_dot_data where  company_name= 'BEST MOVING SERVICE- AMERICAN PRIDE MOVING SERVICE'";

$query_dot=mysql_query($sql_dot);

$res_dot=mysql_fetch_assoc($query_dot);

?>

<div class=mover-details>
<h3>Mover Details</h3>
<strong>Moving Company Business Info</strong><br><br>
<table style="width:100%">
<tr>
<td class="newtd new-pad">In Business Since</td><td class="newtd new-pad">2013</td>
</tr>
<tr>
<td class=" new-pad">Number of Trucks</td><td class=new-pad><?php echo $res_dot['power_units'];?></td>
</tr>
<tr>
<td class="newtd new-pad">USDOT#</td><td class="newtd new-pad"><a href="<?php echo $res_dot['safety_url'];?>" target="_blank" style="color:#0000FF;"><?php echo $res_dot['usdot_number'];?></a></td>
</tr>
<tr>
<td class=new-pad>ICC MC Number</td><td class=new-pad><a href="<?php echo $res_dot['safety_url'];?>" target="_blank" style="color:#0000FF;"><?php echo $res_dot['mc'];?></a></td>
</tr>
</table>
</div>


<div class=ppl_viewed>
<h2>People who viewed this also viewed</h2>
<div>
<?php 

$company = new company();

$sql_pplview="select Comany_ID,company_name,city,state from company where viewed=1 order by Rand() limit 4";

$query_pplview=mysql_query($sql_pplview);

while($res_pplview=mysql_fetch_assoc($query_pplview)){

$average = $company->get_average_reviews_count($res_pplview["Comany_ID"]);

$reviews_count = $company->get_company_reviews_count($res_pplview["Comany_ID"]);

?>
<div class=ppl_view>
<div class=ppl_view_left>
<img src="images/logo1.png" alt=Garland moving group>
</div>
<div class=ppl_view_right>
<div class=ppl_lft style="width:160px;">
<span><?= $res_pplview["company_name"]; ?></span>
<p><?=$res_pplview["city"].",".$res_pplview["state"];   ?>, USA</p>
</div>
<div class=ppl_ryt>
<p class=stars>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
<span class="fa fa-star  <?php if(round($average["avg(review)"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
</p>
<span style="font-size:13px;"><?php echo $reviews_count;?> Reviews</span>
</div>
</div>
</div>
<?php  }  ?>
</div>
</div>
<div class=cmprev>
<p>Write a Moving Company Review Here</p>
<h4>Company Name</h4>
<div class=search-box>
<input class=seach22 value="Looking For..." name=search>
<button type=submit><i class="fa fa-search"></i>&nbsp;Search</button>
</div>
</div>
<div class=nxt><h1>Read up before you make your next move</h1></div>

<div class="row newrow">
<div class="col-md-12">
  <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company-1.jpg"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/moving-checklist-how-to-prepare-for-the-moving-day/">How to Prepare for the Moving Day</a></div>
          <p>Most people are aware on how stressful the moving day can be. To help you lessen the load, we created a comprehensive ... </p>
            
</div>     
</div>
<!--end1-->
<div class="row newrow">
<div class="col-md-12">
 <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company.jpg"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/tips-on-how-do-you-find-a-moving-company/">Tips on how do you find a moving company.</a></div>
          <p>Moving is a very stressful and also pricey experience, as well as if you do not take precaution it can promptly become ... </p>
            
</div>     
</div>
<!--end1-->
<div class="row newrow">
<div class="col-md-12">
<img class="img-left" src="https://www.topmovingreviews.com/images/packing-guide.webp"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/packing-guide/">Packing Guide</a></div>
          <p>Packing can be an intensely challenging step when preparing to move. It is during this time that most damages to goods...</p>
            
</div>     
</div>
<!--end1-->
<div class="row newrow">
<div class="col-md-12">
 <img class="img-left" src="https://www.topmovingreviews.com/images/change.jpg"/>
          <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/change-your-address-when-you-move/">Change Your Address When You Move</a></div>
          <p>Before you can settle in, you have to ensure that your mail is updated to continue receiving your regular mail without any...</p>
            
</div>     
</div>
</div>
</div>

 <!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-weight:bold;">Share Review</h4>
        </div>
        <div class="modal-body">
        <div class="row">
		
		
  <div class="column">
  <div><select name=""></select></div>
<div>  <input name="" type="text" /></div>
  <div><input name="" type="text" /></div>
  <div><select name=""></select></div>
  <div><input name="" type="text" /></div>
  <div><input name="" type="text" /></div>
  <div><input name="" type="text" /></div>
  <div><input name="" type="text" /></div>
  </div>
  <div class="column">
  <div>Your Message:</div>
  <textarea name="" cols="" rows=""></textarea>
  </div>
			</div>
      
    </div>
  </div>
</div>
</div>
<?php include 'footer.php'; ?>

 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-weight:bold;">Share Review</h4>
        </div>
        <div class="modal-body">
         <div class="row">
  <div class="column"><a href="https://www.facebook.com/sharer/sharer.php?u=http://www.topmovingreviews.com/Garland-moving-group.php" target="_blank"><img src="images/fb.jpg" height="45"></a></div>
  <div class="column"><a href="https://twitter.com/intent/tweet?url=http://www.topmovingreviews.com/Garland-moving-group.php" target="_blank"><img src="images/tw.jpg"  height="45"></a></div>
			</div>
        </div>
        <div  style="padding:15px 10px 35px 50px;; text-align:left;">
          <input name="share" value="https://www.mymovingreviews.com/movers/long-distance-movers-5230" type="text" style="width:92%;height: 30px;background: url(images/share-company.png) no-repeat 4px 6px;cursor: pointer;padding: 5px 5px 5px 27px;" readonly="" onClick="this.focus();this.select()">
        </div>
      </div>
      
    </div>
  </div>




 <!-- Modal -->
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="font-weight:bold;">Send a message to Sandy Bruno</h4>
        </div>
        <div class="modal-body"><form name="contact_frm" method="post" action="" >
         	<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#FAFAFA">
					<tr >
						<td width="380" height="45" valign="top"><input placeholder="Your Name" name="contact_name" id="contact_name" required="" style="width:550px; border:1px solid #000000;border-radius: 3px; height:40px;"></td>
					</tr>

					<tr >
	<td width="380" height="45" valign="top"><input placeholder="Your E-mail" name="contact_email" id="contact_email" required="" style="width:550px; border:1px solid #000000;border-radius: 3px; height:40px;"></td>
					</tr>

				

					<tr >
						<td width="380" height="45" valign="top" ><input placeholder="Subject" name="subject" id="contact_subject" required="" style="width:550px; border:1px solid #000000;border-radius: 3px; height:40px;"></td>
					</tr>

					<tr >
						<td width="380" height="45" valign="top">
							Your message (please include contact information so the reviewer can reply back):<br>
							<textarea name="message" id="contact_message" rows="3" cols="43" style="width:550px; border:1px solid #000000;border-radius: 3px; height:90px;"></textarea>
						</td>
					</tr>
				</table>

       
        <div class=load_more_cls style="width:250px;" onClick="javascript:validate();"><a href="#">Send a Message to Sandy Bro</a>&ensp;<i class="fa fa-angle-down"></i></div>
      Ask Sandy Bruno about the services of 495 Movers. Sandy Bruno is a customer of the business and is willing to be used as a reference. Please use this form to contact this customer for reference purposes only.
      </form>
    </div>
  </div>



</body>
</html>

<?php header('Content-Type: text/html; charset=utf-8');
    require 'core/database.class.php';
	
	
	
	require_once 'cit_state_DBupdation.php';
	
//die;	
    ?>
<!DOCTYPE html>

<html lang="en">

<head>
        <title>Compare Movers - Top Moving Company Reviews </title>
 <link rel="icon" href="/favicon.png" type="image/png" sizes="16x16"> 
<meta name="description" content="Research for  Companies and plane your upcoming move with verified Movers and Rating. Get a free quote with a Licensed and insured Movers to service your needs.">
        <meta name="keywords" content="Top Moving Reviews, Company, Move">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="HandheldFriendly" content="true">
        <meta property=og:title content="National Moving Companies ">
        <meta property=og:description content="Our review of moving companies highlights 6 of the best options whether you need a van line or a moving container.">
        <meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">
        <meta property=og:url content=":https://www.topmovingreviews.com">
        <meta name=dc.language content=US>
        <meta name=dc.subject content="NY Movers">
        <meta name=DC.identifier content="/meta-tags/dublin/">
        <meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
        <meta name=HandheldFriendly content=true>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script data-ad-client="ca-pub-6233623795574036" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

<!--<link rel=stylesheet type="text/css" href="https://www.topmovingreviews.com/css/style.css">-->
       
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
        <link rel="stylesheet" href="https://topmovingreviews.com/css/font-awesome.min.css">
		<!-- Global site tag (gtag.js) - Google Analytics -->
		
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-157799891-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-157799891-1');
</script>
		
<script>
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
		<meta name="google-site-verification" content="iJjdbikQFRW00ZG8B69ChVCc1HdYFlu-hdkLaedWMEM" />
	
		
    </head>
          <div style="position:absolute; float:right; z-index:9999; right:0px; top:523px; width:20%">
       <div class="alert alert-success alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Moving To A New Home During The COVID-19 Pandemic</strong> There are several things to consider when planning to move to a new location during the coronavirus (COVID-19) pandemic. 
  <p><button type="button" class="btn btn-primary"><a href="https://www.topmovingreviews.com/Move/moving-to-a-new-home-during-the-covid-19-pandemic/" style="color:#fff">View Blog</a></button></p>
  </div>
           </div>
    <body id="shahaidul-body" onmouseover="loadcss" onLoad="doOnLoad();">
             
                        <?php 
                            //include 'newheader.php'; 
                            include 'newheader_backup.php'; 
                        ?>
        <script>function loadcss(url){var head=document.getElementsByTagName('head')[0],link=document.createElement('link');link.type='text/css';link.rel='stylesheet';link.href=url;head.appendChild(link);return link;} var button=document.getElementsByTagName('button')[0];button.addEventListener('click',function(ev){loadcss('');},false);</script>

       
       <!-- end of first section-->

<!--       
<script async src="https://cse.google.com/cse.js?cx=009714547418684844407:jrqtdjxn1zf"></script>
-->
<div class="gcse-search"></div>
       <div class="content">
                    <h1>Top 10 Recent Moving Company Reviews</h1>
                    <!-- <div class="d1">
                        <img src="images/special.jpg" alt="Top special" width="728" height="90">
                        </div>-->
                </div>
                <div class="content">
                    <?php
                        $companeiesId = [];
                        $companies =  "select distinct company_id from reviews order by date_saved desc limit 0,10";
                        $result = mysqli_query($link,$companies);
                        $count = 1;
                        while($recent_review = mysqli_fetch_array($result)){
                            array_push($companeiesId,$recent_review['company_id']);
                        }
                        
                        foreach($companeiesId as $id){
                            $companies_list = "SELECT * FROM companies where id =".$id;
                            $companies_sql = mysqli_query($link,$companies_list);
                            while($company = mysqli_fetch_array($companies_sql)){
                                $company_address=explode(",",$company['address']);
                                $countarray=count($company_address);
                                $state_code=substr($company_address[$countarray-2],1,2);
                                $sql_state=mysqli_query($link,"select name from states where state_code='$state_code' and usa_state=1");
                                $res_state=mysqli_fetch_array($sql_state);
                                
                                $reviews_count = "SELECT count(*) as cnt FROM reviews where company_id = ".$id;
                                $reviews_count_sql = mysqli_query($link,$reviews_count);
                                
                                $reviews_list = "SELECT * FROM reviews where company_id = $company[id] order by date_saved desc limit 0,1";
                                $reviews_sql = mysqli_query($link,$reviews_list);

                                // Getting the review for the company, to display a proper value
                                $sql = "SELECT * FROM reviews WHERE company_id='". $company['id']."'";
                                $query = mysqli_query($link,$sql);

                                $company_rating = 0;
                                if(mysqli_num_rows($query) > 0)
                                {
                                    $company_review_number = mysqli_num_rows($query);
                                    $review_total = 0;
                                    while ($obj = mysqli_fetch_object($query)) 
                                    {
                                        $review_total+=$obj->rating;
                                    }

                                    $company_rating = number_format($review_total/$company_review_number,1);
                                }

                                while($review = mysqli_fetch_array($reviews_sql)){
                                    ?>
                                    <div class="reviewbox newreviewbox">
                                        <div class="review-rating">
                                            <div class="review-logo hidelogo">
                                                <a href="https://www.topmovingreviews.com/movers/<?php echo str_replace('/','-',str_replace(' ', '-',$company['title']))."-". $company["id"];?>/">
                                                <?php
                                                  if($company["logo"] != NULL)
                                                  {
                                                    $logo_file = str_replace(" ", "", $company["logo"]);
                                                    $logo_file = str_replace("&nbsp;", "", $company["logo"]);
                                                    if(is_file($logo_file))
                                                    {
                                                      $logo_image = $logo_file;
                                                    }
                                                    else
                                                    {

                                                      $new_image = str_replace("mmr_images", "company", $logo_file);

                                                      if(is_file($logo_file))
                                                      {
                                                        $logo_image = $new_image;
                                                      }
                                                      else
                                                      {
                                                        $logo_image = "no";
                                                      }
                                                    }
                                                  }
                                                  else
                                                  {
                                                    $logo_image = "no";
                                                  }
                                                ?> 
                                                <img src="<?php if ($company["logo"]==NULL){echo "no";} else echo $company['logo'];?>" alt="<?php echo $res_recent_company['title'];?> Logo" width="200" height="70" ></a>
                                            </div>
                                            <div class="review-stars">
                                                <a href="https://www.topmovingreviews.com/movers/<?php echo str_replace('/','-',str_replace(' ', '-',$company['title']))."-". $company["id"];?>/"> <b > <?php echo $company['title'];?></b></a>
                                                &emsp;
                                                <span class=stars>
                                                    <span class="fa fa-star  <?php if(round($company_rating)>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                                    <span class="fa fa-star  <?php if(round($company_rating)>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                                    <span class="fa fa-star  <?php if(round($company_rating)>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                                    <span class="fa fa-star  <?php if(round($company_rating)>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                                    <span class="fa fa-star  <?php if(round($company_rating)>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                                </span>
                                                <br/>
                                                <span>
                                                    (<?php echo $company_review_number; ?> reviews)
                                                </span>
                                                <p class="review-rating-title"> <a href="https://www.topmovingreviews.com/usa/<?php echo $res_state['name']."-movers-".$state_code; ?>/"><?php echo $res_state['name']; ?> </a> <i>moving</i> company</p>
                                            </div>
                                        </div>
                                        <div class="review-by">
                                       
                                                <!--<img src="images/ico1.jpg"/>-->
                                                | by&ensp;<span><a href="https://www.topmovingreviews.com/movers/<?php echo str_replace('/','-',str_replace(' ', '-',$company['title']))."-". $company["id"];?>/" style="text-decoration:none;"> <?php echo $review["author"];?></a></span>
                                     
                                    
                                                <!--<img src="images/ico2.jpg">-->
                                                &ensp; | <?php if(strlen($review['move_location_text']) > 0){echo $review['move_location_text'];}?>
                                  
                                        </div>
                                        <p class="review-para">
                                            <?php echo  substr($review['text'],0,400); ?> 
                                            <a href="https://www.topmovingreviews.com/movers/<?php echo str_replace('/','-',str_replace(' ', '-',$company['title']))."-". $company["id"];?>/">
                                                ...Full review&ensp;
                                                <!--<img src="images/ico5.jpg">-->
                                            </a>
                                        </p>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    ?>
                </div>
          
       <section class="padd40">
      
           <div class="container padd40">
               <h2 class="text-center">Best TopMovingReviews Mover Ratings</h2>
               <div class="row padd40">
                   <div class="col-md-6"><img src="images/movers.png" width="100%"/></div>
                     <div class="col-md-6"><p class="p-1">
                         Finding a good and reputable moving company can sometimes be challenging. It is essential to read and understand the national company ratings to avoid inconveniences, such as property loss and damages when moving. TopMovingReviews gives you the most accurate and up-to-date testimonials and consumer reports on licensed and acknowledged moving companies. It is our duty and responsibility to keep you informed.</P>
<p class="p-1">Get an insight into what customers have to say about movers, their credibility reports as well as complaints and referrals. We have listed for you all prominent moving companies not forgetting the blacklisted ones. Also, leave a review to let others know your experience with your mover. 

                     </p>
</div>
                   
               </div>
               
           </div>
           
       </section>
                
<section class="blue-bg">
    <div class="container padd40"><h2 class="text-center">Popular Moving Guide</h2><div class="row">
        <div class="col-md-4"><div class="rvweqhgt"><div class="ppgdbx fadeInLeft wow animated" style="animation-delay: 0.15s;"><div class="popgds pdb15"><a href="https://www.topmovingreviews.com/Move/6-things-to-consider-before-the-relocation/">   6 Things to Consider Before the Relocation</a></div><div class="ctytxt pdb15"><span class="gdspntxt">Relocating can be exciting and stressful at the same time. It is a significant decision that you must carefully plan. Depending on the situation you are in, moving can be filled with anxiety...</span></div></div></div></div>
       <div class="col-md-4"><div class="rvweqhgt"><div class="ppgdbx fadeInLeft wow animated" style="animation-delay: 0.15s;"><div class="popgds pdb15"><a href="https://www.topmovingreviews.com/Move/moving-checklist-when-changing-your-address-3/">   Change Your Address When You Move</a></div><div class="ctytxt pdb15"><span class="gdspntxt">Before you can settle in, you have to ensure that your mail is updated to continue receiving your regular mail without any challenges.

The postal address changes each time you move. Because of this, you have to request the...</span></div></div></div></div>
                <div class="col-md-4"><div class="rvweqhgt"><div class="ppgdbx fadeInLeft wow animated" style="animation-delay: 0.15s;"><div class="popgds pdb15"><a href="https://www.topmovingreviews.com/Move/moving-checklist-how-to-prepare-for-the-moving-day/">  How to Prepare for the Moving Day</a></div><div class="ctytxt pdb15"><span class="gdspntxt">Most people are aware on how stressful the moving day can be. To help you lessen the load, we created a comprehensive moving checklist that will help you remain on the right path...</span></div></div></div></div>

        
        
        </div>
        
        
        
        <div class="row">
        <div class="col-md-4"><div class="rvweqhgt"><div class="ppgdbx fadeInLeft wow animated" style="animation-delay: 0.15s;"><div class="popgds pdb15"><a href="https://www.topmovingreviews.com/Move/tips-on-how-do-you-find-a-moving-company/">   Tips on how you find a moving company.</a></div><div class="ctytxt pdb15"><span class="gdspntxt">Moving is a very stressful and also pricey experience, as well as if you do not take precaution it can promptly become a nightmare. So exactly how do you find a moving company that you can ...</span></div></div></div></div>
        <div class="col-md-4"><div class="rvweqhgt"><div class="ppgdbx fadeInLeft wow animated" style="animation-delay: 0.15s;"><div class="popgds pdb15"><a href="https://www.topmovingreviews.com/Move/essential-tips-when-packing-your-closet-3/"> Packing Guide</a></div><div class="ctytxt pdb15"><span class="gdspntxt">Packing can be an intensely challenging step when preparing to move. It is during this time that most damages to goods occur on most occasions. Besides, this is the moment when different items are likely to be mixed, causing confusion when unpacking them.</span></div></div></div></div>
         <div class="col-md-4"><div class="rvweqhgt"><div class="ppgdbx fadeInLeft wow animated" style="animation-delay: 0.15s;"><div class="popgds pdb15"><a href="https://www.topmovingreviews.com/Move/hire-the-best-moving-company/">  Hire the best moving company</a></div><div class="ctytxt pdb15"><span class="gdspntxt">Perform an initial verification. Once you get a selection of highly recommended moving companies, browse the web to perform a quick overview inspection (you may do more detailed ...</span></div></div></div></div>

 

        
        
        </div>
        </div>
    
</section>                
                <div class="content bottomare">
                    <div class="nwwidth">
                        <div class="lft">
                            <h3 style="font-size: 23px;">Are You a <i>Moving Company?</i></h3>
                            <p>Find out how we can drive more free <br>business your way.</p><br/>
                            <a href="searchcompanybadge.php" class="popular-listing-new">Learn More</a>
                        </div>
                        <div class="rht">
                            <img src="images/box.jpg" alt="top-moving-box" width="280" height="162">
                        </div>
                    </div>
                </div>
                
<!--<section class="joinusbg">
    <div class="container padd40">
        <div class="row">
            <div class="col-md-6"><h2>Are you a Moving Company?</h2>
            <p class="jushd">Join the topmovingreviews.com Network!</p>
            <p class="ctytxt">Our partners have access to moving customers in need of a variety of moving services, as well as the tools and the assistance to increase their online exposure and grow their business. Add your company to the growing network of satisfied relocation professionals.</p>
            <p class="ctytxt"><b>Already with us?</b>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="javascript:window.open('https://www.topmovingreviews.com','','');">Sign In</a></p>
            </div>
            <div class="col-md-6">
                <div class="signup">
          <div class="row">
                    <div class="col-md-5">
                    <a href="#"><img src="https://www.topmovingreviews.com/images/logo.jpg" alt="verified mover"></a>
                    <p class="sealtxt">Grab This Seal. <a href="javascript:window.location='https://www.topmovingreviews.com';">Sign Up</a> Now.</p>
                </div>
                <div class="col-md-7">
                    <ul class="fetlst">
                    <li>Fill Out the Form</li>
                    <li>Choose Movers</li>
                    <li>Compare Quotes & Save</li>
                    
                    </ul>
                </div>
              
          </div>
            </div>
                
            </div>
            
        </div>
        
    </div>
</section>    -->            
                <div class="fooo">
                    <div id="footer_popular_cities" class="well well-lg center-block">
                        <h3 class="11" style="color:#FFFFFF;">Top City <i>Moving</i> Destinations</h3>
                        <br>
                        <div class="grid-row">
                            <div class="col-ms-3"> 
                                <a href="https://www.topmovingreviews.com/moving-companies/Atlanta-GA-30318/">Atlanta</a>																										                     <a href="https://www.topmovingreviews.com/moving-companies/Baltimore-MD-21230/">Baltimore</a> 
                                <a href="https://www.topmovingreviews.com/moving-companies/Boston-MA-02134/">Boston</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Chicago-IL-60639/">Chicago</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Dallas-GA-30318/">Dallas</a>
                            </div>
                            <div class="col-ms-3"> <a href="https://www.topmovingreviews.com/moving-companies/Denver-CO-80239/">Denver</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Houston-TX-77099/">Houston</a> 
                                <a href="https://www.topmovingreviews.com/moving-companies/Las-Vegas-NV-89113/">Las Vegas</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Los-Angeles-CA-90038/">Los Angeles</a>
                                <a  href="https://www.topmovingreviews.com/moving-companies/Miami-FL-33179/">Miami</a>
                            </div>
                            <div class="col-ms-3"> <a href="https://www.topmovingreviews.com/moving-companies/Nashville-TN-37211/">Nashville</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/New-York-NY-11223/">New York</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Orlando-FL-32805/">Orlando</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Philadelphia-PA-19135/">Philadelphia</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Phoenix-AZ-85040/">Phoenix</a>
                            </div>
                            <div class="col-ms-3"> <a href="https://www.topmovingreviews.com/moving-companies/San-Antonio-TX-78216/">San Antonio</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/San-Diego-CA-92121/">San Diego</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/San-Francisco-CA-94124/">San Francisco</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/San-Jose-CA-95131/">San Jose</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Seattle-WA-98115/">Seattle</a>
                                <a href="https://www.topmovingreviews.com/moving-companies/Washington-DC-DC-20001/">Washington DC</a>
                            </div>
                        </div>
                        <?php /*?><div class="footer_link">
                            <br>
                            <hr>
                            <br>
                            <h2 class="11" style="color:#FFFFFF; text-align:left">Find  Mover  in  Your  State</h2>
                            <br>
                            <?php  
                                $sql_state_code="select state_code,name from states where usa_state=1 ORDER BY RAND()";
                                $query_state_cocde=mysqli_query($sql_state_code);
								//$row=1;
                                while($res_state_code=mysqli_fetch_array($query_state_cocde))
                                	{	 		 
                                
                                 ?>
                          <a href="https://www.topmovingreviews.com/usa/<?php echo str_replace(' ', '-',$res_state_code['name']);?>-movers-<?php echo $res_state_code['state_code'];  ?>/"><?php echo $res_state_code['state_code'];  ?></a><span class="footer-line"> |</span> 
							 <?php  } ?>
                        </div>
                        <div class="clear"></div><?php */?>
                    </div>
                </div>
                <?php include 'footer.php'; //mysqli_close();?>
            </div>
        </div>
        <script>//setTimeout(function(){},5000);</script> 
       
    </body>
</html>
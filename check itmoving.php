<?php header('Content-Type: text/html; charset=windows-1252');
require_once('core/database.class.php');
require_once('core/company.class.php');



$bd         = new db_class();
$db_link    = $bd->db_connect();
$company_id = isset($_GET['id']) ? $_GET['id'] : 54;



 //$sql_company   = "SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email,companies.logo,companies.city,companies.state,companies.zipcode  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";
$sql_company    = "SELECT companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.website,companies.email,companies.logo,companies.city,companies.state,companies.zipcode  FROM  companies  , reviews    where companies.id=reviews.company_id and companies.id=$company_id";
$query_company  = mysql_query($sql_company);
$res_company    = mysql_fetch_array($query_company);
//$res_company    = mysql_fetch_row($query_company);

$compnay_address= explode(",",$res_company['address']);

$countarray     = count($compnay_address);



// Getting the review for the company, to display a proper value

$sql = "SELECT * FROM reviews WHERE company_id='".$company_id."'";
$query = mysql_query($sql);



$company_rating = 0;
$company_review_number = mysql_num_rows($query);
if($company_review_number  > 0)

{

	

	$review_total = 0;

	while ($obj = mysql_fetch_object($query)) 

	{

		$review_total+=$obj->rating;

	}



	$company_rating = number_format($review_total/$company_review_number,1);

}



// its for pagination done by srikanta 
$adjacents      = 3;
$tbl_name       = 'reviews';
$query          = "SELECT COUNT(*) as num FROM $tbl_name where  company_id=$company_id";
$total_pages    = mysql_fetch_array(mysql_query($query));
$total_pages    = $total_pages['num'];
/* Setup vars for query. */
$limit          = 10;
 $getpage        = @split("_", $_GET['compname'], 3);
 $countarraypage = count($getpage);
 $page           = $getpage[$countarraypage - 1];
if ($page > 1) {
    $reviews_word = "Reviews";
}

if (is_numeric($page)) {
    $page = $page;
    $compname = $getpage[0];
} else {
    $page = 1;
    $compname = $_GET['compname'];
}
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;        //if no page var is given, set start to 0
    /* Get data. */
$sql = "SELECT * FROM $tbl_name  where  company_id=$company_id order by str_to_date(date,'%b %d, %Y') desc LIMIT $start, $limit";
$result = mysql_query($sql);
/* Setup page vars for display. */
if ($page == 0)
    $page = 1;     //if no page var is given, default to 1.
$prev = $page - 1;       //previous page is page - 1
$next = $page + 1;       //next page is page + 1
$lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;      //last page minus 1
/* 	Now we apply our rules and draw the pagination object. 
  We're actually saving the code to a variable in case we want to draw it more than once.
 */
$pagination = "";
if ($lastpage > 1) {
    $pagination .= "<div class=\"pagination\"  ><ul>";
    //previous button
    if ($page > 1) {
        $pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname" . "_" . "$prev-$_GET[id]/\">Prev 10</a></li>";
    }
    $pagination.= "<li> <span class=\"pagination1\">$company_review_number" . " Reviews</span></li>";
    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
        /* if ($counter == $page)
          $pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
          else
          $pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname_$counter-$_GET[id]/\">$counter</a></li>"; */
    }
    //next button
    if ($page < $counter - 1)
        $pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname" . "_" . "$next-$_GET[id]/\">Next 10</a></li>";
    /* else
      $pagination.= "<li><span class=\"pagination1\">-></span></li>"; */
    $pagination.= "</ul></div>\n";
}
?>
<!DOCTYPE HTML >

<html lang="en">

<head>

<title><?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?></title>

<link href='https://fonts.googleapis.com/css?family=Montserrat:400&display=swap' rel='stylesheet'>
<!--<link href='fonts.googleapis.com/css?family=Montserrat:400'; rel='stylesheet' type='text/css'>-->
<!--<link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css?display=swap">-->

<!-- bar chart starts -->
<link href="../../assets/barstyles.css" rel="stylesheet" />
<style>
      
        #chart {
      max-width: 650px;
      margin: 35px auto;
    }
      
    </style>

    <script>
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
        )
    </script>

    
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-apexcharts"></script>
    
<!-- bar chart ends -->



 <link rel="icon" href="https://www.topmovingreviews.com/favicon.png" type="image/png" sizes="16x16"> 

<meta name="description" content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>">

<meta name=keywords content="<?php echo $res_company['title'];?>">

   <meta http-equiv="X-UA-Compatible" content="IE=edge" />

      <meta name="HandheldFriendly" content="true">

      <meta property=og:title content="<?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?>">

      <meta property=og:description content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>">

      <!--<meta property=og:image content="https://www.topmovingreviews.com/images/logo.jpg">-->

      <meta property=og:url content=":https://www.topmovingreviews.com/movers/Changing-Spaces-Moving-3616">

      <meta name=dc.language content=US>

      <meta name=dc.subject content="NY Movers">

      <meta name=DC.identifier content="/meta-tags/dublin/">

      <meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>

      <meta name=HandheldFriendly content=true>



      <meta name="viewport" content="width=device-width, initial-scale=1.0">



<!-- <link rel="stylesheet" type="text/css" href="https://www.topmovingreviews.com/css/stylez.css">-->
<link rel="stylesheet" type="text/css" href="../../css/stylez.css">



<meta property=og:title content="<?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?>">

<meta property=og:description content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>">

<meta property=og:image content="https://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg">

<meta property=og:site_name content="https://topmovingreviews.com">



<meta name="twitter:url" content="https://www.topmovingreviews.com/moving.php" />

<meta name="twitter:title" content="<?php echo $res_company['title']."-".$res_company['city'].", ".$res_company['state']." With ".$countarray ." Moving Reviews ";?>" />

<meta name="twitter:image" content="https://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id;?>.jpg"/>

<meta name="twitter:description" content="<?php echo $res_company['title']." Reviews and rating, " .$res_company['city'].",".$res_company['state']."."." Compare movers, get a free Moving quotes and pricing of ".date("F Y");?>" />

<meta name="twitter:site" content="@topmovingreviews" />

<?php  
$sllrl = "SELECT * FROM reviews  where  company_id=$company_id order by str_to_date(date,'%b %d, %Y') desc ";
$resultsllrl = mysql_query($sllrl);

$qrer        = " SELECT min(rating) as minRating , max(rating)  as bestRating FROM reviews where company_id=".$company_id; 
                $result111    = mysql_query($qrer);
                $resultt22    = mysql_fetch_array($result111);
                $nnresult     = mysql_num_rows($result111);
                
                $minRating    = $resultt22['minRating'];
                $bestRating   = $resultt22['bestRating'];
                
                //if($bestRating == $minRating) $minRating=0;
                if($nnresult > 0){
                    while ($res_reviews22 = mysql_fetch_array($resultsllrl)) { if (strlen($res_reviews22['text']) > 0) { $move_loca_text  = $res_reviews22['text']; } 
                
                    $author         = $res_reviews22['author'] ; 
                    $datepublush    = date('M d,Y', strtotime($res_reviews22['date']));
                    $move_lo_textsb = substr($move_loca_text, 0 , 150);
                    $rating         = round($res_reviews22["rating"]);
//                    "name": "'.$move_lo_textsb.'",
//                                "description": "'.$move_loca_text.'",
                    $str.='
                        {       "@type": "Review",
                                    "author": "'.$author.'",
                                "datePublished": "'.$datepublush.'",
                                
                                "reviewRating": {
                                    "@type": "Rating",
                                    "bestRating": "'.$bestRating.'",
                                    "worstRating": "'.$minRating.'",
                                    "ratingValue": "'.$rating.'"
                                }
                        },';    } $strr = rtrim($str, ',');   ?>

<?php /*,
                "review": [ 
                    <?php echo $strr;?>
                ]    */?>
<script type="application/ld+json">
            {
                "@context": "https://schema.org/",
                "@type": "Organization",
                "itemReviewed": {
                    "@type": "Moving Company",
                    "name": "<?php echo $res_company['title'] ;?>",
                    "author": "Top moving reviews",
                    "image": "https://topmovingreviews.com/mmr_images/logos/logo_<?php echo $company_id; ?>.jpg",
                    "address": {
                        "@type": "PostalAddress",
                        "streetAddress": "<?php echo $res_company['address'];?>",
                        "addressLocality": "<?php echo $res_company['city'];?>",
                        "addressCountry": "<?php echo $res_company['state'];?>"
                    }
                },
                "review": {
                    "@type": "Rating",
                    "bestRating": "<?php echo $bestRating;?>",
                    "worstRating": "<?php echo $minRating;?>",
                    "reviewCount": "<?php echo $company_review_number ;?>",
                    "ratingValue": "<?php echo $company_rating ;?>"
                }
            }
</script>
<?php } ?>
<style>
            /* CSS FOR PROGRESS BAR REVIEW COUNT AND NUMBERS*/
            .progress-value.review-count {
                width: 50px!important;
            }
            .progress-custom {
                width: 120%!important;
            }
            .progress-custom .progress-value {
                vertical-align: inherit!important;
                font-size: 20px!important;
            }
            /* CSS FOR PROGRESS BAR REVIEW COUNT AND NUMBERS */
            .morelink{
                color:#0188cc!important;
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
            .pagination
            {
                padding-left:400px;
            }
            .pagination ul>li {
                display: inline;
                float:left;


            }.pagination a {
                color: #000;
                float: left;
                padding: 8px 16px;
                text-decoration: none;
                border:none !important;

            }
            @media (max-width: 481px)  {.hiderseach{font-size:18px;}}
        </style>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

<script>

            $(document).ready(function () {




                $("#MovingTo").keyup(function () {



                    $.ajax({
                        type: "POST",
                        url: "../../process/readService.php",
                        data: 'MovingTo=' + $(this).val(),
                        beforeSend: function () {



                            $("#MovingTo").css("background", "#FFF no-repeat 165px");



                        },
                        success: function (data) {



                            $("#suggesstion-box1").show();



                            $("#suggesstion-box1").html(data);



                            $("#MovingTo").css("background", "#FFF");



                        }



                    });



                });



            });







            function selectCountry1(val) {



                $("#MovingTo").val(val);



                $("#suggesstion-box1").hide();



            }

var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();
            if (dd < 10)
            {
                dd = '0' + dd;
            }
            if (mm < 10)
            {
                mm = '0' + mm;}
            today = mm + '-' + dd + '-' + yyyy;var myCalendar;function doOnLoad() {
                myCalendar = new dhtmlXCalendarObject("calendarHere");
                myCalendar.hideTime();
                myCalendar.setDate(getdate());
            }
            function setFrom() {
                myCalendar.setSensitiveRange(today, null);
            }
        </script>

</head>

<body onLoad="doOnLoad();">

<div class=wrapper>

<?php require_once('movingheader.php'); ?>

<div class="container m-top-35">

    

        
<div class="jet jet-1">
                    <div class="jeft-newleft">
                        <div class="row">
                            <?php
                            $img = $res_company["logo"];
                            $mmrimg = "https://www.topmovingreviews.com/mmr_images/logos/logo_" . $company_id . ".jpg";
                            $compimg = "https://www.topmovingreviews.com/company/logos/logo_" . $company_id . ".jpg";

                            if ($res_company["logo"] != NULL) {

                                if (@getimagesize($mmrimg) != '' && stristr($res_company["logo"], "topmovingreviews.com")) {

                                    $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_" . $company_id . ".jpg";
                                } else if (@getimagesize($compimg) != '' && stristr($res_company["logo"], "topmovingreviews.com")) {

                                    $logo_image = "https://www.topmovingreviews.com/company/logos/logo_" . $company_id . ".jpg";
                                } else if (stristr($res_company["logo"], "mymovingreviews.com")) {
                                    $logo_image = $img;
                                } else {
                                    $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
                                }
                            } else {
                                $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
                            }
                            ?>
                            <div class="col-md-4 text-left">
                                <img src="<?php echo $logo_image; ?>"   alt="<?php echo $res_company['title']; ?> - <?php echo $res_company['state']; ?> -<?php echo $res_company['city']; ?> - Reviews">
                                <div class="btn btn-orange" style="cursor:pointer; margin-top:15px" onClick="window.open('https://www.topmovingreviews.com/movercontact.php?company_id=<?php echo $company_id; ?>&title=<?php echo $res_company['title']; ?>&rating=<?php echo round($company_rating); ?>&email=<?php echo $res_company['email']; ?>&image=<?php echo $img; ?>', 'popUpWindow', 'height=550,width=550,left=400,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                                <!--<i class="fa fa-envelope-o"></i>-->Contact Mover
                                </div></div>
                            <div class="col-md-8 text-left m-top-35">
                                <h1 class="colorclass" style="text-align:left!important; "><i><?php echo $res_company['title'] . " " . $reviews_word; ?></i> </h1>
                                <p class=stars>
                            <?php /* ?><span class="fa fa-star  <?php if(round($company_rating)>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                              <span class="fa fa-star  <?php if(round($company_rating)>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                              <span class="fa fa-star  <?php if(round($company_rating)>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                              <span class="fa fa-star  <?php if(round($company_rating)>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                              <span class="fa fa-star  <?php if(round($company_rating)>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                              <?php */ ?>
                                <div class="rating-wrap">
                                    <div  class="rating-stars rating_<?php echo round($company_rating); ?>"></div>
                                </div>
                                <span class=rating><strong style="color:#000;"><?php echo $company_rating; ?> </strong> (<span ><?php echo $company_review_number; ?></span> Reviews)</span>&emsp;
                                <!--<span ><a class="btn btn-orange" href="https://www.topmovingreviews.com/add_reviews.php?company_id=<?php echo $res_company['id']; ?>"><i class="fa fa-star"></i>&nbsp;Write a Review</a></span>
                                -->
                                <!--</p>-->
                                <!--<div  class=jet-share>
                                <a href="#"><i class="fa fa-share"></i> Share</a>
                                </div>-->
                            </div></div>
                        <div class=>
                            <div class=htag>
                            </div>
                            <div class=info>
<?php if ($res_company['address'] <> "") { ?>
                                    <p><div class=location></div> 
                                    <span ><?php echo $res_company['address']; ?> </span><!--| <a rel="nofollow" href="https://www.topmovingreviews.com/map.php?query=<?php echo substr(str_replace(",", "%2C", str_replace(" ", "+", $res_company['address'])), 0, -4); ?>" target="_blank">View On Map</a>--></p><?php } ?>
<?php
$phonw = $res_company['phone'];
if ($phonw <> "") {
    if (strpos($phonw, ',') !== false) {
        $expar = explode(',', $phonw);
        $phone1 = $expar[0];
        $phone2 = $expar[1];
        ?>
                                        <p><div class=headphone></div> Toll Free:  <span > <?php echo $phone1; ?> </span>| <div class=call></div> Phone:  <?php echo $phone2; ?>
                                        </p>
                                        <?php } else {
                                        ?>
                                        <p><div class=headphone></div> Toll Free:  <span ><?php echo $phonw; ?> </span>| <div class=call></div> Phone:  <?php echo $phonw; ?>
                                        </p>        
                                        <?php
                                    }
                                }
                                ?>
                                <p><?php if ($res_company['email'] <> "") { ?><div class=mail></div> <?php echo $res_company['email']; ?> | <?php } if ($res_company['website'] <> "") { ?> <div class=web></div><?php echo $res_company['website'];
                            } ?></p>
                            </div>
                        </div></div>
                    <div class="jeft-right">
                        <div class="shadow-lg p-10 mb-5 bg-white rounded">
                            <h5 class="text-center"><i>The quick and easy money saver</i></h5>
                            <form id="frm1" name="frm1" method="post" action="https://www.topmovingreviews.com/quoteform.php" autocomplete="off">
                                <div class=cstm_frm>
                                    <label> <input  class=newinput  placeholder="Moving Date" onFocus="this.value = ''"  id="calendarHere"  name="calendarHere" onClick="setFrom();" readonly="" required></label>
                                    <label><input class=newinput  placeholder="From Zip" onFocus="this.value = ''" name="MovingFrom" id="MovingFrom" maxlength="5" ></label>
                                    <label><input class=newinput  placeholder="To Zip or City, State" onFocus="this.value = ''" name="MovingTo" id="MovingTo"></label>
                                    <div id="suggesstion-box1"></div>


                                    <div class=submit-new><a  onClick="javascript:return validate()" style="cursor:pointer;">Continue</a> </div>
                                    <p>Save up to 45% off moving costs.</p>
                                </div>
                            </form>
                        </div>

                    </div>
                </div><!---end-left--->

<div><hr/></div>

<div class=block-left>
                    <div style="margin-top:3px; font-size:13px;"><b><a href="https://www.topmovingreviews.com" style="color:#999999;" >
                <!--<i class="fa fa-home" aria-hidden="true"></i>-->
                                <img src="https://www.topmovingreviews.com/images/home-icon.png" width="18px"/>
                            </a>, <a href="https://www.topmovingreviews.com/moving-company.php" style="color:#999999;" >
                                Movers</a>, <a  style="color:#999999;"><?php echo str_replace('-', ' ', $res_company['title']); ?></a></b></div>
                    <div class="row " style="margin-top:20px">
                        <!--Review Summary 18-05-2020-->
<?php
$query_summary = "SELECT id,rating FROM reviews where  company_id=$company_id";
$result_summary = mysql_query($query_summary);
// $res_summary=mysql_fetch_assoc($result_summary);
$res_sumarray = [];
while ($res_summary = mysql_fetch_assoc($result_summary)) {
    $res_sumarray[] = $res_summary;
}
$counts = array_count_values(array_column($res_sumarray, 'rating'));
$max = 0;
$p_one = "";
$p_two = "";
$p_three = "";
$p_four = "";
$p_five = "";


foreach ($counts as $key => $value) {
            
            
            switch ($key) {
                case 1:
                    $p_one = $value;
                case 2:
                    $p_two = $value;
                case 3:
                    $p_three = $value;
                case 4:
                    $p_four = $value;
                //case 5:
                default:
                    $p_five = $value;
            }
        }

$round = 1;
$num = count($res_sumarray);
$p_oneavg = "";
$p_twoavg = "";
$p_threeavg = "";
$p_fouravg = "";
$p_fiveavg = "";
if ($num > 0) {
    $p_oneavg = round($p_one / $num * 100, $round);
    $p_twoavg = round($p_two / $num * 100, $round);
    $p_threeavg = round($p_three / $num * 100, $round);
    $p_fouravg = round($p_four / $num * 100, $round);
    $p_fiveavg = round($p_five / $num * 100, $round);
}
// $max = 0;
foreach ($res_sumarray as $rate => $count) {
    $max = $max + $count['rating'];
}
$avg_rating = '';
if ($num > 0) {
    $Average_rating = floatval($max / $num);
}
$avg_rating = number_format((float) $Average_rating, 1, '.', '');
?>
                        <!--Review Summary 18-05-2020-->
                        <div class="col-md-6 reviiie">
                            <h3>Review Summary
                            </h3>
                            <div class="progress-custom">

                                <div class="progress-value" >
                                    5
                                </div>
                                <div class="progress"> 

                                    <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_fiveavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress-value review-count">
                        <?= ($p_five == '') ? '0' : $p_five ?>
                                </div>
                            </div>
                            <div class="progress-custom">
                                <div class="progress-value" >
                                    4
                                </div>
                                <div class="progress"> 

                                    <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_fouravg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
                                </div>
                                <div class="progress-value review-count">

<?= ($p_four == '') ? '0' : $p_four ?>
                                </div>
                            </div>
                            <div class="progress-custom">

                                <div class="progress-value" >
                                    3
                                </div>
                                <div class="progress"> 

                                    <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_threeavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
                                </div>
                                <div class="progress-value review-count">

<?= ($p_three == '') ? '0' : $p_three ?>
                                </div>
                            </div>
                            <div class="progress-custom">

                                <div class="progress-value" >
                                    2
                                </div>
                                <div class="progress"> 

                                    <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_twoavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
                                </div>
                                <div class="progress-value review-count">
<?= ($p_two == '') ? '0' : $p_two ?>
                                </div>
                            </div>
                            <div class="progress-custom">

                                <div class="progress-value" >
                                    1
                                </div>
                                <div class="progress"> 

                                    <div class="progress-bar bg-orange" role="progressbar" style="width: <?php echo $p_oneavg; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
                                </div>
                                <div class="progress-value review-count">
<?= ($p_one == '') ? '0' : $p_one ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-grey pull-right " href="https://www.topmovingreviews.com/add_reviews.php?company_id=<?php echo $res_company['id']; ?>">WRITE A REVIEW</a>
                            <div class="totals-wrap text-align:center">
                                <div class="average">
<?php echo $avg_rating ?>
                                </div>
                                <div class="count">
<?php echo $num; ?> reviews                        </div>
                            </div>

                        </div>
                    </div>
                    <!--<p class="more-info text-center"><strong>Your trust is our top concern</strong>, so businesses can't pay to alter or remove their reviews.</p>
                    -->
                    <div class=popular-listing-reviews style="margin-top:40px">
                        <!--<h3 class=popular-listing-title>
                        <span class=write-1 style="font-size:26px;"><a href="https://www.topmovingreviews.com/add_reviews.php?company_id=<?php echo $res_company['id']; ?>">WRITE A REVIEW</a></span> About <strong><?php echo $res_company['title']; ?></strong>
                        </h3>-->
                    </div>
                    <div class="row"><div class="col-md-6"><b>(<?php echo $res_company['count(*)']; ?> Reviews) for  <a href="" style="color:#000"><?php echo $res_company['title']; ?></a></b></div>
                        <div class=" col-md-6">
                            <div style=text-align:right>
                                <div class=filter_by1>
                                    <label>Sort by
                                        <select style="border:0; background:none; cursor:pointer;color: #3b65a7; padding: 0 5px 0 0;font-weight: bold;font-size:14px;">
                                            <option value="newest">Newest Reviews</option>
                                            <option value="oldest">Oldest Reviews</option>
                                            <option value="best">Highest Rated</option>
                                            <option value="worst">Lowest Rated</option>
                                        </select></label>
                                </div></div>
                        </div>
                    </div>
<?php
while ($res_reviews = mysql_fetch_array($result)) {
    ?>
                        <div class="reviewbox reviewbox-wid newreviewbox" style="margin-top:0px;">
                            <div class=review-tag>
                                <div class="row">
                                    <div class="col-md-8"><div class=review-tag-left>
                            <!--<i class="fa fa-user"></i>
                                            --><p class=review-tag-name><b><?php echo $res_reviews['author']; ?></b><br/>
                                                <span style="font-size:12px"><?php echo date('M d,Y', strtotime($res_reviews['date'])); ?></span>
                                            </p>
                                        </div></div>
                                    <div class="col-md-4 text-right"><p class=stars>
                                        <div class="rating-wrap">
                                            <div class="float-right rating-stars rating_<?php echo round($res_reviews["rating"]); ?>"></div>
                                        </div>
                                        </p></div>
                                </div>
                                <div class=review-tag-right>
                                </div>
                            </div>
                            <p class=review-place>
                                <!--<div class=truck></div>--><?php if (strlen($res_reviews['move_location_text']) > 0) {
        echo $res_reviews['move_location_text'];
    } ?>
                            </p>
                            <div class="row m-top-35">
                                <div class="col-md-12"><p class="review-para review-para-1 more ">
    <?php echo $res_reviews['text']; ?> </p>
                                    <div class=revbtn>
                                        <p class=sharebtn style="cursor:pointer;" onClick="window.open('https://www.topmovingreviews.com/share.php?comp_name=<?php echo $res_company['title']; ?>&id=<?php echo $res_company['id']; ?>', 'popUpWindow', 'height=220,width=520,left=400,top=200,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                                            <i ></i> Share
                                        </p>
                                        <!--<span class="form-control col-md-2 pull-right text-center m-bttom">
    <?php echo date('M d,Y', strtotime($res_reviews['date'])); ?></span>-->
    <?php /* ?><?php if($res_reviews['wants_to_contact']==1) { ?><?php */ ?>
                                        <!--<p class=cntbtn style="cursor:pointer;" onClick="window.open('https://www.topmovingreviews.com/contact_user.php?author=<?php echo $res_reviews['author']; ?>','popUpWindow','height=460,width=620,left=400,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                                        <i class="fa fa-envelope-o"></i> Contact
                                        </p>--><?php /* ?><?php } ?><?php */ ?>
                                    </div></div>
                            </div>
                        </div>
<?php } ?>
                    <!--<div class=load_more_cls><a href="#">Load More</a>&ensp;<i class="fa fa-angle-down"></i></div>-->
<?= $pagination ?>
                    
                    
<?php
$sql_dot = "select power_units,usdot_number,mc,safety_url,company_url,date_format(granted_date,\"%m-%d-%Y\") as granted_date from company_dot_data where  company_name= '$res_company[title]' and state='$res_company[state]' and city='$res_company[city]'";
$query_dot = mysql_query($sql_dot);
$res_dot = @mysql_fetch_assoc($query_dot);
?>
                    
                    <br>
                    <h2>Company Info</h2>
                    <br>
                    <br>
                    <p class="review-para-1" style="font-size:18px !important;">
                    <?php echo $res_company['title'];
                    if ($res_dot['granted_date'] <> '') {
                        echo "Start doing business after " . $res_dot['granted_date'] . ",";
                    } ?> located in <?php echo $res_company['address'].','.$res_company['city'] .','.$res_company['zip']  .','.$res_company['state'].' , U.S.A'; ?>  <a rel="nofollow" href="https://www.topmovingreviews.com/map.php?query=<?php echo substr(str_replace(",", "%2C", str_replace(" ", "+", $res_company['address'])), 0, -4); ?>" target="_blank">View On Map</a>, but moving companies covered larger areas includes states nearby.
                    </p>
                    <br>
                    <br>
                    <h2>Licenses & Certificates for <?php echo $res_company['title']; ?></h2>
                    <br>
                    <br>
                    
                    <p class="review-para-1" style="font-size:18px !important;">
                        Moving companies are required to register with the FMSCA (Federal Motor Carrier Safety Administration) before they can perform interstate moves. 
                        You may click on <?php echo $res_company['title'];?> license numbers if you would like to view their record information. 
                    </p>
                    <br>
                    <br>
                    <p class="review-para-1" style="font-size:18px !important;">
                        ICC MC number: 
                        <a href="<?php echo $res_dot['company_url']; ?>" target="_blank">
                            <?php if ($res_dot['mc'] == '') {
                                echo "Not Updated";
                            } else {
                                echo "MC-" . $res_dot['mc'];
                            } ?>
                        </a>
                        <br>
                        (Interstate Commerce Commission Motor Carrier number)
                    </p>
                    <br>
                    <br>
                    <p class="review-para-1" style="font-size:18px !important;">US D.O.T: 
                            <a href="<?php echo $res_dot['company_url']; ?>" target="_blank">
                                <?php 
                                    echo $res_dot['usdot_number'] == '' ?  'Not Updated' : $res_dot['usdot_number'];
                                 ?>
                            </a>
                        <br>
                        (US Department of Transportation number)
                    </p>
                    <br>
                    <br>
                    <p class="review-para-1" style="font-size:18px !important;">Local State License: <span style="color:#FF0000;">not provided</span></p>
                    
                    
                    
                    <h2>What is <?php echo $res_company['title']; ?> cost for Out of State moves?</h2>
                    <br>
                    <br>
                    <p class="review-para-1" style="font-size:18px !important;">
                        Based on reviews from our reviews database and information provided by our partners and reliable sources of people moving interstate, the estimated average costs, moving from International Van line Hub state or states nearby, are as follows.
                        <?php  
                        
                            $sql_pric           = "select  * FROM  company_pricing  WHERE  company_id = $company_id" ; 
                            $query_ppany        = mysql_query($sql_pric);
                            $res_ppp            = mysql_fetch_array($query_ppany);
                            
                            $Northeast          = $res_ppp['Northeast'];
                            $SouthEast          = $res_ppp['SouthEast'];
                            $West               = $res_ppp['West'];
                            $Northwest          = $res_ppp['Northwest'];
                            $NorthMedwest       = $res_ppp['NorthMedwest'];
                            $SouthMedwest       = $res_ppp['SouthMedwest'];
                          
                            
                            // satte avergae
                            $state              = $res_company['state']; 
                            $sql_state_avg      = "
                                SELECT state_average.* FROM 
                                `state_average` 
                                inner join states on state_average.state = states.name 
                                where states.state_code =   '$state' "; 
                            
                            $sql_state_a        = mysql_query($sql_state_avg);
                            $sql_sta            = mysql_fetch_array($sql_state_a);
                            
                            $avgNortheast       = $sql_sta['Northeast'];
                            $avgSouthEast       = $sql_sta['SouthEast'];
                            $avgWest            = $sql_sta['West'];
                            $avgNorthwest       = $sql_sta['Northwest'];
                            $avgNorthMedwest    = $sql_sta['NorthMedwest'];
                            $avgSouthMedwest    = $sql_sta['SouthMedwest'];
                          
                            
                            
                            
                            
                        ?>
                    <br>
                    <br>
                    <style>
                        .progress-custom .progress-value{
                            font-size: 12px !important;width: 36%;
                        }
                        .bg-green{ background-color: green !important ; }
                    </style>
                        <?php 
                        $Region          = $res_ppp['Region']; 
                        
                        
                        //region must exists for pricing 
                        if(!empty($Region ) ){ 
                            
                            
                            switch ($Region) {
                                case 'North East':
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false){
                                        
                                        echo  "Moving to the South East would be around $". $SouthEast.'.';
                                        
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        
                                        
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        //for postive ones
                                        //if($SouthEastval  >= 1 ){
                                            
                                        //}else{
                                            //$noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        //}
                                    }
                                    
                                    if(!empty($West) or strpos($West , '- ') !== false){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
                                    
                                        //$avgWest.'-'.$West ;
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        
                                        $ofWest   = round($ofWest, 1);
                                        if(!empty($ofWest)){
                                            $ofWeststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        //echo $avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

                                        
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if(!empty($ofnoNorthwest)){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="'.$ofnoNorthwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    
                                    if(!empty($NorthMedwest ) or strpos($NorthMedwest , '- ') !== false){
                                        echo  " Moving to the North Med West would be around $". $NorthMedwest.'.';
                                        
                                        $NorthMedwest_arr[] = $NorthMedwest.'-'.$avgNorthMedwest; 
                                        
                                
////////z.;.                                         
                                        $NorthMedwestdiff = $avgNorthMedwest-$NorthMedwest;
                                        $noNorthMedwest   = $NorthMedwestdiff/ $avgNorthMedwest;
                                        
                                        $ofnoNorthMedwest = $noNorthMedwest* 100;
                                        if(!empty($ofnoNorthMedwest)){
                                            $ofnoNorthMedweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North Med West $'. $NorthMedwest.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthMedwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false){
                                        echo  " Moving to the South Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        $ofnoSouthMedwest   = round($ofnoSouthMedwest, 1);
                                        
                                        if(!empty($ofnoSouthMedwest)){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width:'.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    break;
                                case 'South East':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false){
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    
                                    if(!empty($West) or strpos($West , '- ') !== false){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
                                        //$avgWest.'-'.$West ;

//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        
                                        $ofWest   = round($ofWest, 1);
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

                                        
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        
//                                        die('Northwest2');
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($NorthMedwest ) or strpos($NorthMedwest , '- ') !== false){
                                        echo  " Moving to the North Med West would be around $". $NorthMedwest.'.';
                                        $NorthMedwest_arr[] = $NorthMedwest.'-'.$avgNorthMedwest; 
                                        
                                        $NorthMedwestdiff = $avgNorthMedwest-$NorthMedwest;
                                        $noNorthMedwest   = $NorthMedwestdiff/ $avgNorthMedwest;
                                        
                                        $ofnoNorthMedwest = $noNorthMedwest* 100;
                                        if($ofnoNorthMedwest ){
                                        $ofnoNorthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North Med West $'. $NorthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthMedwest.'%" aria-valuenow="'.$ofnoNorthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false){
                                        echo  " Moving to the South Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                    }
                                    break;
                                case 'West':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false){
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false){
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }

                                    }
                                    
                                    
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if(!empty($ofnoNorthwest)){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="'.$ofnoNorthwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    
                                    
                                    if(!empty($NorthMedwest ) or strpos($NorthMedwest , '- ') !== false){
                                        echo  " Moving to the North Med West would be around $". $NorthMedwest.'.';
                                        $NorthMedwest_arr[] = $NorthMedwest.'-'.$avgNorthMedwest; 
                                        
                                        
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        
                                        
                                        $NorthMedwestdiff = $avgNorthMedwest-$NorthMedwest;
                                        $noNorthMedwest   = $NorthMedwestdiff/ $avgNorthMedwest;
                                        
                                        $ofnoNorthMedwest = $noNorthMedwest* 100;
                                        if(!empty($ofnoNorthMedwest)){
                                            $ofnoNorthMedweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North Med West $'. $NorthMedwest.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthMedwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false){
                                        
                                        
                                        
                                        echo  " Moving to the South Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        $ofnoSouthMedwest   = round($ofnoSouthMedwest, 1);
                                        
                                        if(!empty($ofnoSouthMedwest)){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                    <div class="progress"> 
                                                        <div class="progress-bar bg-green" role="progressbar" style="width:'.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                            </div>
                                                        </div>';
                                        }
                                    }
                                    break;
                                    
                                    
                                case 'North West':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false){
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }

                                    
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false){ 
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($West) or strpos($West, '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 

//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        $ofWest   = round($ofWest, 1);
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($NorthMedwest ) or strpos($NorthMedwest , '- ') !== false){
                                        echo  " Moving to the North Med West would be around $". $NorthMedwest.'.';
                                        $NorthMedwest_arr[] = $NorthMedwest.'-'.$avgNorthMedwest; 
                                        
                                        
                                        $NorthMedwestdiff = $avgNorthMedwest-$NorthMedwest;
                                        $noNorthMedwest   = $NorthMedwestdiff/ $avgNorthMedwest;
                                        
                                        $ofnoNorthMedwest = $noNorthMedwest* 100;
                                        if($ofnoNorthMedwest ){
                                        $ofnoNorthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North Med West $'. $NorthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthMedwest.'%" aria-valuenow="'.$ofnoNorthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false){
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        echo  " Moving to the South Med West would be around $". $SouthMedwest.'.';
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                    }
                                    break;
                                    
                                    
                                    
                                    
                                    
                                case 'North Med West':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false){
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false){
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($West) or strpos($West , '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
                                        $Westdiff   = $avgWest-$West;
                                        $noWest     = $Westdiff/ $avgWest;
                                        
                                        $ofWest     = $noWest * 100;
                                        
                                        $ofWest   = round($ofWest, 1);
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($Northwest) or strpos($Northwest, '- ') !== false){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

//                                        die('Northwest4');
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false){
                                        echo  " Moving to the South Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                    }
                                    break;
                                    
                                    
                                    
                                    
                                    
                                case 'South Med West':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false) { 
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false) { 
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($West) or strpos($West , '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        if($ofWest){
                                            $bar_str2 = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

//                                        die('Northwest5');
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    
                                    }
                                    if(!empty($NorthMedwest ) or strpos($NorthMedwest , '- ') !== false) {
                                        echo  " Moving to the North Med West would be around $". $NorthMedwest.'.';
                                        
                                        $NorthMedwest_arr[] = $NorthMedwest.'-'.$avgNorthMedwest; 
                                        
                                        $NorthMedwestdiff = $avgNorthMedwest-$NorthMedwest;
                                        $noNorthMedwest   = $NorthMedwestdiff/ $avgNorthMedwest;
                                        
                                        $ofnoNorthMedwest = $noNorthMedwest* 100;
                                        if($ofnoNorthMedwest ){
                                        $ofnoNorthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North Med West $'. $NorthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthMedwest.'%" aria-valuenow="'.$ofnoNorthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    break;
                                    
                                case 'Far North East':
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false) {
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    
                                    }
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

//                                        die('Northwest6');
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($West) or strpos($West, '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        
                                        $ofWest   = round($ofWest, 1);
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($NorthMedwest ) or strpos($NorthMedwest , '- ') !== false) {
                                        echo  " Moving to the North Med West would be around $". $NorthMedwest.'.';
                                        $NorthMedwest_arr[] = $NorthMedwest.'-'.$avgNorthMedwest; 
                                        
                                        $NorthMedwestdiff = $avgNorthMedwest-$NorthMedwest;
                                        $noNorthMedwest   = $NorthMedwestdiff/ $avgNorthMedwest;
                                        
                                        $ofnoNorthMedwest = $noNorthMedwest* 100;
                                        if($ofnoNorthMedwest ){
                                        $ofnoNorthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North Med West $'. $NorthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthMedwest.'%" aria-valuenow="'.$ofnoNorthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false) {
                                        echo  " Moving to the South Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                        
                                    }
                                    break;
                                    
                                case 'Med East':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false) { 
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false) { 
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        
                                        
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    
                                    }
                                    if(!empty($West) or strpos($West , '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        
                                        $ofWest   = round($ofWest, 1);
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($Northwest) or strpos($Northwest, '- ') !== false ){
                                        echo  " Moving to the West would be around $". $Northwest.'.';
                                        
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        
                                        $Northwestdiff = $avgNorthwest-$Northwest;
                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
                                        
                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false) { 
                                        echo  " Moving to the Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                         $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                        
                                    }
                                    break;
                                    
                                    
                                case 'Far Med West':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false) {
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false) { 
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($West) or strpos($West , '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

//                                        die('Northwest7');
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false) {
                                        echo  " Moving to the Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        
                                         $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                        
                                    }
                                    break;
                                    
                                case 'Far North Med west':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false) { 
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false) { 
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($West) or strpos($West , '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
                                        
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

//                                        die('Northwest8');
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        
//                                        $ofnoNorthwest = $noNorthwest* 100;
                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false) { 
                                        echo  " Moving to the Med West would be around $". $SouthMedwest.'.';
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                    }
                                    break;
                                    
                                case 'Middle North West':
                                    if(!empty($NorthEast) or strpos($NorthEast, '- ') !== false) {
                                        echo  " Moving to the North East would be around $". $NorthEast.'.';
                                        
                                       $NorthEast_arr[] = $NorthEast.'-'.$avgNortheast; 

                                        
                                        $NorthEastdiff = $avgNortheast-$NorthEast;
                                        $noNorthEast = $NorthEastdiff  / $avgNortheast;
                                        
                                        $ofNorthEast = $noNorthEast  * 100;
                                        if(!empty($ofNorthEast)){
                                        
                                        $ofNorthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">North East $'. $NorthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofNorthEast.'" aria-valuenow="'.$ofNorthEast .'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthEast ) or strpos($SouthEast, '- ') !== false) { 
                                        echo  " Moving to the South East would be around $". $SouthEast.'.';
                                        $south_east_arr[] = $SouthEast.'-'.$avgSouthEast; 
                                        
                                        
                                        $SouthEastdiff = $avgSouthEast-$SouthEast;
                                        $noSouthEast = $SouthEastdiff / $avgSouthEast;
                                        
                                        $ofSouthEast = $noSouthEast * 100;
                                        
                                        if(!empty($ofSouthEast)){
                                        $ofSouthEaststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South East $'. $SouthEast.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofSouthEast.'%" aria-valuenow="'.$ofSouthEast.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    
                                    
                                    if(!empty($West) or strpos($West , '- ') !== false ){
                                        echo  " Moving to the West would be around $". $West.'.';
                                        $west_arr[] = $West.'-'.$avgWest; 
                                        
                                        
//                                        $Westdiff   = $avgWest-$West;
//                                        $noWest     = $Westdiff/ $avgWest;
//                                        
//                                        $ofWest     = $noWest * 100;
                                        $ofWest = (($West / $avgWest) * 66) / 100;
                                        $ofWest   = round($ofWest, 1);
                                        
                                        if($ofWest){
                                            $ofWeststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">West $'. $West.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofWest.'%" aria-valuenow="'.$ofWest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    
                                    
                                    if(!empty($Northwest) or strpos($Northwest , '- ') !== false ){
                                        echo  " Moving to the North West would be around $". $Northwest.'.';
                                        $Northwest_arr[] = $Northwest.'-'.$avgNorthwest; 
//                                        $Northwestdiff = $avgNorthwest-$Northwest;
//                                        $noNorthwest   = $Northwestdiff/ $avgNorthwest;
//                                        echo '<br>';
//                                        echo $ofnoNorthwest = $noNorthwest* 100; 
//                                        die;
                                        $Northwestt = (($Northwest / $avgNorthwest) * 66) / 100;
                                        $ofnoNorthwest   = round($Northwestt, 1);

                                        if($ofnoNorthwest){
                                            $ofnoNorthweststr = '<div class="progress-custom">
                                                    <div class="progress-value" style="font-size: 12px !important;width: 36%;">North west $'. $Northwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoNorthwest.'%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                        }
                                        
                                    }
                                    if(!empty($SouthMedwest) or strpos($SouthMedwest , '- ') !== false) {
                                        echo  " Moving to the Med West would be around $". $SouthMedwest.'.';
                                        
                                        $south_med_west_arr[] = $SouthMedwest.'-'.$avgSouthMedwest; 
                                        
                                        $SouthMedwestdiff   = $avgSouthMedwest-$SouthMedwest;
                                        $noSouthMedwest     = $SouthMedwestdiff/ $avgSouthMedwest;
                                        
                                        $ofnoSouthMedwest   = $noSouthMedwest* 100;
                                        if($ofnoSouthMedwest   ){
                                            $ofnoSouthMedweststr = '<div class="progress-custom">
                                                <div class="progress-value" style="font-size: 12px !important;width: 36%;">South Med West $'. $SouthMedwest.'</div>
                                                <div class="progress"> 
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: '.$ofnoSouthMedwest.'%" aria-valuenow="'.$ofnoSouthMedwest.'" aria-valuemin="0" aria-valuemax="100"> </div>
                                                        </div>
                                                    </div>';
                                            }
                                        
                                    }
                                    break;

                                default:
                                    break;
                            }
                            
                            
                        }else{
                            // companies dont having price
                            
                            $qr = "SELECT   MIN(CAST(`FarNorthEast` AS UNSIGNED)),
                                            MIN( CAST(`FarNorthEast` AS DECIMAL(5, 3))),
                                            FarNorthEast
                                    FROM
                                        `company_pricing`
                                    WHERE
                                        FarNorthEast != ' - ' AND FarNorthEast IS NOT NULL AND FarNorthEast <> ''
                                    ORDER BY
                                        FarNorthEast ASC" ; 
                            /* query will find min and max from mysql column SELECT
MIN( REPLACE(`FarNorthEast` , ",", ""))
FROM
    `company_pricing`
WHERE
    FarNorthEast != ' - ' AND FarNorthEast IS NOT NULL AND FarNorthEast <> ''
ORDER BY
    FarNorthEast ASC          */                              
    
                        }
                        ?>
                        
                    </p>
                    <br>
                    
                    <div class="col-md-8 reviiie">
                        <?php echo $ofSouthEaststr.$ofWeststr.$ofnoNorthweststr.$ofnoNorthMedweststr.$ofNorthEaststr.$ofnoSouthMedweststr; ?>
                    </div>
                    
                    
                    <br>
                    
                    <div id="app">
                        <div id="chart">
                            <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                        </div>
                    </div>
                    
                    <p class="review-para-1" style="font-size:18px !important;">
                        
                        
                        Take into consideration that this pricing based on average size move. 
                        <br>
                        It only means that <?php echo $res_company['title']; ?> is cheaper by an approximate of 10% when it comes to long-distance moving costs. 
                        <br>
                        Please take note that the prices provided based on data such as reviews and other sources.
                        The estimated amount that you receive from the company is lower. 
                        Several factors, like shipment size, the distance between locations, and other services like Packing and assembly, will contribute to the price of the move.
                        <!--We recommend you to save on packing and packing labor by <a href="https://www.topmovingreviews.com/Moving-Boxes.php" target="_blank"> purchasing packing supplies here</a>.
                        <br>
                        And 
                        <a href="https://www.topmovingreviews.com/Moving-Boxes.php" target="_blank"> compare the moving costs from licensed movers here. </a>-->
                    </p>
                    
                    <br>
                    <br>
                    <h2>Average service costs information</h2>
                    <br>
                    <br>
                    <p class="review-para-1" style="font-size:18px !important;text">
                        The average prices generated are based on our reviews of <?php echo $res_company['title']; ?>. The rates will vary or change once you contact the company; usually, you will get a lower price in the estimate and go higher on the move itself. 
                        <br>
                        Also, there are factors like home size, the distance between locations, and other services like Packing and assembly.
                        <br>
                        The approximate moving costs are generated based on reviews on our database and data collected from other reliable sources and partners. 
                        <br>
                        We based the calculations on five different prices based on five destinations or regions with five different prices. Also, the rates are determined by the moving company based on distance and season demand.
                        <br>
                        Our information based on the company's moves for a medium-sized house or a family apartment. 
                        <br>
                        If you have a bigger house, move the price is going to be higher. And if you have a smaller house move, the price is going to be lower unless you have a higher amount of Packing. 
                        <br>
                       <!-- To save in Packing, recommend you to <a href="https://www.topmovingreviews.com/Moving-Boxes.php" target="_blank">purchase packing materials.</a>
                        <br>--->
                    </p>
                    
                    
                </div>
                <div class=block-right>
                    <!--<div class=mover-details>
                    <h3>Mover Details Moving Company Business Info</h3><br><br>
                    <table style="width:100%">
                    <tr>
                    <td class="newtd new-pad"><b>In Business Since</b></td><td class="newtd new-pad"><b><?php echo $res_dot['granted_date']; ?></b></td>
                    </tr>
                    <tr>
                    <td class="newtd new-pad"><b>USDOT#</b></td><td class="newtd new-pad"><a href="<?php echo $res_dot['safety_url']; ?>" target="_blank" style="color:#0000FF;"><b><?php echo $res_dot['usdot_number']; ?></b></a></td>
                    </tr>
                    <tr>
                    <td class=new-pad><b>ICC MC Number</b></td><td class=new-pad><a href="<?php echo $res_dot['safety_url']; ?>" target="_blank" style="color:#0000FF;"><b><?php echo $res_dot['mc']; ?></b></a></td>
                    </tr>
                    </table>
                    </div>-->


                    <!--<div class=cmprev>
                    <h2>Search Moving Company</h2><br>
                    <div class="search-box"><form name="frm_search" action="https://www.topmovingreviews.com/searchcompany.php" method="post" class="seach22">
                    <input class="seach22" value="" name=company_search>
                    <button type=submit><i ></i>&nbsp;<span class="hiderseach">Search</span></button>
                    </form>
                    </div>
                    </div>-->
                    <div class=nxt><h3>Read before hiring</h3></div>
                    <div class="row newrow">
                        <div class="col-md-12">
                            <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company-1.jpg"/>
                            <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/moving-checklist-how-to-prepare-for-the-moving-day/"><h4>How to Prepare for the Moving Day</h4></a></div>
                            <p>Most people are aware on how stressful the moving day can be. To help you lessen the load, we created a comprehensive ... </p>

                        </div>     
                    </div>
                    <!--end1-->
                    <div class="row newrow">
                        <div class="col-md-12">
                            <img class="img-left" src="https://www.topmovingreviews.com/images/moving-company.jpg"/>
                            <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/tips-on-how-do-you-find-a-moving-company/"><h4>Tips on how do you find a moving company.</h4></a></div>
                            <p>Moving is a very stressful and also pricey experience, as well as if you do not take precaution it can promptly become ... </p>

                        </div>     
                    </div>
                    <!--end1-->
                    <div class="row newrow">
                        <div class="col-md-12">
                            <img class="img-left" src="https://www.topmovingreviews.com/images/packing-guide.jpg"/>
                            <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/essential-tips-when-packing-your-closet-3/"><h4>Packing Guide</h4></a></div>
                            <p>Packing can be an intensely challenging step when preparing to move. It is during this time that most damages to goods...</p>

                        </div>     
                    </div>
                    <!--end1-->
                    <div class="row newrow">
                        <div class="col-md-12">
                            <img class="img-left" src="https://www.topmovingreviews.com/images/change.jpg"/>
                            <div class="content-heading"><a href="https://www.topmovingreviews.com/tips/moving-checklist-when-changing-your-address-3/"><h4>Change Your Address When You Move</h4></a></div>
                            <p>Before you can settle in, you have to ensure that your mail is updated to continue receiving your regular mail without any...</p>

                        </div>     
                    </div>

                    <!--addedby naiya---->
                    <div>
                        
                        <div style="padding:20px 0px" class="newmain">
                            <h2>Best movers nearby</h2>
                     <div class="input-group "><form name="frm_zip" action="https://www.topmovingreviews.com/searchzipcode.php" method="post">
	<input type="text" class="form-control" placeholder="Find movers by zip" name="zipcode_search" ></form>
      <span class="input-group-btn">
        <input class="btn btn-search newbtn" name="bt" type="submit" value="Find">
      </span>
</div>
                        </div>
                        <div id="move-tips" class="hidden-xs">

                            <div id="deals">
                                <h5>Moving Deals:</h5>
                                <div class="deal-container">
                                    <a href="https://www.topmovingreviews.com/Moving-Boxes.php">Moving Boxes &amp; Supplies</a>
                                    <hr style="border:1px dashed #DADADA;"/>
                                    <p>Be Prepared on moving day, <br/>

                                        Save money, and Save time!</br>

                                        FREE 1-2 business day shipping!

                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class=ppl_viewed>
                        <h3>Other Movers Nearby</h3>
                        <div>
<?php
$company = new company();
$cityname = $res_company['city'];
$statename = $res_company['state'];
//select id,company_name,city,state from companies where viewed=1 order by Rand() limit 4  i have  removed viewed for temporary on 18022018
/* address like '%$cityname%' and address like '%$statename%'  and title!='$res_company[title]' */
$sql_pplview = "select id,title,address,logo,city,state from companies where rating>3 and state ='$statename' order by Rand() limit 4";
$query_pplview = mysql_query($sql_pplview);
while ($res_pplview = mysql_fetch_assoc($query_pplview)) {
    $compnay_address1 = explode(",", $res_pplview['address']);
    $countarray1 = count($compnay_address1);
    $average = $company->get_average_reviews_count($res_pplview["id"]);
    $reviews_count = $company->get_company_reviews_count($res_pplview["id"]);
    $comp_name = str_replace(' ', '-', $res_pplview['title']);
    ?>
                                <?php /* ?><div class=ppl_view>
                                  <div class=ppl_view_left><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/">
                                  <img src="https://www.topmovingreviews.com/mmr_images/smalllogos/logo_<?php if ($res_pplview["logo"]==NULL){echo "no";} else echo $res_pplview["id"];?>.jpg"  alt="<?php echo $res_pplview["id"];?>" style="width:47px !important; height:25px !important;"></a>
                                  </div>
                                  <div class=ppl_view_right>
                                  <div class=ppl_lft style="width:160px;">
                                  <h4><span><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/"> <?= $res_pplview["title"]; ?></a></span></h4>
                                  <p><?=$res_pplview['city'].", ".$res_pplview['state'];   ?>, USA</p>
                                  </div>
                                  <div class=ppl_ryt>
                                  <p class=stars>
                                  <span class="fa fa-star  <?php if(round($average["avg(rating)"])>=1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                  <span class="fa fa-star  <?php if(round($average["avg(rating)"])>=2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                  <span class="fa fa-star  <?php if(round($average["avg(rating)"])>=3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                  <span class="fa fa-star  <?php if(round($average["avg(rating)"])>=4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                  <span class="fa fa-star  <?php if(round($average["avg(rating)"])>=5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>
                                  </p>
                                  <span style="font-size:13px;"><?php echo $reviews_count;?> Reviews</span>
                                  </div>
                                  </div>
                                  </div><?php */ ?>
                                <div class="wrap">
                                    <div class="left"><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/">
                                            <!-- CODE BY GANESH -->

                                <?php
                                $smallogo1 = "https://www.topmovingreviews.com/mmr_images/smalllogos/logo_" . $res_pplview["logo"];
                                $smallogo2 = "https://www.topmovingreviews.com/mmr_images/smalllogos/logo_" . $res_pplview["id"] . ".jpg";
                                $noimg = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
                                if ($res_pplview["logo"] != NULL) {
                                    if (@getimagesize($smallogo1) != '') {
                                        $logo_small = $smallogo1;
                                    } else if (@getimagesize($smallogo2) != '') {
                                        $logo_small = $smallogo2;
                                    } else {
                                        $logo_small = $noimg;
                                    }
                                } else {
                                    $logo_small = $noimg;
                                }
                                ?>

                                            <img src="<?= $logo_small ?>"  alt="" style="width:47px !important; height:25px !important;"></a></div>
                                    <div class="right">




                                        <div class="rating-wrap rating-stars-new">
                                            <div class="rating-stars  rating_<?php echo round($average["avg(rating)"]); ?>" style="margin-left: -14px !important;"></div>
                                        </div>
                                        </p>
                                        <span style="font-size:13px;"><?php echo $reviews_count; ?> Reviews</span></div>
                                    <div class="center"><h4><span><a href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_pplview["id"]; ?>/"> <?= $res_pplview["title"]; ?></a></span></h4><p><?= $res_pplview['city'] . ", " . $res_pplview['state']; ?></p></div>
                                </div>
<?php } ?>
                        </div>
                    </div>


                    <div style="margin-top:10px;" >
<?php
$state_code = $res_company['state'];
$sql_state = mysql_query("select name from states where state_code='$state_code' and usa_state=1");
$res_state = mysql_fetch_array($sql_state);
$numrows = mysql_num_rows($sql_state);

if ($numrows <> 0) {
    ?>
                            See more <a href="https://www.topmovingreviews.com/moving-companies/<?php echo trim($res_company['city']) . str_replace(' ', '-', $compnay_address[$countarray - 2]); ?>/" style="font-weight:bold; color:#839AEB;">movers in <?php echo $res_company['city']; ?>, <?php echo $state_code; ?></a><br>Explore more
                            <a href="https://www.topmovingreviews.com/usa/<?php echo $res_state['name'] . "-movers-" . $state_code; ?>/" style="font-weight:bold; color:#839AEB;;"><?php echo $res_state['name']; ?> Movers</a>
                        <?php } ?>

                    </div>



                </div>
            </div><div></div>
        </div>
<?php include 'footer.php'; ?>



</div>
</div>
    <script>
        $(document).ready(function () {
            var showChar = 950;
            var ellipsestext = "...";
            var moretext = "Read More";
            var lesstext = "Less";
            $('.more').each(function () {
                var content = $(this).html();
                if (content.length > showChar) {
                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar - 1, content.length - showChar);
                    var html = c + '<span class="moreelipses">' + ellipsestext +
                            '</span><span class="morecontent"><span style="display:none;">' + h +
                            '</span>&nbsp;&nbsp;\n<a href="" class="morelink">' + moretext + '</a></span>';
                    $(this).html(html);
                }
            });
            $(".morelink").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        });
        //for after filter
        function reload_more() {
            // alert();
            var showChar = 950;
            var ellipsestext = "...";
            var moretext = "Read More";
            var lesstext = "Less";
            $('.more').each(function () {
                var content = $(this).html();
                if (content.length > showChar) {
                    var c = content.substr(0, showChar);
                    var h = content.substr(showChar - 1, content.length - showChar);
                    var html = c + '<span class="moreelipses">' + ellipsestext +
                            '</span>&nbsp;<span class="morecontent"><span style="display:none;">' + h +
                            '</span>&nbsp;&nbsp;\n<a href="" class="morelink">' + moretext + '</a></span>';
                    $(this).html(html);
                }
            });
            $(".morelink").click(function () {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        }
        
        //c code for demo .js starts
        !function(o){o(function(){o("#id_0").datetimepicker({allowInputToggle:!0,showClose:!0,showClear:!0,showTodayButton:!0,format:"MM/DD/YYYY hh:mm:ss A"}),o("#id_1").datetimepicker({allowInputToggle:!0,showClose:!0,showClear:!0,showTodayButton:!0,format:"MM/DD/YYYY HH:mm:ss"}),o("#id_2").datetimepicker({allowInputToggle:!0,showClose:!0,showClear:!0,showTodayButton:!0,format:"hh:mm:ss A"}),o("#id_3").datetimepicker({allowInputToggle:!0,showClose:!0,showClear:!0,showTodayButton:!0,format:"HH:mm:ss"}),o("#id_4").datetimepicker({allowInputToggle:!0,showClose:!0,showClear:!0,showTodayButton:!0,format:"MM/DD/YYYY"})})}(jQuery);
        // code for demo .js ends
        
        
        
        
    </script>
    <!-- </div> -->
<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js" ></script>
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
  <script src="https://www.topmovingreviews.com/responsive/popper.min.js"></script>

  <script src="https://www.topmovingreviews.com/responsive/bootstrap-datetimepicker.min.js"></script>

  <script src="https://www.topmovingreviews.com/responsive/bootstrap.min.js"></script>


<!--<script src="https://www.topmovingreviews.com/responsive/demo.js"></script>-->
<!--<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js" async></script>-->

<!-- font awesome for starts--->

<?php 
$pricearr = '';
$avharraa = '';


$x_axis  = ''; 
//echo $west_arr[0];die;

if(count($west_arr) >= 1){
    $westi      = str_replace(",","",$west_arr[0]);
    $esp        = explode('-',$westi);
    $pricearr.=$esp[0].',';
    $avharraa.=$esp[1].',';
    $x_axis.='"West"'.',';
}
if(count($south_med_west_arr) >= 1){
    $south_med_west_arrii      = str_replace(",","",$south_med_west_arr[0]);
    $esp = explode('-',$south_med_west_arrii);
    $pricearr.=$esp[0].',';
    $avharraa.=$esp[1].',';
    $x_axis.='"South Med West"'.',';
}
if(count($Northwest_arr) >= 1){
    $Northwest_arrii      = str_replace(",","",$Northwest_arr[0]);
    $esp = explode('-',$Northwest_arrii);
    $pricearr.=$esp[0].',';
    $avharraa.=$esp[1].',';
    $x_axis.='"North West"'.',';
}
if(count($NorthMedwest_arr) >= 1){
    $NorthMedwest_arrii      = str_replace(",","",$NorthMedwest_arr[0]);
    $esp = explode('-',$NorthMedwest_arrii);
    $pricearr.=$esp[0].',';
    $avharraa.=$esp[1].',';
    $x_axis.='"North Med West"'.',';
}
if(count($south_east_arr) >= 1){
    $south_east_arrii      = str_replace(",","",$south_east_arr[0]);
    $esp = explode('-',$south_east_arrii);
    $pricearr.=$esp[0].',';
    $avharraa.=$esp[1].',';
    $x_axis.='"South East"'.',';
}
if(count($NorthEast_arr) >= 1){
    $NorthEast_arrii      = str_replace(",","",$NorthEast_arr[0]);
    $esp = explode('-',$NorthEast_arrii);
    $pricearr.=$esp[0].',';
    $avharraa.=$esp[1].',';
    $x_axis.='"North East"'.',';
}
    $pricearr = rtrim($pricearr , ',');// echo '<br>';
    $avharraa = rtrim($avharraa , ',');
    $x_axis   = rtrim($x_axis , ',');
echo $x_axis   ;die;
?>

<script>
      new Vue({
        el: '#app',
        components: {
          apexchart: VueApexCharts,
        },
        data: {
          
          series: [{
            name: 'State Price',
            //data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            data: [<?php echo  $pricearr;?>]
          }, {
            name: 'Average',
            data: [<?php echo  $avharraa;?>]
            //data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
          }],
          chartOptions: {
            chart: {
              type: 'bar',
              height: 350
            },
            plotOptions: {
              bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
              },
            },
            dataLabels: {
              enabled: true
            },
            stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
            },
            xaxis: {
              //categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
              categories: [<?php echo $x_axis;?>],
            },
            yaxis: {
              title: {
                text: '$ '
              }
            },
            fill: {
              opacity: 1
            },
            legend: {
              position: 'right',
              offsetX: 0,
              offsetY: 50
          },
            tooltip: {
              y: {
                formatter: function (val) {
                  //return "$ " + val + " thousands"
                  return "$ " + val + " "
                }
              }
            }
          },
          
          
        },
        
      })
    </script>
</body>
</html>


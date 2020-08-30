<?php
header('Content-Type: text/html; ');
error_reporting(E_ALL);
require_once('core/database.class.php');
require_once('core/company.class.php');
$bd = new db_class();
$db_link = $bd->db_connect();
//die;
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
        </style>



    </head>
    <body onLoad="doOnLoad();">
        <div class="wrapper">
            <?php include 'newheaderwithoutbanner.php'; ?>
            <br/>
            <div class="container" >
                <div>
                    <h3 class="card-row-title">List of Movers in America by State - Find a <strong>Moving Company</strong> Near You</h3>

                    <div class="card-row-content">

                        <p style="text-align:justify">Make movers comparison based on moving companies reviews and consumer reports reviews. Find new movers, piano mover, pool table mover or trailer movers with recommendations. Find the best mover services by reading local movers reviews. Select the best mover price and the best local movers ratings. The best piano mover, pool table mover is here. As we are expanding our index, we are planning to include storage facilities around the country.</p>

                        <h1 class="newstyless">By selecing your state you can read authentic local moving company reviews based on customer testimonials, consumer reports, ratings and testimonials.</h1>
                        <br><br>
                    </div>
                    <div class="map_wr2">

                        <h2>Select Your State Below:</h2>
                        <br><br>
                        <div class="col-sm-12 ">

                            <div class="map_list">



                                <ul>

                                    <?php
									
									
                                    $sql = mysqli_query($link, "SELECT state_code,lower(name) as name FROM states where usa_state=1 GROUP BY name");

                                    while ($row = mysqli_fetch_array($sql)) {

                                        $sta = $row['state_code'];

                                        $sname = strtolower($row['name']);

                                        $stname = str_replace(' ', '-', $sname);
//$stname=$sname;
//$sql1 = mysqli_query("SELECT * FROM companies WHERE state='$sta'");
                                        $sql1 = mysqli_query($link, "SELECT id FROM companies WHERE state='$sta'");
                                        $r = mysqli_num_rows($sql1);
                                        ?>



                                        <li>
                   <a  href="<? echo $connection->baseUrl(); ?>usa/<?php echo str_replace(" ", "-", $stname); ?>-movers-<?php echo $sta; ?>/">
                                                <?php echo $sname; ?> (<?php echo $r; ?>)
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
                <br/><br/><br/><br/><br/>

                <!--srikanta 26/08/2020-->


                <?php
                $adjacents = 3;



                $query = mysqli_query($link, "SELECT *  FROM    companies  , reviews    where companies.id=reviews.company_id group by companies.id");


                $total_pages = mysqli_num_rows($query);

                /* Setup vars for query. */
                $arr = explode("?", $_SERVER['REQUEST_URI']);
                $isID = false;
                $Ispage_num = 0;

                if (isset($arr[1])) {
                    $isID = true;
                    $pCount = explode("=", $arr[1]);
                    $Ispage_num = $pCount[1];
                }
                $limit = 10;         //how many items to show per page

                if ($isID)
                    $page = $Ispage_num;
                else
                    $page = 1;


                if ($page)
                    $start = ($page - 1) * $limit;    //first item to display on this page
                else
                    $start = 0;        //if no page var is given, set start to 0






                $sql = "SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id group by companies.id order by   companies.rating desc,count(*) desc  LIMIT $start, $limit";



                $result = mysqli_query($link, $sql);



                if ($page == 0)
                    $page = 1;     //if no page var is given, default to 1.


                $prev = $page - 1;       //previous page is page - 1


                $next = $page + 1;       //next page is page + 1


                $lastpage = ceil($total_pages / $limit);  //lastpage is = total pages / items per page, rounded up.


                $lpm1 = $lastpage - 1;      //last page minus 1


                $targetpage = "moving-company.php";
                $pagination = "";


                if ($lastpage > 1) {
                    $pagination .= "<div class=\"pagination\"  ><ul>";
                    //previous button
                    if ($page > 1) {
                        $pagination .= "<li><a href=\"$targetpage?page=$prev\">Prev 10</a></li>";
                    }
                    $pagination .= "<li> <span class=\"pagination1\">$total_pages" . " Movers</span></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        /* if ($counter == $page)
                          $pagination.= "<li><span class=\"pagination1\">$counter</span></li>";
                          else
                          $pagination.= "<li><a href=\"https://www.topmovingreviews.com/movers/$compname_$counter-$_GET[id]/\">$counter</a></li>"; */
                    }
                    //next button
                    if ($page < $counter - 1)
                        $pagination .= "<li><a href=\"$targetpage?page=$next\">Next 10</a></li>";
                    /* else
                      $pagination.= "<li><span class=\"pagination1\">-></span></li>"; */
                    $pagination .= "</ul></div>\n";
                }

echo "<h2>  $total_pages Moving Companies</h2>";
                while ($res_comp_city = mysqli_fetch_array($result)) {


                    $sql_reviewcount = mysqli_query($link, "select * from reviews where company_id= '$res_comp_city[id]'");

                    $res_reviewcount = mysqli_num_rows($sql_reviewcount);


                    $compnay_address = explode(",", $res_comp_city['address']);


                    $countarray = count($compnay_address);

                    $compnay_address_zip = explode(" ", $compnay_address[$countarray - 2]);

                    $comp_name = str_replace('/', '-', str_replace(' ', '-', $res_comp_city["title"]));
                    ?>





                    <div class="row" style="padding-top: 40px; " onClick="window.location.href = 'https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_comp_city["id"]; ?>/'">

                        <div class="col-md-3">
                            <?php
                            $img = $res_comp_city["logo"];

                            $mmrimg = "https://www.topmovingreviews.com/mmr_images/logos/logo_" . $res_comp_city["id"] . ".jpg";

                            $compimg = "https://www.topmovingreviews.com/company/logos/logo_" . $res_comp_city["id"] . ".jpg";

                            if ($res_comp_city["logo"] != NULL) {

                                if (@getimagesize($mmrimg) != '' && stristr($res_comp_city["logo"], "topmovingreviews.com")) {

                                    $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_" . $res_comp_city["id"] . ".jpg";
                                } else if (@getimagesize($compimg) != '' && stristr($res_comp_city["logo"], "topmovingreviews.com")) {

                                    $logo_image = "https://www.topmovingreviews.com/company/logos/logo_" . $res_comp_city["id"] . ".jpg";
                                } else if (stristr($res_comp_city["logo"], "mymovingreviews.com")) {

                                    $logo_image = $img;
                                } else {

                                    $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
                                }
                            } else {

                                $logo_image = "https://www.topmovingreviews.com/mmr_images/logos/logo_no.jpg";
                            }
                            ?>



                            <img src="<?php echo $logo_image; ?>" height="80" width="190" ></div>

                        <div class="col-md-9" >

                            <h4  style="text-align:left!important;"><a style="color:#000000;" href="https://www.topmovingreviews.com/movers/<?php echo $comp_name; ?>-<?php echo $res_comp_city["id"]; ?>/"><?php echo $res_comp_city["title"]; ?></a></h4>


                            <p class=stars>


                                <span class="fa fa-star  <?php if (round($res_comp_city["rating"]) >= 1) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

                                <span class="fa fa-star  <?php if (round($res_comp_city["rating"]) >= 2) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>


                                <span class="fa fa-star  <?php if (round($res_comp_city["rating"]) >= 3) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

                                <span class="fa fa-star  <?php if (round($res_comp_city["rating"]) >= 4) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>

                                <span class="fa fa-star  <?php if (round($res_comp_city["rating"]) >= 5) { ?> checked <?php } else { ?> checkednot <?php } ?>"></span>


                            </p>


                            <span style="color:#000000" >(<?php echo $res_reviewcount; ?> Reviews)</span><br>

                            <span style="color:#000000"><?php echo $res_comp_city["address"]; ?></span>
                            <div style="clear: both; padding-top: 8px;">


                                <?php
                                echo substr($res_comp_city["text"], 0, 240) . "...";
                                ?>







                            </div>


                            <div style="clear:both"></div>


                        </div>	 



                    </div>
                <?php }
                ?>

                <?= $pagination ?>



            </div>
        </div>
        <?php include 'footer.php'; ?>
    </body>
</html>
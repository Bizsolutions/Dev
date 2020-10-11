<?php

require './core/database.class.php';

define("OUTPUT_FILE", "sitemap.xml");
// Set the start URL. Example: define ("SITE", "https://www.example.com");
define("SITE", "https://www.topmovingreviews.com");
// Set true or false to define how the script is used.
// true:  As CLI script.
// false: As Website script.
define("CLI", false);
define("FREQUENCY", "weekly");
define("PRIORITY", "0.5");
define("VERSION", "2.4");
define("NL", CLI ? "\n" : "<br>");
// When your web server does not send the Content-Type header, then set
// this to 'true'. But I don't suggest this.
define("IGNORE_EMPTY_CONTENT_TYPE", false);

// Print configuration
echo "Plop PHP XML Sitemap Generator Configuration:" . NL;
echo "VERSION: " . VERSION . NL;
echo "OUTPUT_FILE: " . OUTPUT_FILE . NL;
echo "SITE: " . SITE . NL;
echo "CLI: " . (CLI ? "true" : "false") . NL;
echo "IGNORE_EMPTY_CONTENT_TYPE: " . (IGNORE_EMPTY_CONTENT_TYPE ? "true" : "false") . NL;
echo "DATE: " . date("Y-m-d H:i:s") . NL;
echo NL;

if (!SITE) {
    die("ERROR: You did not set the SITE variable at line number " .
            "68 with the URL of your website!\n");
}

define("AGENT", "Mozilla/5.0 (compatible; Plop PHP XML Sitemap Generator/" . VERSION . ")");
define("SITE_SCHEME", parse_url(SITE, PHP_URL_SCHEME));
define("SITE_HOST", parse_url(SITE, PHP_URL_HOST));

error_reporting(E_ERROR | E_WARNING | E_PARSE);
$pf = fopen(OUTPUT_FILE, "w");
if (!$pf) {
    echo "ERROR: Cannot create " . OUTPUT_FILE . "!" . NL;
    return;
}

fwrite($pf, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
        "<!-- Date: " . date("Y-m-d H:i:s") . " -->\n" .
        "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n" .
        "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n" .
        "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n" .
        "        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n" .
        "  <url>\n" .
        "    <loc>" . SITE . "/</loc>\n" .
        // "    <changefreq>" . FREQUENCY . "</changefreq>\n" .
        "  </url>\n");

echo "Scanning..." . NL;
$UrlArray = array(
    "https://www.topmovingreviews.com/add-moving-company.php",
    "https://www.topmovingreviews.com/moving-company.php",
    "https://www.topmovingreviews.com/write-your-review.php",
    "https://www.topmovingreviews.com/Move",
    "https://www.topmovingreviews.com/privacy.php",
    "https://www.topmovingreviews.com/terms-service.php",
    "https://www.topmovingreviews.com/quoteform.php",
);


$sql = mysqli_query($link, "SELECT state_code,lower(name) as name FROM states where usa_state=1 GROUP BY name");

while ($row = mysqli_fetch_array($sql)) {
    $sta = $row['state_code'];
    $sname = strtolower($row['name']);
    $stname = str_replace(' ', '-', $sname);
    $state_code = $row['state_code'];
    $getRecordByState = mysqli_query($link, "SELECT count(*),companies.title,companies.address,reviews.text,companies.id,companies.rating,companies.address,companies.phone,companies.logo  FROM  companies  , reviews    where companies.id=reviews.company_id and state='$state_code'  group by companies.id order by companies.rating desc,count(*)");
    $UrlArray[] = $connection->baseUrl() . 'usa/' . str_replace(" ", "-", $stname) . '-movers-' . $sta;
    $page_number = 0;
    while ($row1 = mysqli_fetch_array($getRecordByState)) {
        $page_number++;
        $UrlArray[] = $connection->baseUrl() . 'usa/' . str_replace(" ", "-", $sname) . '-movers-' . $state_code . '?page=' . $page_number . '#listing';
        $sql_reviewcount = mysqli_query($link, "select * from reviews where company_id= '$row1[id]'");
        $res_reviewcount = mysqli_num_rows($sql_reviewcount);

        $compnay_address = explode(",", $row1['address']);
        $countarray = count($compnay_address);
        $compnay_address_zip = explode(" ", $compnay_address[$countarray - 2]);
        $comp_name = str_replace('/', '-', str_replace(' ', '-', $row1["title"]));
        // echo '<pre>';
        // print_r($comp_name).'<br>';
        $UrlArray[] = $connection->baseUrl() . 'movers/' . htmlentities($comp_name). '-' . $row1["id"];
    }
}

foreach ($UrlArray as $key => $value) {
    fwrite($pf, "  <url>\n" .
            "    <loc>" . htmlentities($value) . "</loc>\n" .
            // "    <changefreq>" . FREQUENCY . "</changefreq>\n" .
            "    <priority>" . PRIORITY . "</priority>\n" .
            "  </url>\n");
}

fwrite($pf, "</urlset>\n");
fclose($pf);

echo "Done." . NL;
echo OUTPUT_FILE . " created." . NL;

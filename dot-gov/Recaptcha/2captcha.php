<?php
include("db.php");
function date_sort($a, $b) {
    return strtotime($a) - strtotime($b);
}

$header = array("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
"Accept-Encoding:gzip, deflate, br",
"Accept-Language:en-US,en;q=0.9,hi;q=0.8",
"Cache-Control:max-age=0",
"Host:ai.fmcsa.dot.gov",
"Connection:keep-alive",
"Upgrade-Insecure-Requests:1",
"User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");

# Add these values
$api_key = 'e1335bb3fef6ce8616db3ce4ad1d0138';
$site_key = '6Ldbx1gUAAAAAMEylxZ_DoZS430WhNsmV47Y5t58';

$l = array(
    "0",
    "49",
    "99",
    "149",
    "199",
);
$offset = $l[$argv[1]]; 

$check = mysqli_query($db, "select id, mc_mx_ff_url from company_dot_data where operating_state LIKE '%HHG%' AND mc_mx_ff_url != '' and mc_mx_ff_check = '0' ") or die(mysqli_error($db));
$row = mysqli_fetch_assoc($check);
echo "Left ##### " . mysqli_num_rows($check)."\n\n\n\n\n";

do{
    $check = mysqli_query($db, "select id, mc_mx_ff_url from company_dot_data where operating_state LIKE '%HHG%' AND mc_mx_ff_url != '' and mc_mx_ff_check = '0'  LIMIT 50 OFFSET ".$offset) or die(mysqli_error($db));
    $row = mysqli_fetch_assoc($check);
    echo mysqli_num_rows($check)."\n";
    $url = $row['mc_mx_ff_url'];
    //$url = "http://li-public.fmcsa.dot.gov/LIVIEW/pkg_carrquery.prc_carrlist?n_dotno=1664649&s_prefix=MC&n_docketno=660785&s_legalname=&s_dbaname=&s_state=";

    $captcha = "https://2captcha.com/in.php?key=" . $api_key . "&method=userrecaptcha&googlekey=" . $site_key . "&pageurl=" . $url . "&json=1";
    $json = file_get_contents($captcha);
    $encode = json_decode($json, TRUE);
    $id = $encode['request'];
    if (is_numeric($id)) {
        echo $row['id']."\n";
        $cookiefile = dirname(__FILE__) . "/_cookies/".time()."_ai.fmcsa.dot.gov.txt";
        $i = 1;
        $g_recaptcha_response = "";
        do{
            $html = scurl($url, $header, $cookiefile);
            echo "Attempt ". $i."\n";
        
            sleep(28);
            $req = "http://2captcha.com/res.php?key=" . $api_key . "&action=get&id=" . $id . "&json=1";
            $res_json = json_decode(file_get_contents($req), TRUE);
            $g_recaptcha_response = $res_json['request'];
            $i++;
        }while( $g_recaptcha_response == "CAPCHA_NOT_READY" ); //
//      }while( strlen($g_recaptcha_response) < 111 ); //"CAPCHA_NOT_READY"

        echo $g_recaptcha_response."\n";

        $xHeader = $header;
        $xHeader[] = "Content-Type: application/x-www-form-urlencoded";
        
        $html = $html['HTML'];
        //file_put_contents("step1.html", $html);
        $dom = get_dom($html);

        $n_dotno = $dom->query('//input[@name="n_dotno"]/@value');
        $n_dotno = isset($n_dotno->item(0)->nodeValue)?trim($n_dotno->item(0)->nodeValue):"";

        $s_prefix = $dom->query('//select[@name="s_prefix"]/option[@selected]');
        $s_prefix = isset($s_prefix->item(0)->nodeValue)?trim($s_prefix->item(0)->nodeValue):"";

        $n_docketno = $dom->query('//input[@name="n_docketno"]/@value');
        $n_docketno = isset($n_docketno->item(0)->nodeValue)?trim($n_docketno->item(0)->nodeValue):"";

        $post_data = array();
        $post_data['n_dotno'] = $n_dotno;
        $post_data['s_prefix'] = $s_prefix;
        $post_data['n_docketno'] = $n_docketno;
        $post_data['s_legalname'] = "";
        $post_data['s_dbaname'] = "";
        $post_data['s_state'] = "~~";
        $post_data['g_recaptcha_response'] = $g_recaptcha_response;
        $post_data['pv_vpath'] = "LIVIEW";

        $post_data = http_build_query($post_data);

        $html = scurl("http://li-public.fmcsa.dot.gov/LIVIEW/pkg_carrquery.prc_carrlist", $xHeader, $cookiefile, $post_data);
        $html = $html['HTML'];
        //file_put_contents("step2.html", $html);
        $dom = get_dom($html);

        $pv_apcant_id = $dom->query('//input[@name="pv_apcant_id"]/@value');
        $pv_apcant_id = isset($pv_apcant_id->item(0)->nodeValue)?trim($pv_apcant_id->item(0)->nodeValue):"";
        
        $pv_vpath = $dom->query('//input[@name="pv_vpath"]/@value');
        $pv_vpath = isset($pv_vpath->item(0)->nodeValue)?trim($pv_vpath->item(0)->nodeValue):"";

        $post_data = array();
        $post_data['pv_apcant_id'] = $pv_apcant_id;
        $post_data['pv_vpath'] = $pv_vpath;
        $post_data = http_build_query($post_data);

        $html = scurl("http://li-public.fmcsa.dot.gov/LIVIEW/pkg_carrquery.prc_getdetail", $xHeader, $cookiefile, $post_data);
        $html = $html['HTML'];
        //file_put_contents("step3.html", $html);
        $dom = get_dom($html);

        $authorityhistory = $dom->query("//a[contains(@href,'authorityhistory')]/@href");
        $authorityhistory = isset($authorityhistory->item(0)->nodeValue)?trim($authorityhistory->item(0)->nodeValue):"";
        $authorityhistory = "http://li-public.fmcsa.dot.gov/LIVIEW/".$authorityhistory;

        $html = scurl($authorityhistory, $xHeader, $cookiefile, $post_data);
        $html = $html['HTML'];
        $dom = get_dom($html);

        $data = array();
        
        foreach ($dom->query('//table[@summary="table used for formating purposes only"][3]/tr') as $td) { 
            $d = array();
            $auth_type = $dom->query('th[1]/center/font', $td);
            $auth_type = isset($auth_type->item(0)->nodeValue)?$auth_type->item(0)->nodeValue:""; 	
            $d["auth_type"] = trim($auth_type);

            $original_action = $dom->query('td[2]/center/font', $td);
            $original_action = isset($original_action->item(0)->nodeValue)?$original_action->item(0)->nodeValue:""; 	
            $d["original_action"] = trim($original_action);

            $original_action_date = $dom->query('td[3]/center/font', $td);
            $original_action_date = isset($original_action_date->item(0)->nodeValue)?$original_action_date->item(0)->nodeValue:""; 	
            $d["original_action_date"] = trim($original_action_date);

            $disposition = $dom->query('th[2]/center/font', $td);
            $disposition = isset($disposition->item(0)->nodeValue)?$disposition->item(0)->nodeValue:""; 	
            $d["disposition"] = $disposition;

            $disposition_date = $dom->query('td[4]/center/font', $td);
            $disposition_date = isset($disposition_date->item(0)->nodeValue)?$disposition_date->item(0)->nodeValue:""; 	
            $d["disposition_date"] = $disposition_date;
            
            if(!empty(array_filter($d)) ){
                $d['dot_data_id'] = $row["id"];

                $val = "";
                foreach($d as $v){
                    $val .= "'".mysqli_real_escape_string($db, $v)."',";
                }
                $val = trim($val, ",");
                $col = implode(", ", array_keys($d));
                $query = "INSERT INTO `company_dot_authority_data` (".$col.") VALUES ($val)";
                mysqli_query($db, $query) or die(mysqli_error($db));

                $query = "UPDATE  `company_dot_data` SET  `mc_mx_ff_check` = '1' WHERE  `id` = '$row[id]' ;";
                mysqli_query($db, $query) or die(mysqli_error($db));

                echo ".";
            }
        }
        echo "\n";
    } else {
        echo "ID: " . $id;
    }
}while(mysqli_num_rows($check) >= 1);
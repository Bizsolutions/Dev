<?php
include("db.php");

$cookiefile = dirname(__FILE__) . "/_cookies/".time()."_ai.fmcsa.dot.gov.txt";

$counties = array(
	"CA" => "California",
	"TX" => "Texas",
	"NJ" => "New Jersey",
	"NY" => "New York",
	"PA" => "Pennsylvania",
	"CT" => "Connecticut",
	"VA" => "Virginia",
	"IL" => "Illinois",
	"OH" => "Ohio",
	"GA" => "Georgia",
	"MI" => "Michigan",
	"NC" => "North Carolina",
	"WA" => "Washington",
	"MA" => "Massachusetts",
	"AZ" => "Arizona",
	"IN" => "Indiana",
	"TN" => "Tennessee",
	"MO" => "Missouri",
	"MD" => "Maryland",
	"WI" => "Wisconsin",
	"MN" => "Minnesota",
	"CO" => "Colorado",
	"AL" => "Alabama",
	"AK" => "Alaska",
	"AR" => "Arkansas",
	"DE" => "Delaware",
	"DC" => "District of Columbia",
	"FL" => "Florida",
	"HI" => "Hawaii",
	"ID" => "Idaho",
	"IA" => "Iowa",
	"KS" => "Kansas",
	"KY" => "Kentucky",
	"LA" => "Louisiana",
	"ME" => "Maine",
	"MS" => "Mississippi",
	"MT" => "Montana",
	"NE" => "Nebraska",
	"NV" => "Nevada",
	"NH" => "New Hampshire",
	"NM" => "New Mexico",
	"ND" => "North Dakota",
	"OK" => "Oklahoma",
	"OR" => "Oregon",
	"RI" => "Rhode Island",
	"SC" => "South Carolina",
	"SD" => "South Dakota",
	"UT" => "Utah",
	"VT" => "Vermont",
	"WV" => "West Virginia",
	"WY" => "Wyoming",
);

$header = array("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
"Accept-Encoding:gzip, deflate, br",
"Accept-Language:en-US,en;q=0.9,hi;q=0.8",
"Cache-Control:max-age=0",
"Connection:keep-alive",
"Upgrade-Insecure-Requests:1",
"User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");
		
foreach ($counties as $code => $state) {
	$url = "https://ai.fmcsa.dot.gov/hhg/SearchResults.asp?search=5&ads=a&state=".$code."&Submit=Search";
	$out = scurl($url, $header, $cookiefile);
	$pagehtml = $out['HTML'];
	$maindom = get_dom($pagehtml);

	foreach ($maindom->query('//td[@class="clsTopRightBoxPadd"]/table/tr/td/a/@href') as $mainkey => $a) {
		$company_url = "https://ai.fmcsa.dot.gov/hhg/".$a->nodeValue;

		echo $code." - ".$company_url."\n";
		$check = mysqli_query($db, "select id from company_dot_data_sri where company_url = '$company_url'") or die(mysqli_error($db));
		if(mysqli_num_rows($check) <= 0){
			
			$html = scurl($company_url, $header, $cookiefile);
			$html = $html['HTML'];
			$html = preg_replace('/<!--(.*)-->/Uis', '', $html);
			$html = str_replace("<br>","[br]", $html);
			$html = str_replace("&nbsp;"," ", $html);
			
			$dom = get_dom($html);
			$company_name =$dom->query('//td[@class="righttableBorder"]/table/tr[1]/td[3]');
			$company_name = isset($company_name->item(0)->nodeValue)?trim($company_name->item(0)->nodeValue):"";
			$address=$dom->query('//td[@class="righttableBorder"]/table/tr[5]/td[3]');
			$address = isset($address->item(0)->nodeValue)?$address->item(0)->nodeValue:"";
			if($address != ""){
				$addressArr = explode(",",$address);
				$temp = explode(" ", trim($addressArr[1]));
				$state = isset($temp[0])?trim($temp[0]):"";
				$zipcode = isset($temp[1])?trim($temp[1]):"";

				$addressArr = explode("[br]", trim($addressArr[0]));
				$city = trim(end($addressArr));
			}

			$address = str_replace("[br]", " ", $address);
			$address = str_replace("\n", " ", $address);
			$address = str_replace("\t", "", $address);

			$telephone = $dom->query('//td[@class="righttableBorder"]/table/tr[7]/td[3]');
			$telephone = isset($telephone->item(0)->nodeValue)?trim($telephone->item(0)->nodeValue):"";
	
			$fax = $dom->query('//td[@class="righttableBorder"]/table/tr[8]/td[3]');
			$fax = isset($fax->item(0)->nodeValue)?trim($fax->item(0)->nodeValue):"";

			$usdot_number = $dom->query('//td[@class="righttableBorder"]/table/tr[2]/td[3]');
			$usdot_number = isset($usdot_number->item(0)->nodeValue)?$usdot_number->item(0)->nodeValue:""; 

			$mc = $dom->query('//td[@class="righttableBorder"]/table/tr[3]/td[3]');
			$mc = isset($mc->item(0)->nodeValue)?$mc->item(0)->nodeValue:""; 		
			
			$safety_url = "";
			foreach ($dom->query('//tr[@class="MiddleAltTDFMCSA"]/td/a') as $a) { 
				if (strpos($a->nodeValue, 'Safety Rating Data') !== false) {
					$safety_url = $dom->query('@href', $a);
					$safety_url = isset($safety_url->item(0)->nodeValue)?$safety_url->item(0)->nodeValue:""; 	
					$safety_url = str_replace("http", "https", $safety_url);
				}
			} 
			if($safety_url !=""){
				$out1 = scurl($safety_url, $header, $cookiefile);

				$html1 = $out1['HTML'];
				$html1 = str_replace("&nbsp;"," ", $html1);
				$html1 = preg_replace('/<!--(.*)-->/Uis', '', $html1);
				$subdom = get_dom($html1);

				$operating_state = "";
				$out_of_service_date = "";
				$power_units = "";
				$mcs_150_from_date = "";
				$drivers = "";
				$mcs150_mileage = "";
				$operation_clsf = "";
				$carrier_operation = "";
				$cargo_carried = "";
				$mc_mx_ff_url = "";
				
				$safety_data = 'Record Not Found';
				if (strpos($html1, 'Record Not Found') === false) {
					$safety_data = "";
					$operating_state = $subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[3]/td[1]');
					$operating_state = isset($operating_state->item(0)->nodeValue)?$operating_state->item(0)->nodeValue:"";

					$out_of_service_date = $subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[3]/td[2]');
					$out_of_service_date = isset($out_of_service_date->item(0)->nodeValue)?$out_of_service_date->item(0)->nodeValue:"";

					$power_units = $subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[11]/td[1]');
					$power_units = isset($power_units->item(0)->nodeValue)?$power_units->item(0)->nodeValue:"";

					$mcs_150_from_date=$subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[12]/td[1]');
					$mcs_150_from_date=isset($mcs_150_from_date->item(0)->nodeValue)?$mcs_150_from_date->item(0)->nodeValue:"";

					$drivers=$subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[11]/td[2]');
					$drivers=isset($drivers->item(0)->nodeValue)?$drivers->item(0)->nodeValue:"";

					$mcs150_mileage=$subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[12]/td[2]');
					$mcs150_mileage=isset($mcs150_mileage->item(0)->nodeValue)?$mcs150_mileage->item(0)->nodeValue:"";
					
					$operation_clsf = $subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[14]/td/table/tr[2]');
					$operation_clsf = isset($operation_clsf->item(0)->nodeValue)?$operation_clsf->item(0)->nodeValue:"";
					$operation_clsf = str_replace("SAFER Layout", "", $operation_clsf);
					$array = explode("\n", $operation_clsf);
					$operation_clsf = "";
					$collect = false;
					foreach ($array as $key => $value) {
						$s = trim($value);
						if($s=="X"){ $collect = true; continue; }
						$operation_clsf .=  $collect==true?$s.", ":"";
						$collect = false;
					}
					$operation_clsf = rtrim($operation_clsf, ", ");

					$carrier_operation = $subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[16]/td/table/tr[2]');
					$carrier_operation = isset($carrier_operation->item(0)->nodeValue)?$carrier_operation->item(0)->nodeValue:"";
					$carrier_operation = str_replace("SAFER Layout", "", $carrier_operation);
					$array = explode("\n", $carrier_operation);
					$carrier_operation = "";
					foreach ($array as $key => $value) {
						$s = trim($value);
						if($s=="X"){ $collect = true; continue; }
						$carrier_operation .=  $collect==true?$s.", ":"";
						$collect = false;
					}
					$carrier_operation = rtrim($carrier_operation, ", ");

					$cargo_carried = $subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[19]/td/table/tr[2]');
					$cargo_carried = isset($cargo_carried->item(0)->nodeValue)?$cargo_carried->item(0)->nodeValue:"";
					$cargo_carried = str_replace("SAFER Layout", "", $cargo_carried);
					$array = explode("\n", $cargo_carried);
					$cargo_carried = "";

					foreach ($array as $key => $value) {
						$s = trim($value);
						if($s=="X"){ $collect = true; continue; }
						$cargo_carried .=  $collect==true?$s.", ":"";
						$collect = false;
					}
					$cargo_carried = rtrim($cargo_carried, ", ");
					
					$entity_type=$subdom->query('//body/table/tr[2]/td/table/tr/td/center/table/tr[2]/td');
					$entity_type=isset($entity_type->item(0)->nodeValue)?$entity_type->item(0)->nodeValue:"";

					$mc_mx_ff_url = $subdom->query('//td[@class="queryfield"]/a/@href');
					$mc_mx_ff_url = isset($mc_mx_ff_url->item(0)->nodeValue)?$mc_mx_ff_url->item(0)->nodeValue:"#";
					
					// $html = scurl($ff_url, $header, $cookiefile);
					// $html = $html['HTML'];
					// exit;
				}
			}
			
			$data = array(
				"company_name" => $company_name,
				"address" => trim($address),
				"city" => trim($city),
				"state" => trim($state),
				"zipcode" => trim($zipcode),
				"telephone" => trim($telephone),
				"fax" => $fax,
				"usdot_number" => $usdot_number,
				"mc" => $mc,
				"entity_type" => trim($entity_type),
				"operating_state" => $operating_state,
				"out_of_service_date" => trim($out_of_service_date),
				"power_units" => $power_units,
				"mcs_150_from_date" => $mcs_150_from_date,
				"drivers" => $drivers,
				"mcs150_mileage" => $mcs150_mileage,
				"operation_clsf" => $operation_clsf,
				"carrier_operation" => $carrier_operation,
				"cargo_carried" => $cargo_carried,
				"company_url" => $company_url,
				"safety_url" => $safety_url,
				"mc_mx_ff_url" => $mc_mx_ff_url,
			);
			
			$val = "";
			foreach($data as $v){
				$val .= "'".mysqli_real_escape_string($db, $v)."',";
			}
			$val = trim($val, ",");
			$col = implode(", ", array_keys($data));
			$query = "INSERT INTO `company_dot_data_sri` (".$col.") VALUES ($val)";
			mysqli_query($db, $query) or die(mysqli_error($db));
			
		}
	}
}

$filename = 'dot-gov_'.date('m-d-Y_hia').'.csv';
$result = mysqli_query($db, "select * from company_dot_data_sri") or die(mysqli_error($db));
$fp = fopen($filename, 'w');
while($row = mysqli_fetch_assoc($result))
{
	fputcsv($fp, $row);
}
fclose($fp);

try {
	mail("bishara7@gmail.com,bhaveshac@gmail.com", "Dot.gov Script completed", "Hi, <br>Please download data file at topmovingreviews.com/dot-gov/".$filename."<br>Thanks");
}catch(Exception $e) {
	echo 'Fail to send email:';
}

<?php

require_once('core/database.class.php');
require_once('core/company.class.php');

$bd = new db_class();
$db_link = $bd->db_connect();


    $data1=$_POST['data_to_be_pass'];

 
 if(isset($data1) && $data1!=null)
 {
     list(
         $data['fname'],
         $data['lname'],
         $data['phone'],
         
         $data['email'],
         $data['f_zip'],
         $data['f_city'],
         $data['f_state'],
         $data['t_zip'],
         $data['t_city'],
         $data['t_state'],
         $data['move_date'],
         $data['move_size'],
         $data['message'],
         $data['ip']         
         )=explode("^",$data1);
     


     
$move_size=str_replace("+"," ",$data['move_size']);
   
     
$query = array( 
    'lp_campaign_id' => '5e95a3290f61f', 
    'lp_campaign_key' => 'mt4LTj8X9f2GwkgqWFx3', 
    /*'lp_test' => '1',*/
    
    'first_name' => $data['fname'],
    'last_name' => $data['lname'],
    'phone_home' => $data['phone'],	    
    'email_address' => $data['email'],	    
    
	'city' => $data['f_city'],	
    'state' => $data['f_state'],
    'zip_code' => $data['f_zip'],	
    'country' => 'US',
	
    'to_city' => $data['t_city'],	
    'to_state' => $data['t_state'],	
    'to_zip_code' => $data['t_zip'],	
    'to_country' => 'US',
    
    'move_size' =>  $move_size,	
    'move_date' => $data['move_date'],
	
	'ip_address' => $data['ip'],
	
	'lp_response' => 'JSON'
	
	
);
$dataquery = http_build_query($query, '', '&');


					$ch = curl_init('https://tolmco.leadspediatrack.com/ping.do');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $dataquery);
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Authorization: Bearer 81d2fd84-691a-4ebe-ae01-dba7383baea3')) ;
					$curl_results= curl_exec($ch);
					var_dump($curl_results);
					$campaignListJson = json_decode(($curl_results));
					//var_dump($campaignListJson);
					curl_close($ch);										         
     

if(isset($campaignListJson->result) && $campaignListJson->result=="success")
{
    $query1 = array( 
        'lp_campaign_id' => '5e95a3290f61f', 
        'lp_campaign_key' => 'mt4LTj8X9f2GwkgqWFx3', 
        /*'lp_test' => '1',*/
        
        'lp_ping_id' => $campaignListJson->ping_id,
        
        
        'first_name' => $data['fname'],
        'last_name' => $data['lname'],
        'phone_home' => $data['phone'],	    
        'email_address' => $data['email'],	    
        
    	'city' => $data['f_city'],	
        'state' => $data['f_state'],
        'zip_code' => $data['f_zip'],	
        'country' => 'US',
    	
        'to_city' => $data['t_city'],	
        'to_state' => $data['t_state'],	
        'to_zip_code' => $data['t_zip'],	
        'to_country' => 'US',
        
        'move_size' =>  $move_size,	
        'move_date' => $data['move_date'],
        
        'ip_address' => $data['ip'],
    	
    	'lp_response' => 'JSON'
    	
    	
    );
    $dataquery1 = http_build_query($query1, '', '&');
    
    
					$ch1 = curl_init('https://tolmco.leadspediatrack.com/post.do');
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch1, CURLOPT_POST, true);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch1, CURLOPT_POSTFIELDS, $dataquery1);
					$curl_results1= curl_exec($ch1);
					$campaignListJson1 = json_decode(($curl_results1));
					curl_close($ch1);										         
}
 





// $sql=mysql_query("insert into lead_history (
//                 lead_history_detail
                
//     ) values(
        
//             '".$curl_results."_____".$dataquery."____".$ping_id." '
//         )"); 




if(isset($campaignListJson) && $campaignListJson->ping_id!=null)
{
  $ping_ping_id=$campaignListJson->ping_id;
  $ping_result=$campaignListJson->result;
  $ping_price=$campaignListJson->price;
  $ping_msg=$campaignListJson->msg;
  if(isset($campaignListJson->errors))
  {
      foreach($campaignListJson->errors as $key)
      {
        $ping_errors=$key->error;
        break;
      }
  }
  else {$ping_errors="";}  
}
else
{
  $ping_ping_id="";
  $ping_result="";
  $ping_price="";
  $ping_msg="";
  $ping_errors="";
}
if(isset($campaignListJson1) && $campaignListJson1->lead_id!=null)
{
	$post_lead_id=$campaignListJson1->lead_id;
	$post_result=$campaignListJson1->result;
	$post_price=$campaignListJson1->price;	
	if(isset($campaignListJson1->redirect_url))
	  {
			$post_redirect_url=$campaignListJson1->redirect_url;
	  }
	else {$post_redirect_url="";}	
	$post_msg=$campaignListJson1->msg;
	if(isset($campaignListJson1->errors))
	{
	$post_errors=$campaignListJson1->errors;
	}
	else {$post_errors="";}  	
}
else
{
	$post_lead_id="";
	$post_result="";
	$post_price="";	
	$post_redirect_url="";
	$post_msg="";
	$post_errors="";

}






$date=date("Y-m-d", strtotime($data['move_date'])); 




$sql=mysql_query("insert into lead_history 
(
    lead_history_detail,
    lead_history_status,
    lead_ping_response,
    lead_post_response,
    lead_history_ping_id,
    lead_history_ping_result,
    lead_history_ping_price,
    lead_history_ping_msg,
    lead_history_ping_error,
    lead_history_post_lead_id,
    lead_history_post_status,
    lead_history_post_price,
    lead_history_post_redirect_url,
    lead_history_post_msg,
    lead_history_post_error,
    first_name,
    last_name,
    phone_home,
    city,
    state,
    zip_code,
    country,
    email_address,
    ip_address,
    to_city,
    to_state,
    to_zip_code,
    to_country,
    move_size,
    move_date,
    message,
    other_details
) 
values
(
'{".$data['move_date']."}__{ ".date('Y-m-d',$data['move_date'])." }__ {".$date." }__ {".$data."}',
'',
'"."pingRawResult=".$curl_results."___postRawResult=".$curl_results1."',
'',
'".$ping_ping_id."',
'".$ping_result."',
'".$ping_price."',
'".$ping_msg."',
'".$ping_errors."',
'".$post_lead_id."',
'".$post_result."',
'".$post_price."',
'".$post_redirect_url."',
'".$post_msg."',
'".$post_errors."',
'".$data['fname']."',
'".$data['lname']."',
'".$data['phone']."',
'".$data['f_city']."',
'".$data['f_state']."',
'".$data['t_zip']."',
'US',
'".$data['email']."',
'".$data['ip']."',
'".$data['t_city']."',
'".$data['t_state']."',
'".$data['t_zip']."',
'".US."',
'".$data['move_size']."',
'".$date."',
'".$data['message']."',
''
        )"); 

 }
 
 
 
 
 
 
 



?>
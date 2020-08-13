<?php 
session_start();

require_once('../core/database.class.php');
require_once('../core/company.class.php');

$database = new db_class();
$company = new company();

$db_link = $database->db_connect();

if (isset($_POST['register'])) {
$company_name = $_REQUEST['company_name'];
$email = $_REQUEST['company_email'];
$address = $_REQUEST['company_address'];
$city = $_REQUEST['company_city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['company_zip'];
$phone = $_REQUEST['company_phone'];
$fax = $_REQUEST['company_fax'];
/*$sic_code = $_REQUEST['company_sic_code'];
$sic_description = $_REQUEST['company_sic_desc'];*/
$website = $_REQUEST['company_site'];
$mc_number = $_REQUEST['company_mc'];
$dot_number = $_REQUEST['company_dot'];
$about = str_replace("'","\'", $_REQUEST['about']);
$user_name=$_REQUEST['user_name'];
$pwd=$_REQUEST['pwd'];

  	// Get image name
  	$image = $_FILES['logo']['name'];
 

  	// image file directory
  	$target = "../upload/".basename($image);



  	move_uploaded_file($_FILES['logo']['tmp_name'], $target);

if($company->create_company($company_name, $email, $address, $city, $state, $zip, $phone, $fax, $website, $mc_number,$dot_number,$about,$image,$user_name,$pwd)) {
	      
		   echo '<script>alert("New company is added successfully,<br> Our team will review and add your company to our listing. THANK YOU")</script>';

echo '<meta http-equiv="refresh" content="0; url=http://www.topmovingreviews.com/add-moving-company.php" />';

	
} else {
	echo 'Company could not have been created, please try again';
}
}

$database->db_disconnect($db_link);


?>
<?php 
/*session_start();*/

require_once('../core/database.class.php');
require_once('../core/users.class.php');
require_once('../core/reviews.class.php');

$database = new db_class();
$user = new user();
$reviews = new reviews();

$db_link = $database->db_connect();


//gather details
$company_id=$_REQUEST['company_id'];
$review=$_REQUEST['rating_value'];
$review_subject=$_REQUEST['review_subject'];
$your_review=$_REQUEST['your_review'];
$move_size=$_REQUEST['moving_size'];
$order_id=$_REQUEST['order_id'];
$origin_city = $_REQUEST['origin_city'];
$origin_state = $_REQUEST['origin_state'];
$origin_country = $_REQUEST['origin_country'];
$destination_city = $_REQUEST['destination_city'];
$destination_state = $_REQUEST['destination_state'];
$destination_country = $_REQUEST['destination_country'];
$firstname = $_REQUEST['name'];
$email = $_REQUEST['email'];
$allow=$_REQUEST['allow'];
$ip = $_SERVER["REMOTE_ADDR"];
$date= date('M d,Y');
$container = $_SERVER['HTTP_USER_AGENT'];


 
/*$day = date('j');
$month = date('n');
$year = date('Y');*/
  	$image = $_FILES['photo']['name'];
  	$target = "../upload/".basename($image);
  	move_uploaded_file($_FILES['photo']['tmp_name'], $target);
	
// i have addd user details in review table srikanta dtd08092018
/*if($user->create_user($firstname, $email,$image,$allow, $day, $month, $year, $ip)) {
	//echo json_encode(array('success'=>1, 'message'=>'User created successfully'));
} else {
	echo json_encode(array('success'=>0, 'message'=>'User could not have been created, please try again'));
}
*/
if($reviews->create_review($company_id,$review,$firstname,$email,$date,$review_subject,$your_review,$move_size,$order_id,$origin_city,$origin_state,$origin_country,$destination_country,$destination_state, $destination_city, $ip,$container,$link)) {
	//echo json_encode(array('success'=>1, 'message'=>'Review created successfully'));
} else {
	echo json_encode(array('success'=>0, 'message'=>'Review could not have been created, please try again'));
}



 $sql_lastid="SELECT MAX(id) FROM reviews";
	$query=mysqli_query($link,$sql_lastid);
	$res_id=mysqli_fetch_row($query);




$subject = "Confirm your review at TopMovingReviews - action needed";

$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
	
	<tbody><tr>
		<td bgcolor="#e05333" align="center">
			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:475px">
				<tbody><tr>
					<td align="center" valign="top" style="padding:25px 10px">
						<a href="http://topmovingreviews.com" target="_blank" >
							<img alt="Logo" src="http://topmovingreviews.com/images/logo-7.png" width="205" height="61" style="display:block;width:205px;max-width:205px;min-width:205px;font-family:Roboto,Helvetica,Arial,sans-serif;color:#ffffff;font-size:18px" border="0" class="CToWUd">
						</a>
					</td>
				</tr>
			</tbody></table>
			
		</td>
	</tr>
	
	<tr>
		<td bgcolor="#e05333" align="center" style="padding:0px 10px 0px 10px">
			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:475px">
			  
				<tbody><tr>
					<td bgcolor="#ffffff" align="left" style="padding:0px 0px 0px 0px">
						<img alt="Animated Checklist" src="https://www.mymovingreviews.com/images/email/activate-review-hero.gif" width="475" style="display:block;width:100%;max-width:100%;min-width:100px;font-family:Roboto,Helvetica,Arial,sans-serif;color:#ffffff;font-size:18px" border="0" class="CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 728.5px; top: 315px;"><div id=":u4" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Download attachment " data-tooltip-class="a1V" data-tooltip="Download"><div class="aSK J-J5-Ji aYr"></div></div></div>
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff" align="center" valign="top" style="padding:20px;color:#9fb1c1;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:48px;font-weight:700;line-height:48px;color:#666">
						<h1 style="margin:0;font-weight:400;font-size:25px;line-height:34px;color:#263238">Moving Review Activation!</h1>
					</td>
				</tr>
			</tbody></table>
			
		</td>
	</tr>
		
	<tr>
		<td align="center" style="padding:0px 10px 0px 10px">
			
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:475px">
				
				<tbody><tr>
					<td bgcolor="#ffffff" align="left">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tbody><tr>
								<td align="center" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;color:#6f8996;font-weight:400;line-height:26px;padding:15px 20px 5px 20px">
									<strong>Thank you for submitting</strong><br>
									<strong>your moving review at Topmovingreviews.com!</strong>
								</td>
							</tr>
							<tr>
								<td align="left" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;color:#6f8996;font-weight:400;line-height:26px;padding:15px 20px 5px 20px">
									In an effort to make the moving reviews as accurate as possible, we are asking you to confirm your email address. You can do this by copying and pasting the link below in your browser. If you do not confirm your email address, your review will not be visible.
								</td>
							</tr>
							<tr>
								<td align="left" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;color:#6f8996;font-weight:400;line-height:26px;padding:15px 20px 5px 20px">
									Please click on the button bellow to activate your review on Sinclair Moving &amp; Storage:
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				
				<tr>
					<td bgcolor="#ffffff" align="left">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tbody><tr>
								<td bgcolor="#ffffff" align="center" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;padding:20px 20px 10px 20px">
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
										<tbody><tr>
											<td align="center" style="border-radius:3px" bgcolor="#1FA85D"><a href="www.topmovingreviews.com/activate.php?review_id='.$res_id[0].'" style="font-size:16px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;text-decoration:none;color:#ffffff;text-decoration:none;border-radius:3px;display:block;width:100%;text-transform:uppercase;border-bottom:15px solid #1fa85d;border-top:15px solid #1fa85d" target="_blank" >Activate Review</a></td>
										</tr>
									</tbody></table>
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				
				<tr>
					<td bgcolor="#ffffff" align="left">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tbody><tr>
								<td align="left" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;color:#6f8996;font-weight:400;line-height:26px;padding:15px 20px 5px 20px">
									Once you activate your review, you will be able to share it with your friends and family. Your review will help others to decide whether to use Sinclair Moving &amp; Storage or not.<br>
									Your review is so important to us that we will put it on the home page so everyone can read it. Let all your friends know by sharing it on your favourite social network.
								</td>
							</tr>
							<tr>
								<td align="left" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;color:#6f8996;font-weight:400;line-height:26px;padding:15px 20px 5px 20px">
									You can always deactivate your review once its activated. You will receive more info how to do it in the email after the activation.
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
				
				<tr>
					<td bgcolor="#ffffff" align="left" style="border-left:1px solid #dddddd;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;padding:20px;border-radius:0px 0px 4px 4px;color:#6f8996;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;color:#6f8996;font-weight:400;line-height:26px">
						Thank you for your help.<br>
						The MyMovingReviews Team
					</td>
				</tr>
			</tbody></table>
			
		</td>
	</tr>
	
	<tr>
		<td align="center">
			

			
		</td>
	</tr>
	
	<tr>
		<td align="center" style="padding:0px 10px 0px 10px">
			
	
			
		</td>
	</tr>
</tbody></table>';
 
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
// More headers
$headers .= "From: info@topmovingreviews.com" .  "\r\n";
/*,'-freturn@topmovingreviews.com'*/
$sent = mail($email, $subject, $message, $headers,'-freturn@topmovingreviews.com') ;

if($sent)
{
header("location:../review_submitted.php?email=$email");

}
else
{print "We encountered an error sending your mail"; }

$database->db_disconnect($db_link);

?>
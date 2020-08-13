<?php


class reviews {
	
	//function to check if the review id is valid
	function is_valid_review_id($id) {
		$id = intval($id);
		$str = "SELECT * FROM company_review WHERE review_ID = $id";
		$req = mysql_query($str);
		$num = mysql_num_rows($req);
		if($num == 1) return true;
		else return false;
	}
	
	//function to disapprove review
	function remove_review($id) {
		$str = "DELETE FROM company_review WHERE review_ID = $id";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	//function to approve review
	function approve_review($id) {
		$str = "UPDATE company_review SET approved = 1 WHERE review_ID = $id";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	
	//function to disapprove review
	function disapprove_review($id) {
		$str = "UPDATE company_review SET approved = 0 WHERE review_ID = $id";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	
	//function to get reviewd company name
	function get_reviewed_company_name($company_id) {
		$reviewed_company = "<<Deleted User>>";
		$str = "SELECT * FROM company WHERE Comany_ID = $company_id";
		$req = mysql_query($str);
		while($res = mysql_fetch_array($req)) {
			$reviewed_company = $res['company_name'];
		}
		return $reviewed_company;
	}
	
	
	//function to get user's profile picture
	function get_user_profile_picture($user_id) {
		$profile_pic = "";
		$str = "SELECT * FROM users WHERE ID = $user_id";
		$req = mysql_query($str);
		while($res = mysql_fetch_array($req)) {
			$profile_pic = $res['profile_picture'];
		}
		return $profile_pic;
	}
	
	
	//function to get reviewer first and last name
	function get_reviewer_name($user_id) {
		$reviewer_name = "<<Deleted User>>";
		$str = "SELECT * FROM users WHERE ID = $user_id";
		$req = mysql_query($str);
		while($res = mysql_fetch_array($req)) {
			$reviewer_name = $res['first_name']." ".$res['last_name'];
		}
		return $reviewer_name;
	}
	
	
	
		//function to approve review
	function approve_company($id) {
		$str = "UPDATE company SET approved = 1 WHERE Comany_ID = $id";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	
	//function to disapprove review
	function disapprove_company($id) {
		$str = "UPDATE company SET approved = 0 WHERE Comany_ID= $id";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	//function to create a new review
	
	
	
	function create_review($company_id,$review,$firstname,$email,$date,$review_subject,$your_review,$move_size,$order_id,$origin_city,$origin_state,$origin_country,$destination_country,$destination_state, $destination_city,$ip,$container) {
	
	
		  $str = "INSERT INTO reviews
		(id,company_id,author,email,date,rating,review_subject,text,move_size,order_id,origin_country,origin_state,origin_city,destination_country,destination_state,destination_city,ip_address,browser_details) 
		VALUES 
		('','$company_id','$firstname','$email','$date','$review','$review_subject','$your_review','$move_size','$order_id','$origin_city','$origin_state','$origin_country','$destination_country','$destination_state', '$destination_city','$ip','$container')";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	
	
}


?>
<?php 

class user {
	
	//function to delete user
	function delete_user($user_id) {
		$user_id = intval($user_id);
		$str = "DELETE FROM users WHERE ID = $user_id";
		if($req = mysql_query($str)) { $this->delete_related_reviews($user_id); return true; }
		else return false;
	}
	
	//function to delete related reviews
	function delete_related_reviews($user_id) {
		$str = "DELETE FROM company_review WHERE User_ID = $user_id";
		@mysql_query($str);
	}
	
	
	//function to create a new user
	function create_user($firstname,  $email,$image,$allow, $day, $month, $year, $ip) {
	 	$str = "INSERT INTO users 
		(first_name, email,profile_picture, allow_visitor, reg_day, reg_month, reg_year, ip_address) 
		VALUES 
		('$firstname', '$email','$image','$allow', $day, $month, $year, '$ip')";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	//function to update a user
	function update_user($id, $firstname, $lastname, $email, $address, $city, $state, $zip, $country) {
		$str = "UPDATE users SET first_name = '$firstname', last_name = '$lastname', email = '$email', address = '$address', city = '$city', state = '$state', zip = '$zip', country = '$country' WHERE ID = $id";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	
	//function to select single user
	function select_single_user($user_id) {
		$str = "SELECT * FROM users WHERE ID = $user_id";
		$req = mysql_query($str);
		while($res = mysql_fetch_assoc($req)){
			$data[]=$res;
		}
		return $data;
	}
	
	
	//get all users
	function select_all_user() {
		$str = "SELECT * FROM users";
		$req = mysql_query($str);
		while($res = mysql_fetch_assoc($req)){
			$data[]=$res;
		}
		return $data;
	}
	
	//function to  get total reviews by a current user
	function get_user_reviews_count($user_id) {
		$str = "SELECT * FROM company_review WHERE User_ID = $user_id";
		$req = mysql_query($str);
		$num = mysql_num_rows($req);
		return $num;
	}

		
} //end class

?>
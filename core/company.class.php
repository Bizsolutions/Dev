<?php 
 
class company {
	
	//function to update company logo
	function update_company_logo($company_id, $logo_url) {
		$str = "UPDATE company SET company_logo = '$logo_url' WHERE Comany_ID = $company_id";
		if($req = mysql_query($str)) return true;
		else return false;	
	}
	
	
	//function to check if the company id is valid
	function is_valid_company($id) {
		$str = "SELECT * FROM company WHERE Comany_ID = $id";
		$req = mysql_query($str);
		$num = mysql_num_rows($req);
		if($num == 1) return true;
		else return false;
	}
	
	//function to delete company
	function delete_company($company_id) {
		$company_id = intval($company_id);
		$str = "DELETE FROM company WHERE Comany_ID = $company_id";
		if($req = mysql_query($str)) { $this->delete_related_reviews($company_id); return true; }
		else return false;
	}
	
	//function to delete related reviews
	function delete_related_reviews($company_id) {
		$str = "DELETE FROM company_review WHERE Company_ID = $company_id";
		@mysql_query($str);
	}
	
	
	//function to update company
	function update_company($company_id, $company_name, $email, $address, $city, $state, $zip, $phone, $fax, $sic_code, $sic_description, $website,$mc_number,$dot_number) {
		 $str = "UPDATE company SET company_name = '$company_name', email_address = '$email', address = '$address', city = '$city', state = '$state', zipcode = '$zip', phone_number = '$phone', fax_number = '$fax', sic_code = $sic_code, sic_description = '$sic_description', web_address = '$website',mc_number = '$mc_number', dot_number = '$dot_number'  WHERE Comany_ID = $company_id"; 
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	//function to create company
	function create_company($company_name, $email, $address, $city, $state, $zip, $phone, $fax, $website, $mc_number,$dot_number,$about,$image,$user_name,$pwd) {
		 $str = "INSERT INTO  register_company
		(c_id,company_name, email, address, city, state, zipcode, phone, fax, web_address, mc_number,dot_number,about_us,company_logo,user_name,pwd)
		VALUES
		('','$company_name', '$email', '$address', '$city', '$state', '$zip', '$phone', '$fax', '$website', '$mc_number','$dot_number','$about','$image','$user_name','$pwd')";
		if($req = mysql_query($str)) return true;
		else return false;
	}
	
	//function to update the marker location
	function update_map($id, $coords) {
		$str = "UPDATE company SET gmap_coords = '$coords' WHERE Comany_ID = $id";
		if($req = mysql_query($str)) return true;
		else return false;	
	}
	
	//function select all company
	function select_all_company() {
		$qury="SELECT * FROM company";
		$requet=mysql_query($qury);
		
		while($res=mysql_fetch_assoc($requet)) {
			$data[]=$res;
		}
		return $data;
	}
	
   // select one company
   function select_company($id) {
	   $req = "SELECT * FROM company WHERE Comany_ID = $id";
	   $rt = mysql_query($req);
	   while($res = mysql_fetch_assoc($rt)){
			$data[] = $res;
		}
		return $data;
	}//end function select 			  
	 
	 		//function to  get total reviews by a current company
	function get_company_reviews_count($company_id) {
		$str = "SELECT * FROM reviews WHERE Company_ID = $company_id";
		$req = mysql_query($str);
		$num = mysql_num_rows($req);
		return $num;
	}
		function get_average_reviews_count($company_id) {
		$str = "SELECT avg(rating) FROM reviews WHERE Company_ID = $company_id";
		$rat = mysql_query($str);
		$rating = mysql_fetch_assoc($rat);
		return $rating;
	}
	 		//function to  get feature listing name by a current company
	function get_company_feature_listing($feature_id) {
		 $str = "SELECT feature_name FROM feature_listing WHERE feature_id = $feature_id"; 
		$fea = mysql_query($str);
		$res = mysql_fetch_assoc($fea);
		return $res;
	}
	 
	   
}//end class
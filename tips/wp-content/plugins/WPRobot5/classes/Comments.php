<?php
class WPR5_Comments {

    function __construct()
    {
    }
	
    function create($args)
    {

		$comment_post_ID = $args["post_id"];

		if($args['postdate']) {
			$comment_date = $args['postdate'];	
		} else {
			$comment_date = current_time('mysql');
		}			
		
		if(function_exists("split")) {
			list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $comment_date );	
		} else {
			list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $comment_date );			
		}
		$comment_date = mktime($hour, $minute + rand(0, 59), $second + rand(0, 59), $today_month, $today_day, $today_year);
		$comment_date=date("Y-m-d H:i:s", $comment_date); 		
		$comment_date_gmt = $comment_date;					

		$rnd= rand(1,9999);
		$comment_author_email="someone$rnd@domain.com";
		$comment_author= $args["comment"]["author"];
		$comment_author_url='';  
		$comment_content="";
		$comment_content.= $args["comment"]["content"];
		$comment_type='';
		$user_ID='';
		$comment_approved = 1;
		$commentdata = compact('comment_post_ID', 'comment_date', 'comment_date_gmt', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID', 'comment_approved');
		return wp_insert_comment( $commentdata );	
    }	
}
?>
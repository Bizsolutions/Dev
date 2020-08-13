<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Top Moving Reviews</title>
</head>
<style type="text/css">
  
  .column {
    float: left;
    width: 50%;
	text-align:center;
}
  
/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}
</style> 
<body>
<?php
$comp_name = str_replace('-', ' ', $_REQUEST['title']);
?>
 <h3  style="font-weight:bold; text-align:center;"> <?php echo $comp_name; ?> Badge</h3>
 
 <div style="width:530px; height:490px; border:solid 1px #CCCCCC; background:#E1E1E1 border-radius:5px; vertical-align:middle; padding:20px; margin-left:8px;">

<p align="center">PASTE THE BADGE CODE TO YOUR WEBSITE</p>
 <p align="center"><img src="https://www.topmovingreviews.com/images/badge.png"> </p>       
          <textarea style="margin: 0px; width: 530px; height: 60px;"><a  href="https://www.topmovingreviews.com/movers/<?php echo $_REQUEST['title']; ?>-<?php echo $_REQUEST['company_id']; ?>/"><img src="https://www.topmovingreviews.com/images/badge.jpg" alt="Ranked On topmovingreviews.com - Ratings &amp; Rankings of Moving companies" border="0" ></a>  </textarea>
         <h3  style="text-align:center; font-weight:bold;">Let new customers know you have existing customers rooting for you, with our badges.</h3>

				  <p align="justify">Our badges easily link customers to your online profiles. When used successfully, badges encourage people to choose your company over competitors.</p>

				  <p align="justify">Our badges are an eye catching, one-way ticket to your business listing. They allow customers to easily research your licenses, read & write reviews.</p>

      </div>

</body>
</html>

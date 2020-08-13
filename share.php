<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Top Moving Reviews</title>
 <link rel="stylesheet" href="https://topmovingreviews.com/responsive/bootstrap.min.css">

  <script src="https://topmovingreviews.com/responsive/jquery.min.js"></script>

  <script src="https://topmovingreviews.com/responsive/popper.min.js"></script>

  <script src="https://topmovingreviews.com/responsive/bootstrap-datetimepicker.min.js"></script>

  <script src="https://topmovingreviews.com/responsive/bootstrap.min.js"></script>

    <script src="https://topmovingreviews.com/responsive/demo.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

</head>

</style> 
<body>
    <div class="container">
 <h3  style="font-weight:bold;border-bottom:1px solid #CCCCCC;">Share Review</h3>
 <div style=" border:solid 1px #CCCCCC; background:#F8F8F8; border-radius:3px; vertical-align:middle; padding:20px; ">

<div class="row text-center">
  <div class="column"><a href="https://www.facebook.com/sharer/sharer.php?u=https://www.topmovingreviews.com/movers/<?php echo $_REQUEST['comp_name']; ?>-<?php echo $_REQUEST['id']; ?>/" target="_blank"><img src="images/fb.jpg" height="45"></a></div>
  <div class="column"><a href="https://twitter.com/intent/tweet?url=https://www.topmovingreviews.com/movers/<?php echo $_REQUEST['comp_name']; ?>-<?php echo $_REQUEST['id']; ?>/" target="_blank"><img src="images/tw.jpg"  height="45"></a></div>
			</div>
       <br />
        
          <input name="share" value="https://www.topmovingreviews.com/movers/<?php echo $_REQUEST['comp_name']; ?>-<?php echo $_REQUEST['id']; ?>/" type="text" style="width:92%;height: 20px;background: url(images/share-company.png) no-repeat 4px 6px;cursor: pointer;padding: 5px 5px 5px 27px;border: 1px solid #ccc;border-radius: 4px;" readonly="" onClick="this.focus();this.select()">
        
      </div>
</div>
</body>
</html>

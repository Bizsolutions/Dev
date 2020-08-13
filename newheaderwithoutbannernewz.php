
  <link rel="stylesheet" href="https://www.topmovingreviews.com/responsive/bootstrap.min.z.css">

  <!--<script src="https://www.topmovingreviews.com/responsive/jquery.min.js"></script>-->

  
  
    


<!--        <link rel="stylesheet" href="bootstrap-datetimepicker.min.css">
-->
 
<link rel=stylesheet href="https://www.topmovingreviews.com/css/dhtmlxcalendar.css"/>

<script>var today=new Date();var dd=today.getDate();var mm=today.getMonth()+1;var yyyy=today.getFullYear();if(dd<10)
   {dd='0'+dd;}
   if(mm<10)
   {mm='0'+mm;}
   today=mm+'-'+dd+'-'+yyyy;var myCalendar;function doOnLoad(){myCalendar=new dhtmlXCalendarObject("calendarHere");myCalendar.hideTime();myCalendar.setDate(getdate());}
   function setFrom(){myCalendar.setSensitiveRange(today,null);}
   
   function validate()
   {
    
   		
         if ( document.getElementById('MovingFrom').value == '' || (isNaN(document.getElementById('MovingFrom').value)))
           {
                   alert('Enter Valid Zip Code Where  you are Moving From!');
   				document.getElementById('MovingFrom').focus();
                   return false;				
           }
   		
   		  else if ( document.getElementById('MovingTo').value == '' )
           {
                   alert('Enter Valid Zip Code Where  you are Moving To!');
   				document.getElementById('MovingTo').focus();
                   return false;				
           }
   		else if ( document.getElementById('calendarHere').value == '' )
           {
           
                   alert('Select Moving Date!');
   				document.getElementById('calendarHere').focus();
                   return false;				
           }
   		
   		else
   		{
   		document.getElementById("frm1").submit();	
   		return true;
   		}
   }
   
</script>



<div class="top-bar">

<div class="container">

    <div class="row">

        <div class="col-sm-12 text-right bar-pad"><a href="https://www.topmovingreviews.com/index.php">Home</a> | <a href="https://www.topmovingreviews.com/add-moving-company.php">Register Business</a></div>

        

    </div>

    

</div>    

    

</div>

<div class="bg-dark">

<div class="container">

    <nav class="navbar navbar-expand-lg navbar-light">

  <a class="navbar-brand" href="https://www.topmovingreviews.com/"><img src="https://www.topmovingreviews.com/images/logo.jpg" alt="top-moving-logo"></a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

    <span class="navbar-toggler-icon"></span>

  </button>



  <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">

      <li class="nav-item active">

        <a class="nav-link" href="https://www.topmovingreviews.com/">Home <span class="sr-only">(current)</span></a>

      </li>

      <li class="nav-item">

        <a class="nav-link" href="https://www.topmovingreviews.com/moving-company.php">Moving Companies</a>

      </li>

       <li class="nav-item">

        <a class="nav-link" href="https://www.topmovingreviews.com/write-your-review.php">Write A Review</a>

      </li>

       <li class="nav-item">

        <a class="nav-link" href="https://www.topmovingreviews.com/add-moving-company.php">Add Moving Company</a>

      </li>

    <!--   <li class="nav-item">

        <a class="nav-link" href="https://www.topmovingreviews.com/moving-company-reviews.php">Moving Reviews</a>

      </li>-->

   

    </ul>

   <form class="form-inline my-2 my-lg-0" name="search" action="https://www.topmovingreviews.com/searchcompany.php" method="post">

     <div class="input-group">

    <input type="text" class="form-control" placeholder="Search " id="search" name="company_search">

    <div class="input-group-append">

      <button class="btn btn-secondary" type="submit">

        <i class="fa fa-search"></i>

      </button>

    </div>

  </div>

    </form>

  </div>

</nav>

    

    

</div></div>

<!--<div style="background-image:url('https://www.topmovingreviews.com/responsive/00banner.png');height: 156px;
    background-size: cover;
    background-position: 10px 402px;">

    <div class="container">

    <div class="row">

        <div class="col-md-7">  </div>

<div class="col-md-5">




    

</div>

    </div></div>

 

</div>
-->



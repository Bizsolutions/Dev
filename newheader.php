
  <link rel="stylesheet" href="https://topmovingreviews.com/responsive/bootstrap.min.css">

  <script src="https://topmovingreviews.com/responsive/jquery.min.js"></script>

  <script src="https://topmovingreviews.com/responsive/popper.min.js"></script>

  <script src="https://topmovingreviews.com/responsive/bootstrap-datetimepicker.min.js"></script>

  <script src="https://topmovingreviews.com/responsive/bootstrap.min.js"></script>

    <script src="https://topmovingreviews.com/responsive/demo.js"></script>

    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://topmovingreviews.com/css/font-awesome.min.css">


<!--        <link rel="stylesheet" href="bootstrap-datetimepicker.min.css">
-->
 
<link rel=stylesheet href="https://topmovingreviews.com/css/dhtmlxcalendar.css"/>
<script src="https://topmovingreviews.com/js/dhtmlxcalendar.js"></script>
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

    <ul class="navbar-nav mr-auto pull-right">

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

   <!--    <li class="nav-item">

        <a class="nav-link" href="https://www.topmovingreviews.com/moving-company-reviews.php">Moving Reviews</a>

      </li>-->

   

    </ul>

   <!--<form class="form-inline my-2 my-lg-0" name="search" action="https://www.topmovingreviews.com/searchcompany.php" method="post">

     <div class="input-group">

    <input type="text" class="form-control" placeholder="Search " id="search" name="company_search">

    <div class="input-group-append">

      <button class="btn btn-secondary" type="submit">

        <i class="fa fa-search"></i>

      </button>

    </div>

  </div>

    </form>-->

  </div>

</nav>

    

    

</div></div>

<div class="jumbotron" >

    <div class="container">
<div class="fadeInLeft wow animated delay-1"><h1>Top Moving Company Rating & Reviews</h1></div>
    <div class="row">

        <div class="col-md-3">  </div>

<div class="col-md-6">

    <div class="shadow-lg p-3 mb-5 bg-white rounded"> <form  id="frm1" name="frm1" method="post" action="quoteform.php" autocomplete="off">

         <div class="banner-rect-box">

            <div class="ban_rct">

                

               <div class="packup">

                  <label>Where are you moving from?</label>

                  <div class="banner-main">

                     <div class="rectBox"> <input type="text" name="MovingFrom" id="MovingFrom" value="Zip Code" onfocus="this.value=''" class="form-control form-control-sm"></div>

                  </div>

               </div>

               <div class="delivery">

                  <label>Where are you <i>moving</i> to?</label>

                  <div class="banner-main">

                     <div class="rectBox"> <input type="text" name="MovingTo" id="MovingTo" value="Zip Code"  onfocus="this.value=''" class="form-control form-control-sm"></div>

                  </div>

               </div>

                 <div class="form-group">

                      <label>Move Date

</label>

                        <div class="input-group date" id="id_0">

           <input type="text" id="calendarHere" class="datepicker form-control" name="calendarHere" onClick="setFrom();" placeholder="Moving Date" readonly="" required/>

                            <div class="input-group-addon input-group-append" onClick="setFrom();">

                                <div class="input-group-text" >

                                    <i class="glyphicon glyphicon-calendar fa fa-calendar" ></i>

                                </div>

                            </div>

                        </div>

                    </div>

            </div>

            <div >



                     <button type="submit" class="btn btn-primary" onclick="javascript:return validate()" style="cursor:pointer;">Continue</button> The quick and easy money saver.



               </div>

             

            </div>

         </div>

      </form></div>

 <div class="col-md-3">  </div>

    

</div>

    </div></div>
<div class="container mm">
    <div class="row ">
        <div class="col-md-2"></div>
        <div class="col-md-1"></div>
        <div class="col-md-2 p-10">
            <div class="new-head">
                <img src="responsive/icon.png
" class="arrow-1"/>
            <h4>Fill Out the Form</h4>
</div>
</div>
         <div class="col-md-2 p-10">
              <div class="new-head">
                   <img src="responsive/icon.png
"class="arrow-1"/>
                  <h4>Choose <br/>Movers</h4></div>

</div>
          <div class="col-md-2 p-10"> <div class="new-head">
               
              <h4>Compare Quotes & Save</h4>
</div>
</div>
<div class="col-md-2"></div>
 <div class="col-md-1"></div>
    </div>
    
</div>
 

</div>






<!--calendar for submission form dated 12012018-->
<link rel=stylesheet href="https://www.topmovingreviews.com/css/dhtmlxcalendar.css"/>
<script src="https://www.topmovingreviews.com/js/dhtmlxcalendar.js"></script>
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
<!--<link href="https://www.topmovingreviews.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">-->

<div class="header" >
   <div class="header-left">
      <a href="https://www.topmovingreviews.com/"><img src="https://www.topmovingreviews.com/images/logo.jpg" alt="top-moving-logo" ></a>
      <div class="navtop-left" style="width: 75%; overflow: hidden;">
         <ul>
            <li><a href="https://www.topmovingreviews.com/index.php" class="current_page">Home</a></li>
            <li><a href="https://www.topmovingreviews.com/moving-company.php">Moving Companies</a></li>
            <li><a href="https://www.topmovingreviews.com/write-your-review.php">Write A Review</a></li>
            <li><a href="https://www.topmovingreviews.com/add-moving-company.php">Add Moving Company</a></li>
            <li><a href="https://www.topmovingreviews.com/moving-company-reviews.php">Moving Reviews</a></li>
         </ul>
      </div>
   </div>
   <div class="header-right">
      <div class="nav">
         <ul>
            <li><a href="https://www.topmovingreviews.com/index.php">Home</a></li>
            <!--<li><a href="#">Sign in</a></li>-->
            <li><a href="https://www.topmovingreviews.com/add-moving-company.php">Register Business</a></li>
            <!--<li><a href="https://www.topmovingreviews.com/New-Jersey-Movers-NJ-USA/Garland-moving-group.php#">Mobile App</a></li>-->
         </ul>
      </div>
      <form name="search" action="https://www.topmovingreviews.com/searchcompany.php" method="post">
         <div class="search-box"> <input type="text" id="search" name="company_search" value="Looking For" onfocus="this.value=''" > <button><i class="fa fa-search"></i>&nbsp;Search</button></div>
      </form>
   </div>
</div>
<div class="header-banner">
   <div class="banner-right">
      <h2 class="banner-titile">Plan Your Perfect Move</h2>
      <form  id="frm1" name="frm1" method="post" action=quoteform.php autocomplete="off">
         <div class="banner-rect-box">
            <div class="ban_rct">
               <div class="packup">
                  <label>Where are you moving from?</label>
                  <div class="banner-main">
                     <div class="rectBox"> <input type="text" name="MovingFrom" id="MovingFrom" value="Zip Code" onfocus="this.value=''"></div>
                  </div>
               </div>
               <div class="delivery">
                  <label>Where are you <i>moving</i> to?</label>
                  <div class="banner-main">
                     <div class="rectBox"> <input type="text" name="MovingTo" id="MovingTo" value="Zip Code"  onfocus="this.value=''"></div>
                  </div>
               </div>
               <div class="date">
                  <label><i>Move</i> Date</label>
                  <div class="calander"> <input id="calendarHere" class="datepicker" name="calendarHere" onClick="setFrom();" placeholder="Moving Date" readonly=""></div>
               </div>
            </div>
            <div class="lets_go">
               <div class="letsgo"> <a onclick="javascript:return validate()" style="cursor:pointer;">Continue</a></div>
               <div class="quick_text">
                  <p>The quick and easy money saver.</p>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>


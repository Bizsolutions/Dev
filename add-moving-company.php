<?php header('Content-Type: text/html; charset=ISO-8859-1');
require_once('core/database.class.php');
require_once('core/company.class.php');
$bd = new db_class();
$db_link = $bd->db_connect();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Best Moving Companies Reviews | Top Rated Moving Company | Compare Movers</title>

<meta name=viewport content="width=device-width, initial-scale=1.0">
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
 <link rel="icon" href="favicon.png" type="image/png" sizes="16x16"> 
<meta name="description" content="Quote for Top Moving Reviews">
<meta name=keywords content="Services,(800) 219-4008,Local Manhattan, NY, New York, USDOT 2868936 ,Long Distance Movers, MC 961621, Relocation, 10005,Moving">

<link href="https://www.topmovingreviews.com/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<meta name=dc.language content=US>
<meta name=dc.subject content="NY Movers">
<meta name=DC.identifier content="/meta-tags/dublin/">
<meta http-equiv=X-UA-Compatible content="IE=edge, chrome=1">
<meta name=msvalidate.01 content=F5DD6D983E8D9C08B3E5BC46AECA66D3>
<meta name=HandheldFriendly content=true>
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","url":"http://topmovingreviews.com/","contactPoint":[{"@type":"ContactPoint","telephone":"+1-800-219-4008","contactType":"customer service"}]}</script>

	<script>

function showUser(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("DivCity").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","statefromzip.php?q="+str,true);
xmlhttp.send();
}



function validate()

{

 var stringEmail = document.getElementById('company_email').value;

 if ( document.getElementById('company_name').value == '' )

        {

        

                alert('Enter Company Name!');
				document.getElementById('company_name').focus();
                return false;				

        }

		     if ( document.getElementById('user_name').value == '' )

        {

        

                alert('Enter User Name!');
				document.getElementById('user_name').focus();
                return false;				

        }
		 if ( document.getElementById('pwd').value == '' )

        {

        

                alert('Enter Password!');
				document.getElementById('pwd').focus();
                return false;				

        }  

         else if ( document.getElementById('company_email').value == '' )

        {

                alert('Enter your Email!');
				document.getElementById('company_email').focus();
                return false;				

        }

       

        

		 else  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(stringEmail)))

        {

            

            alert('Enter Valid Email Id');            
			document.getElementById('company_email').focus();
            return false;

        } 

         else if ( document.getElementById('company_address').value == '' )

        {

                alert('Enter Company Address!');
				document.getElementById('company_address').focus();
                return false;				

        }
		     else if ( document.getElementById('company_city').value == '' )

        {

                alert('Enter Company City!');
				document.getElementById('company_city').focus();
                return false;				

        }
		
		  else if ( document.getElementById('company_zip').value.length != 5 )

        {

                alert('Enter 5 Digit Zip Code!');
				document.getElementById('company_zip').focus();
                return false;				

        }
		  else if ( document.getElementById('state').value== '' )

        {

                alert('Enter State Name!');
				document.getElementById('state').focus();
                return false;				

        }

	
		  else if ( document.getElementById('company_phone').value.length != 10 )

        {

                alert('Enter 10 Digit Phone Number!');
				document.getElementById('company_phone').focus();
                return false;				

        }
    

			     else if ( document.getElementById('company_site').value == '' )

        {

                alert('Enter Company Web Site!');
				document.getElementById('company_site').focus();          
      			return false;				

        }
		  else if ( document.getElementById('company_mc').value.length < 5 )

        {

                alert('Enter Correct MC Number!');
				document.getElementById('company_mc').focus();
                return false;				

        }
	  else if ( document.getElementById('company_dot').value.length < 5 )

        {

                alert('Enter Correct DOT Number!');
				document.getElementById('company_dot').focus();
                return false;				

        }

		
		else

		{
		return true;

		}

}

</script>
	<!--srikanta datd 15092018-->
 
</head>
<body onLoad="doOnLoad();">
    <?php include 'newheaderwithoutbanner.php'; ?>
<div class=wrapper>

<div class=container>
<div class="row">
<br>
<br>
<div class="col-md-3"></div>
<div class="col-md-6 shadow-lg p-3 mb-5 bg-light rounded">
    <h3 class="text-center">Free Account Setup Form</h3>
    <form action="process/add_company.php" method="post" class="native" enctype="multipart/form-data"  >
        
<table border="0" cellspacing="0" width="100%">
  
  <tr>
    <td  height="51" ><div align="txxt">Company Name*: </div></td>
    <td >
        
        <input name="company_name" id="company_name"class="form-control" value="" placeholder="Company Name*: " type="text" maxlength="340"></td>
  </tr>
  <tr>
    <td height="43" >User Name*:</td>
    <td><input name="user_name" id="user_name" value="" class="form-control"placeholder="User Name*:" type="text" maxlength="320" ></td>
  </tr>
  <tr>
    <td height="43" >Password*:</td>
    <td><input name="pwd" id="pwd" value="" type="password" class="form-control" placeholder="Password*:"maxlength="320" ></td>
  </tr>
  <tr>
    <td height="43" ><div align="txxt">E-mail Adress*:</div></td>
    <td><input name="company_email" id="company_email" value="" class="form-control" placeholder="E-mail Adress*:" type="email" maxlength="320" ></td>
  </tr>
  <tr>
    <td height="57" ><div align="txxt">Address*:</div></td>
    <td><input name="company_address" id="company_address" value="" class="form-control" placeholder="Address*:" required="" type="text" maxlength="340"></td>
  </tr>
  <tr>
    <td height="51" ><div align="txxt">City*:</div></td>
    <td><input name="company_city" id="company_city" value="" class="form-control" required="" placeholder="City*" type="text" maxlength="340"></td>
  </tr>  
  
  <tr>
    <td><div align="txxt">Zip Code*:</div></td>
    <td><input name="company_zip" id="company_zip" value="" class="form-control" placeholder="Zip Code*:" required="" type="number"  onBlur="return showUser(this.value)" ></td>
  </tr>
  <tr>
    <td height="52" align="right">

	
	<div >State*:</div></td>
    <td>	<div id="DivCity">
Please Enter Zip Code
</div></td>
  </tr>

  <tr>
    <td height="54" ><div align="txxt">Phone Number*:</div></td>
    <td><input name="company_phone" id="company_phone" value="" class="form-control" placeholder="Phone Number*:" required="" type="number" ></td>
  </tr>
  <tr>
    <td height="53" ><div align="txxt">Fax Number:</div></td>
    <td><input name="company_fax" id="company_fax" value="" class="form-control" placeholder="Fax Number:" type="text" maxlength="340" ></td>
  </tr>
  <tr>
    <td height="47" ><div align="txxt">Web Address*:</div></td>
    <td><input name="company_site" id="company_site" value=""class="form-control" required="" placeholder="Web Address*" type="text" maxlength="340" ></td>
  </tr>
  <tr>
    <td height="46" ><div align="txxt">MC Number*:</div></td>
    <td><input name="company_mc" id="company_mc" value="" required="" class="form-control" placeholder="MC Number*:" type="number" ></td>
  </tr>
    <tr>
    <td height="43" ><div align="txxt">DOT Number*:</div></td>
    <td><input name="company_dot" id="company_dot" value="" required="" class="form-control" placeholder="DOT Number*:" type="number" ></td>
  </tr>
    <tr>
    <td height="99" ><div align="txxt">About Us:</div></td>
    <td><textarea name="about" id="about" placeholder="About Us" cols="43" class="form-control" rows="10"></textarea></td>
  </tr>
    <tr>
    <td height="38" ><div align="txxt">Upload LOGO:</div></td>
    <td><input name="logo" id="logo" type="file" placeholder="Upload Logo"></td>
  </tr>
    <tr>
    <td></td>
    <td><input value="Create Moving Company Account" name="register" type="submit" class="btn btn-primary"  onClick="javascript:return validate();"></td>
  </tr>
</table>
</form>
    
</div>
<div class="col-md-3"></div>


</div>

</div>

<?php include 'footer.php'; ?>
</body>
</html>

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

<script type="text/javascript">
function validate()

{

 var stringEmail = document.getElementById('contact_email').value;

 if ( document.getElementById('contact_name').value == '' )

        {

                alert('Enter Your Name!');
				document.getElementById('contact_name').focus();
                return false;				

        }

         else if ( document.getElementById('contact_email').value == '' )

        {

                alert('Enter your Email!');
				document.getElementById('contact_email').focus();
                return false;				

        }

		 else  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(stringEmail)))

        {

            alert('Enter Valid Email Id');            
			document.getElementById('contact_email').focus();
            return false;

        } 
		
		else

		{
		document.contact_frm.submit();
		return true;

		}
}
</script>
</head>

<body >
<br />

<br/>

<div class="container">
<h3 class="text-center">Send a Message to <?php echo $_REQUEST['author']; ?> </h3>

<form name="contact_frm" method="post" action="contact_user_messagesent.php?author=<?php echo $_REQUEST['author']; ?>" class="shadow-sm p-3 mb-5 bg-white rounded" >
         	<table " border="0" align="center" cellpadding="2" cellspacing="2"  style="vertical-align:middle;" >
					<tr >
						<td height="45" valign="top"><input placeholder="Your Name" name="contact_name" id="contact_name" required="" class="form-control"></td>
					</tr>

					<tr >
	<td width="380" height="45" valign="top"><input placeholder="Your E-mail" name="contact_email" id="contact_email" required="" class="form-control"></td>
					</tr>

				

					<tr >
						<td height="45" valign="top" ><input placeholder="Subject" name="subject" id="contact_subject" required="" class="form-control"></td>
					</tr>

					<tr >
						<td height="45" valign="top">
							Your message (please include contact information so the reviewer can reply back):<br>
							<textarea name="message" id="contact_message" rows="3" cols="43" class="form-control"></textarea>
						</td>
					</tr>
  </table>

       <br/>
        <div class="text-center" onClick="javascript:validate();"><a href="#" class=" btn btn-primary">Send a Message to <?php echo $_REQUEST['author']; ?></a>&ensp;</div>
		</div>
     
</form>

</body>
</html>

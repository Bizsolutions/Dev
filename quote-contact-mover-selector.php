<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="css.css" rel=stylesheet type="text/css" >

</head>

<body>
<div class="main-container">
			<form class="form-horizontal" id="quote" action="" method="post">
				<div class="row">
					<div class="col-xs-12">
	
						<div class="form-group">
							<div class="col-xs-12 top">
								<span id="success" class="">Contact 495 Movers:</span>
								<span id="error" class=""></span>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<!-- Left inputs -->
					<div class="col-xs-6">

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="movingSize" class="control-label pull-right">Moving Size:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<select class="form-control" id="movingSize" name="movingSize">
											<option value="">Select Size</option>
											<option value="4 Bedroom, 11200 lbs" >4+ Bedroom Home</option>
											<option value="3 Bedroom, 8750 lbs" >3 Bedroom Home</option>
											<option value="2 Bedroom, 6300 lbs" >2 Bedroom Home</option>
											<option value="1 Bedroom, 3850 lbs" >1 Bedroom Home</option>
											<option value="Studio, 2450 lbs" >Studio</option>
											<option value="PartialHome, under 2000 lbs" >Partial Move</option>
											<option value="Commercial" >Commercial</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="moving_date" class="control-label pull-right">Moving Date:</label>
									</div>

									<div class="col-xs-7 no-left-padding">
										<input type="text" readonly="" class="form-control" name="moving_date" id="moving_date" value="09/06/2018" />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 pull-right">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="from_zip" class="control-label pull-right">From Zip:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<input type="tel" class="form-control input" name="from_zip" id="from_zip" maxlength="5" value="" />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 pull-right">
								<div class="form-group">
									<div class="col-xs-12">
										<div id="from-city" class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="to_state" class="control-label pull-right">Going To:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<select class="form-control" name="to_state" id="to_state">
											<option value="">Destination State</option>
											<option value="AK" >AK - Alaska</option>
											<option value="AL" >AL - Alabama</option>
											<option value="AR" >AR - Arkansas</option>
											<option value="AZ" >AZ - Arizona</option>
											<option value="CA" >CA - California</option>
											<option value="CO" >CO - Colorado</option>
											<option value="CT" >CT - Connecticut</option>
											<option value="DC" >DC - District of Columbia</option>
											<option value="DE" >DE - Delaware</option>
											<option value="FL" >FL - Florida</option>
											<option value="GA" >GA - Georgia</option>
											<option value="HI" >HI - Hawaii</option>
											<option value="ID" >ID - Idaho</option>
											<option value="IL" >IL - Illinois</option>
											<option value="IN" >IN - Indiana</option>
											<option value="IA" >IA - Iowa</option>
											<option value="KS" >KS - Kansas</option>
											<option value="KY" >KY - Kentucky</option>
											<option value="LA" >LA - Louisiana</option>
											<option value="MA" >MA - Massachusetts</option>
											<option value="MD" >MD - Maryland</option>
											<option value="ME" >ME - Maine</option>
											<option value="MI" >MI - Michigan</option>
											<option value="MN" >MN - Minnesota</option>
											<option value="MO" >MO - Missouri</option>
											<option value="MS" >MS - Mississippi</option>
											<option value="MT" >MT - Montana</option>
											<option value="NC" >NC - North Carolina</option>
											<option value="ND" >ND - North Dakota</option>
											<option value="NE" >NE - Nebraska</option>
											<option value="NH" >NH - New Hampshire</option>
											<option value="NJ" >NJ - New Jersey</option>
											<option value="NM" >NM - New Mexico</option>
											<option value="NV" >NV - Nevada</option>
											<option value="NY" >NY - New York</option>
											<option value="OH" >OH - Ohio</option>
											<option value="OK" >OK - Oklahoma</option>
											<option value="OR" >OR - Oregon</option>
											<option value="PA" >PA - Pennsylvania</option>
											<option value="PR" >PR - Puerto Rico</option>
											<option value="RI" >RI - Rhode Island</option>
											<option value="SC" >SC - South Carolina</option>
											<option value="SD" >SD - South Dakota</option>
											<option value="TN" >TN - Tennessee</option>
											<option value="TX" >TX - Texas</option>
											<option value="UT" >UT - Utah</option>
											<option value="VT" >VT - Vermont</option>
											<option value="VA" >VA - Virginia</option>
											<option value="WA" >WA - Washington</option>
											<option value="WV" >WV - West Virginia</option>
											<option value="WI" >WI - Wisconsin</option>
											<option value="WY" >WY - Wyoming</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="to_city" class="control-label pull-right">Going To:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<input type="text" class="form-control input" name="to_city" id="to_city" autocomplete="off" placeholder="Destination City" value="" />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="name" class="control-label pull-right">Full Name:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<input type="text" class="form-control input" name="name" id="name" placeholder="First and Last Name" value="" />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="phone" class="control-label pull-right">Phone:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<input type="tel" class="form-control input" name="phone" id="phone" value="" />
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-5">
										<label for="email" class="control-label pull-right">E-Mail:</label>
									</div>

									<div class="col-xs-7 no-left-padding pull-right">
										<input type="email" class="form-control input" name="email" id="email" value="" />
									</div>
								</div>
							</div>
						</div>

					</div>

					<!-- Right inputs -->
					<div class="col-xs-6">


						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-12 message-label">
										<label for="message" class="control-label">Your Message:</label>
									</div>

									<div class="col-xs-12 message-textarea">
										<textarea name="message" id="message" class="form-control" rows="4"></textarea>
									</div>
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<div class="col-xs-12">
										<div class="checkbox">
											<label style="font-size:11px;">
												<input type="checkbox" name="allow_quotes" id="allow_quotes" value="" checked="" />
												<span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
												<!--Allow movers to contact me? -->Compare with other moving companies?
											</label>
										</div>
										<div class="info-box">
											<i class="glyphicon glyphicon-info-sign info-ico"></i>
											<div class="the_info"><span class="info-box-close">×</span>Allow other moving companies to contact me via phone or/and email in connection with my project estimate request.</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<input type="text" id="website" name="website" value="" />
								<input type="hidden" id="parent_webpage" name="parent_webpage" value="" />
								<input type="hidden" id="domain_from" name="domain_from" value="mymovingreviews.com" />
								<input type="hidden" id="moving_type" name="moving_type" value="LOCAL MOVE" />
								<input type="hidden" id="route" name="route" value="" />
								<input type="hidden" id="distance" name="distance" value="" />
								<input type="hidden" id="from_city" name="from_city" />
								<input type="hidden" id="quote_type" name="quote_type" value="us" />
								<input type="hidden" id="failed" name="failed" value=""/>
								<button class="btn btn-warning col-xs-12" type="submit">
									<strong>
										<i class="cr-icon glyphicon glyphicon-ok"></i>
										Send Message
									</strong>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>


</body>
</html>

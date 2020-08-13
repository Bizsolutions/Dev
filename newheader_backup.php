<?php
require_once('core/database.class.php');
?>

<link rel="stylesheet" href="<?= $connection->base_url() ?>assets/css/bootstrap.min.css">
<script src="<?= $connection->base_url() ?>assets/js/jquery.min.js" defer></script>
<script src="<?= $connection->base_url() ?>assets/js/popper.min.js" defer></script>
<script src="<?= $connection->base_url() ?>assets/js/bootstrap-datetimepicker.min.js" defer></script>
<script src="<?= $connection->base_url() ?>assets/js/bootstrap.min.js" defer></script>
<script src="<?= $connection->base_url() ?>assets/js/demo.js" defer></script>
<link rel=stylesheet href="<?= $connection->base_url() ?>assets/css/dhtmlxcalendar.css"/>
<script src="<?= $connection->base_url() ?>assets/js/dhtmlxcalendar.js" defer></script>
<script>
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10)
    {
        dd = '0' + dd;
    }
    if (mm < 10)
    {
        mm = '0' + mm;
    }
    today = mm + '-' + dd + '-' + yyyy;
    var myCalendar;
    function doOnLoad() {
        myCalendar = new dhtmlXCalendarObject("calendarHere");
        myCalendar.hideTime();
        myCalendar.setDate(getdate());
    }
    function setFrom() {
        myCalendar.setSensitiveRange(today, null);
    }

    function validate()
    {

        if (document.getElementById('MovingFrom').value == '' || (isNaN(document.getElementById('MovingFrom').value)))
        {
            alert('Enter Valid Zip Code Where  you are Moving From!');
            document.getElementById('MovingFrom').focus();
            return false;
        } else if (document.getElementById('MovingTo').value == '')
        {
            alert('Enter Valid Zip Code Where  you are Moving To!');
            document.getElementById('MovingTo').focus();
            return false;
        } else if (document.getElementById('calendarHere').value == '')
        {

            alert('Select Moving Date!');
            document.getElementById('calendarHere').focus();
            return false;
        } else
        {
            document.getElementById("frm1").submit();
            return true;
        }
    }

</script>
<script>
    $(document).ready(function () {
        $("#MovingTo").keyup(function () {
            $.ajax({
                type: "POST",
                url: "../process/readService.php",
                data: 'MovingTo=' + $(this).val(),
                beforeSend: function () {
                    $("#MovingTo").css("background", "#FFF no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-box1").show();
                    $("#suggesstion-box1").html(data);
                    $("#MovingTo").css("background", "#FFF");
                }
            });
        });
    });

    function selectCountry1(val) {
        $("#MovingTo").val(val);
        $("#suggesstion-box1").hide();
    }

</script>
<div class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-right bar-pad">
                <a href="<?= $connection->base_url(); ?>index.php">
                    Home
                </a> | 
                <a href="<?= $connection->base_url(); ?>add-moving-company.php">
                    Register Business</a>
            </div>
        </div>
    </div>    
</div>
<div class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="<?= $connection->base_url(); ?>">
                <img src="<?= $connection->base_url(); ?>images/logo.jpg" alt="top-moving-logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto pull-right">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= $connection->base_url(); ?>">
                            Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $connection->base_url() ?>moving-company.php">Moving Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $connection->base_url() ?>write-your-review.php">Write A Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $connection->base_url() ?>add-moving-company.php">Add Moving Company</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $connection->base_url() ?>tips">Moving Tips</a>
                    </li>
                </ul>
                <div class="pull-right" style="float: right; position: absolute; right: 0px;">
                    <a href="<?= $connection->base_url() ?>quoteform.php"> 
                        <button type="button" class="btn btn-primary btn-lg">Get Quotes</button>
                    </a>
                </div> 
            </div>
        </nav>
    </div>
</div>
<div class="jumbotron" >
    <div class="container">
        <div class="fadeInLeft wow animated delay-1"><h1>Top Moving Company Rating & Reviews</h1></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <form  id="frm1" name="frm1" method="post" action="quoteform.php" autocomplete="off">
                        <div class="banner-rect-box">
                            <div class="ban_rct">
                                <div class="packup">
                                    <label>Where are you moving from?.</label>
                                    <div class="banner-main">
                                        <div class="rectBox"> 
                                            <input type="text" name="MovingFrom" id="MovingFrom" value="From Zip"  onfocus="this.value = ''" class="form-control form-control-sm" maxlength="5"  >
                                        </div>
                                        <div class="invalid-tooltip" id="fzip-error" style="position:unset !Important; margin-bottom:15px; "> Please provide a valid zip.</div>
                                    </div>
                                </div>
                                <div class="delivery">
                                    <label>Where are you <i>moving</i> to?</label>
                                    <div class="banner-main">
                                        <div class="rectBox">
                                            <input type="text" name="MovingTo" id="MovingTo" value="To Zip or City,State"  onfocus="this.value = ''" class="form-control form-control-sm" >
                                        </div>
                                        <div id="suggesstion-box1"></div>
                                        <div class="invalid-tooltip" id="tzip-error" style="position:unset !Important; margin-bottom:15px; "> 
                                            Please provide a valid zip.
                                        </div>

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

                            <div>
                                <button type="submit" class="btn btn-primary" onclick="javascript:return validate()" style="cursor:pointer;">Continue</button> The quick and easy money saver.
                            </div>
                        </div>
                </div>
                </form>
            </div>
            <div class="col-md-3">  </div>
        </div>
    </div>
</div>
<div class="container mm">
    <div class="row ">
        <div class="col-md-2"></div>
        <div class="col-md-1"></div>
        <div class="col-md-2 p-10">
            <div class="new-head">
                <img src="<?= $connection->base_url(); ?>responsive/icon.png" class="arrow-1"/>
                <h4>Fill Out the  <br/>Form</h4>
            </div>
        </div>
        <div class="col-md-2 p-10">
            <div class="new-head">
                <img src="<?= $connection->base_url(); ?>responsive/icon.png "class="arrow-1"/>
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
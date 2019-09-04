<?php
if($result != ""){
    $user_id       = $result->user_id;
    $user_name     = $result->user_name;
    $email_id      = $result->email_id;
    $confirm_email = $result->confirm_email;
    $otp           = $result->password;
 } 
?>


<!doctype html>
<html class="no-js" lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>CRM | Tech Vyas Solutions</title>
<link rel="icon" type="image/ico" href="<?=base_url();?>assets/assets/images/favicon.ico" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- ============================================
        ================= Stylesheets ===================
        ============================================= -->
<!-- vendor css files -->
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/vendor/animate.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/vendor/font-awesome.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/js/vendor/animsition/css/animsition.min.css">
<!-- project main css files -->
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/main.css">
<!--/ stylesheets -->
<!-- ==========================================
        ================= Modernizr ===================
        =========================================== -->
<script src="<?=base_url();?>assets/assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<!--/ modernizr -->
</head>
<body id="minovate" class="appWrapper">

<div id="wrap" class="animsition">
 
  <div class="page page-core page-locked">
    <div class="text-center">
      <h3 class="text-light text-white"><img src="<?=base_url();?>assets/assets/images/techvyas-forgot.png" alt=""></h3>
    </div>

    <div class="container w-420 p-15 bg-white mt-40">
      <div class="bg-slategray lt wrap-reset mb-20 text-center">
        <h2 class="text-light text-greensea m-0">Let's get you signed up!.</h2>
      </div>
      <div class="media p-15">
        <div class="pull-left thumb thumb-lg mr-20"> <img class="media-object img-circle" src="<?=base_url();?>img/placeholder-contact.png" alt=""> </div>
        <div class="media-body">
        <form method="post" action="dashboard" class="form-validation">
            
             <h5 class="media-heading mb-0">Email&nbsp;&nbsp; :&nbsp; <strong> <?=$email_id;?></strong></h5>
             <br/>
             <h5 class="media-heading mb-0">Name &nbsp;: &nbsp;<b> <?=$user_name;?></b></h5>
            <div class="form-group mt-10">
              <input type="hidden" name="username" value="<?=$email_id;?>" class="form-control underline-input">
              <input type="hidden" name="confirmid" value="<?=$confirm_email;?>" class="form-control underline-input">
              <input type="hidden" name="useridd" value="<?=$user_id;?>" class="form-control underline-input">
              <input type="hidden" name="password" value="<?=$otp;?>" class="form-control underline-input">
              <input type="password" name="entrpaswrd" id="entrpaswrd" placeholder="Enter Password" class="form-control underline-input" required="">
              <input type="password" name="rentrpaswrd" id="rentrpaswrd" placeholder="Confirm Password" class="form-control underline-input" required="">
            </div>
        <div class="form-group text-left"> 
        <button type="button" onclick="validatePassword(this);" class="btn btn-greensea b-0 br-2 mr-5 block">Submit</button>
        </div>
        </form>
        </div>
    </div>
    <div class="bg-slategray lt wrap-reset mt-0 text-center">
     <p class="m-0"> <a href="<?=base_url();?>">Not you?</a> </p>
    </div>
  </div>
 </div>
</div>
<!--/ Application Content -->
<!-- ============================================
        ============== Vendor JavaScripts ===============
        ============================================= -->
<script src="../../ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?=base_url();?>assets/assets/js/vendor/jquery/jquery-1.11.2.min.js"><\/script>')</script>
<script src="<?=base_url();?>assets/assets/js/vendor/bootstrap/bootstrap.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/jRespond/jRespond.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/sparkline/jquery.sparkline.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/animsition/js/jquery.animsition.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/screenfull/screenfull.min.js"></script>
<!--/ vendor javascripts -->
<!-- ============================================
        ============== Custom JavaScripts ===============
        ============================================= -->
<script src="<?=base_url();?>assets/assets/js/main.js"></script>
<!--/ custom javascripts -->
<!-- ===============================================
        ============== Page Specific Scripts ===============
        ================================================ -->
<script>
  $(window).load(function(){
    
  });
</script>

<script type="text/javascript">

function validatePassword(v)
{
    var newpass=document.getElementById('entrpaswrd').value;
    var cnfpass=document.getElementById('rentrpaswrd').value;

    //alert(cnfpass);

    if(newpass == cnfpass)
    {
        v.type="submit";
    }
    else
    {
        alert("Enter Password and Confirm Password Not Match !");
    }
}

</script>

<!--/ Page Specific Scripts -->
</body>
</html>

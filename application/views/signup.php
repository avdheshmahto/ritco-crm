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
<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
<!-- ====================================================
        ================= Application Content ===================
        ===================================================== -->
<div id="wrap" class="animsition">
  <div class="page page-core page-login">
    <div class="text-center">
      <h3 class="text-light text-white"><img src="assets/images/techvyas.png" alt=""></h3>
    </div>
    <div class="container w-420 p-15 bg-white mt-40 text-center">
      <h2 class="text-light text-greensea">Sign Up</h2>
      <form method="post" class="form-validation mt-20" action="<?=base_url();?>master/Item/insert_signup">
        <p class="help-block text-left"> Enter your personal details below : </p>
        <div class="form-group">
		<div class="row">
			<div class="col-sm-6"><input type="text" name="first_name" class="form-control underline-input" placeholder="First Name"></div>
        	<div class="col-sm-6"><input type="text" name="last_name" class="form-control underline-input" placeholder="Last name"></div>
		</div>
		</div>
        <div class="form-group">
          <input type="email" name="email_id" class="form-control underline-input" placeholder="Email">
        </div>
		<div class="form-group">
		<div class="row">
			<div class="col-sm-6"><input type="text" name="user_name" placeholder="Username" class="form-control underline-input"></div>
    	    <div class="col-sm-6"> <input type="password" name="password" placeholder="Password" class="form-control underline-input"></div>
        </div>
		</div>
        <div class="form-group text-left">
          <label class="checkbox checkbox-custom-alt checkbox-custom-sm inline-block">
          <input type="checkbox">
          <i></i> I agree to the <a href="javascript:;">Terms of Service</a> &amp; <a href="javascript:;">Privacy Policy</a> </label>
        </div>
      
       <div class="bg-slategray lt wrap-reset mt-20 text-left">
         <p class="m-0"> <input type="submit" class="btn btn-greensea b-0 text-uppercase pull-right" value="Sign Up"> 
		 <a href="index" class="btn btn-lightred b-0 text-uppercase">Back</a> </p>
      </div>
	  </form>
	  
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
<!--/ Page Specific Scripts -->
</body>
</html>

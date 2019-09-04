<?php
   $this->load->view("header.php");
?>

<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="pageheader tile-bg">
<div class="p-0 bg-white-">
<div class="btn-toolbar pull-right">
<div class="btn-group">
<div class="input-group">

</div>
</div>
</div>
<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">System Setting</h3>
</div>
</div>
</div>
</div><!--pageheader close-->

<div class="tile-body p-15">
<div class="row">
<div class="col-sm-2">
<div class="sidebar-nav-">
<div class="navbar navbar-default" role="navigation">

<div class="navbar-collapse collapse sidebar-navbar-collapse">
<?php $this->load->view("main_nav_system.php"); ?>
</div>
</div>
</div>
</div><!--col-sm-10 close-->


</div><!--row close-->
</div><!--tile-body p-0 close-->
</section>
</div>
</div>
</div>
</section>
  <!--/ CONTENT -->

<?php
$this->load->view("footer.php");
?>

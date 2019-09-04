<?php
   $this->load->view("header.php");
?>

<style>
.mg-tb-30{margin:30px 0}
.email-ctn-round h2,.recent-post-title h2,.recent-items-title h2,.realtime-title h2,.contact-hd h2,.animation-ctn-hd h2,.alert-hd h2,.color-hd h2,.tab-hd h2,.wizard-hd h2,.dropdown-trig-hd h2,.modals-hd h2,.accordion-hd h2,.tooltips-hd h2,.popovers-hd h2,.typography-list h2,.typography-heading h2,.typography-in-hd h2,.tpgp-hp-hd h2,.view-mail-hrd h2,.multiupload-sys h2,.basic-tb-hd h2{font-size:20px;color:#333}
.email-round-nock{height:130px}.email-ctn-nock p{margin-top:10px;position:relative;z-index:9;margin-bottom:0}.email-ctn-round{text-align:center}.email-round-gp{display:flex;margin-top:40px}.email-signle-gp{height:90px}.email-round-pro{width:100%}.email-ctn-round h2{margin-bottom:0;text-align:center;}.email-statis-wrap{display:block}
.email-rdn-hd,.recent-post-title,.recent-items-title{margin-bottom:20px;width:100%}
.email-statis-wrap{display:block}
.email-round-nock{height:130px}
</style>

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

<div class="col-sm-10">

<section class="tile" style="top:0px;">
<div class="tile-bg">
<div class="row">
<div class="col-sm-12">
<strong>Account Statistics</strong>
</div>
</div>
</div>

<div class="tile-body p-0">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div>
<article class="page-content">
<section class="block bottom20 entity-detail">
<div class="row" style="display: none;">
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-slategray">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-eye fa-4x"></i> </div>
<div class="col-xs-8">
<?php 
$usr=$this->db->query("select * from tbl_user_mst where status='A' ");
$getUsr=$usr->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getUsr;?></p>
<span>Active Users</span> </div>
</div>
</div>
</div>
</div>

<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-blue">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-eye-slash fa-4x"></i> </div>
<div class="col-xs-8">
<?php 
$inusr=$this->db->query("select * from tbl_user_mst where status='I' ");
$getInUsr=$inusr->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getInUsr;?></p>
<span>Inactive Users</span> </div>
</div>
</div>
</div>
</div>

<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-greensea">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-users fa-4x"></i> </div>
<div class="col-xs-8">
<?php
$brnch=$this->db->query("select * from tbl_branch_mst where status='A' ");
$getBrnch=$brnch->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getBrnch;?></p>
<span>Total Branch</span> </div>
</div>
</div>
</div>
</div>

<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-lightred">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-user fa-4x"></i> </div>
<div class="col-xs-8">
<?php
$profile=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A'");
$getProfile=$profile->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getProfile;?></p>
<span>Total Profile</span> </div>
</div>
</div>
</div>
</div>
</div><!--row close-->

<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<div class=" mg-tb-30">
<div class="email-ctn-round">
<div class="email-rdn-hd">
<h2>Users</h2>
</div>
<?php 
$tusr=$this->db->query("select * from tbl_user_mst");
$getTusr=$tusr->num_rows();
$pertg=$getTusr/30*100;
?>
<div class="email-statis-wrap">
<div class="email-round-nock">
<input type="text" class="knob" value="0" data-rel="<?=$pertg?>" data-linecap="round" data-width="130" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true">
</div>
<div class="email-ctn-nock">
<p>Total <?=$getTusr?> Users Out Of 30 Users</p>
</div>
</div>
</div>
</div>
</div>

<!-- <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
<div class=" mg-tb-30">
<div class="email-ctn-round">
<div class="email-rdn-hd">
<h2>Records</h2>
</div>
<div class="email-statis-wrap">
<div class="email-round-nock">
<input type="text" class="knob" value="0" data-rel="69" data-linecap="round" data-width="130" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true">
</div>
<div class="email-ctn-nock">
<p>Max: 2.5k Records</p>
</div>
</div>
</div>
</div>
</div> -->

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
<div class=" mg-tb-30">
<div class="email-ctn-round">
<div class="email-rdn-hd">
<h2>File Storage</h2>
</div>
<?php 
$storage=$this->db->query("SELECT SUM(ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2)) AS SIZE_IN_MB FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA ='techvyas_ritco_demo' ");
$getStorage=$storage->row();
?>
<div class="email-statis-wrap">
<div class="email-round-nock">
<input type="text" class="knob" value="0" data-rel="<?=$getStorage->SIZE_IN_MB;?>" data-linecap="round" data-width="130" data-bgcolor="#E4E4E4" data-fgcolor="#00c292" data-thickness=".10" data-readonly="true">
</div>
<div class="email-ctn-nock">
<p>Max: 15 GB</p>
</div>
</div>
</div>
</div>
</div>
</div><!--row close-->

</section>
</article>
</div>
</div>
</div>
</div>
</section>
</div>	

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
<script src="<?php echo base_url(); ?>assets/assets/circle_chart/jquery.knob.js"></script>
<script src="<?php echo base_url(); ?>assets/assets/circle_chart/jquery.appear.js"></script>
<script src="<?php echo base_url(); ?>assets/assets/circle_chart/knob-active.js"></script>
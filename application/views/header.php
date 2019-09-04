<!doctype html>
<html class="no-js" lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>CRM  | Tech Vyas Solutions</title>
<link rel="icon" type="image/ico" href="<?=base_url();?>assets/assets/images/favicon.ico" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ============== Stylesheets =================== -->
<!-- vendor css files -->
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/vendor/animate.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/vendor/font-awesome.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/js/vendor/summernote/summernote.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/js/vendor/animsition/css/animsition.min.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/js/vendor/fullcalendar/fullcalendar.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/js/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css">


<!-- project main css files -->
<link rel="stylesheet" href="<?=base_url('customjs/jquery.typeahead.css');?>">
<style type="text/css">
  .typeahead__container{
    font-size: 14px !important;
  }


span.highlight__ {
    background-color: #ff0;
    display: -webkit-inline-box;
}

</style>

<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/main.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/css/activity.css">
<link rel="stylesheet" href="<?=base_url();?>assets/assets/js/vendor/chosen/chosen.css">
<!-- <link href="<?=base_url();?>assets/assets/scrollbar/scrollBar.css" rel="stylesheet"> 
<link href="<?=base_url();?>assets/assets/scrollbar/scrollBarstyles.css" rel="stylesheet">-->
<!--/ stylesheets -->
<!-- ==================== Modernizr ===================== -->

<script src="<?=base_url();?>assets/assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<link href="<?=base_url();?>assets/assets/css/pipeline.css" rel="stylesheet">


<!--/ modernizr -->
</head>

<body id="minovate" class="appWrapper">
<div id="wrap" class="animsition">
<!-- ======================== HEADER Content =============================== -->
<section id="header">
<header class="clearfix">
    <!-- Branding -->
    
    <!-- Branding end -->
    <!-- Left-side navigation -->
    <ul class="nav-left pull-left list-unstyled list-inline">
	  <li class="sidebar-collapse divided-right"> <a href="#" class="collapse-sidebar"> <i class="fa fa-outdent"></i> <a class="brand" href="<?=base_url();?>master/master/manage_dashboard"> <span><img src="<?=base_url();?>assets/assets/images/techvyas-logo.png" alt=""></span> </a></a> </li>
    </ul>
    <!-- Left-side navigation end -->
    <!-- Search -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    // if(value == ""){
    //        $(this).css('color','black');
    //       } else {         
    //         $(this).css('color','yellow');
    //     }
    $("#myList").css('display','block');
    $("#myList li").filter(function() {
      //$(this).css('background-color','yellow');
      //$("h1, h2, p").addClass("blue");
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
$(document).click(function (event) {            
    $('#myList').css('display','none');
    $('#myInput').val("");
});
</script>
    <div class="search" id="main-search">
      <input type="text" class="form-control underline-input" id="myInput" placeholder="Search...">
    </div>

    <ul id="myList" style="display: none;position: absolute;max-height: 200px !important;">
      <?php 
        $sftlog=$this->db->query("SELECT * FROM tbl_software_log WHERE mdl_name !='Task' AND slog_name='New'"); 
        //$getSftLog=$sftlog->result();
        foreach ($sftlog->result() as $getSftLog) 
        {
          if($getSftLog->mdl_name == 'Orgz')
          {
            $orgnm=$this->db->query("select * from tbl_organization where org_id='$getSftLog->slog_id' ");
            $gtOrgNm=$orgnm->row();
            
      ?>
      <li class="list-group-item" style="margin: 7px 485px -7px 415px;">                   
           <div class="media-body">
            <span class="fa fa-building-o block highlight" style="font-size: 14px;">&nbsp;&nbsp;
              <a href="<?=base_url('organization/Organization/view_organization?id=');?><?=$getSftLog->slog_id;?>">
                <?php echo $gtOrgNm->org_name;?>
                </a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <small class="text-muted">Organization</small>
              </span>
            </div>        
      </li>

    <?php
          }
          elseif($getSftLog->mdl_name == 'Contact') 
          {
            $cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getSftLog->slog_id' ");
            $getCtNm=$cntnm->row();
    ?>      
      <li class="list-group-item" style="margin: 7px 485px -7px 415px;">                   
           <div class="media-body">
            <span class="fa fa-user block highlight" style="font-size: 14px;">&nbsp;&nbsp;
              <a href="<?=base_url('contact/Contact/view_contact?id=');?><?=$getSftLog->slog_id;?>">
                <?php echo $getCtNm->contact_name;?>
                </a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <small class="text-muted">Contact</small>
              </span>
            </div>        
      </li>      
    <?php
        }
        elseif($getSftLog->mdl_name == 'Lead') 
        {
          $ldnm=$this->db->query("select * from tbl_leads where lead_id='$getSftLog->slog_id' ");
          $getLdNm=$ldnm->row();
    ?>   
      <li class="list-group-item" style="margin: 7px 485px -7px 415px;">                   
           <div class="media-body">
            <span class="fa fa-users block highlight" style="font-size: 14px;">&nbsp;&nbsp;
              <a href="<?=base_url('lead/Lead/view_lead?id=');?><?=$getSftLog->slog_id;?>">
                <?php echo $getLdNm->lead_number;?>
                </a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <small class="text-muted">Lead</small>
              </span>
            </div>        
      </li>        
    <?php
        }
      }
    ?>
  
    </ul>

    <!-- Search end -->
   
    <!-- Right-side navigation -->
    <ul class="nav-right pull-right list-inline">
    <li class="dropdown nav-profile" style="display: none;"><a href class="dropdown-toggle" data-toggle="dropdown" title="Add New Item"> <span><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i></span> </a>
        <ul class="dropdown-menu animated littleFadeInRight" role="menu" >          
          <li> <a role="button" tabindex="0" data-toggle="modal" data-target="#HdrOrgzModalS" formid="#HdrOrgzFormS" id="formreset1"> <i class="fa fa-building-o" aria-hidden="true"></i>Add New Organization</a> </li>
          <li> <a role="button" tabindex="0" data-toggle="modal" data-target="#CntHdrModalS" formid="#CntHdrFormS" id="formreset1"> <i class="fa fa-user" aria-hidden="true"></i>Add New Contact </a> </li>
          <li> <a role="button" tabindex="0" data-toggle="modal" data-target="#leadHdrModal" formid="#LeadHdrForm" id="formreset1"> <i class="fa fa-users" aria-hidden="true"></i>Add New Lead </a> </li>
          <li> <a role="button" tabindex="0" data-toggle="modal" data-target="#taskMsodal" formid="#TasskForm" id="formreset1"> <i class="fa fa-check-square-o" aria-hidden="true"></i>Add New Task</a> </li>          
        </ul>
    </li>

<li class="dropdown notifications" id="content_div">
<?php
$crdate = date('m/d/Y');
$login_id=$this->session->userdata('user_id');

 $sql = "SELECT * FROM tbl_software_log WHERE (slog_name = 'Lead' OR slog_name = 'Task' OR slog_name = 'New' OR slog_name = 'Note__')  AND (slog_type = 'Stage' OR slog_type = 'Status' OR slog_type = 'User' OR slog_type = 'Lead Notes__' OR slog_type = 'Task Notes__' OR slog_type = 'Lead Create' OR slog_type = 'Task Create' ) ORDER BY `sid` DESC";
 $getSql = $this->db->query($sql);
//$getCnt = $getSql->num_rows();
    $getCnt=0;
    $sids=array();
    foreach($getSql->result() as $getLogData) 
    {

        $getSid = $getLogData->seen_id;
        $sid = explode(',', $getSid);
        $lgnusr = explode(',', $login_id);
        $resultsid=array_intersect($sid,$lgnusr);

        $getNotifyid = $getLogData->notify;
        $notifyid = explode(',', $getNotifyid);
        $notifyresult=array_diff($resultsid,$notifyid);        
        //print_r($notifyid);
        $getSize = sizeof($notifyresult);

        if($getSize > 0){

          $sids[]=$getLogData->sid;
          $getCnt ++;
        }
    }
    $logids=implode(',', $sids);
    //print_r($sids);
    //echo $logids;
?>
<?php if($getCnt >= 1) { ?>
<a href onClick="notifyBell('<?=$logids;?>');" class="dropdown-toggle" data-toggle="dropdown" title="Notifications"> <i class="fa fa-bell"></i> <span class="badge bg-lightred"><?=$getCnt?></span> </a>
<?php } else { ?>
<a href class="dropdown-toggle" data-toggle="dropdown" title="Notifications"><i class="fa fa-bell"></i> </a>
<?php } ?>
<div class="dropdown-menu pull-right with-arrow panel panel-default animated littleFadeInLeft">
<div class="panel-heading"> You have <strong class="badge bg-lightred"><?=$getCnt?></strong> new notifications 
</div>
<ul class="list-group msg_container_base_hdr">

<?php 

 $Slog=$this->db->query("SELECT * FROM tbl_software_log WHERE (slog_name = 'Lead' OR slog_name = 'Task' OR slog_name = 'New' OR slog_name = 'Note__')  AND (slog_type = 'Stage' OR slog_type = 'Status' OR slog_type = 'User' OR slog_type = 'Lead Notes__' OR slog_type = 'Task Notes__' OR slog_type = 'Lead Create' OR slog_type = 'Task Create' ) ORDER BY `sid` DESC LIMIT 0,50 ");
foreach($Slog->result() as $getLog) {

$getSid = $getLog->seen_id;
$sid = explode(',', $getSid);
$lgnusr = explode(',', $login_id);
$sidresult=array_intersect($sid,$lgnusr);

/*$getnotifyid = $getLog->notify;
$idnotify = explode(',', $getnotifyid);
$notifyresult=array_diff($sidresult,$idnotify);*/
//print_r($notifyresult);
$getSize = sizeof($sidresult);

if($getSize > 0){
?>

<li class="list-group-item"> 
<a role="button" tabindex="0" class="media" <?php if($getLog->mdl_name=='Orgz'){?> href="<?=base_url('organization/Organization/view_organization?id=');?><?=$getLog->slog_id;?>"  <?php } elseif($getLog->mdl_name=='Contact') {?> href="<?=base_url('contact/Contact/view_contact?id=');?><?=$getLog->slog_id; ?>" <?php } elseif($getLog->mdl_name=='Lead') {?> href="<?=base_url('lead/Lead/view_lead?id=');?><?=$getLog->slog_id; ?>" <?php } elseif($getLog->mdl_name=='Task') {?> href="<?=base_url('task/Task/view_task?id=');?><?=$getLog->slog_id;?>" <?php } ?> > 

<div class="media-body">
	<span class="pull-left media-object media-icon bg-primary"> <i class="fa fa-bell"></i></span> 
  <?php if($getLog->mdl_name == 'Lead') {
  $lead=$this->db->query("select * from tbl_leads where lead_id='$getLog->slog_id' ");
  $getLead=$lead->row();
  $usr=$this->db->query("select * from tbl_user_mst where user_id='$getLog->maker_id' ");
  $getUsr=$usr->row();
  $brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getLog->brnh_id' ");
  $getBrnh=$brnh->row();

  ?>
	<span class="block"><?php echo $getLead->lead_number." ".$getLog->remarks." By ".$getUsr->user_name."(".$getBrnh->brnh_name.")"; ?></span> 
  <?php } ?>

  <?php if($getLog->mdl_name == 'Task') {
  $task=$this->db->query("select * from tbl_task where task_id='$getLog->slog_id' ");
  $getTask=$task->row();
  $mstr=$this->db->query("select * from tbl_master_data where serial_number='$getTask->task_name' ");
  $getMstr=$mstr->row();
  $usr1=$this->db->query("select * from tbl_user_mst where user_id='$getLog->maker_id' ");
  $getUsr1=$usr1->row();
  $brnh1=$this->db->query("select * from tbl_branch_mst where brnh_id='$getLog->brnh_id' ");
  $getBrnh1=$brnh1->row();
  ?>
  <span class="block"><?php echo $getMstr->keyvalue." ".$getLog->remarks." By ".$getUsr1->user_name."(".$getBrnh1->brnh_name.")"; ?></span> 
  <?php } ?>

	<small class="text-muted"><?=$getLog->maker_date; ?></small>


</div>

</a> 
</li>
<?php } ?>
<?php } ?>

</ul>
</div>
</li>



      <li class="dropdown nav-profile"> <a href class="dropdown-toggle" data-toggle="dropdown">
        <!-- <img src="<?php //echo base_url();?>assets/assets/images/profile-photo.png" alt="" class="img-circle size-30x30"> -->
        <i class="fa fa-user"></i> <span>
          <?php 
            $user=$this->db->query("select * from tbl_user_mst where user_id='".$this->session->userdata('user_id')."' ");
            $getUser=$user->row();
          ?>
        <?=$getUser->user_name;?>
        <i class="fa fa-angle-down"></i></span> </a>

        <ul class="dropdown-menu animated littleFadeInRight pull-right" role="menu">
          <li> <a role="button" tabindex="0" href="<?=base_url();?>master/master/userprofileview"> <i class="fa fa-user"></i>Profile </a> </li>

          <?php if($this->session->userdata('role')==1){ ?>
          <li class="divider"></li>
          <li> <a role="button" tabindex="0" href="<?=base_url('master/master/userprofileview');?>"> <i class="fa fa-user"></i>User Setting </a> </li>
          <li> <a role="button" tabindex="0" href="<?=base_url('master/System/manage_system');?>"> <i class="fa fa-cogs"></i>System Setting </a> </li>
          <!-- <li> <a role="button" tabindex="0" href="<?=base_url('master/master/manage_account');?>"> <i class="fa fa-check-square-o"></i>Billing & Account </a> </li>  -->
          <?php } ?>

          <li class="divider"></li>
          <li> <a role="button" tabindex="0" href="<?=base_url();?>master/master/logout"> <i class="fa fa-sign-out"></i>Logout </a> </li>
        </ul>
      </li>

    </ul>
    <!-- Right-side navigation end -->
  </header>
</section>

<!--/ HEADER Content  -->
<div id="controls">
<!-- ============== SIDEBAR Content ============================ -->
<aside id="sidebar">
<div id="sidebar-wrap">
<div class="panel-group slim-scroll" role="tablist">
<div class="panel panel-default">
<div id="sidebarNav" class="panel-collapse collapse in" role="tabpanel">
<div class="panel-body">
<!-- ======================= NAVIGATION Content ======================= -->
<ul id="navigation">
      
      <?php $uri = $_SERVER['REQUEST_URI'];
			@$uri1=explode('/',$uri);
		  @$uri2=@$uri1[2]."/".@$uri1[3]."/".@$uri1[4]; ?>

		<li <?php if($uri2 == "master/master/manage_dashboard") { ?> class="active" <?php } ?> ><a href="<?=base_url()?>master/master/manage_dashboard" title="Dashboard"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
      
        <?php
	
  			@session_start();
				@$main=$_session['main'];
				@$submain=$_session['submain'];
				@$sub=$_session['sub'];
				@$page0=$_SERVER['REQUEST_URI'];
				@$page=explode('/',$page0);
				 @$page1=@$page[2]."/".@$page[3]."/".@$page[4];
				 @$page2=@$page[2]."/".@$page[3];
				
				 $moduleQuery=$this->db->query("select * from tbl_profile_mst where status='A' and profile_id='".$this->session->userdata('role')."' ORDER BY module_id ASC");
				 foreach($moduleQuery->result() as $getModule){
				
				if($getModule->create_id=='1' or $getModule->edit_id=='1' or $getModule->read_id=='1' or $getModule->delete_id=='1'){
				$moQuery=$this->db->query("select *from tbl_module_mst where module_id='$getModule->module_id' AND status='A' order by module_id asc");
				$getMo=$moQuery->row();
				?>
                <li <?php if($getMo->url==$page1 or $getMo->url==$page2){?> class="active"<?php } ?>><a href="<?php echo base_url();?><?php echo $getMo->url; ?>" title="<?=$getMo->module_name;?>"><i class="<?php echo $getMo->module_url; ?>"></i><span ><?php echo $getMo->module_name; ?></span></a></li>
                <?php 
				}
				}
		    ?>

</ul>
<!--/ NAVIGATION Content -->
</div>
</div>
</div>
</div>
</div>
</aside>
<!--/ SIDEBAR Content -->
</div>

<style type="text/css">
  .msg_container_base_hdr{
  background: #F5F5F5;
  margin: 0;
  padding: 0 0px 21px;
  max-height:300px;
  overflow-x:hidden;
}
.msg_container_base_hdr::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

.msg_container_base_hdr::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

.msg_container_base_hdr::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
</style>


<script type="text/javascript">
  
function notifyBell(logids) 
{
  //alert(logids);
  var urll="<?= base_url('master/master/update_software_log');?>";
  //setTimeout(function() {   //calls click event after a certain time
        $.ajax({
          type:"POST",
          url:urll,
          data : {'logid':logids},
          success:function(data){            
            //alert(data);
            if(data != ''){
              //alert(data);
              //location.reload();
             $("#content_div").empty().append(data);
            }            
          }
      });
  //}, 1000);
}

</script>


<?php include 'allmodal.php'; ?>

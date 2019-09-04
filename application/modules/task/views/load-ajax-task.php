<?php 
  
     $task_id       = "";   $lead_id         = ""; $task_name = ""; $date_due    = "";
     $reminder_date = "";   $progress_percnt = ""; $priority  = ""; $task_status = ""; 
     $user_resp     = "";   $contact_person  = ""; $org_name  = ""; $description = "";

if($result != "")
{
      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';die;

  $task_id   = $result->task_id;
  
  $ldno      = $this->db->query("select * from tbl_leads where lead_id='".$result->lead_id."' ");
  $getLeadNo = $ldno->row();
  $lead_nm   = $getLeadNo->lead_number;

  $tsknm     = $this->db->query("select * from tbl_master_data where serial_number='".$result->task_name."' ");
  $getTsk    = $tsknm->row();
  $Tname     = $getTsk->keyvalue;
     
  $date_due        = $result->date_due;
  $reminder_date   = $result->reminder_date;
  $progress_percnt = $result->progress_percnt;
    
  $prty       = $this->db->query("select * from tbl_master_data where serial_number='".$result->priority."' ");
  $getPrty    = $prty->row();
  $PriorityNm = $getPrty->keyvalue;
    
  $tskstats   = $this->db->query("select * from tbl_master_data where serial_number='".$result->task_status."'");
  $getTstatus = $tskstats->row();
  $TskStatus  = $getTstatus->keyvalue;

  $usrnm    = $this->db->query("select * from tbl_user_mst where user_id='".$result->user_resp."'");
  $getUsrNm = $usrnm->row();
  $usrname  = $getUsrNm->user_name;

  $MkrNm    = $this->db->query("select * from tbl_user_mst where user_id='".$result->maker_id."'");
  $getMkrNm = $MkrNm->row();
  $mkrname  = $getMkrNm->user_name;

  $cntp    = $this->db->query("select * from tbl_contact_m where contact_id='".$result->contact_person."' ");
  $getCnt  = $cntp->row();
  $contper = $getCnt->contact_name;

  $orgnm    = $this->db->query("select * from tbl_organization where org_id='".$result->org_name."' ");
  $getOrgNm = $orgnm->row();
  $orgzname = $getOrgNm->org_name;

  $description  = $result->description;

}


$userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='4'");
$getAcc=$userPerQuery->row();

?>

 <!-- ajax_content -->

<div id="main-content">
<div id="guts">

<section id="content">
<div class="page page-tables-bootstrap" >
<div class="row">
<div class="col-md-12">
<section class="tile">

<div class="pageheader tile-bg">
<div class="media">
<!-- <div class="pull-left thumb">
<img class="media-object img-thumbnail" src="<?=base_url();?>assets/assets/images/random-avatar8.jpg" alt="">
</div> -->
<div class="btn-toolbar pull-right mt-10">
<div class="btn-group">
<a href="<?php echo base_url('task/Task/manage_task');?>"><button class="btn btn-default btn-sm br-2"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button></a> 
</div>
<div class="btn-group">
   <?php

  if($getAcc->edit_id=='1')
{
?>
<a href="#" onclick="editTaskInner(this);myfunLeadno(<?=$result->lead_id?>);" property = "edit" type="button" data-toggle="modal" data-target="#taskEditModal" arrt= '<?=json_encode($result);?>'  data-backdrop='static' data-keyboard='false'><button class="btn btn-default btn-sm br-2"><i class="fa fa-pencil"></i></button></a> 
<?php } ?>
</div>
<?php
  if($getAcc->edit_id=='1' || $getAcc->delete_id=='1') {
?>
<div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action<span class="caret"></span></button>
<ul class="dropdown-menu dropdown-menu-right" role="menu">
<?php
  if($getAcc->edit_id=='1') {
?>
<li><a href="#" onclick="editTaskInner(this);myfunLeadno(<?=$result->lead_id?>);" property = "edit" type="button" data-toggle="modal" data-target="#taskEditModal" arrt= '<?=json_encode($result);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i>Edit This Task</a></li>
<?php } ?>

  
<?php if($getAcc->delete_id=='1') { ?>
<?php
// $cntct=$this->db->query("select * from tbl_contact_m where org_name='$result->org_id' ");
// $cntctNumRow=$cntct->num_rows();
$lead=$this->db->query("select * from tbl_leads where lead_id='$result->lead_id' ");
$leadNumRow=$lead->num_rows();
// $task=$this->db->query("select * from tbl_task where contact_person='$result->contact_id' ");
// $tskNumRow=$task->num_rows();

$num_rows=$leadNumRow;

if($num_rows > 0) { ?>
  <li><a href="#" onclick="return confirm('Task already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Task </a></li>
<?php } else { ?>
<li><a onclick = "if (! confirm('Are You Sure! You Want To Delete ?')) { return false; }" href="<?=base_url('task/Task/deleteTask?task_id=');?><?=$task_id?>"><i class="fa fa-trash"></i>Delete This Task</a></li>
<?php } ?>
<?php } ?>

</ul>
</div>
<?php } ?>
</div>

<div class="media-body">
<p class="media-heading mb-0 mt-5">Task</p>
<small class="text-lightred"><?=$Tname?></small>
</div>
</div>
</div><!--pageheader close-->

<div class="pageheader">
<div class="table-responsive">
<table class="table mb-0">
<tbody>
<tr>
<td style="border:none;">
<small class="text-muted">Task</small>
<h5 class="media-heading mb-0"><?=$Tname?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Due Date</small>
<h5 class="media-heading mb-0"><?=$date_due?></h5>
</td>
<!-- <td style="border:none;">
<small class="text-muted">Reminder Date</small>
<h5 class="media-heading mb-0"><?=$reminder_date?></h5>
</td> -->
<td style="border:none;">
<small class="text-muted">Status</small>
<h5 class="media-heading mb-0"><?=$TskStatus?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Task Assign To</small>
<h5 class="media-heading mb-0"><?=$usrname?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Task Owner</small>
<h5 class="media-heading mb-0"><?=$mkrname?></h5>
</td>
</tr>
</tbody>
</table>
</div>
</div><!--pageheader close-->


<div class="add-nav">
<div role="tabpanel">
<!-- Nav tabs -->
<ul class="nav nav-tabs pt-15-" role="tablist">
<li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
<li role="presentation"><a href="#related" aria-controls="related" role="tab" data-toggle="tab">Related</a></li>
<li role="presentation"><a href="#activity" aria-controls="activity" role="tab" data-toggle="tab">All Activity</a></li>
</ul>
<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="details">
<div class="row">
<div class="col-md-12">
<section class="tile____ time-simple">
<div class="tile-body">
<div class="tile-body p-0">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="nameandoccupation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation1" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Task Details</span> </a> </h4>
</div>
<div id="nameandoccupation1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<table class="property-table">
<tbody>
<tr>
<td class="ralign"><span class="title">Record ID </span></td>
<td><div class="info"><?=$task_id;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Task Name</span></td>
<td><div class="info"><?=$Tname;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Assign To </span></td>
<td><div class="info"><?=$usrname;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Due Date </span></td>
<td><div class="info"><?=$date_due;?> </div></td>
</tr>
</tbody>
</table>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!-- name and occupation close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="AddressInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#AddressInformation3" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Additional Information</span> </a> </h4>
</div>
<div id="AddressInformation3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="AddressInformation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<table class="property-table">
<tbody>
<tr>
<!-- <td class="ralign"><span class="title">Reminder Date </span></td>
<td><div class="info"><?=$reminder_date;?></div></td>
</tr> -->
<tr>
<td class="ralign"><span class="title">Priority</span></td>
<td><div class="info"><?=$PriorityNm?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Progress %</span></td>
<td><div class="info"><?=$progress_percnt;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Status</span></td>
<td><div class="info"><?=$TskStatus;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Task Created</span></td>
<td><div class="info"><?=$result->maker_date;?> </div></td>
</tr>
</tbody>
</table>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Address Information close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="AdditionalInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#AddressInformation5" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Related To</span> </a> </h4>
</div>
<div id="AddressInformation5" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="AdditionalInformation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<table class="property-table">
<tbody>
<tr>
<td class="ralign"><span class="title">Lead Number</span></td>
<td><div class="info"><?=$lead_nm;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Contact Person</span></td>
<td><div class="info"><?=$contper;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Organization Name</span></td>
<td><div class="info"><?=$orgzname;?> </div></td>
</tr>
</tbody>
</table>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Additional Information close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="DescriptionInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#DescriptionInformation6" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>  Description Information</span> </a> </h4>
</div>

<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#taskNoteModal" formid="#TaskNotesForm" id="formreset" style="margin: -35px 0px 0px 0px;">Add Remarks</button>
</p>
</div>

<div id="DescriptionInformation6" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="DescriptionInformation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<table class="property-table">
<tbody>

<section class="tile- time-simple">
<div class="tile-body">
<div class="streamline mt-20">

<?php 

$tskDes = $this->db->query("select * from tbl_note where note_logid='".$task_id."' AND main_lead_id_note='Inner Task' AND note_type='Task' ORDER BY note_id DESC ");
foreach ($tskDes->result() as $getTskdesc) {

    $tskOwnr = $this->db->query("select * from tbl_user_mst where user_id='".$getTskdesc->maker_id."' ");
    $getTskownr = $tskOwnr->row();
    $takowner = $getTskownr->user_name;
?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-file-text-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<!-- <div class="panel-heading bg-white"> Task Notes </div> --> 
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?= $takowner; ?></span></li>
<li>|</li>
<li><span><?=$getTskdesc->note_date?></span></li>
</ul>
</div>
<div class="call-footer">
<?php $big=$getTskdesc->note_desc;
//$bigs = strip_tags($big);
echo $big; ?>
</div>
</div><!--panel panel-default close-->
</div>
</article>
<?php  } ?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-file-text-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?= $mkrname; ?></span></li>
<li>|</li>
<li><span><?=$result->maker_date?></span></li>
</ul>
</div>
<div class="call-footer">
<?php $big=$description;
$bigs = strip_tags($big);
echo $bigs; ?>
</div>
</div><!--panel panel-default close-->
</div>
</article>

</div><!--streamline mt-20 close-->
</div>
</section>

</tbody>
</table>
</div>
</section>

</article>
</div> <!--close-->
</div>
</div>
</div><!--Description Information close-->


</div>
</div><!--tile-body p-0 close-->

</div>
</section>
</div>
</div>
</div><!-- details close -->


<div role="tabpanel" class="tab-pane" id="related">
<div class="row">
<div class="col-md-12">
<div class="tile-body">
<div class="activity-stats" data-total-count="0">
<div class="block-items">

<?php 
$cntctrow = $this->db->query("select * from tbl_contact_m where contact_id='".$result->contact_person."' ");
$cntCntct = $cntctrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Contact" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#projects10" aria-expanded="true">
<span class="top-label">Contact</span>
<div class="block-item-count"><?=$cntCntct?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$orgz = $this->db->query("select * from tbl_organization where org_id='".$result->org_name."' ");
$cntrows = $orgz->num_rows();
?>
<div id="note-stats" class="block-item large-block" title="Organization" data-target="#notes-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation8" aria-expanded="true"> 
<span class="top-label">Organization</span>
<div class="block-item-count"><?=$cntrows?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$leadrow = $this->db->query("select * from tbl_leads where lead_id='".$result->lead_id."' ");
$cntlead = $leadrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Lead" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#Opportunities9" aria-expanded="true">
<span class="top-label">Lead</span>
<div class="block-item-count"><?=$cntlead?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$notesrow = $this->db->query("select * from tbl_note where note_logid='".$task_id."' AND main_lead_id_note='Inner Task' AND note_type='Task' ");
$cntNotes = $notesrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Notes" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#notes12" aria-expanded="true">
<span class="top-label">Remarks</span>
<div class="block-item-count"><?=$cntNotes?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$filesrow = $this->db->query("select * from tbl_file where file_logid='".$task_id."' AND file_type='Task' ");
$cntFiles = $filesrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Files" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#files" aria-expanded="true">
<span class="top-label">Files</span>
<div class="block-item-count"><?=$cntFiles?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

</div><!--block-items close-->

</div><!--activity-stats close-->



<div class="tile-body p-0">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="projects">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#projects10" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Contacts</span><span class="badge bg-lightred"><?=$cntCntct?></span> </a> </h4>
</div>
<div id="projects10" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body_">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<!-- <th>Address</th> -->
<th>City</th>
<th>Pin Code</th>
</tr>
</thead>
<tbody>
<?php 
foreach ($cntctrow->result() as $getCntct) {
?>
<tr>
<td>
<!-- <a href="#" onclick="getViewContactPage('<?=$getCntct->contact_id;?>');"><?=$getCntct->contact_name?></a> -->
<a href="<?=base_url('contact/Contact/view_contact?id=');?><?=$getCntct->contact_id; ?>"><?=$getCntct->contact_name?></a>
</td>
<?php
// Convert JSON string to Array
$someArrayPhone = json_decode($getCntct->phone, true);
$someArrayEmail = json_decode($getCntct->email, true);
//print_r($someArray);        // Dump all data of the Array
//echo $someArray[0]["name"]; // Access Array data 
?>
<td><?=$someArrayPhone[0]?></td>
<td><?=$someArrayEmail[0]?></td>
<!-- <td><?=$getCntct->address?></td> -->
<td><?=$getCntct->city_name?></td>
<td><?=$getCntct->pincode?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Dates to remember close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="nameandoccupation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation8" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Organization</span><span class="badge bg-lightred"><?=$cntrows?></span> </a> </h4>
</div>
<div id="nameandoccupation8" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body_">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<!-- <th>Address</th> -->
<th>City</th>
<th>Pin Code</th>
</tr>
</thead>
<tbody>
<?php 
foreach ($orgz->result() as $getOrzn) {
?>
<tr>
<td>
<!-- <a href="#" onclick="getViewOrgPage('<?=$getOrzn->org_id;?>');"><?=$getOrzn->org_name?></a> -->
<a href="<?php echo base_url('organization/Organization/view_organization?id=');?><?=$getOrzn->org_id;?>"><?=$getOrzn->org_name?></a>
</td>
<?php
// Convert JSON string to Array
$someArrayPhone = json_decode($getOrzn->phone_no, true);
$someArrayEmail = json_decode($getOrzn->email, true);
//print_r($someArray);        // Dump all data of the Array
//echo $someArray[0]["name"]; // Access Array data 
?>
<td><?=$someArrayPhone[0]?></td>
<td><?=$someArrayEmail[0]?></td>
<!-- <td><?=$getOrzn->address?></td> -->
<td><?=$getOrzn->city?></td>
<td><?=$getOrzn->pin_code?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Contact Details close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="Opportunities">
<div class="btn-group">  
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#Opportunities9" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Lead</span> <span class="badge bg-lightred"><?=$cntlead?></span> </a></h4>
</div>
</div>

<div id="Opportunities9" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="Opportunities" aria-expanded="true" style="">
<div class="panel-body_">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Lead Number</th>
<th>Assign To</th>
<!-- <th>Source</th> -->
<th>Expted Closure Date</th>
<th>Contact Person Name</th>
<th>Organization Name</th>
</tr>
</thead>
<tbody>

<?php 
foreach ($leadrow->result() as $getLead) {
?>

<tr>
<td>
<!-- <a href="#" onclick="getViewLeadPage('<?=$getLead->lead_id;?>');"><?=$getLead->lead_number?></a> -->
<a href="<?=base_url('lead/Lead/view_lead?id=');?><?=$getLead->lead_id; ?>"><?=$getLead->lead_number?></a>
</td>
<td>
<?php
$usr = $this->db->query("select * from tbl_user_mst where user_id='".$getLead->user_resp."' ");
$getUsr = $usr->row();
echo $getUsr->user_name; ?>
</td>
<!-- <td>
<?php 
$src = $this->db->query("select * from tbl_master_data where serial_number='".$getLead->source."' ");
$getSrc = $src->row();
echo $getSrc->keyvalue; ?>
</td> -->
<td><?=$getLead->closuredate?></td>
<td>
<?php
$cntLead = $this->db->query("select * from tbl_contact_m where contact_id='".$getLead->contact_id."' ");
$getcntLead = $cntLead->row();
echo $getcntLead->contact_name; ?>
</td>
<td>
<?php
$orgz = $this->db->query("select * from tbl_organization where org_id='".$getLead->org_id."' ");
$getOrgz = $orgz->row();
echo $getOrgz->org_name;?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Address Information close-->



<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="notes">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#notes12" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Remarks</span> <span class="badge bg-lightred"><?=$cntNotes?></span> </a></h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#taskNoteModal" formid="#TaskNotesForm" id="formreset">Add Remarks</button>
</p>
</div>
</div>
</div>
<div id="notes12" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="notes">
<div class="panel-body_">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">

<div id="listingNotesData"> <!-- listdataid -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>S. No.</th>
<th>Remarks Date</th>
<th>Remarks Desc</th>
<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>
<?php 
$i=1;
foreach($notesrow->result() as $getNote){
?>
<tr class="record" data-row-id="<?=$getNote->note_id; ?>">
<td><?=$i?></td>
<td><?=$getNote->note_date?></td>
<td>
<div class="tooltip-col">
<?php 
$big = $getNote->note_desc;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<td>
<?php 
  $pri_col='note_id ';
  $table_name='tbl_note';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <li><a href="#" onclick="editTaskNote(this);mainLeadIdNote('<?= $getNote->main_lead_id_note; ?>');" property = "edit" type="button" data-toggle="modal" data-target="#taskNoteModal" arrt= '<?=json_encode($getNote);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Remarks</a></li>
  <li><a href="#" class="delbutton" id="<?php echo $getNote->note_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Remarks</a></li>
</ul>
</div>
</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</div><!-- listdataid -->

</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Additional Information close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="DescriptionInformation">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a  data-toggle="collapse" data-parent="#accordion" href="#files" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Files</span> <span class="badge bg-lightred"><?=$cntFiles?></span> </a>   </h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#fileTaskModal" formid="#FilesTaskForm" id="formreset">Add Files</button>
</p>
</div>
</div>
</div>
<div id="files" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="DescriptionInformation">
<div class="panel-body_">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">

<div id="listingFilesData"> <!-- listdataid -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>S. No.</th>
<th>Name</th>
<th>Description</th>
<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>
<?php 
$i=1;
foreach($filesrow->result() as $getFiles){
?>
<tr class="record" data-row-id="<?=$getFiles->file_id; ?>">
<td><?=$i?></td>
<td><a href="<?=base_url();?>crmfiles/taskfile/<?=$getFiles->files_name;?>" target="_blank"><?=$getFiles->files_name?></a></td>
<td>
<div class="tooltip-col">
<?php 
$big = $getFiles->files_desc;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<td>
<?php 
  $pri_col='file_id ';
  $table_name='tbl_file';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <li><a href="#" onclick="editTaskFiles(this);" property = "edit" type="button" data-toggle="modal" data-target="#fileTaskModal" arrt= '<?=json_encode($getFiles);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This File</a></li>
  <li><a href="#" class="delbutton" id="<?php echo $getFiles->file_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This File</a></li>
</ul>
</div>
</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</div><!-- listdataid -->

</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Description Information close-->
</div>
</div><!--tile-body p-0 close-->


</div>
</div>
</div>
</div><!-- related close -->

<div role="tabpanel" class="tab-pane" id="activity">
<div class="row">
<div class="col-md-12">

<section class="tile- time-simple">
<div class="tile-body">
<div class="streamline mt-20">

<?php 

$SftLog = $this->db->query("select * from tbl_software_log where slog_id='".$task_id."' AND mdl_name='Task' ORDER BY sid DESC ");


foreach ($SftLog->result() as $getSftLog) {  

if($getSftLog->slog_name == 'Task' && $getSftLog->slog_type != 'User' && $getSftLog->slog_type != 'Status' ) {

$tskname = $getSftLog->slog_type;

$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->old_id."'");
$getMakr = $makr->row();
$makrNm = $getMakr->user_name;

$asnUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->new_id."'");
$getAsnusr = $asnUsr->row();
$AsinName = $getAsnusr->user_name;


?>

<article class="streamline-post">
<aside>
<?php if($tskname == 'Phone Call') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-phone"></i></button>
<?php } elseif($tskname == 'Email') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-envelope"></i></button>
<?php } elseif($tskname == 'Meeting') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-building-o"></i></button>
<?php } elseif($tskname == 'Deadline') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-ellipsis-v"></i></button>
<?php } elseif($tskname == 'Follow Up') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-users"></i></button>
<?php } ?>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-heading bg-white">
  <span class="badge bg-greensea call"> <i class="fa fa-check"></i> </span> <?=$tskname; ?>
</div>
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$getSftLog->maker_date?></span></li>
<li>|</li>
<!-- <li><span><?=$getTasklog->designation?></span></li>
<li>|</li> -->
<li><span><i class="fa fa-user"></i> <!-- <a href="#"> --><?= $AsinName; ?></span></li>
<li>|</li>
<li><span><!-- <i class="fa fa-user" aria-hidden="true"></i> --><?= $makrNm; ?></span></li>
</ul>
</div>
<div class="call-footer">
<?php echo $getSftLog->remarks; ?>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php  } ?>

<?php 

if($getSftLog->slog_name == 'Task' && $getSftLog->slog_type == 'User') {


$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getRespUsr = $RespUsr->row();
$resp_usr_name = $getRespUsr->user_name;

$slog_type = $getSftLog->slog_type;

$oldusr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->old_id."' ");
$getOldUsr = $oldusr->row();
$oldUsrName = $getOldUsr->user_name;

$newUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->new_id."' ");
$getNewUsr = $newUsr->row();
$newUsrName = $getNewUsr->user_name;


?>
<article class="streamline-post streamline:after streamline-post-to">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width: 10px;height: 10px;padding: 5px;margin: 0 0 0 14px;"></button>
</aside>
<div class="post-container">
<div class="panel panel-default panel-to">
<div class="bg-white"><?=$oldUsrName?> <i class="fa fa-long-arrow-right"></i> <?=$newUsrName?> </div>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li><span><?= $getSftLog->maker_date; ?> </span></li>
<li>|</li>
<li><span> <?= $resp_usr_name; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php } ?>

<?php 

if($getSftLog->slog_name == 'Task' && $getSftLog->slog_type == 'Status') {


$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getRespUsr = $RespUsr->row();
$resp_usr_name = $getRespUsr->user_name;

$slog_type = $getSftLog->slog_type;

$oldkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog->old_id."' ");
$getOldKey = $oldkey->row();
$oldKeyName = $getOldKey->keyvalue;

$newkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog->new_id."' ");
$getNewKey = $newkey->row();
$newKeyName = $getNewKey->keyvalue;


?>
<article class="streamline-post streamline:after streamline-post-to">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width: 10px;height: 10px;padding: 5px;margin: 0 0 0 14px;"></button>
</aside>
<div class="post-container">
<div class="panel panel-default panel-to">
<div class="bg-white"><?=$oldKeyName?> <i class="fa fa-long-arrow-right"></i> <?=$newKeyName?> </div>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li><span><?= $getSftLog->maker_date; ?> </span></li>
<li>|</li>
<li><span> <?= $resp_usr_name; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php } ?>


<?php 

if($getSftLog->slog_name == 'File') {

$filename = $getSftLog->slog_type;
$file_ext = pathinfo($filename);
$file_extn = $file_ext[extension];

$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getMakr = $makr->row();
$makrNm = $getMakr->user_name;

?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-files-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-heading bg-white">
<?php if($file_extn == 'tif' || $file_extn == 'jpg' || $file_extn == 'gif' || $file_extn == 'png'){?>
<i class="fa fa-file-image-o"></i>
<?php } elseif($file_extn == 'xls' || $file_extn == 'csv' || $file_extn == 'xlsx') { ?>
<i class="fa fa-file-excel-o"></i>
<?php } elseif($file_extn == 'pdf') { ?>
<i class="fa fa-file-pdf-o"></i> 
<?php } elseif($file_extn == 'docx' || $file_extn == 'doc') { ?>
<i class="fa fa-file-word-o"></i> 
<?php } else { ?>
<i class="fa fa-file-text-o"></i> 
<?php } ?>     
<a href="<?=base_url();?>crmfiles/taskfile/<?=$filename;?>" target="_blank">&nbsp;<?=$filename;?></a>
</div>
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$getSftLog->maker_date?></span></li>
<li>|</li>
<li><span><!-- <i class="fa fa-user" aria-hidden="true"></i> --> &nbsp;<?= $makrNm; ?></span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php  } ?>

<?php 

if($getSftLog->slog_name == 'Note' && $getSftLog->slog_type == 'Task Notes' ) {

$notename = $getSftLog->slog_type;

$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getMakr = $makr->row();
$makrNm = $getMakr->user_name;

?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-file-text-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-heading bg-white"> <?=$notename?>
  <!-- <span class="badge bg-greensea call"> <i class="fa fa-pdf"></i> </span> 
  <a href="<?=base_url();?>crmfiles/leadfile/<?=$filename;?>" target="_blank">
    <?=$filename; ?></a> -->
</div> 
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$getSftLog->maker_date?></span></li>
<li>|</li>
<li><span><!-- <i class="fa fa-user" aria-hidden="true"></i> --> &nbsp;<?= $makrNm; ?></span></li>
</ul>
</div>
<div class="call-footer">
<?php echo $getSftLog->remarks; ?>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php  } ?>

<?php } ?>

<article class="streamline-post streamline:after streamline-post-to">
<aside>
<button type="button" class="btn btn-rounded-20" style="width: 10px;height: 10px;padding: 5px;margin: 0 0 0 14px;"></button>
</aside>
<div class="post-container">
<div class="panel panel-default panel-to">
<div class="bg-white">Task Created</div>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li>by &nbsp;<span><?= $mkrname; ?> &nbsp; <?= $result->maker_date; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

</div><!--streamline mt-20 close-->

</div>
</section>
</div>
</div>
</div><!-- activity close -->


</div>
</div>
</div>

</section>
</div>
</div>

</div>
</section>

</div>
</div>


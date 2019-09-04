<?php

  $contact = $this->db->query("select contact_id,contact_name  from tbl_contact_m where status = 'A' ");
  $arr = array();
  foreach ($contact->result() as $getContact) 
  { 
     
    $arr[] = $getContact->contact_name."^".$getContact->contact_id;
  } 


  $json_contact   = json_encode($arr,true);

  //print_r($arr);

  $contact = $this->db->query("select * from tbl_organization where status='A' ORDER BY org_name ");
  $arrorgnization = array();

    foreach ($contact->result() as $getContact) 
    { 
       $arrorgnization[] = $getContact->org_name.'^'.$getContact->org_id;
    } 

  $json_orgnization = json_encode($arrorgnization,true);

  ?>
<?php 
  
     $contact_id = "";   $contact_name = ""; $org_name = ""; $designation = "";
     $email      = "";   $phone        = ""; $address  = ""; $city_name   = ""; 
     $state_id   = "";   $pincode      = ""; $country  = ""; $description = "";

if($result != "")
{
      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';die;

    $contact_id   = $result->contact_id;
    $orgz_id      = $result->org_name;
    $contact_name = $result->contact_name;
     
    $designation  = $result->designation;

     $phone      = $result->phone;
     $FrstPhone  = json_decode($phone,true);
     
     $email      = $result->email;
     $FrstEmail  = json_decode($email,true);

     $address    = $result->address;
     $city_name  = $result->city_name;
    
    $stateName   = $this->db->query("select * from tbl_state_m where stateid='".$result->state_id."' ");
    $getName     = $stateName->row();
    $stName      = $getName->stateName;
    
     $pincode    = $result->pincode;

    $cntryName     = $this->db->query("select * from tbl_country_m where contryid='".$result->country."' ");
    $getCntryName  = $cntryName->row();
    $cName         = $getCntryName->countryName;
    
     $description  = $result->description;

    $MkrNm    = $this->db->query("select * from tbl_user_mst where user_id='".$result->maker_id."'");
    $getMkrNm = $MkrNm->row();
    $mkrname  = $getMkrNm->user_name;

   $orgnm        = $this->db->query("select * from tbl_organization where org_id='".$orgz_id."' ");
   $getOrgNm     = $orgnm->row();
   $orgzname     = $getOrgNm->org_name;

}
  
  $userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='2'");
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

  <input type="hidden" id="json_contact" value='<?=$json_contact;?>'>
  <input type="hidden" id="json_orgnization" value='<?=$json_orgnization;?>'>

<div class="pageheader tile-bg">
<div class="media">
<div class="pull-left thumb">
<img class="media-object img-thumbnail" src="<?=base_url();?>img/placeholder-contact.png" alt="">
</div>
<div class="btn-toolbar pull-right mt-10">
<div class="btn-group">
<a href="<?php echo base_url('contact/Contact/manage_contact');?>"><button class="btn btn-default btn-sm br-2"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button></a> 
</div>
<div class="btn-group">
  <?php
if($getAcc->edit_id=='1')
{
?>
<a href="#" onclick="editContactInner(this);" property = "edit" type="button" data-toggle="modal" data-target="#contactEditModal" arrt= '<?=json_encode($result);?>'  data-backdrop='static' data-keyboard='false'><button class="btn btn-default btn-sm br-2"><i class="fa fa-pencil"></i></button></a> 
<?php } ?>
</div>
<div class="btn-group">
<?php
  if($getAcc->edit_id=='1' || $getAcc->delete_id=='1') {
?>
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action<span class="caret"></span></button>
<?php } ?>
<ul class="dropdown-menu dropdown-menu-right" role="menu">
<?php if($getAcc->edit_id=='1') { ?>
<li><a href="#" onclick="editContactInner(this);" property = "edit" type="button" data-toggle="modal" data-target="#contactEditModal" arrt= '<?=json_encode($result);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i>Edit This Contact</a></li>
<?php } ?>
<?php if($getAcc->delete_id=='1') { ?>
<?php
// $cntct=$this->db->query("select * from tbl_contact_m where org_name='$result->org_id' ");
// $cntctNumRow=$cntct->num_rows();
$lead=$this->db->query("select * from tbl_leads where contact_id='$result->contact_id' ");
$leadNumRow=$lead->num_rows();
$task=$this->db->query("select * from tbl_task where contact_person='$result->contact_id' ");
$tskNumRow=$task->num_rows();

$num_rows=$leadNumRow + $tskNumRow;

if($num_rows > 0) { ?>
  <li><a href="#" onclick="return confirm('Contact already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Contact </a></li>
<?php } else { ?>
<li><a onclick = "if (! confirm('Are You Sure! You Want To Delete ?')) { return false; }" href="<?=base_url('contact/Contact/deleteContact?contact_id=');?><?=$contact_id?>" ><i class="fa fa-trash"></i>Delete This Contact</a></li>
<?php } ?>
<?php } ?>
</ul>
</div>

</div>

<div class="media-body">
<p class="media-heading mb-0 mt-5">Contact</p>
<small class="text-lightred"><?=$contact_name?></small>
</div>
</div>
</div><!--pageheader close-->

<div class="pageheader">
<div class="table-responsive">
<table class="table mb-0">
<tbody>
<tr>
<td style="border:none;">
<small class="text-muted">Designation</small>
<h5 class="media-heading mb-0"><?=$designation?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Phone</small>
<h5 class="media-heading mb-0"><?=$FrstPhone[0]?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Email</small>
<h5 class="media-heading mb-0"><?=$FrstEmail[0]?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Organization</small>
<h5 class="media-heading mb-0"><?=$orgzname?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Contact Created</small>
<h5 class="media-heading mb-0"><?=$result->maker_date?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Contact Owner</small>
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
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation1" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Contact Person Details</span> </a> </h4>
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
<td><div class="info"><?=$contact_id;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Contact Person Name</span></td>
<td><div class="info"><?=$contact_name;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Designation </span></td>
<td><div class="info"><?=$designation;?> </div></td>
</tr>



<?php
if($phone!='')
{
$jsondata = $phone;
$arr = json_decode($jsondata, true);
foreach ($arr as $k=>$v) { ?> 
    <tr>
      <td class="ralign"><span class="title">Phone</span></td>
      <td><div class="info"><?=$v?></div></td>
    </tr>
<?php } } ?>


<?php
if($email!='')
{

$jsondata = $email;
$arr = json_decode($jsondata, true);
foreach ($arr as $k=>$v) { ?>
    <tr>
      <td class="ralign"><span class="title">Email</span></td>
      <td><div class="info"><?=$v?></div></td>
    </tr>

<?php } } ?>


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
<div class="panel-heading" role="tab" id="ContactDetails">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#ContactDetails2" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Contact Person Address</span> </a> </h4>
</div>
<div id="ContactDetails2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="ContactDetails">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<table class="property-table">
<tbody>
<tr>
<td class="ralign"><span class="title">Address </span></td>
<td><div class="info"><?=$address;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">City</span></td>
<td><div class="info"><?=$city_name?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">State</span></td>
<td><div class="info"><?=$stName;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Pin Code</span></td>
<td><div class="info"><?=$pincode;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Country</span></td>
<td><div class="info"><?=$cName;?> </div></td>
</tr>
</tbody>
</table>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Contact Details close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="AddressInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#AddressInformation3" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Organization Details </span> </a> </h4>
</div>
<div id="AddressInformation3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="AddressInformation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<table class="property-table">
<tbody>
<?php 
   // $orgnm        = $this->db->query("select * from tbl_organization where org_id='".$orgz_id."' ");
   // $getOrgNm     = $orgnm->row();
   // $orgzname     = $getOrgNm->org_name;
?>
<tr>
<td class="ralign"><span class="title">Organization Name </span></td>
<td><div class="info"><?=$orgzname;?></div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Website </span></td>
<td><div class="info"><?=$getOrgNm->website;?></div></td>
</tr>

<?php
if($getOrgNm->phone_no!='')
{
$jsondata = $getOrgNm->phone_no;
$arr = json_decode($jsondata, true);
foreach ($arr as $k=>$v) { ?> 
    <tr>
      <td class="ralign"><span class="title">Phone</span></td>
      <td><div class="info"><?=$v?></div></td>
    </tr>
<?php } } ?>


<?php
if($getOrgNm->email!='')
{

$jsondata = $getOrgNm->email;
$arr = json_decode($jsondata, true);
foreach ($arr as $k=>$v) { ?>
    <tr>
      <td class="ralign"><span class="title">Email</span></td>
      <td><div class="info"><?=$v?></div></td>
    </tr>

<?php } } ?>

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
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#AddressInformation5" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Additional Information</span> </a> </h4>
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
<td class="ralign"><span class="title">Contact Created</span></td>
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
</div><!--Additional Information close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="DescriptionInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#DescriptionInformation6" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Description Information</span> </a> </h4>
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

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-file-text-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<!-- <div class="panel-heading bg-white"> Lead Notes </div> --> 
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$result->maker_date?></span></li>
<li>|</li>
<li><span><?= $mkrname; ?></span></li>
</ul>
</div>
<div class="call-footer">
<?php 
$bigs = strip_tags($description);
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
</div><!--close-->
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
$orgz = $this->db->query("select * from tbl_organization where contact_id='".$contact_id."' ");
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
$leadrow = $this->db->query("select * from tbl_leads where contact_id='".$result->contact_id."' ");
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
$taskrow = $this->db->query("select * from tbl_task where contact_person='".$result->contact_id."' ");
$cntTask = $taskrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Task" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#projects10" aria-expanded="true">
<span class="top-label">Task</span>
<div class="block-item-count"><?=$cntTask?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$notesrow = $this->db->query("select * from tbl_note where note_logid='".$contact_id."' AND note_type='Contact' ");
$cntNotes = $notesrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Notes" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#notes12" aria-expanded="true">
<span class="top-label">Self Notes</span>
<div class="block-item-count"><?=$cntNotes?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$filesrow = $this->db->query("select * from tbl_file where file_logid='".$contact_id."' AND file_type='Contact' ");
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
<div class="panel-heading" role="tab" id="nameandoccupation">
<div class="btn-group">  
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation8" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Organization</span><span class="badge bg-lightred"><?=$cntrows?></span> </a> </h4>
</div>
<!-- <div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#mutiOrgCntModal" formid="#MultiOrgzCntForm" id="formreset">Add Link</button>
</p>
</div> -->
</div>
<div id="nameandoccupation8" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">

<div id="listingMultiOrgCntData"> <!-- listdataid -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Organization Name</th>
<th>Website</th>
<th>Email</th>
<th>Phone</th>
</tr>
</thead>
<tbody>

<?php 
foreach ($orgz->result() as $getOrg) {
?>

<tr class="record" data-row-id="<?=$getOrg->org_id; ?>">
<td>
<!-- <a href="#" onclick="getViewOrgPage('<?=$getOrg->orgid;?>');"><?=$getOnm->org_name?></a> --> 
<a href="<?php echo base_url('organization/Organization/view_organization?id=');?><?=$getOrg->org_id;?>"><?=$getOrg->org_name?></a>
</td>
<td><?=$getOrg->website ?></td>
<?php 
$json_phone = json_decode($getOrg->phone_no,true);
$json_email = json_decode($getOrg->email,true);
?>
<td><?=$json_phone[0]; ?></td>
<td><?=$json_email[0]; ?></td>
<td>

</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Contact Details close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="Opportunities">
<div class="btn-group">  
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#Opportunities9" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Lead</span> <span class="badge bg-lightred"><?=$cntlead?></span> </a></h4>
</div>
</div>

<div id="Opportunities9" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="Opportunities" aria-expanded="true" style="">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Lead Number</th>
<th>User Responsible</th>
<th>Source</th>
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
 <a href="<?=base_url('lead/Lead/view_lead?id=');?><?=$getLead->lead_id; ?>"> <?=$getLead->lead_number?></a>
</td>
<td>
<?php
$usr = $this->db->query("select * from tbl_user_mst where user_id='".$getLead->user_resp."' ");
$getUsr = $usr->row();
echo $getUsr->user_name; ?>
</td>
<td>
<?php 
$src = $this->db->query("select * from tbl_master_data where serial_number='".$getLead->source."' ");
$getSrc = $src->row();
echo $getSrc->keyvalue; ?>
</td>
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
<div class="panel-heading" role="tab" id="projects">

<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#projects10" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Task</span> <span class="badge bg-lightred"><?=$cntTask?></span></a></h4>
</div>
<div id="projects10" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="projects" aria-expanded="true" style="">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Name</th>
<th>Reminder Date</th>
<th>Date Due</th>
<th>Responsible User</th>
<th>Description</th>
<th>Status</th>
<th>Progress Percent</th>
</tr>
</thead>
<tbody>
<?php 
foreach($taskrow->result() as $getTask){
?>
<tr>
<td>
<!-- <a href="#" onclick="getViewTaskPage('<?=$getTask->task_id;?>');"> -->
  <a href="<?=base_url('task/Task/view_task?id=');?><?=$getTask->task_id;?>">
<?php
$tname = $this->db->query("select * from tbl_master_data where serial_number='".$getTask->task_name."' ");
$getTname = $tname->row();
echo $getTname->keyvalue; ?>  
</a>
</td>
<td><?=$getTask->reminder_date?></td>
<td><?=$getTask->date_due?></td>
<td>
<?php
$tusr = $this->db->query("select * from tbl_user_mst where user_id='".$getTask->user_resp."' ");
$getTusr = $tusr->row();
echo $getTusr->user_name; ?>
</td>
<td>
<div class="tooltip-col">
<?php 
$big = $getTask->description;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<td>
<?php
$tstatus = $this->db->query("select * from tbl_master_data where serial_number='".$getTask->task_status."' ");
$getTstatus = $tstatus->row();
echo $getTstatus->keyvalue; ?>
</td>
<td>
<div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 150px; margin-right: 5px">
<div class="progress-bar progress-bar-greensea" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: <?=$getTask->progress_percnt?>%;"></div>
</div>
<small><?=$getTask->progress_percnt?>%</small></td>
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
<div class="panel-heading" role="tab" id="notes">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#notes12" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Self Notes</span> <span class="badge bg-lightred"><?=$cntNotes?></span> </a></h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#contactNoteModal" formid="#ContactNotesForm" id="formreset">Add Self Notes</button>
</p>
</div>
</div>
</div>
<div id="notes12" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="notes">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">

<div id="listingNotesData"> <!-- listdataid -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>S. No.</th>
<th>Note Date</th>
<th>Note Desc</th>
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
  <li><a href="#" onclick="editCntctNote(this);" property = "edit" type="button" data-toggle="modal" data-target="#contactNoteModal" arrt= '<?=json_encode($getNote);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Note</a></li>
  <li><a href="#" class="delbutton" id="<?php echo $getNote->note_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Note</a></li>
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
<div class="panel-heading" role="tab" id="DescriptionInformation">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a  data-toggle="collapse" data-parent="#accordion" href="#files" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Files</span> <span class="badge bg-lightred"><?=$cntFiles?></span> </a>   </h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#fileContactModal" formid="#FilesContactForm" id="formreset">Add Files</button>
</p>
</div>
</div>
</div>
<div id="files" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="DescriptionInformation">
<div class="panel-body">
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
<td><a href="<?=base_url();?>crmfiles/contactfile/<?=$getFiles->files_name;?>" target="_blank"><?=$getFiles->files_name?></a></td>
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
  <li><a href="#" onclick="editCntctFiles(this);" property = "edit" type="button" data-toggle="modal" data-target="#fileContactModal" arrt= '<?=json_encode($getFiles);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This File</a></li>
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

$SftLog = $this->db->query("select * from tbl_software_log where slog_id='".$contact_id."' AND mdl_name='Contact' ORDER BY sid DESC ");


foreach ($SftLog->result() as $getSftLog) {  

if($getSftLog->slog_name == 'Task') {

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

if($getSftLog->slog_name == 'Contact' && $getSftLog->slog_type == 'User') {


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
<div class="bg-white"><?=$slog_type?> : <?=$oldUsrName?> <i class="fa fa-long-arrow-right"></i> <?=$newUsrName?> </div>
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
<a href="<?=base_url();?>crmfiles/contactfile/<?=$filename;?>" target="_blank">&nbsp;<?=$filename;?></a>
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

if($getSftLog->slog_name == 'Note' && $getSftLog->slog_type == 'Contact Notes') {

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
<div class="bg-white">Contact Created</div>
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

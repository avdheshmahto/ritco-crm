
<?php $this->load->view('header.php');

$entries = ""; $filter = "";
if($this->input->get('entries')!="")
{
  $entries = $this->input->get('entries');
}
if($this->input->get('filter')!="")
{
  $filter = $this->input->get('filter');
}

$userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='3'");
$getAcc=$userPerQuery->row();

?>

<?php

  //$contact = $this->db->query("select C.contact_id,C.contact_name,O.org_name  from tbl_contact_m C LEFT JOIN tbl_organization O On C.org_name = O.org_id");
  $contact = $this->db->query("select contact_id,contact_name  from tbl_contact_m where status = 'A' ");
  $arr = array();

  foreach ($contact->result() as $getContact) 
  { 
    //$arr[] = $getContact->contact_name." ( ".$getContact->org_name." )^".$getContact->contact_id;
    $arr[] = $getContact->contact_name."^".$getContact->contact_id;
  } 

  //print_r($arr);

  $json_contact   = json_encode($arr,true);
  

  $contact = $this->db->query("select * from tbl_organization where status='A' ORDER BY org_name ");
  $arrorgnization = array();

  foreach ($contact->result() as $getContact) 
  { 
      $arrorgnization[] = $getContact->org_name.'^'.$getContact->org_id;
  } 

  $json_orgnization = json_encode($arrorgnization,true);

  ?>

<div id="ajax_content">
<section id="content">
<div class="page page-tables-bootstrap">

<div class="row">
<div class="col-md-12">
<section class="tile" >
  <input type="hidden" id="json_contact" value='<?=$json_contact;?>'>
  <input type="hidden" id="json_orgnization" value='<?=$json_orgnization;?>'>
<div class="pageheader tile-bg">
<span>LEADS</span>
<div class="page-bar">

<?php if($this->session->userdata('role')!='1') { ?>
<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" id="filter" name="filter" url="<?=base_url('lead/Lead/manage_lead?');?>">
<option value="4">All Leads </option>  
<option value="1" <?=$filter=='1'?'selected':'';?> >My Leads</option>
<option value="2" <?=$filter=='2'?'selected':'';?> >Assigned To Me</option>
<option value="3" <?=$filter=='3'?'selected':'';?> >Assigned By Me</option>
</select>
</div>
</div>
</ul>

<?php } ?>

<?php if($this->session->userdata('role')=='1') { ?>
<form method="get"> 
<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" name="filter" >
<option value="4" >All Leads </option>
<option value="1" <?=$filter=='1'?'selected':'';?> >My Leads</option>
<option value="2" <?=$filter=='2'?'selected':'';?> >Assigned To Me</option>
<option value="3" <?=$filter=='3'?'selected':'';?> >Assigned By Me</option>
</select>
</div>
</div>
</ul>

<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" name="user" >
<option value="">--Select User--</option>
<?php
$usr=$this->db->query("select * from tbl_user_mst where status='A'");
foreach($usr->result() as $getUsr) {
  $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsr->brnh_id' ");
  $getBranch=$branch->row();
 ?>
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['user']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")";?> </option>
<?php } ?>
</select>
</div>
</div>
</ul>

<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" name="status" >
<option value="">--Select Status--</option>
<?php
$status=$this->db->query("select * from tbl_master_data where param_id=22 AND serial_number !='79' ");
foreach($status->result() as $getStatus) {
 ?>
<option value="<?=$getStatus->serial_number;?>" <?php if($_GET['status']==$getStatus->serial_number) { ?>selected <?php } ?> ><?=$getStatus->keyvalue;?> </option>
<?php } ?>
</select>
</div>
</div>
</ul>

<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" name="stage" >
<option value="">--Select Stage--</option>
<?php
$stage=$this->db->query("select * from tbl_master_data where param_id='20' ");
foreach($stage->result() as $getStage) {
 ?>
<option value="<?=$getStage->serial_number;?>" <?php if($_GET['stage']==$getStage->serial_number) { ?>selected <?php } ?> ><?=$getStage->keyvalue;?> </option>
<?php } ?>
</select>
</div>
</div>
</ul>

<button class="btn btn-sm btn-default" type="submit" style="margin: -23px 0px 0px 5px;" value="search" name="search">Search</button>
<button class="btn btn-sm btn-default" type="reset" onclick="ResetLead();" style="margin: -23px 0px 0px 5px;">Reset</button> 
</form>
<?php } ?>

  <!-- combination search end -->


<div class="btn-toolbar pull-right" <?php if($this->session->userdata('role')=='1') { ?>style="margin: -35px 0px 0px 0px;" <?php } ?> >
<div class="btn-group">
<?php
if($getAcc->create_id=='1')
{
?>
<button class="btn btn-danger mb-10" data-toggle="modal" data-target="#leadModal" formid="#LeadForm" id="formreset">Add New Lead</button></div>
<?php }?>
<div class="btn-group">
<?php
if($getAcc->delete_id=='1')
{
?>

<button class="btn btn-primary btn-sm mb-10 delete_all" style="display: none;"> Delete All</button>
<?php }?>
</div>
</div>
</div>
</div><!--pageheader close-->


<div id="listingData"> <!-- listdataid -->
<div class="tile-widget-to tile-widget-top">
<div class="show-entries">
<div class="row">
<div class="col-sm-5">
<div style="line-height:30px;">
Show
<select class="btn btn-default btn-sm" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" id="entries"  url="<?=base_url('lead/Lead/manage_lead').'?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&stage='.$_GET['stage'].'&search='.$_GET['search'];?>">
<option value="10">10</option>
<option value="25" <?=$entries=='25'?'selected':'';?>>25</option>
<option value="50" <?=$entries=='50'?'selected':'';?>>50</option>
<option value="100" <?=$entries=='100'?'selected':'';?>>100</option>
<option value="500" <?=$entries=='500'?'selected':'';?>>500</option>
<option value="<?=$dataConfig['total'];?>" <?=$entries==$dataConfig['total']?'selected':'';?>>ALL</option>
</select>
entries</div>
</div>

<div class="col-sm-4"></div>
<div class="col-sm-3">
<div class="input-group">
<input type="text" class="input-sm form-control" id="searchTerm"  onkeyup="doSearch()" placeholder="Search...">
<span class="input-group-btn">
<!-- <button class="btn btn-sm btn-default" type="button" onclick="exportTableToExcel('tblData')">Excel</button>  -->
</span> 
</div>
</div>
</div>
</div>
</div>

<!-- tile body -->
<div class="tile-body p-0">
<div class="table-responsive table-overflow__">
<table class="table mb-0 table-hover">
<thead>
<tr>
    <th style="width:20px;"> 
    <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
    <input type="checkbox" id="check_all" onClick="checkall(this.checked)" value="check_all">
    <i></i> </label></th>
    <th></th>
    <th>Lead Name</th>
    <th>Lead Owner</th>
    <th><div style="width:130px;">Expted Closure Date</div></th>
    <th>Description</th>
    <th><div style="width:100px;">Lead Status</div></th>
    <th><div style="width:70px;">Lead Stage</div></th>
    <th>Assign To</th>
    <th style="text-align: right;">Action</th>
</tr>
</thead>

<tbody id="dataTable">
<?php
foreach($result as $fetch_list){

  $login_id=$this->session->userdata('user_id');
  $getSid = $fetch_list->visibility;
  $sid = explode(',', $getSid);
  $lgnusr = explode(',', $login_id);
  $resultsid=array_intersect($sid,$lgnusr);
  if(sizeof($resultsid) > 0){
?>
<tr class="record selected" data-row-id="<?=$fetch_list->lead_id; ?>">
<?php } else { ?>
<tr class="record unread" data-row-id="<?=$fetch_list->lead_id; ?>">
<?php } ?>  
<td>
  <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
    <input name="cid[]" type="checkbox" id="cid[]" class="sub_chk" data-id="<?=$fetch_list->lead_id; ?>" value="<?=$fetch_list->lead_id;?>">
    <i></i>
  </label>
</td>



<?php 
$exptd_dt=$fetch_list->closuredate;
$freeze=explode(" ",$exptd_dt);
$crdate=date("Y/m/d");

//if(($crdate > $freeze[0] || $fetch_list->lead_state == 79 ) && $this->session->userdata('role')!='1') { ?>
<!-- <td>
<?php
$string=$fetch_list->lead_number;
$firstCharacter = $string[0];
?>
<a href="#" onclick="return confirm('Lead Is Freeze. Please Contact Admin!');">
<div class="thumb thumb-sm">
<div class="img-circle bg-greensea circle"><?=$firstCharacter?></div>
</div>
</a>
</td>
<td>
<a href="#" onclick="return confirm('Lead Is Freeze. Please Contact Admin!');"><?=$fetch_list->lead_number?></a>
</td> -->
<?php //} else { ?>
<td>
<?php
$string=$fetch_list->lead_number;
$firstCharacter = $string[0];
?>
<!-- <a href="#" onclick="getViewLeadPage('<?=$fetch_list->lead_id;?>');"> -->
<a href="<?=base_url('lead/Lead/view_lead?id=');?><?=$fetch_list->lead_id; ?>" onclick="readUnread('<?=$fetch_list->lead_id; ?>');">
<div class="thumb thumb-sm">
<div class="img-circle bg-greensea circle"><?=$firstCharacter?></div>
</div>
</a>
</td>
<td>
<a href="<?=base_url('lead/Lead/view_lead?id=');?><?=$fetch_list->lead_id; ?>" onclick="readUnread('<?=$fetch_list->lead_id; ?>');"><?=$fetch_list->lead_number?></a>
</td>
<?php //} ?>



<td><?php
  $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$fetch_list->maker_id' ");
  $getOwner = $sqlgroup1->row();
  echo  $getOwner->user_name;?>
</td>

<td><?=$fetch_list->closuredate;?></td>

<?php 
  $tskDes = $this->db->query("select * from tbl_note where note_logid='".$fetch_list->lead_id."' AND (main_lead_id_note='main_lead' OR main_lead_id_note='main_task' OR main_lead_id_note='Inner Lead') AND (note_type='Lead' OR note_type='Task') ORDER BY note_id DESC ");

  $getTskdesc = $tskDes->row();
?>
<td>
<div class="tooltip-col">
<?php 
   $big = $getTskdesc->note_desc;  
$big = strip_tags($big);
$small = substr($big, 0, 20);
echo strtolower($small ."....."); ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<td><?php
$status1 = $this->db->query("select * from tbl_master_data where serial_number='".$fetch_list->lead_state."' ");
  $getStatus1 = $status1->row();
  echo $getStatus1->keyvalue; ?>
</td>
<td>
<a href="#" data-toggle="modal" data-target="#modalManageStage" formid="#ManagechangeStageForm" id="formresetstage" onclick="stageleadid(<?=$fetch_list->lead_id;?>);readUnread('<?=$fetch_list->lead_id; ?>');">
  <?php
  $stg = $this->db->query("select * from tbl_master_data where serial_number='".$fetch_list->stage."' ");
  $getStage = $stg->row();
  echo $getStage->keyvalue; ?></a>
</td>

<?php //if(($crdate > $freeze[0] || $fetch_list->lead_state == 79 ) && $this->session->userdata('role')!='1') { ?>
<!-- <td>
  <a href="#" onclick="return confirm('Lead Is Freeze. Please Contact Admin!');">
<?php
  $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$fetch_list->user_resp' ");
  $getUser = $sqlgroup1->row();
 echo $getUser->user_name;?> </a>
</td> -->
<?php //} else {?>  
<td>
  <a href="#" data-toggle="modal" data-target="#userAssignModal" id="formreset" onclick="assignid(<?=$fetch_list->lead_id; ?>);readUnread('<?=$fetch_list->lead_id; ?>');">
  <?php
  $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$fetch_list->user_resp' ");
  $getUser = $sqlgroup1->row();
 echo $getUser->user_name;?> </a>
</td>
<?php //} ?>

<?php //if(($crdate > $freeze[0] || $fetch_list->lead_state == 79 ) && $this->session->userdata('role')!='1') { ?>
<!-- <td>
  <div class="btn-group pull-right">
  <a href="#" onclick="return confirm('Lead Is Freeze. Please Contact Admin!');"><i class="fa fa-ellipsis-h"></i></a>
  </div>
</td> -->
<?php //} else {?> 
<td>
<?php 
  $pri_col='lead_id ';
  $table_name='tbl_leads';

?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <!-- <li><a href="#" onclick="viewLead(this);" property="view" type="button" data-toggle="modal" data-target="#viewleadModal" arrt= '<?=json_encode($fetch_list);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-eye"></i> View This Lead</a></li> -->

<?php if($getAcc->edit_id=='1') { ?>
  <li><a href="#" onclick="editLead(this);readUnread('<?=$fetch_list->lead_id; ?>');" property = "edit" type="button" data-toggle="modal" data-target="#leadModal" arrt= '<?=json_encode($fetch_list);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Lead</a></li>
  <?php }?>

<?php if($getAcc->delete_id=='1') { ?>
<?php
// $cntct=$this->db->query("select * from tbl_contact_m where org_name='$fetch_list->org_id' ");
// $cntctNumRow=$cntct->num_rows();
// $lead=$this->db->query("select * from tbl_leads where contact_id='$fetch_list->contact_id' ");
// $leadNumRow=$lead->num_rows();
$task=$this->db->query("select * from tbl_task where lead_id='$fetch_list->lead_id' ");
$tskNumRow=$task->num_rows();

$num_rows=$tskNumRow;

if($num_rows > 0) { ?>
  <li><a href="#" onclick="return confirm('Lead already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Lead </a></li>
<?php } else { ?>
  <li><a href="#" class="delbutton_lead" id="<?php echo $fetch_list->lead_id."^".$table_name."^".$pri_col ; ?>" onclick="readUnread('<?=$fetch_list->lead_id; ?>');"><i class="fa fa-trash"></i> Delete This Lead </a></li>
<?php } ?>
<?php }?>

<li><a href="#" data-toggle="modal" data-target="#userAssignModal" id="formreset" onclick="assignid(<?=$fetch_list->lead_id; ?>);readUnread('<?=$fetch_list->lead_id; ?>');"><i class="fa fa-user"></i>Assign Lead</a></li>
<li><a href="#" data-toggle="modal" data-target="#leadTaskModal" id="formreset" onclick="leadidTask(<?=$fetch_list->lead_id; ?>);readUnread('<?=$fetch_list->lead_id; ?>');">
  <i class="fa fa-plus"></i> Add New Task For This Lead</a></li>
  
</ul>
</div>
</td>
<?php //} ?>


</tr>
<?php } ?>
<!-- <tr>
  <td colspan="14" style="height:100px;">&nbsp;</td>
</tr> -->
</tbody>
</table>
</div>
</div>
<!-- /tile body -->

<!-- tile footer -->
  <div class="tile-footer dvd dvd-top" style="height: 55px">
  <div class="row">
  <div class="col-sm-5 hidden-xs">
  <small class="text-muted">
  Showing <?=$dataConfig['page']+1;?> to 
  <?php
  $m=$dataConfig['page']==0?$dataConfig['perPage']:$dataConfig['page']+$dataConfig['perPage'];
  echo $m >= $dataConfig['total']?$dataConfig['total']:$m;
  ?> of <?php echo $dataConfig['total'];?> entries
  </small>
  </div>
      <div class="col-sm-3 text-center"></div>
      <div class="col-sm-4 text-right" style="margin: -22px 0px 0px 0px;">
        <?=$pagination;?>
      </div>
  </div>
  </div>
<!-- /tile footer -->

</div> <!-- listdataid -->          

     
   
</section>
</div>
</div>

</div>
</section>

</div> <!-- /ajax_content -->

<!-- Modal Lead-->
<div class="modal fade" id="leadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add New</span> Lead</h4>
<div id="resultarea" style="font-size: 15px;color: red; text-align:center;"></div> 
</div>
<form  id="LeadForm"> 
<div class="modal-body">
<div class="sb-container container-example1">
<!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#LEADINFO" aria-expanded="true" aria-controls="LEADINFO">
<span><i class="fa fa-minus text-sm mr-5"></i>LEAD INFORMATION</span>
</a>
</h4>
</div>
<div id="LEADINFO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label>*Contact Person Name : <span class="label label-success" id="newid" style="display: none;">new</span></label>
<input type="hidden" name="lead_id" id="lead_id" class="hiddenField">
<input type="hidden" name="oldcontact" id="oldcontact" class="hiddenField">
<input type="hidden" name="orgidcontant" id="orgidcontant" class="hiddenField">

<div class="typeahead__container ">
<div class="typeahead__field">
    <div class="typeahead__query input-group mb-10">
       <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
      <input class="js-typeahead form-control"
          name="contact"
          type="search"
          autofocus
          autocomplete="off" id="contacttypahead">
    </div>
</div>
</div>
</div>
<div class="form-group col-md-6" id="cntctorg">
<label>*Organization Name : <span class="label label-success" id="newidorg" style="display: none;">new</span></label>
<div class="typeahead__container ">
<div class="typeahead__field">
<div class="typeahead__query">
<input class="orgnizationjs-typeahead form-control"
       name="org_name" 
       id="org_name"
       type="search"
       autofocus
       autocomplete="off" >
</div>
</div>
</div>
</div>
<div class="form-group col-md-6" id='mltiorg' style="display: none;">
<label>*Organization Names :</label>
<select name="corg" id="corg" class="form-control">
   <option value="">----Select----</option>
</select>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>*Lead Name : </label>
<input type="text" name="lead_number" id="lead_number" class="form-control" readonly="" required="">
</div>
<div class="form-group col-md-6">
<label>Assign To : </label>
<select name="user_resp" id="user_resp" class="chosen-select form-control">
<option value="">----Select User----</option>
          <?php 
                $sqlgroup=$this->db->query("select * from tbl_user_mst where  status='A' ");
                foreach ($sqlgroup->result() as $fetchgroup){

          $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$fetchgroup->brnh_id' ");
          $getBranch=$branch->row();
          ?>
    <option value="<?php echo $fetchgroup->user_id; ?>" <?php if($this->session->userdata('user_id') == $fetchgroup->user_id) { ?> selected <?php } ?> ><?php echo $fetchgroup->user_name ." (".$getBranch->brnh_name .")" ?></option>
    <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#LEADCONTACT" aria-expanded="true" aria-controls="LEADCONTACT">
<span><i class="fa fa-minus text-sm mr-5"></i>LEAD DETAILS</span>
</a>
</h4>
</div>

<div id="LEADCONTACT" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label>Email : </label>
  <table id="compaddrowboxemail">
    <tr style="line-height: 3;">
     <td style="width:100%;">
       <input type="email" name="email_id[]" id="email_id0"  class="form-control">
     </td>
     <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
     </td>
  </tr>
  </table>
  <a style="cursor: pointer;" onclick="addRowCompemail();"><small>+ add one more</small></a>
</div>
<div class="form-group col-md-6">
<label>Phone : </label>

<table id="compaddrowboxphone">
  <tr style="line-height:3;">
    <td style="width:100%;">
     <input type="tel" name="phone_no[]" id="phone_no0" minlength="10" maxlength="11" class="form-control" required="">
    </td>
    <td align="center" style="width: 150px;">
     <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
    </td>
  </tr>
</table>
  <a  style="cursor: pointer;" onclick="addRowComphone();"><small>+ add one more</small></a>

</div>
</div>

<div class="row">
<div class="form-group col-md-12">
<label>Address : </label>
<textarea name="address" id="address"  class="form-control" ></textarea>
</div>
</div>

</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#ADDITIONALINFO" aria-expanded="true" aria-controls="ADDITIONALINFO">
<span><i class="fa fa-minus text-sm mr-5"></i>ADDITIONAL INFORMATION</span>
</a>
</h4>
</div>
<div id="ADDITIONALINFO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label>Industry : </label>
<select name="industry" id="industry" class="chosen-select form-control">
<option value="">----select----</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='24'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
</div>

<div class="form-group col-md-6">
<label>Stage : </label>
<select name="stage" id="stage" class="chosen-select__ form-control">
<!-- <option value="">----select----</option> -->
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='20'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } elseif($fetchPrio->serial_number != 17){?> style="display: none;" <?php  } ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
</div>
</div>

<!-- <div class="row">
  <div class="form-group col-md-6">
  <label>Source : </label>
  <select name="source" id="source"  class="chosen-select form-control">
  <option value="">--Select--</option>
  <?php 
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='18'");
    foreach ($sqlprio->result() as $fetchPrio){
  ?>
  <option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchPrio->keyvalue ; ?></option>
  <?php }?>
  </select>
  </div>
<div class="form-group col-md-6">
<label>Probability of Winning % : </label>
<input type='number' name="probability" id="probability" min="0" max="100" class="form-control" >
</div>
</div> -->

<div class="row">
<div class="form-group col-md-6">
<label>Expected Business Per Month : </label>
<input type='number' name="opp_value" id="opp_value" class="form-control" >
</div>
<div class="form-group col-md-6">
<label> Expected Closure Date: </label>
<?php
date_default_timezone_set("Asia/Kolkata");
$crntdate=date('Y/m/d H:i');
$expt_dt=date('Y/m/d H:i', strtotime($crntdate. ' + 7 days'));
 ?>
<input type='text' name="closuredate" id="closuredate" class="form-control datetimepicker_mask">
</div>
</div>


</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATION" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATION">
<span><i class="fa fa-minus text-sm mr-5"></i> NOTES </span>
</a>
</h4>
</div>
<div id="DESCRIPTIONINFORMATION" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea name="summernote" id="summernote"></textarea>
</div>
</div>
</div>
</div>
</div><!--panel close-->


</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="formsave" class="btn btn-primary">Save</button>
  <span id="saveload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>

</form>

</div>
</div>
</div>
<!-- Modal Lead close-->

<!-- Modal leadTaskModal -->
<div class="modal fade" id="leadTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Task</h4>
<div id="resultstask" style="font-size: 15px;color: red; text-align:center"></div> 
</div>

<form  id="LeadTaskForm"> 
<div class="modal-body">
<!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
<div class="sb-container container-example1">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#TASKDETAILS" aria-expanded="true" aria-controls="TASKDETAILS">
<span><i class="fa fa-minus text-sm mr-5"></i>TASK DETAILS</span>
</a>
</h4>
</div>
<div id="TASKDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label for="name">Task Name : </label>
<input type="hidden" name="lead_idz" id="lead_idz">
<select name="task_name" id="task_name" class="chosen-select form-control">
      <option value="">----Select ----</option>
          <?php 
            $sqlgroup=$this->db->query("select * from tbl_master_data where status='A' and param_id='23'");
            foreach ($sqlgroup->result() as $fetchgroup){
          ?>
        <option value="<?php echo $fetchgroup->serial_number; ?>" <?php if($fetchgroup->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchgroup->keyvalue ; ?></option>
        <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Priority : </label>
<select name="priority" id="priority"  class="chosen-select form-control">
<option value="">--Select--</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='17'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Status : </label>
<select name="status" id="status" class="chosen-select form-control">
<option value="">----select----</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='21'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchPrio->keyvalue ; ?></option> 
<?php }?>
</select>
</div>
<div class="form-group col-md-6">
<label for="contactemail">Progress % : </label>
<input type="number" name="progress"  value="0" id="progress" class="form-control" readonly="">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="contactemail">Due Date : </label>
<input type='text' name="due_date" id="due_date" class="form-control datetimepicker_mask" required="">
</div>  
<!-- <div class="form-group col-md-6">
<label for="contactemail">Reminder Date: </label>
<input type='text' name="reminder_date" id="reminder_date" class="form-control datetimepicker_mask" >
</div> -->  
<div class="form-group col-md-6">
<label>Assign To : </label>
<select name="user_resp" id="user_resp" class="chosen-select form-control">
<option value="">----Select User----</option>
          <?php 
                $sqlgroup=$this->db->query("select * from tbl_user_mst where  status='A' ");
                foreach ($sqlgroup->result() as $fetchgroup){

          $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$fetchgroup->brnh_id' ");
          $getBranch=$branch->row();
          ?>
    <option value="<?php echo $fetchgroup->user_id; ?>" <?php if($this->session->userdata('user_id') == $fetchgroup->user_id) { ?> selected <?php } ?> ><?php echo $fetchgroup->user_name ." (".$getBranch->brnh_name .")" ?></option>
    <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#RELATEDTO" aria-expanded="true" aria-controls="RELATEDTO">
<span><i class="fa fa-minus text-sm mr-5"></i>RELATED TO</span>
</a>
</h4>
</div>
<div id="RELATEDTO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">


<div class="row">
<div class="form-group col-md-6">
<label>Linked To : </label>
<input type="hidden" name="leadid" id="leadid"> 
<select id="tleadid"  class="form-control" disabled="true">
<option value="">------Select option------</option>
<?php
  $contact = $this->db->query("select * from tbl_leads where status='A' ORDER BY lead_number ");
  foreach ($contact->result() as $getContact) {    ?>
   <option value="<?=$getContact->lead_id; ?>"><?php echo $getContact->lead_number; ?></option>
 <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Contact Name : </label>
<input type="text" id="tcontact_person"  value="" class="form-control" readonly>
<input type="hidden" name="tcontact_person" id="tcontact_personid" value="" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Organization Name : </label>
<input type="text" id="torg_name" value="" class="form-control" readonly>
<input type="hidden" name="torg_name" id="torg_nameid" value="" class="form-control">
</div>
</div>


</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATION" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATION">
<span><i class="fa fa-minus text-sm mr-5"></i>NOTES</span>
</a>
</h4>
</div>
<div id="DESCRIPTIONINFORMATION" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea id="summernotess"></textarea>
</div>
</div>
</div>
</div>
</div><!--panel close-->



</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="tasksave" class="btn btn-primary">Save</button>
  <span id="taskload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>

</form>

</div>
</div>
</div>
<!-- /Modal leadTaskModal close-->

<!------Modal Lead Manage Page Assign To---------->
<div class="modal fade" id="userAssignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel">Update Lead Assign To</h4>
<div id="resultuser" style="font-size: 15px;color: red; text-align:center;"></div> 
</div>
<form  id="LeadAssignForm"> 
<div class="modal-body">
<!-- <div class="sb-container container-example1"> -->
<div class="tile-body slim-scroll--" style1="max-height: 350px;overflow:auto;">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div id="LEADINFO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">
<div class="row">
<div class="form-group col-md-6">
<label>Assign To : </label>
<input type="hidden" name="leadassignid" id="leadassignid" value="">
<select name="assign_user" id="assign_user" class="chosen-select form-control" required="">
<option value="">----Select User----</option>
          <?php 
                $sqlgroup=$this->db->query("select * from tbl_user_mst where  status='A' ");
                foreach ($sqlgroup->result() as $fetchgroup){

          $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$fetchgroup->brnh_id' ");
          $getBranch=$branch->row();
          ?>
    <option value="<?php echo $fetchgroup->user_id; ?>" <?php if($this->session->userdata('user_id') == $fetchgroup->user_id) { ?> selected <?php } ?> ><?php echo $fetchgroup->user_name ." (".$getBranch->brnh_name .")" ?></option>
    <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->



</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="usersave" class="btn btn-primary">Save</button>
  <span id="userload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>

</form>

</div>
</div>
</div>
<!-- Modal Lead Manage Page Assign To close-->



<!-- Pipeline Stage -->
<div class="modal fade" id="modalManageStage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span>Change Pipeline Stage</span></h4>
<div id="resultstage" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="ManagechangeStageForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">

<div class="row">

<div class="form-group col-md-6">
<label for="email">New Stage : </label>
<select name="new_stage" id="new_stage" class="form-control" onchange="chkStage(this.value);">
<option value="">----select----</option>
<?php 
  if($this->session->userdata('role') == 1){
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='20' ");  
  }
  else
  {
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='20' AND serial_number !='78' ");
  }
  
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php //if($fetchPrio->serial_number == $result->stage) {?><?php //} ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
<input type="hidden" name="stage_leadid" id="stage_leadid" value="">
</div>

</div>



</div>
</div>
<!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="stagesave" class="btn btn-primary">Save</button>
<span id="stageload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div><!-- Pipeline Stage Close -->



<?php 
    $uri = $_SERVER['REQUEST_URI'];
    @$uri1=explode('/',$uri);
    @$uri2=@$uri1[2]."/".@$uri1[3]."/".@$uri1[4]; 
?>
<input type="text" style="display:none;" id="pageurl" value="<?=$uri2;?>">  
<input type="text" style="display:none;" id="table_name" value="tbl_leads">  
<input type="text" style="display:none;" id="pri_col" value="lead_id">

<?php $this->load->view('footer.php'); ?>


<script>
     
      var json_contact     = JSON.parse($('#json_contact').val());

      //var json_orgnization = JSON.parse($('#json_orgnization').val());

      //console.log(json_contact);
     
      var data = {countries:json_contact};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new contact !",
            source: {
                country: {
                    data: data.countries
                },
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    event.preventDefault();

                    var splitjs = item.display.split('^');
                    var sindex  = "";
                    if(splitjs.indexOf(1)==-1)
                      sindex = splitjs[1];

                      ur = "ajaxget_contactAlldata";
                      $.ajax({
                          type : "POST",
                          url  :  ur,
                          data :  {'id':sindex}, // serializes the form's elements.
                            success : function (data) {
                             // alert(data); // show response from the php script.
                             if(data != false){
                                 console.log(data);
                                 data = JSON.parse(data);
                                
                                 $("#newid").css("display","none");
                                 $('#contacttypahead').val(data.contact_name);
                                 $('#lead_number').val(data.contact_name+'_');
                                 $('#oldcontact').val(data.contact_id);
                               
                                
                                  if(data.cemail != ""){
                                    j_email = JSON.parse(data.cemail);
                                    console.log(j_email);
                                       if(j_email != ""){
                                        for(var i=0;i<j_email.length;i++){
                                          if(i == 0)
                                            $('#email_id0').val(j_email[0]);
                                          else
                                            addRowCompemail(j_email[i],i);

                                     }
                                    }
                                  }

                                   if(data.cphone != ""){
                                    j_phone = JSON.parse(data.cphone);
                                    if(j_phone != ""){
                                    for(var i=0;i<j_phone.length;i++){
                                      if(i == 0)
                                       $('#phone_no0').val(j_phone[0]);
                                      else
                                       addRowComphone(j_phone[i],i);

                                      }
                                    }
                                  }
                                 
                                 $('#address').val(data.caddress);
                                 $('#orgidcontant').val(data.org_id);
                                                              
                              }
                          }
                      });          
                    $('.js-result-container').text('');
               },
                onSearch:function (node, query) {
                  console.log(node);
                  if(query != "")
                  $("#newid").css("display","inline");
                  $(".project_images").remove();
                  $('#oldcontact').val("");
                  $('#org_name').val("");
                  $('#orgidcontant').val("");
                  $('lead_number').val("");
                  $('#email_id0').val("");
                  $('#phone_no0').val("");
                  $('#address').val("");
                  $('#lead_number').val($('#contacttypahead').val()+'_'+$('#org_name').val());
                  $('#summernote').code("");
                },
                onResult: function (node, query, obj, objCount) {
                    var text = "";
                    if (query !== "") {
                        text = objCount + ' elements matching "' + query + '"';
                    }
                   console.log(node);
                   $("#newid").css("display","none");
                   if(objCount == 0)
                    $("#newid").css("display","inline");
                    $('.js-result-container').text(text);
               }
            },
           // debug: true
        });



      var json_orgnization = JSON.parse($('#json_orgnization').val());

      var data = {countries:json_orgnization};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".orgnizationjs-typeahead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new organization !",
            source: {
              country: {
                data: data.countries
              },
            },
            callback: {
              onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                var splitjs = item.display.split('^');
                var sindex  = "";
                  if(splitjs.indexOf(1)==-1)
                    sindex = splitjs[1];
                    ur = "ajaxget_organizationAlldata";
                    $.ajax({
                        type : "POST",
                        url  :  ur,
                        data :  {'id':sindex}, // serializes the form's elements.
                          success : function (data) {
                          // alert(data); // show response from the php script.
                          if(data != false){
                            console.log(data);
                            data = JSON.parse(data);
                            $('#orgidcontant').val(data.org_id);
                            $('#org_name').val(data.org_name);
                            $('#lead_number').val($('#contacttypahead').val()+'_'+data.org_name);
                          }
                        }
                      });          
                    $('.js-result-container').text('');
                },
                onSearch:function (node, query) {
                 console.log(node);
                 $('#orgidcontant').val("");
                 $('#lead_number').val($('#contacttypahead').val()+'_'+$('#org_name').val());
              },
                onResult: function (node, query, obj, objCount) {
                  var text = "";
                  if(query !== ""){
                    text = objCount + ' elements matching "' + query + '"';
                  }
                   console.log(node);
                   $("#newidorg").css("display","none");
                  if(objCount == 0)
                   $("#newidorg").css("display","inline");
                   $('.js-result-container').text(text);
                }
             },
            // debug: true
        });



   function addRowCompemail(emailval = "",rowid = ""){

    var  style = "";

    var compemailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="email_id[]" id="email_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#compaddrowboxemail').append(compemailData);

   }

  function addRowComphone(phoneval = "",prowid = ""){

    var  style = "";

    var comphoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="tel" name="phone_no[]" id="phone_no'+prowid+'" class="form-control" minlength="10" maxlength="11" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#compaddrowboxphone').append(comphoneData);

   }

 
   function removeRows(ths){
     $(ths).parent().parent().remove();
   }

   

 function assignid(v)
 {    
    $("#leadassignid").val(v);
 }   

function leadidTask(v)
   {    
    $("#leadid").val(v);
    $("#tleadid").val(v);
    $("#lead_idz").val(v);
      myfunLeadno(v);
   } 

function myfunLeadno(lid)
{
  //alert(lid);
  ur = "ajaxget_leadAlldata";
    $.ajax({
        type : "POST",
        url  :  ur,
        data :  {'id':lid}, // serializes the form's elements.
          success : function (data) {
           // alert(data); // show response from the php script.
           if(data != false){
               //console.log(data);
               data = JSON.parse(data);
               $('#tcontact_person').val(data.contact_name);
               $('#torg_name').val(data.org_name);
               $('#tcontact_personid').val(data.contact_id);
               $('#torg_nameid').val(data.org_id);
           }
        }
    });   
}


    
  $("#status").change(function(){
        var status=$("#status").val();
          
     if(status == 19)
     {
          $("#progress").val("0");   
     }
     else if(status == 20)
     {
          $("#progress").val("50");          
     }
     else if(status == 21)
     {
          $("#progress").val("100");    
     }
     
    });

    

    // var pgurl = $('#pageurl').val();
    
    // if(pgurl == "lead/Lead/manage_lead")
    // {
    //   //alert(pgurl);  
    //   ur = "ajax_updateLeadStatus";
    //   $.ajax({
    //     type : "POST",
    //     url : ur,
    //     //data : {},
    //     success : function(data){
    //       //alert(data);
    //       //$("#listingData").empty().append(data).fadeIn();
    //     }

    //   });
    // }

function stageleadid(v)
{    
  $("#stage_leadid").val(v);
}


 function chkStage(v)
 {
  var ur = "ajax_chKRates";
  var abc = $('#stage_leadid').val();
   //alert(abc);
      if(v==53)
      {
          $.ajax({
          type:'POST',
          url:ur,
          data:{'id':abc},
          success:function(data)
          {
              //alert(data);
             // console.log(data);
             if(data == 1){
               //alert("Please Fill Rate First!");
               $("#resultstage").html("Please Fill Rate First!");
               $("#stagesave").prop('disabled', true);
             }

          }
       }); 
      }else{
         $("#resultstage").html('');
         $("#stagesave").prop('disabled', false);
       } 
 }


function readUnread(value)
{
  //alert(value);
  var ur = "ajax_readUnread";
  $.ajax({
    type : 'POST',
    url : ur,
    data : {'id':value},
    success:function(data)
    {
      //alert(data)
    }

  });
}

function ResetLead()
{
  location.href="<?=base_url('/lead/Lead/manage_lead');?>";
}

</script>  
 

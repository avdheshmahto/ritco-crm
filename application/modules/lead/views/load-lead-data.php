<?php 

$userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='3'");
$getAcc=$userPerQuery->row();
?>

   <!-- listdataid -->
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


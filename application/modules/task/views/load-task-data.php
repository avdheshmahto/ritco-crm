<?php 
 $userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='4'");
$getAcc=$userPerQuery->row();
?>
 
   <!-- listdataid -->
<div class="tile-widget-to tile-widget-top">
<div class="show-entries">
<div class="row">
<div class="col-sm-5">
<div style="line-height:30px;">
Show
<select class="btn btn-default btn-sm" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" id="entries"  url="<?=base_url('task/Task/manage_task').'?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&search='.$_GET['search'];?>">
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
<!-- <button class="btn btn-sm btn-default" type="button" onclick="exportTableToExcel('tblData')">Excel</button> 
 --></span> 
</div>
</div>
</div>
</div>
</div>      

<!-- tile body -->
<div class="tile-body p-0">
<div class="table-responsive table-overflow__">
<table class="table mb-0 table-hover" id="tblData">
<thead>
  <tr>
      <th style="width:20px;"> 
        <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
        <input type="checkbox" id="check_all" onClick="checkall(this.checked)" value="check_all">
        <i></i> </label>
      </th>
      <th></th>
      <th>Task Name</th>
      <th>Task Owner</th>
      <th>Lead Name</th>
      <!-- <th>Reminder Date</th> -->
      <th>Due Date</th>
      <th>Description</th>
      <!-- <th>Priority</th> -->
      <th>Task Status</th>
      <th>Assign To</th>
      <!-- <th style="text-align: center;">Progress %</th> -->
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
<tr class="record selected" data-row-id="<?=$fetch_list->task_id; ?>">
<?php } else { ?>
<tr class="record unread" data-row-id="<?=$fetch_list->task_id; ?>">
<?php } ?>
<td>
  <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
    <input name="cid[]" type="checkbox" id="cid[]" class="sub_chk" data-id="<?=$fetch_list->task_id; ?>" value="<?=$fetch_list->task_id;?>">
    <i></i>
  </label>
</td>
<?php 
  $sqlstatus=$this->db->query("select * from tbl_master_data where serial_number='$fetch_list->task_name'");
  $fecthStatus = $sqlstatus->row();
  $tname = $fecthStatus->keyvalue; 
?>
<td>
<a href="<?=base_url('task/Task/view_task?id=');?><?=$fetch_list->task_id;?>" onclick="readUnreadTask('<?=$fetch_list->task_id;?>');">
<div class="thumb thumb-sm">
<div class="img-circle bg-red circle"><?=$tname[0]?></div>
</div>
</a>
</td>
<td>
<a href="<?=base_url('task/Task/view_task?id=');?><?=$fetch_list->task_id;?>" onclick="readUnreadTask('<?=$fetch_list->task_id;?>');"><?=$tname?></a>
</td>
<td><?php
  $townr = $this->db->query("select * from tbl_user_mst where user_id='".$fetch_list->maker_id."' ");
  $getTownr = $townr->row();
  echo $getTownr->user_name;
?></td>
<td><?php 
 $Ltask = $this->db->query("select * from tbl_leads where lead_id='".$fetch_list->lead_id."' ");
 $getLtask = $Ltask->row();
 echo $getLtask->lead_number; ?>
</td>
<!-- <td><?=$fetch_list->reminder_date; ?></td> -->
<td><?=$fetch_list->date_due; ?></td>


<?php 
  $tskDes = $this->db->query("select * from tbl_note where note_logid='".$fetch_list->task_id."' AND main_lead_id_note='Inner Task' AND note_type='Task' ORDER BY note_id DESC ");

  $getTskdesc = $tskDes->row();
if(sizeof($getTskdesc) > 0) {  
?>
<td>
<div class="tooltip-col">
<?php 
   $big = $getTskdesc->note_desc;  
$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<?php } else { ?>
<td>
<div class="tooltip-col">
<?php 
   $big = $fetch_list->description;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo strtolower($small ."....."); ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<?php } ?>
<!-- <td><?php
  //$sqlstatus=$this->db->query("select * from tbl_master_data where serial_number='$fetch_list->priority'");
  //$fecthStatus = $sqlstatus->row();
  //echo $fecthStatus->keyvalue; ?>
</td> -->
<td>
  <a href="#" data-toggle="modal" data-target="#modalTaskStatus" formid="#changeTaskStatusForm" id="formresetstatus" onclick="taskstatus(<?=$fetch_list->task_id;?>);readUnreadTask('<?=$fetch_list->task_id;?>');">
  <?php
  $sqlstatus=$this->db->query("select * from tbl_master_data where serial_number='$fetch_list->task_status'");
  $fecthStatus = $sqlstatus->row();
  echo $fecthStatus->keyvalue; ?></a>
</td> 
<td><a href="#" data-toggle="modal" data-target="#taskAssignModal" id="formreset" onclick="assignid(<?=$fetch_list->task_id; ?>);readUnreadTask('<?=$fetch_list->task_id;?>');">
  <?php
  $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$fetch_list->user_resp' ");
  $res11 = $sqlgroup1->row();
  echo  $res11->user_name;?></a>
</td>
<!-- <td>
<div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 100px; margin-right: 5px">
<div class="progress-bar progress-bar-greensea" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width: <?=$fetch_list->progress_percnt?>%;"></div>
</div>
<small><?=$fetch_list->progress_percnt?>%</small>
</td> -->
<td>
<?php 
  $pri_col='task_id';
  $table_name='tbl_task';
?>

<div class="btn-group pull-right">
  <a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
  <ul class="dropdown-menu" role="menu">
  <!-- <li><a href="#" onclick="editTask(this);" property = "view" type="button" data-toggle="modal" data-target="#taskModal" arrt= '<?=json_encode($fetch_list);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-eye"></i> View This Task</a></li> -->
<?php
  if($getAcc->edit_id==1)
{
?>
  <li><a href="#" onclick="editTask(this);readUnreadTask('<?=$fetch_list->task_id;?>');" property = "edit" type="button" data-toggle="modal" data-target="#taskModal" arrt= '<?=json_encode($fetch_list);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Task</a></li>
<?php } ?>

<?php  if($getAcc->delete_id==1) { ?>
<?php
// $cntct=$this->db->query("select * from tbl_contact_m where org_name='$fetch_list->org_id' ");
// $cntctNumRow=$cntct->num_rows();
$lead=$this->db->query("select * from tbl_leads where lead_id='$fetch_list->lead_id' ");
$leadNumRow=$lead->num_rows();
// $task=$this->db->query("select * from tbl_task where contact_person='$fetch_list->contact_id' ");
// $tskNumRow=$task->num_rows();

$num_rows=$leadNumRow;

if($num_rows > 0) { ?>
  <li><a href="#" onclick="return confirm('Task already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Task </a></li>
<?php } else { ?>
  <li><a href="#" onclick="readUnreadTask('<?=$fetch_list->task_id;?>');" class="delbutton_task" id="<?php echo $fetch_list->task_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Task </a></li>
<?php } ?>
<?php } ?>

<li><a href="#" data-toggle="modal" data-target="#taskAssignModal" id="formreset" onclick="assignid(<?=$fetch_list->task_id; ?>);readUnreadTask('<?=$fetch_list->task_id;?>');"><i class="fa fa-user"></i>Assign Task</a></li>
</ul>
</div>
</td>
</tr>
<?php } ?>
<!-- <tr>
  <td colspan="14" style="height:80px;">&nbsp;</td>
</tr> -->
</tbody>
</table>
</div>
</div>

<!-- /tile body -->

<!-- tile footer -->
  <div class="tile-footer dvd dvd-top">
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
      <div class="col-sm-4 text-right">
        <?=$pagination;?>
      </div>
  </div>
  </div>
<!-- /tile footer -->


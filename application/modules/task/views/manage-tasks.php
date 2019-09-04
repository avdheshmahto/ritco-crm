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


$userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='4'");
$getAcc=$userPerQuery->row();
?>

<div id="ajax_content">

<section id="content">
<div class="page page-tables-bootstrap">

<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="pageheader tile-bg">
<span>TASK</span>
<div class="page-bar">

<?php if($this->session->userdata('role')!='1') { ?>
<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" id="filter" name="filter" url="<?=base_url('task/Task/manage_task?');?>">
<option value="4" >All Task </option>
<option value="1" <?=$filter=='1'?'selected':'';?> >My Task</option>
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
<option value="4" >All Task </option>
<option value="1" <?=$filter=='1'?'selected':'';?> >My Task</option>
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
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['user']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")" ;?> </option>
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
$status=$this->db->query("select * from tbl_master_data where param_id=21 ");
foreach($status->result() as $getStatus) {
 ?>
<option value="<?=$getStatus->serial_number;?>" <?php if($_GET['status']==$getStatus->serial_number) { ?>selected <?php } ?> ><?=$getStatus->keyvalue;?> </option>
<?php } ?>
</select>
</div>
</div>
</ul>

<button class="btn btn-sm btn-default" type="submit" style="margin: -23px 0px 0px 5px;" value="search" name="search">Search</button> 
<button class="btn btn-sm btn-default" type="reset"  onclick="ResetTask();" style="margin: -23px 0px 0px 5px;">Reset</button>
</form>
<?php } ?>
    <!-- combination search end -->

<div class="btn-toolbar pull-right" <?php if($this->session->userdata('role')=='1') { ?>style="margin: -35px 0px 0px 0px;" <?php } ?> >
<div class="btn-group">
<?php
if($getAcc->create_id=='1')
{
?>
<button class="btn btn-danger mb-10" data-toggle="modal" data-target="#taskModal" formid="#TaskForm" id="formreset">Add New Task</button></div>
<?php } ?>
<div class="btn-group">
<?php
if($getAcc->delete_id=='1')
{
?>
<button class="btn btn-primary btn-sm mb-10 delete_all" style="display: none;"> Delete All</button>
<?php } ?>
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

</div> <!-- /listdataid -->

</section>
</div>
</div>

</div>
</section>
</div> <!-- /ajax_content -->



<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Task</h4>
<div id="resultarea" style="font-size: 15px;color: red; text-align:center"></div> 
</div>

<form  id="TaskForm"> 
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
<input type="hidden" name="task_id" id="task_id" class="hiddenField">
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
<select name="priority" id="priority"  class="chosen-select form-control" onchange="chkDate();">
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
<!-- <option value="<?php echo $fetchPrio->serial_number; ?>" ><?php echo $fetchPrio->keyvalue ; ?></option> -->
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
<label for="contactemail">Due Date: </label>
<input type='text' name="due_date" id="due_date" class="form-control datetimepicker_mask" required="">
</div>  
<!-- <div class="form-group col-md-6">
<label for="contactemail">Reminder Date: </label>
<input type='text' name="reminder_date" id="reminder_date" class="form-control datetimepicker_mask" required="">
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
<select name="leadid" id="leadid" onchange="myfunLeadno(this.value);" class="chosen-select form-control">
<option value="0">------Select option------</option>
<?php
  $contact = $this->db->query("select * from tbl_leads where status='A' ORDER BY lead_number ");
  foreach ($contact->result() as $getContact) {    ?>
   <option value="<?=$getContact->lead_id; ?>"><?php echo $getContact->lead_number; ?></option>
 <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Contact Name : </label>
<input type="text" id="contact_person" class="form-control" readonly="">
<input type="hidden" name="contact_person" id="contact_personid" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Organization Name : </label>
<input type="text" id="org_name" class="form-control" readonly="">
<input type="hidden" name="org_name" id="org_nameid" class="form-control">
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
<!-- <label class="col-sm-2 control-label">Description</label> -->
<div class="col-sm-12">
<textarea name="summernote" id="summernote"></textarea>
<!-- <div id="summernote">Hello Summernote</div> -->
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
<!-- /Modal close-->


<!------Modal Task Manage Page Assign To---------->
<div class="modal fade" id="taskAssignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel">Update Task Assign To</h4>
<div id="resultuser" style="font-size: 15px;color: red; text-align:center;"></div> 
</div>
<form  id="TaskAssignForm"> 
<div class="modal-body">
<div class="tile-body slim-scroll--" style1="max-height: 350px;overflow:auto;">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div id="LEADINFO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">
<div class="row">
<div class="form-group col-md-6">
<label>Assign To : </label>
<input type="hidden" name="taskassignid" id="taskassignid" value="">
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
<!-- Modal Task Manage Page Assign To close-->


<!-- Change Task Status -->
<div class="modal fade" id="modalTaskStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span>Change Task Status</span></h4>
<div id="resultstatus" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="changeTaskStatusForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">

<div class="row">

<div class="form-group col-md-6">
<label for="email">New Status : </label>
<select name="new_status" id="new_status" class="form-control">
<option value="">----select----</option>
<?php 
  if($this->session->userdata('role') == 1){
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='21' ");  
  }
  
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php //if($fetchPrio->serial_number == $result->task_status) {?><?php //} ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
<input type="hidden" name="task_status_id" id="task_status_id" value="">
</div>

</div>



</div>
</div>
<!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="tasks_save" class="btn btn-primary">Save</button>
<span id="task_load" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div><!-- Change Task Status Close -->


<input type="text" style="display:none;" id="table_name" value="tbl_task">  
<input type="text" style="display:none;" id="pri_col" value="task_id">

<?php $this->load->view('footer.php'); ?>


<script type="text/javascript">

function myfunLeadno(lid)
{
  //alert(lid);
  ur = "ajaxget_leadAlldata";
    $.ajax({
        type : "POST",
        url  :  ur,
        data :  {'id':lid}, // serializes the form's elements.
          success : function (data) {
            //alert(data); // show response from the php script.
           if(data != false){
               //console.log(data);
               data = JSON.parse(data);
               $('#contact_person').val(data.contact_name);
               $('#org_name').val(data.org_name);
               $('#contact_personid').val(data.contact_id);
               $('#org_nameid').val(data.org_id);
           } else {
               $('#contact_person').val('');
               $('#org_name').val('');
               $('#contact_personid').val('');
               $('#org_nameid').val('');
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



function chkDate()
{
   var dd = $("#due_date").val();
   var rd = $("#reminder_date").val();
   if( rd > dd)
   {
    alert("Reminder date should be less than due date !")
    $("#reminder_date").val('');
   }
}


function assignid(v)
 {    
    $("#taskassignid").val(v);
 } 

 function taskstatus(v)
{    
  //alert(v);
  $("#task_status_id").val(v);
}


function readUnreadTask(value)
{
  //alert(value);
  var ur = "ajax_readUnreadTask";
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

function ResetTask()
{
  location.href="<?=base_url('/task/Task/manage_task');?>";
}

</script>
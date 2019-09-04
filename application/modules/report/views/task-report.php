<?php $this->load->view('header.php');

$entries = ""; $filter= "";
if($this->input->get('entries')!="")
{
  $entries = $this->input->get('entries');
}
if($this->input->get('filter')!="")
{
  $filter = $this->input->get('filter');
}


?>

<div id="ajax_content"> <!-- ajax_content -->

<section id="content">
<div class="page page-full__ page-mail">
<div class="tbox tbox-sm">

<div class="tcol">
<div class="p-15 bg-white b-b">
<div class="btn-toolbar">
<h3>Task Report</h3>
</div>
</div><!--p-15 bg-white b-b close-->


<div class="row">
<div class="col-md-12">
<section class="tile" >
<ul class="nav nav-tabs mt-20_ mb-20">
<li><a href="<?=base_url('report/Report/manage_report');?>">Lead</a></li>
<li class="active"><a href="<?=base_url('report/Report/searchTask'); ?>">Task</a></li>
<!-- <li><a href="#">Report</a></li>
<li><a href="#">Report</a></li> -->
</ul>

<div class="pageheader tile-bg">
<span>TASK</span>
<div class="page-bar">

<?php if($this->session->userdata('role')!='1') { ?>
<!-- <ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" id="filter" name="filter" url="<?=base_url('report/Report/searchTask?');?>">
<option value="4" >All Task </option>
<option value="1" <?=$filter=='1'?'selected':'';?> >My Task</option>
<option value="2" <?=$filter=='2'?'selected':'';?> >Assigned To Me</option>
<option value="3" <?=$filter=='3'?'selected':'';?> >Assigned By Me</option>
</select>
</div>
</div>
</ul> -->
<?php } ?>


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

<?php if($this->session->userdata('role')=='1') { ?>
<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline" name="user" ><option value="">--Select User--</option>
<?php
$usr=$this->db->query("select * from tbl_user_mst where status='A'");
foreach($usr->result() as $getUsr) {
  $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsr->brnh_id' ");
  $getBranch=$branch->row();
 ?>
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['user']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")";?> </option>
<?php } ?></select>
</div>
</div>
</ul>
<?php } ?>

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


<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group" style="width: 150px;">
From <div class='input-group date datetimepicker_report'>
<input type='text' class="input-sm form-control" name="from_date" value="<?=$_GET['from_date'];?>" />
<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
</div>
</div>
</div>
</ul>

<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group" style="width: 150px;">
To <div class='input-group date datetimepicker_report'>
<input type='text' class="input-sm form-control" name="to_date" value="<?=$_GET['to_date'];?>" />
<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
</div>
</div>
</div>
</ul>

<button class="btn btn-sm btn-default" type="submit" style="margin: -23px 0px 0px 5px;" value="search" name="search">Search</button> 
<button class="btn btn-sm btn-default" type="reset"  onclick="ResetTaskReport();" style="margin: -23px 0px 0px 5px;">Reset</button>
</form>

    <!-- combination search end -->

</div>
</div><!--pageheader close-->


<div id="listingData"> <!-- listdataid -->
<div class="tile-widget-to tile-widget-top">
<div class="show-entries">
<div class="row">
<div class="col-sm-5">
<div style="line-height:30px;">
Show
<select class="btn btn-default btn-sm" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" id="entries"  url="<?=base_url('report/Report/searchTask').'?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&search='.$_GET['search'];?>">
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
  <a class="btn btn-sm btn-default" type="button" href="<?=base_url('report/Report/task_excel').'?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&search='.$_GET['search'];?>">Excel</a> 
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
<table class="table mb-0" id="tblData">
<thead>
  <tr>
     <!--  <th>S. No.</th> -->
      <th>Task Name</th>
      <th>Task Owner</th>
      <th>Lead Name</th>
      <!-- <th>Reminder Date</th> -->
      <th>Due Date</th>
      <!-- <th>Description</th> -->
      <th>Priority</th>
      <th>Task Status</th>
      <th>Assign To</th>
      <!-- <th style="text-align: center;">Progress %</th> -->

  </tr>
</thead>

<tbody id="dataTable">
<?php
$i=1;
foreach($result as $fetch_list){
?>
<tr>
<!-- <td><?=$i++;?></td> -->
<td><?php 
  $sqlstatus=$this->db->query("select * from tbl_master_data where serial_number='$fetch_list->task_name'");
  $fecthStatus = $sqlstatus->row();
  echo $fecthStatus->keyvalue; ?>
</td>
<td><?php
  $townr = $this->db->query("select * from tbl_user_mst where user_id='".$fetch_list->maker_id."' ");
  $getTownr = $townr->row();
  echo $getTownr->user_name; ?>
</td>
<td><?php 
 $Ltask = $this->db->query("select * from tbl_leads where lead_id='".$fetch_list->lead_id."' ");
 $getLtask = $Ltask->row();
 echo $getLtask->lead_number; ?>
</td>
<!-- <td><?=$fetch_list->reminder_date; ?></td> -->
<td><?=$fetch_list->date_due; ?></td>
<!-- <td>
<div class="tooltip-col">
<?php 
   $big = $fetch_list->description;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td> -->
<td><?php
  $sqlstatus=$this->db->query("select * from tbl_master_data where serial_number='$fetch_list->priority'");
  $fecthStatus = $sqlstatus->row();
  echo $fecthStatus->keyvalue; ?>
</td>
<td><?php
  $sqlstatus=$this->db->query("select * from tbl_master_data where serial_number='$fetch_list->task_status'");
  $fecthStatus = $sqlstatus->row();
  echo $fecthStatus->keyvalue; ?>
</td> 
<td><?php
  $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$fetch_list->user_resp' ");
  $res11 = $sqlgroup1->row();
  echo  $res11->user_name;?>
</td>
<!-- <td>
<div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 100px; margin-right: 5px">
<div class="progress-bar progress-bar-greensea" role="progressbar" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100" style="width: <?=$fetch_list->progress_percnt?>%;"></div>
</div>
<small><?=$fetch_list->progress_percnt?>%</small>
</td> -->

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
</div>
</div>
</section>

</div> <!-- ajax_content -->
<?php $this->load->view('footer.php'); ?>


<script type="text/javascript">

function exportTableToExcel(tableID,filename=''){
 
    //alert();
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'Task Report_<?php echo date('d-m-Y');?>.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{

        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}


function ResetTaskReport()
{
  location.href="<?=base_url('/report/Report/searchTask');?>";
}

</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.en.min.js"></script>
<script >
$(function () {
$('.datetimepicker_report').datepicker({
format: "dd/mm/yyyy",
language: "en",
autoclose: true,
todayHighlight: true
});
});
</script>

<style>
@import url("https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker3.css");

.input-group.date .input-group-addon {
    cursor: pointer;
    padding: 0 10px 0 10px;
    /* margin: 30px 20px 20px 30px; */
    line-height: initial;
}
</style>

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

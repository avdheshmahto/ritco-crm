<?php
@extract($_GET);
$contents="Task Name,Task Owner,Lead Name,Due Date,Priority,Task Status,Assign To \n";	


@extract($_GET);

if($_GET['search'] !='search')
{

	//==================Software Log===============

	  $in = array();
	  $out = array();

	  $slog = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND maker_id='".$this->session->userdata('user_id')."' ");
	  $numCnt=$slog->num_rows();

			  foreach($slog->result() as $slogdata)
			  {

			    $oldusr = $slogdata->slog_id;
			    array_push($in,$oldusr);

			  }
			  
			  if($numCnt > 0)
			    {
			    	$oldData= implode(', ', $in);
			    }
			    else
			    {
			    	$oldData='9999999';
			    }

	    $slog1 = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$this->session->userdata('user_id')."' ");
	    $numCnt1=$slog1->num_rows();

	    	  foreach($slog1->result() as $slogdata1)
			  {
			    $newusr = $slogdata1->slog_id; 
			    array_push($out,$newusr); 	    
			  }
			  
			  if($numCnt1 > 0)
			    {
			    	$newData= implode(', ', $out);
			    }
			    else
			    {
			    	$newData='9999999';
			    }

  //======================End=========================
	if($this->session->userdata('role') == 1)
	{
 		//$query=("select T.*,C.contact_id,C.contact_name as cname,O.org_id,O.org_name as oname from tbl_task T,tbl_contact_m C,tbl_organization O where (C.contact_id=T.contact_person OR T.contact_person='') AND (O.org_id=T.org_name OR T.org_name='') AND T.status='A' Order by T.task_id DESC limit $strat,$last ");
 		$query=("select * from tbl_task where status='A' Order by task_id DESC ");
 	}
 	else
 	{
 		$query=("select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) Order by task_id DESC ");
 	}
	
	$getQuery = $this->db->query($query);
   
    $result=$getQuery->result();
  
}

if($_GET['search'] == 'search')
{

 	//==================Software Log===============

	  $in = array();
	  $out = array();

	  $slog = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND maker_id='".$this->session->userdata('user_id')."' ");
	  $numCnt=$slog->num_rows();

			  foreach($slog->result() as $slogdata)
			  {

			    $oldusr = $slogdata->slog_id;
			    array_push($in,$oldusr);

			  }
			  
			  if($numCnt > 0)
			    {
			    	$oldData= implode(', ', $in);
			    }
			    else
			    {
			    	$oldData='9999999';
			    }

	    $slog1 = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$this->session->userdata('user_id')."' ");
	    $numCnt1=$slog1->num_rows();

	    	  foreach($slog1->result() as $slogdata1)
			  {
			    $newusr = $slogdata1->slog_id; 
			    array_push($out,$newusr); 	    
			  }
			  
			  if($numCnt1 > 0)
			    {
			    	$newData= implode(', ', $out);
			    }
			    else
			    {
			    	$newData='9999999';
			    }

  //======================End=========================
    
    if($this->session->userdata('role') == 1)
	{
    	$qry = "select * from tbl_task where status='A'";
    }
    else
 	{
 		$qry = "select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) )";
 	}			    


	 if($this->input->get('filter') == '1')
	 {
       $qry .= " AND maker_id='".$this->session->userdata('user_id')."' ";
     }
     if($this->input->get('filter') == '2')
	 {
       $qry .= " AND task_id in ($newData) ";
     }
     if($this->input->get('filter') == '3')
	 {
       $qry .= " AND task_id in ($oldData) ";
     }
  //    if($this->input->get('filter') == '4')
	 // {
  //      $qry .= " AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) ";
  //    }
     //=========================User Wise Filter==============

        $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$_GET['user']."' ");
	    $numCount=$usrfltr->num_rows();

	    	  $allusr = array();
	    	  foreach($usrfltr->result() as $getUsr)
			  {
			    $newids = $getUsr->slog_id; 
			    array_push($allusr,$newids); 	    
			  }
			  
			  if($numCount > 0)
			    {
			    	$allTaskIDs= implode(', ', $allusr);
			    }
			    else
			    {
			    	$allTaskIDs='9999999';
			    }

		//===============End==========================


     if($this->input->get('user') != '')
	 {
       $qry .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['user']."') ";
     }

     if($this->input->get('status') != '')
	 {
       $qry .= " AND  task_status='".$_GET['status']."' ";
     }

     if($this->input->get('from_date') != '' && $this->input->get('to_date') != '')
	 {
	
		$fdate=explode("/",$this->input->get('from_date'));
		
		$tdate=explode("/",$this->input->get('to_date'));

		$fdate1=$fdate[2]."-".$fdate[1]."-".$fdate[0];
		$todate1=$tdate[2]."-".$tdate[1]."-".$tdate[0];
        
		$qry .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
	 }

     	$qry.=" Order by task_id DESC";
		   
	  //$qry .=" LIMIT $pages,$perpage ";
    $result =  $this->db->query($qry)->result();
//  return $data;

}		

$i=1;
   
foreach($result as $line){

$task=$this->db->query("select * from tbl_master_data where serial_number='$line->task_name'");
$getTask=$task->row();

$usr=$this->db->query("select * from tbl_user_mst where user_id='$line->maker_id' ");
$getUsr=$usr->row();

$ldnm=$this->db->query("select * from tbl_leads where lead_id='$line->lead_id' ");
$getLdnm=$ldnm->row();

$priority=$this->db->query("select * from tbl_master_data where serial_number='$line->priority'");
$getPriority=$priority->row();

$task_status=$this->db->query("select * from tbl_master_data where serial_number='$line->task_status'");
$getTask_status=$task_status->row();

$user_resp=$this->db->query("select * from tbl_user_mst where user_id='$line->user_resp'");
$getUser_resp=$user_resp->row();
						
$contents.=str_replace(',',' ',$getTask->keyvalue).",";
$contents.=str_replace(',',' ',$getUsr->user_name).",";
$contents.=str_replace(',',' ',$getLdnm->lead_number).",";
//$contents.=str_replace(',',' ',$line->reminder_date).",";
$contents.=str_replace(',',' ',$line->date_due).",";
$contents.=str_replace(',',' ',$getPriority->keyvalue).",";
$contents.=str_replace(',',' ',$getTask_status->keyvalue).",";
$contents.=str_replace(',',' ',$getUser_resp->user_name).",\n";
//$contents.=str_replace(',',' ','Quotation').",\n";

$i++;
}  
//=================================================================================

$filename = "Task"."_".@date('Y-m-d');
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . @date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
print $contents;
exit;

?>		
	

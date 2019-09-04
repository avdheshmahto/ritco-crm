<?php
class model_task extends CI_Model {

//====================Task Data =============

function getTaskData($last,$strat)
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
 		$query=("select * from tbl_task where status='A' Order by last_update DESC limit $strat,$last ");
 	}
 	else
 	{
 		//$query=("select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) Order by last_update DESC limit $strat,$last ");
 		$query=("select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR user_resp='".$this->session->userdata('user_id')."' ) Order by last_update DESC limit $strat,$last ");
 	}
	
	$getQuery = $this->db->query($query);
   
    return $result=$getQuery->result();
  
}


function filterTaskData($perpage,$pages,$get)
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
 		$qry = "select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR user_resp='".$this->session->userdata('user_id')."' )";
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

     	$qry.=" Order by last_update DESC";
		   
	  $qry .=" LIMIT $pages,$perpage ";
    $data =  $this->db->query($qry)->result();
  return $data;

}


function count_task($tableName,$status = 0,$get)
{

	//$qry ="select count(*) as countval from $tableName where status='A' ";
 	
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
 		$qry=("select count(*) as countval from $tableName where status='A'  ");
 	}
 	else
 	{
 		$qry=("select count(*) as countval from $tableName where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR user_resp='".$this->session->userdata('user_id')."' ) ");
 	}			    

	 if($this->input->get('filter') == '1')
	 {
       $qry .= " AND maker_id='".$this->session->userdata('user_id')."' ";
     }
     if($this->input->get('filter') == '2')
	 {
       $qry .= " AND task_id in ($newData)  ";
     }
     if($this->input->get('filter') == '3')
	 {
       $qry .= " AND task_id in ($oldData) ";
     }
  //    if($this->input->get('filter') == '4')
	 // {
  //      $qry .= " AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) ";
  //    }
     /*if($this->input->get('filter') == '' )
     {
     	$qry .= " AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) ";
     }*/
     //=========================User Wise Filter==============

        $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND (new_id='".$_GET['user']."' OR maker_id='".$_GET['user']."' ) ");
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
    
      
   $query=$this->db->query($qry)->result_array();
   return $query[0]['countval'];

}


//========================Pagination End==========================


function getTaskDtl($id)
{
	//$tsk  = $this->db->query("select T.*,C.contact_id,C.contact_name,O.org_id,O.org_name from tbl_task T,tbl_contact_m C,tbl_organization O where C.contact_id=T.contact_person AND O.org_id=T.org_name AND task_id='$id' ");
	$tsk  = $this->db->query("select * from tbl_task where task_id='$id' ");
	return $result  = $tsk->row();
}

function getNotes($id)
{ 
  //$nid = $this->input->get('id');
  $notes = $this->db->query("select * from tbl_note where note_logid='$id' AND note_type='Task' ");
  return $result = $notes->result();
}

function getFiles($id)
{
  $files = $this->db->query("select * from tbl_file where file_logid='$id' AND file_type='Task' ");
  return $result = $files->result();
}
	
} ?>
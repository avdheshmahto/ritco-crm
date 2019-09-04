<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);

class model_report extends CI_Model {


//================================Lead====================================


function getLead($last,$strat)
{

	//==================Software Log===============

	  $in = array();
	  $out = array();

	  $slog = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND maker_id='".$this->session->userdata('user_id')."' ");
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

	    $slog1 = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$this->session->userdata('user_id')."' ");
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
	
		$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' Order by L.lead_id DESC limit $strat,$last");	
	}	
	else
	{
		$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.lead_id in ($oldData) OR L.lead_id in ($newData) ) Order by L.lead_id DESC limit $strat,$last");
	}	    
    

	$getQuery = $this->db->query($query);
   
    return $result=$getQuery->result();
  
}


function filterLeadList($perpage,$pages,$get)
{

 	
 	//==================Software Log===============

	  $in = array();
	  $out = array();

	  $slog = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND maker_id='".$this->session->userdata('user_id')."' ");
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

	    $slog1 = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$this->session->userdata('user_id')."' ");
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
 	  $qry = "select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' ";
 	}
 	else
	{
	  $qry = "select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.lead_id in ($oldData) OR L.lead_id in ($newData) ) ";
	}

	 if($this->input->get('filter') == '1')
	 {
       $qry .= " AND L.maker_id='".$this->session->userdata('user_id')."' ";
     }
     if($this->input->get('filter') == '2')
	 {
       $qry .= " AND L.lead_id in ($newData) ";
     }
     if($this->input->get('filter') == '3')
	 {
       $qry .= " AND L.lead_id in ($oldData) ";
     }
     
     // if($this->input->get('filter') == '4')
	 // {
     //   $qry .= " AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.lead_id in ($oldData) OR L.lead_id in ($newData) ) ";
     // }

     //=========================User Wise Filter==============

        $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$_GET['user']."' ");
	    $numCount=$usrfltr->num_rows();

	    	  $allusr = array();
	    	  foreach($usrfltr->result() as $getUsr)
			  {
			    $newids = $getUsr->slog_id; 
			    array_push($allusr,$newids); 	    
			  }
			  
			  if($numCount > 0)
			    {
			    	$allLeadIDs= implode(', ', $allusr);
			    }
			    else
			    {
			    	$allLeadIDs='9999999';
			    }

		//===============End==========================


     if($this->input->get('user') != '')
	 {
       $qry .= " AND (L.lead_id in ($allLeadIDs) OR L.maker_id='".$_GET['user']."') ";
     }

     if($this->input->get('status') != '')
	 {
       $qry .= " AND  L.lead_state='".$_GET['status']."' ";
     }

     if($this->input->get('stage') != '')
	 {
       $qry .= " AND  L.stage='".$_GET['stage']."' ";
     }

     if($this->input->get('from_date') != '' && $this->input->get('to_date') != '')
		{
		
			$fdate=explode("/",$this->input->get('from_date'));
			
			$tdate=explode("/",$this->input->get('to_date'));

			$fdate1=$fdate[2]."-".$fdate[1]."-".$fdate[0];
			$todate1=$tdate[2]."-".$tdate[1]."-".$tdate[0];
	        
			$qry .=" AND L.maker_date >='$fdate1' AND L.maker_date <='$todate1'";
		}


	   
	   $qry .=" Order by L.lead_id DESC";	   
	  $qry .=" LIMIT $pages,$perpage ";

	  //echo $qry;

    $data =  $this->db->query($qry)->result();
  return $data;



}

function count_lead($tableName,$get)
{

	  
    //$qry ="select count(*) as countval from $tableName where status='A' ";
 	
	//==================Software Log===============

	  $in = array();
	  $out = array();

	  $slog = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND maker_id='".$this->session->userdata('user_id')."' ");
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

	    $slog1 = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$this->session->userdata('user_id')."' ");
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
	
		$qry=("select count(*) as countval from $tableName where status='A'");	
	}	
	else
	{
		$qry=("select count(*) as countval from $tableName where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($oldData) OR lead_id in ($newData) ) ");
	}


	 if($this->input->get('filter') == '1')
	 {
       $qry .= " AND maker_id='".$this->session->userdata('user_id')."' ";
     }
     if($this->input->get('filter') == '2')
	 {
       $qry .= " AND lead_id in ($newData) ";
     }
     if($this->input->get('filter') == '3')
	 {
       $qry .= " AND lead_id in ($oldData) ";
     }
    
     // if($this->input->get('filter') == '4')
	 // {
     //  $qry .= " AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($oldData) OR lead_id in ($newData) ) ";
     // }
     /*if($this->input->get('filter') == '' )
     {
     	$qry .= " AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($oldData) OR lead_id in ($newData) ) ";
     }*/

     //=========================User Wise Filter==============

        $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND (new_id='".$_GET['user']."' OR maker_id='".$_GET['user']."' ) ");
	    $numCount=$usrfltr->num_rows();

	    	  $allusr = array();
	    	  foreach($usrfltr->result() as $getUsr)
			  {
			    $newids = $getUsr->slog_id; 
			    array_push($allusr,$newids); 	    
			  }
			  
			  if($numCount > 0)
			    {
			    	$allLeadIDs= implode(', ', $allusr);
			    }
			    else
			    {
			    	$allLeadIDs='9999999';
			    }

		//===============End==========================


     if($this->input->get('user') != '')
	 {
       $qry .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['user']."') ";
     }

     if($this->input->get('status') != '')
	 {
       $qry .= " AND  lead_state='".$_GET['status']."' ";
     }

     if($this->input->get('stage') != '')
	 {
       $qry .= " AND  stage='".$_GET['stage']."' ";
     }

     if($this->input->get('from_date') != '' && $this->input->get('to_date') != '')
	 {
	
		$fdate=explode("/",$this->input->get('from_date'));
		
		$tdate=explode("/",$this->input->get('to_date'));

		$fdate1=$fdate[2]."-".$fdate[1]."-".$fdate[0];
		$todate1=$tdate[2]."-".$tdate[1]."-".$tdate[0];
        
		$qry .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
	 }
    
      
   $query=$this->db->query($qry)->result_array();
   return $query[0]['countval'];



}


//===================Task===================

function getTaskList($last,$strat)
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
 		$query=("select * from tbl_task where status='A' Order by task_id DESC limit $strat,$last ");
 	}
 	else
 	{
 		$query=("select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) Order by task_id DESC limit $strat,$last ");
 	}
	
	$getQuery = $this->db->query($query);
   
    return $result=$getQuery->result();
  
}

function filterTaskList($perpage,$pages,$get)
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
		   
	  $qry .=" LIMIT $pages,$perpage ";
    $data =  $this->db->query($qry)->result();
  return $data;

}

function count_task($tableName,$get)
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
 		$qry=("select count(*) as countval from $tableName where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($oldData) OR task_id in ($newData) ) ");
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

     if($this->input->get('from_date') != '' && $this->input->get('to_date') != '')
	 {
	
		$fdate=explode("/",$this->input->get('from_date'));
		
		$tdate=explode("/",$this->input->get('to_date'));

		$fdate1=$fdate[2]."-".$fdate[1]."-".$fdate[0];
		$todate1=$tdate[2]."-".$tdate[1]."-".$tdate[0];
        
		$qry .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
	 }
    
      
   $query=$this->db->query($qry)->result_array();
   return $query[0]['countval'];

}




}?>
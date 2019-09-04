<?php
class model_lead extends CI_Model {
	

function getLeadData($last,$strat)
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
	
		$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' Order by L.last_update DESC limit $strat,$last");	
	}	
	else
	{
		//$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.lead_id in ($oldData) OR L.lead_id in ($newData) ) Order by L.last_update DESC limit $strat,$last");
		$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.user_resp='".$this->session->userdata('user_id')."' ) Order by L.last_update DESC limit $strat,$last");
	}	    
    

	$getQuery = $this->db->query($query);
   
    return $result=$getQuery->result();
  
}


function filterLeadData($perpage,$pages,$get)
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
	  $qry = "select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.user_resp='".$this->session->userdata('user_id')."') ";
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
  //    if($this->input->get('filter') == '4')
	 // {
  //      $qry .= " AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.lead_id in ($oldData) OR L.lead_id in ($newData) ) ";
  //    }

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
	   
	   $qry .=" ORDER BY L.last_update DESC";	   
	  $qry .=" LIMIT $pages,$perpage ";

	 // echo $qry;

    $data =  $this->db->query($qry)->result();
  return $data;

}


function count_leads($tableName,$status = 0,$get)
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
		$qry=("select count(*) as countval from $tableName where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR user_resp='".$this->session->userdata('user_id')."' ) ");
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
  //    if($this->input->get('filter') == '4')
	 // {
  //      $qry .= " AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($oldData) OR lead_id in ($newData) ) ";
  //    }
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
    
      
   $query=$this->db->query($qry)->result_array();
   return $query[0]['countval'];

}



//================================Pagination end================================

function getLeadDtl($id)
{
	$org  = $this->db->query("select L.*,C.contact_id,C.contact_name,C.email as cemail,C.phone as cphone,C.address as caddress,O.org_id,O.org_name from tbl_leads L,tbl_contact_m C,tbl_organization O where lead_id='$id' AND C.contact_id=L.contact_id AND O.org_id=L.org_id  ");
	return $result  = $org->row();	
}

function getNotes($id)
{	
	//$nid = $this->input->get('id');
	$notes = $this->db->query("select * from tbl_note where note_logid='$id' AND note_type='Lead' ");
	return $result = $notes->result();
}

function getFiles($id)
{
	$files = $this->db->query("select * from tbl_file where file_logid='$id' AND file_type='Lead' ");
	return $result = $files->result();
}



function getMorgz($id)
{
	$multiOrgz = $this->db->query("select * from tbl_mulit_orgz where morg_logid='$id' AND morg_type='Lead' ");
	return $result = $multiOrgz->result();
}

function getTask($id)
{
	$leadTask = $this->db->query("select * from tbl_task where lead_id='$id' ");
	return $result = $leadTask->result();
}

}?>
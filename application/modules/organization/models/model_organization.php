<?php
class model_organization extends CI_Model {

//====================Task Data =============

function getOrgkData($last,$strat)
{
	
	// if($this->session->userdata('user_id')=='1')
	// {
	 	$query=("select * from tbl_organization where status='A' Order by org_id DESC limit $strat,$last ");
	// }
	// else
	// {
	// 	$query=("select * from tbl_tour where sales_person_id='".$this->session->userdata('user_id')."' Order by tour_id DESC limit $strat,$last ");
	// }
	
	$getQuery = $this->db->query($query);
   
    return $result=$getQuery->result();
  
}


function filterOrgData($perpage,$pages,$get)
{

 // 	if($this->session->userdata('user_id')=='1')
	// {
       $qry = "select * from tbl_organization where status='A' ";
 //    }
	// else
	// {
	//    $qry = "select * from tbl_task where sales_person_id='".$this->session->userdata('user_id')."' ";
	// }
	   // if(sizeof($get) > 0)
	   // {}
 		
		$qry .=" LIMIT $pages,$perpage ";
    $data =  $this->db->query($qry)->result();
  return $data;

}


function count_org($tableName,$status = 0,$get)
{
	
	//if($this->session->userdata('user_id')=='1')
	//{
		$qry ="select count(*) as countval from $tableName where status='A' ";
	// }
	// else
	// {   
 //   		$qry ="select count(*) as countval from $tableName where sales_person_id='".$this->session->userdata('user_id')."' ";
 //    }
      
	 // if(sizeof($get) > 0)
	 //   {}

   $query=$this->db->query($qry,array($status))->result_array();
   return $query[0]['countval'];
}

function getOrgDtl($id)
{
	$org  = $this->db->query("select * from tbl_organization where org_id='$id' ");
	return $result  = $org->row();	
}

function getNotes($id)
{ 
  //$nid = $this->input->get('id');
  $notes = $this->db->query("select * from tbl_note where note_logid='$id' AND note_type='Orgz' ");
  return $result = $notes->result();
}

function getFiles($id)
{
  $files = $this->db->query("select * from tbl_file where file_logid='$id' AND file_type='Orgz' ");
  return $result = $files->result();
}
	
}?>
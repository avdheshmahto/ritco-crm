<?php
class model_contact extends CI_Model {


//=============================Contact Master===============================

function contact_get($last,$strat)
{
	$query=$this->db->query("select * from tbl_contact_m where status='A' ORDER BY contact_id DESC limit $strat,$last");
    return $result=$query->result();  
}

function filterContactList($perpage,$pages,$get)
{
 
      $qry = "select * from tbl_contact_m where status = 'A'";
	  
 	 $qry .=" LIMIT $pages,$perpage";
  $data =  $this->db->query($qry)->result();
 return $data;

}

function count_contact($tableName,$status = 0,$get)
{
  
       $qry ="select count(*) as countval from $tableName where status='A'";
  
  	$query=$this->db->query($qry,array($status))->result_array();
  
  return $query[0]['countval'];

}

function getContactDtl($id)
{
		$contact   =  $this->db->query("select * from tbl_contact_m where contact_id='$id' ");
	  return $result  =  $contact->row();	
}

function getNotes($id)
{ 
  //$nid = $this->input->get('id');
  $notes = $this->db->query("select * from tbl_note where note_logid='$id' AND note_type='Contact' ");
  return $result = $notes->result();
}

function getFiles($id)
{
  $files = $this->db->query("select * from tbl_file where file_logid='$id' AND file_type='Contact' ");
  return $result = $files->result();
}

function getMorgz($id)
{
  $multiOrgz = $this->db->query("select * from tbl_mulit_orgz where morg_logid='$id' AND morg_type='Contact' ");
  return $result = $multiOrgz->result();
}

}?>
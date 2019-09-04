<?php
class model_master extends CI_Model {



 public function getuserlist()
 {
 	  
 	  if($this->session->userdata('role') == 1)
         $query = $this->db->query("select * from tbl_user_mst  ORDER BY user_id DESC");
      else
      	 $query = $this->db->query("select * from tbl_user_mst where status='A' AND (maker_id = '".$this->session->userdata('user_id')."' OR  user_id = '".$this->session->userdata('user_id')."')");
      
    return $result = $query->result();  
 }

 public function getEditUsers($id)
 {
       $query = $this->db->query("select * from tbl_user_mst where status='A' AND user_id = $id");
       return $result = $query->row();
 }

public function geCatglist($pid)
{	
	//$pid = $_GET['param_id'];
	$query = $this->db->query("select * from tbl_master_data where param_id='$pid' ORDER BY serial_number DESC  ");
	return $result = $query->result();
}


public function getBranch()
{
  $branch = $this->db->query("select * from tbl_branch_mst where status='A' ORDER BY brnh_id DESC ");
  return $result = $branch->result();
}
	

//get lead data 
public function get_all_lead_status($userleadstatus) 
{   

    //=========================User Wise Filter==============

      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='$userleadstatus' ");
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

    if($userleadstatus != '')
    {
      $leadchart = "select *,count('lead_state') as countval from tbl_leads where status='A' ";
      $leadchart .= " AND (lead_id in ($allLeadIDs) OR maker_id='$userleadstatus') ";
      $leadchart .= " GROUP BY lead_state";
    } 
    else
    {
      $leadchart= "select *,count('lead_state') as countval from tbl_leads GROUP BY lead_state ";
    }
    
    return $result = $this->db->query($leadchart)->result();        
} 

public function get_all_lead_stage($userleadstage) 
{ 

    //=========================User Wise Filter==============

      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='$userleadstage' ");
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

    if($userleadstage != '')
    {
      $stage= "select *,count('stage') as countval from tbl_leads where status='A'  ";
      $stage .= " AND (lead_id in ($allLeadIDs) OR maker_id='$userleadstage') ";
      $stage .= " GROUP BY stage";
    } 
    else
    {
      $stage= "select *,count('stage') as countval from tbl_leads GROUP BY stage ";
    }
    
    return $result = $this->db->query($stage)->result();        
}


function getdata_lead()
{
    $query = $this->db->query("select *,SUM(opp_value) AS sales,SUBSTRING_INDEX(SUBSTRING_INDEX(closuredate, ' ', 1), '/', 2) as exptdyear from tbl_leads GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(closuredate, '/', 2), ' ', -1) ");
    return $query->result_array();
}


// function getdata_task()
// {
//     $task= "select *,count('task_status') as countval from tbl_task GROUP BY task_status ";
//     return $result = $this->db->query($task)->result();
// }


} ?>

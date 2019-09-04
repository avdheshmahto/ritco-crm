<?php
@extract($_GET);
$contents="Organization Name,Contact Person Name,Phone,Email,Address,Lead Name,Lead Owner,Industry,Expted Closure Date,Lead Status,Lead Stage,Assign To \n";	


@extract($_GET);

if($_GET['search'] !='search')
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
	
		$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' Order by L.lead_id DESC ");	
	}	
	else
	{
		$query=("select L.*,C.contact_name,C.address as caddress,C.email as cemail,C.phone as cphone,O.org_name as oname from tbl_leads L,tbl_contact_m C,tbl_organization O where C.contact_id = L.contact_id AND O.org_id = L.org_id AND L.status='A' AND (L.maker_id='".$this->session->userdata('user_id')."' OR L.brnh_id='".$this->session->userdata('brnh_id')."' OR L.lead_id in ($oldData) OR L.lead_id in ($newData) ) Order by L.lead_id DESC ");
	}	    
    

	$getQuery = $this->db->query($query);
   
    $result=$getQuery->result();
  

}

if($_GET['search'] == 'search')
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

	 if($_GET['filter'] == '1')
	 {
       $qry .= " AND L.maker_id='".$this->session->userdata('user_id')."' ";
     }
     if($_GET['filter'] == '2')
	 {
       $qry .= " AND L.lead_id in ($newData) ";
     }
     if($_GET['filter'] == '3')
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


     if($_GET['user'] != '')
	 {
       $qry .= " AND (L.lead_id in ($allLeadIDs) OR L.maker_id='".$_GET['user']."') ";
     }

     if($_GET['status'] != '')
	 {
       $qry .= " AND  L.lead_state='".$_GET['status']."' ";
     }

     if($_GET['stage'] != '')
	 {
       $qry .= " AND  L.stage='".$_GET['stage']."' ";
     }

     if($_GET['from_date'] != '' && $_GET['to_date'] != '')
		{
		
			$fdate=explode("/",$_GET['from_date']);
			
			$tdate=explode("/",$_GET['to_date']);

			$fdate1=$fdate[2]."-".$fdate[1]."-".$fdate[0];
			$todate1=$tdate[2]."-".$tdate[1]."-".$tdate[0];
	        
			$qry .=" AND L.maker_date >='$fdate1' AND L.maker_date <='$todate1'";
		}


	   
	   $qry .=" Order by L.lead_id DESC";	   
	  //$qry .=" LIMIT $pages,$perpage ";

	  //echo $qry;

    $result =  $this->db->query($qry)->result();

}		

$i=1;
   
foreach($result as $line){


$org=$this->db->query("select * from tbl_organization where org_id='$line->org_id' ");
$getOrg=$org->row();

$cntct=$this->db->query("select * from tbl_contact_m where contact_id='$line->contact_id' ");
$getCntct=$cntct->row();

//$big = strip_tags($line->discription);
$usr=$this->db->query("select * from tbl_user_mst where user_id='$line->maker_id' ");
$getUsr=$usr->row();

$indst=$this->db->query("select * from tbl_master_data where serial_number='$line->industry'");
$getIndst=$indst->row();

$lead_state=$this->db->query("select * from tbl_master_data where serial_number='$line->lead_state'");
$getLead_state=$lead_state->row();

$stage=$this->db->query("select * from tbl_master_data where serial_number='$line->stage'");
$getStage=$stage->row();

$user_resp=$this->db->query("select * from tbl_user_mst where user_id='$line->user_resp'");
$getUser_resp=$user_resp->row();


$contents.=str_replace(',',' ',$getOrg->org_name).",";
$contents.=str_replace(',',' ',$getCntct->contact_name).",";
$contents.=str_replace(',',' ',$getCntct->phone).",";
$contents.=str_replace(',',' ',$getCntct->email).",";
$contents.=str_replace(',',' ',$getCntct->address).",";
//$contents.=str_replace(',',' ',$big).",";						
$contents.=str_replace(',',' ',$line->lead_number).",";
$contents.=str_replace(',',' ',$getUsr->user_name).",";
$contents.=str_replace(',',' ',$getIndst->keyvalue).",";
$contents.=str_replace(',',' ',$line->closuredate).",";
$contents.=str_replace(',',' ',$getLead_state->keyvalue).",";
$contents.=str_replace(',',' ',$getStage->keyvalue).",";
$contents.=str_replace(',',' ',$getUser_resp->user_name).",\n";
//$contents.=str_replace(',',' ','Quotation').",\n";

$i++;
}  
//=================================================================================

if($_GET['search'] == 'search')
{

	$filename="";
	if($_GET['filter'] == '1')
	{
		$nm = "My Leads";
		$filename .= $nm;
	}
	elseif($_GET['filter'] == '2')
	{
		$nm = "Assigned To Me";	
		$filename .= $nm;
	}
	elseif($_GET['filter'] == '3')
	{
		$nm = "Assigned By Me";
		$filename .= $nm;
	}
	elseif($_GET['filter'] == '4')
	{
		$nm = "All Leads";
		$filename .= $nm;
	}
	 

	if($_GET['user'] != '')
	{
        $usrs=$this->db->query("select * from tbl_user_mst where user_id='".$_GET['user']."' ");
		$getUsrs=$usrs->row();
		$brnch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsrs->brnh_id' ");
		$getBrnch=$brnch->row();
		$filename .= "-".$getUsrs->user_name."(".$getBrnch->brnh_name.")";
    }    

    if($_GET['status'] != '')
	{
        $sts=$this->db->query("select * from tbl_master_data where serial_number='".$_GET['status']."' ");
		$getSts=$sts->row();
		$filename .= "-".$getSts->keyvalue;
    }

    if($_GET['stage'] != '')
	{
        $stg=$this->db->query("select * from tbl_master_data where serial_number='".$_GET['stage']."' ");
		$getStg=$stg->row();
		$filename .= "-".$getStg->keyvalue;
    }

    if($_GET['from_date'] != '' && $_GET['to_date'] != '')
    {
    	$filename .= "-".$_GET['from_date']." To ".$_GET['to_date'];	
    }

	//$filename .="_".@date('Y-m-d');

}
else
{
	$filename = "Lead"."_".@date('Y-m-d');	
}	

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . @date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
print $contents;
exit;
?>		
	

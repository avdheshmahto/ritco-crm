<?php $this->load->view('header.php'); ?>

<div id="ajax_content"> <!-- ajax_content -->

<section id="content">
<div class="page page-forms-common">
<div class="pageheader">
<h2>Ritco Dashboard <span> </span></h2>
<div class="page-bar">
</div>
</div>

<?php if($this->session->userdata('role') == 1) { ?>
<div class="row" style="display: none;">
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-slategray">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-eye fa-4x"></i> </div>
<div class="col-xs-8">
<?php 
$usr=$this->db->query("select * from tbl_user_mst where status='A' ");
$getUsr=$usr->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getUsr;?></p>
<span>Active Users</span> </div>
</div>
</div>
</div>
</div>

<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-blue">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-eye-slash fa-4x"></i> </div>
<div class="col-xs-8">
<?php 
$inusr=$this->db->query("select * from tbl_user_mst where status='I' ");
$getInUsr=$inusr->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getInUsr;?></p>
<span>Inactive Users</span> </div>
</div>
</div>
</div>
</div>

<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-greensea">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-users fa-4x"></i> </div>
<div class="col-xs-8">
<?php
$brnch=$this->db->query("select * from tbl_branch_mst where status='A' ");
$getBrnch=$brnch->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getBrnch;?></p>
<span>Total Branch</span> </div>
</div>
</div>
</div>
</div>

<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-lightred">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-user fa-4x"></i> </div>
<div class="col-xs-8">
<?php
$profile=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A'");
$getProfile=$profile->num_rows();
?>	
<p class="text-elg text-strong mb-0"><?=$getProfile;?></p>
<span>Total Profile</span> </div>
</div>
</div>
</div>
</div>
</div><!--row close-->
<?php } ?>
<!-- -----------------------------Lead--------------------------- -->

<?php

    //==================Lead Software Log===============

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
			    	$LeadoldData= implode(', ', $in);
			    }
			    else
			    {
			    	$LeadoldData='9999999';
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
			    	$LeadnewData= implode(', ', $out);
			    }
			    else
			    {
			    	$LeadnewData='9999999';
			    }

  	//======================End=========================

?>

<div class="row">
<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Lead </strong>Stage</h1>
<ul class="controls">
<li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
<li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
</ul>
</li>
<li class="remove"><a role="button" tabindex="0" class="tile-close"><i class="fa fa-times"></i></a></li>
</ul>
</div><!-- /tile header -->
<?php 
/////////////////////////////////////Graph Calculation///////////////////////////////
if($_GET['filterstg'] == 'filterstg')
{

	$leads = "select *,count('stage') as countval from tbl_leads where status='A' ";

    if($_GET['daterangestg'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangestg']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$leads .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }
   
   if($_GET['userleadstage'] != '')
   {
	//=========================User Wise Filter==============

      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$_GET['userleadstage']."' ");
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

	  $leads .= "AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') ";
      
  }
  	$leads .= " GROUP BY stage";
  	$leadstg = $this->db->query($leads);

}
else
{
	if($this->session->userdata('role') == 1){
	  $leadstg=$this->db->query("select *,count('stage') as countval from tbl_leads where status='A' GROUP BY stage");	
	}
	else{
	  $leadstg=$this->db->query("select *,count('stage') as countval from tbl_leads where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) GROUP BY stage");	
	}
}

foreach ($leadstg->result() as $getLdStg) 
{ 


      $key=$this->db->query("select * from tbl_master_data where serial_number='$getLdStg->stage'");
      $getKeyValue=$key->row();

      $arr11[] = $getKeyValue->keyvalue;
      $arr22[] = (int)$getLdStg->countval;

      $arr33[]=array_merge($arr11,$arr22);  
      unset($arr11);
      unset($arr22);
     // continue;          
} 

?>
<?php 

/////////////////////////////////////Total Calculation///////////////////////////////
if($_GET['filterstg'] == 'filterstg')
{
	$ttld     ="select * from tbl_leads where status='A' ";
	$nwopp    ="select * from tbl_leads where status='A' and stage='17' ";
    $cnting   ="select * from tbl_leads where status='A' and stage='18' ";
    $rates    ="select * from tbl_leads where status='A' and stage='53' ";
    $clsd     ="select * from tbl_leads where status='A' and stage='78' ";    

    if($_GET['daterangestg'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangestg']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$ttld    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$nwopp   .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$cnting  .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$rates   .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$clsd    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }

	if($_GET['userleadstage'] != '')
	{

		//=========================User Wise Filter==============

	      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$_GET['userleadstage']."' ");
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


		$ttld .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') ";
		$nwopp .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') ";
		$cnting .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') ";
		$rates .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') ";
		$clsd .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') ";
	}

	//====================
	
	$getTotalLead=$this->db->query($ttld)->num_rows();
	$getNewOpp=$this->db->query($nwopp)->num_rows();
	$getCnting=$this->db->query($cnting)->num_rows();
	$getRates=$this->db->query($rates)->num_rows();
	$getClsd=$this->db->query($clsd)->num_rows();

	//====================

	//$lead .= " GROUP BY lead_state ";
	//$leadsts = $this->db->query($lead);
	//echo $lead;

}else{
		
		if($this->session->userdata('role') == 1){
		
		$ttld=$this->db->query("select * from tbl_leads where status='A' ");
		$getTotalLead=$ttld->num_rows();

		$nwopp=$this->db->query("select * from tbl_leads where status='A' and stage='17' ");
		$getNewOpp=$nwopp->num_rows();

		$cnting=$this->db->query("select * from tbl_leads where status='A' and stage='18' ");
		$getCnting=$cnting->num_rows();

		$rates=$this->db->query("select * from tbl_leads where status='A' and stage='53' ");
		$getRates=$rates->num_rows();

		$clsd=$this->db->query("select * from tbl_leads where status='A' and stage='78' ");
		$getClsd=$clsd->num_rows();
	
	}else{

		$ttld=$this->db->query("select * from tbl_leads where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
		$getTotalLead=$ttld->num_rows();

		$nwopp=$this->db->query("select * from tbl_leads where status='A' and stage='17' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
		$getNewOpp=$nwopp->num_rows();

		$cnting=$this->db->query("select * from tbl_leads where status='A' and stage='18' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
		$getCnting=$cnting->num_rows();

		$rates=$this->db->query("select * from tbl_leads where status='A' and stage='53' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
		$getRates=$rates->num_rows();

		$clsd=$this->db->query("select * from tbl_leads where status='A' and stage='78' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
		$getClsd=$clsd->num_rows();
	}

}

?>


<div class="tile-body">
<div class="row" style="display: none1;">	
<?php if($this->session->userdata('role') == 1) { ?>	
<form>
<div class="col-sm-7">
<div class="input-group">
<input type="text" class="form-control reportrange" name="daterangestg" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%" value="<?=$_GET['daterangestg'];?>">
<span class="input-group-addon">
 <span class="fa fa-calendar"></span>
</span>	
</div>
</div>

<div class="col-sm-4">
<div class="btn-group" style="margin: 0 0 0 -10px;">
<select class="chosen-select form-control" url="<?=base_url('master/master/manage_dashboard?');?>" id="userleadstage" name="userleadstage">
<option value="">--Select User--</option>
<?php
$usr=$this->db->query("select * from tbl_user_mst where status='A'");
foreach($usr->result() as $getUsr) {
  $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsr->brnh_id' ");
  $getBranch=$branch->row();
 ?>
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['userleadstage']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")" ;?> </option>
<?php } ?>
</select>
</div>	
</div>

<div class="col-sm-1" style="margin: 0 0 0 -25px;">
	<button class="btn btn-sm btn-default" type="submit" name="filterstg" value="filterstg">Go!</button>
</div>
</form>
<?php } ?>
</div>  <!-- row close -->

<?php if($this->session->userdata('role') == 1) {?>
<div class="pull-right" style="margin: 20px 10px 0px 0px; font-size: 11px;">
<?php } else {?>
<div class="pull-right" style="margin: 20px 5px 0px 0px; font-size: 11px;">
<?php } ?>	
<strong>Total Leads : </strong><?=$getTotalLead;?><br>
<strong>New Opportunity : </strong><?=$getNewOpp;?><br>
<strong>Contacting : </strong><?=$getCnting;?><br>
<strong>Rates/Quotation Submitted : </strong><?=$getRates;?><br>
<strong>Closed : </strong><?=$getClsd;?><br>
</div>	
<div id="jqChart1" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->

<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Lead </strong>Status</h1>

<ul class="controls">
<li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
<li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
</ul>
</li>
<li class="remove"><a role="button" tabindex="0" class="tile-close"><i class="fa fa-times"></i></a></li>
</ul>
</div><!-- /tile header -->


<?php 
/////////////////////////////////////Graph Calculation///////////////////////////////
if($_GET['filtersts'] == 'filtersts')
{

	$lead = "select *,count('lead_state') as countval from tbl_leads where status='A' ";

    if($_GET['daterangests'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangests']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$lead .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }

	if($_GET['userleadstatus'] != '')
	{

		//=========================User Wise Filter==============

	      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$_GET['userleadstatus']."' ");
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


		$lead .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') ";
	}


	$lead .= " GROUP BY lead_state ";
	$leadsts = $this->db->query($lead);
	//echo $lead;

}

else
{
	
	if($this->session->userdata('role') == 1){
	  $leadsts=$this->db->query("select *,count('lead_state') as countval from tbl_leads where status='A' GROUP BY lead_state ");
	}
	else{
	  $leadsts=$this->db->query("select *,count('lead_state') as countval from tbl_leads where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) GROUP BY lead_state ");
	}	

}


foreach ($leadsts->result() as $getLdSts) 
{ 


      $stkey=$this->db->query("select * from tbl_master_data where serial_number='$getLdSts->lead_state'");
      $getStKey=$stkey->row();

      $arr7[] = $getStKey->keyvalue;
      $arr8[] = (int)$getLdSts->countval;

      $arr9[]=array_merge($arr7,$arr8);  
      unset($arr7);
      unset($arr8);
     // continue;          
} 

?>  



<?php 
/////////////////////////////////////Total Calculation///////////////////////////////
if($_GET['filtersts'] == 'filtersts')
{
	$totalds ="select * from tbl_leads where status='A' ";
	$opn     ="select * from tbl_leads where status='A' and lead_state='65' ";
    $won     ="select * from tbl_leads where status='A' and lead_state='66' ";
    $lost    ="select * from tbl_leads where status='A' and lead_state='67' ";
    $frz     ="select * from tbl_leads where status='A' and lead_state='79' ";    

    if($_GET['daterangests'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangests']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$totalds .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$opn     .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$won     .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$lost    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$frz     .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }

	if($_GET['userleadstatus'] != '')
	{

		//=========================User Wise Filter==============

	      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' AND new_id='".$_GET['userleadstatus']."' ");
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


		$totalds .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') ";
		$opn .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') ";
		$won .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') ";
		$lost .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') ";
		$frz .= " AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') ";
	}

	//====================
	
	$getTtlLead=$this->db->query($totalds)->num_rows();
	$getOpn=$this->db->query($opn)->num_rows();
	$getWon=$this->db->query($won)->num_rows();
	$getLost=$this->db->query($lost)->num_rows();
	$getFreeze=$this->db->query($frz)->num_rows();

	//====================

	//$lead .= " GROUP BY lead_state ";
	//$leadsts = $this->db->query($lead);
	//echo $lead;

}

else
{
   if($this->session->userdata('role') == 1){
   	//=============
	$totalds=$this->db->query("select * from tbl_leads where status='A' ");
	$getTtlLead=$totalds->num_rows();

	$opn=$this->db->query("select * from tbl_leads where status='A' and lead_state='65' ");
	$getOpn=$opn->num_rows();

	$won=$this->db->query("select * from tbl_leads where status='A' and lead_state='66' ");
	$getWon=$won->num_rows();

	$lost=$this->db->query("select * from tbl_leads where status='A' and lead_state='67' ");
	$getLost=$lost->num_rows();

	$frz=$this->db->query("select * from tbl_leads where status='A' and lead_state='79' ");
	$getFreeze=$frz->num_rows();
	//=============
   
   }else{   	
   	
   	//=============
	$totalds=$this->db->query("select * from tbl_leads where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
	$getTtlLead=$totalds->num_rows();

	$opn=$this->db->query("select * from tbl_leads where status='A' and lead_state='65' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
	$getOpn=$opn->num_rows();

	$won=$this->db->query("select * from tbl_leads where status='A' and lead_state='66' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
	$getWon=$won->num_rows();

	$lost=$this->db->query("select * from tbl_leads where status='A' and lead_state='67' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
	$getLost=$lost->num_rows();

	$frz=$this->db->query("select * from tbl_leads where status='A' and lead_state='79' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR lead_id in ($LeadoldData) OR lead_id in ($LeadnewData) ) ");
	$getFreeze=$frz->num_rows();
	//=============
   }
	
}


?>

<div class="tile-body">
<div class="row" style="display: none1;">
<?php if($this->session->userdata('role') == 1) { ?>
<form>

<div class="col-sm-7">
<div class="input-group">
<input type="text" class="form-control reportrange" name="daterangests" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%" value="<?=$_GET['daterangests'];?>">
<span class="input-group-addon">
 <span class="fa fa-calendar"></span>
</span>	
</div>
</div>

<div class="col-sm-4">
<div class="btn-group" style="margin: 0 0 0 -10px;">
<select class="chosen-select form-control" url="<?=base_url('master/master/manage_dashboard?');?>" id="userleadstatus" name="userleadstatus">
<option value="">--Select User--</option>
<?php
$usr=$this->db->query("select * from tbl_user_mst where status='A'");
foreach($usr->result() as $getUsr) {
$branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsr->brnh_id' ");
$getBranch=$branch->row();
?>
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['userleadstatus']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")" ;?> </option>
<?php } ?>
</select>
</div>
</div>

<div class="col-sm-1" style="margin: 0 0 0 -25px;">
	<button class="btn btn-sm btn-default" type="submit" name="filtersts" value="filtersts">Go!</button>
</div>
</form>
<?php } ?>
</div><!--row close-->

<div class="pull-right" style="margin: 20px 10px 0px 0px; font-size: 11px;">
<strong>Total Leads : </strong><?=$getTtlLead;?><br>
<strong>Open : </strong><?=$getOpn;?><br>
<strong>Won : </strong><?=$getWon;?><br>
<strong>Lost : </strong><?=$getLost;?><br>
<!-- <strong>Freeze : </strong><?=$getFreeze;?><br> -->
</div>	
<div id="jqChart2" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->
</div><!--row close-->

<!-- -----------------------------Lead Close--------------------------- -->

<!-- -----------------------------Task--------------------------- -->

<?php

	//==================Task Software Log===============

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
			    	$TaskoldData= implode(', ', $in);
			    }
			    else
			    {
			    	$TaskoldData='9999999';
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
			    	$TasknewData= implode(', ', $out);
			    }
			    else
			    {
			    	$TasknewData='9999999';
			    }

  //======================End=========================

?>

<div class="row">
<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Task </strong></h1>
<ul class="controls">
<li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
<li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
</ul>
</li>
<li class="remove"><a role="button" tabindex="0" class="tile-close"><i class="fa fa-times"></i></a></li>
</ul>
</div><!-- /tile header -->
<?php 

/////////////////////////////////////Graph Calculation///////////////////////////////
if($_GET['filtertask'] == 'filtertask')
{

	$task = "select task_name,count('task_name') as countval from tbl_task where status='A' ";

    if($_GET['daterangetsk'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangetsk']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$task .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }
   
   if($_GET['usertask'] != '')
   {
	//=========================User Wise Filter==============

      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$_GET['usertask']."' ");
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

	  $task .= "AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
      
  }
  	$task .= " GROUP BY task_name";
  	$tasknm = $this->db->query($task);

}
else
{
	if($this->session->userdata('role') == 1){
	  $tasknm=$this->db->query("select task_name,count('task_name') as countval from tbl_task where status='A' GROUP BY task_name");	
	}
	else{
	  $tasknm=$this->db->query("select task_name,count('task_name') as countval from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) GROUP BY task_name");	
	}
}


//$tasknm=$this->db->query("select task_name,count('task_name') as countval from tbl_task GROUP BY task_name");


foreach ($tasknm->result() as $getTasknm) 
{ 


      $mstr=$this->db->query("select * from tbl_master_data where serial_number='$getTasknm->task_name'");
      $getMstKey=$mstr->row();

      $arr4[] = $getMstKey->keyvalue;
      $arr5[] = (int)$getTasknm->countval;

      $arr6[]=array_merge($arr4,$arr5);  
      unset($arr4);
      unset($arr5);
     // continue;          
  } 

?>
<?php 

/////////////////////////////////////Total Calculation///////////////////////////////
if($_GET['filtertask'] == 'filtertask')
{
	$ttltask  ="select * from tbl_task where status='A' ";
	$phncall  ="select * from tbl_task where status='A' and task_name='23' ";
    $email    ="select * from tbl_task where status='A' and task_name='24' ";
    $meeting  ="select * from tbl_task where status='A' and task_name='25' ";
    $deadline ="select * from tbl_task where status='A' and task_name='31' ";  
    $followup ="select * from tbl_task where status='A' and task_name='57' ";  

    if($_GET['daterangetsk'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangetsk']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$ttltask    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$phncall   .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$email  .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$meeting   .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$deadline    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$followup    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }

	if($_GET['usertask'] != '')
	{

		//=========================User Wise Filter==============

	      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$_GET['usertask']."' ");
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


		$ttltask .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
		$phncall .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
		$email .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
		$meeting .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
		$deadline .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
		$followup .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertask']."') ";
	}

	//====================
	
	$getTtlTask=$this->db->query($ttltask)->num_rows();
	$getPhnCall=$this->db->query($phncall)->num_rows();
	$getEmail=$this->db->query($email)->num_rows();
	$getMeeting=$this->db->query($meeting)->num_rows();
	$getDeadline=$this->db->query($deadline)->num_rows();
	$getFolloup=$this->db->query($followup)->num_rows();

	//====================

	//$lead .= " GROUP BY lead_state ";
	//$leadsts = $this->db->query($lead);
	//echo $lead;

}else{
		
		if($this->session->userdata('role') == 1){

		$ttltask=$this->db->query("select * from tbl_task where status='A' ");
		$getTtlTask=$ttltask->num_rows();

		$phncall=$this->db->query("select * from tbl_task where status='A' and task_name='23' ");
		$getPhnCall=$phncall->num_rows();

		$email=$this->db->query("select * from tbl_task where status='A' and task_name='24' ");
		$getEmail=$email->num_rows();

		$meeting=$this->db->query("select * from tbl_task where status='A' and task_name='25' ");
		$getMeeting=$meeting->num_rows();

		$deadline=$this->db->query("select * from tbl_task where status='A' and task_name='31' ");
		$getDeadline=$deadline->num_rows();

		$followup=$this->db->query("select * from tbl_task where status='A' and task_name='57' ");
		$getFolloup=$followup->num_rows();
	
	}else{

		$ttltask=$this->db->query("select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getTtlTask=$ttltask->num_rows();

		$phncall=$this->db->query("select * from tbl_task where status='A' and task_name='23' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getPhnCall=$phncall->num_rows();

		$email=$this->db->query("select * from tbl_task where status='A' and task_name='24' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getEmail=$email->num_rows();

		$meeting=$this->db->query("select * from tbl_task where status='A' and task_name='25' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getMeeting=$meeting->num_rows();

		$deadline=$this->db->query("select * from tbl_task where status='A' and task_name='31' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getDeadline=$deadline->num_rows();

		$followup=$this->db->query("select * from tbl_task where status='A' and task_name='57' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getFolloup=$followup->num_rows();

	}
}	


?>

<div class="tile-body">
<div class="row" style="display: none1;">
<?php if($this->session->userdata('role') == 1) { ?>
<form>

<div class="col-sm-7">
<div class="input-group">
<input type="text" class="form-control reportrange" name="daterangetsk" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%" value="<?=$_GET['daterangetsk'];?>">
<span class="input-group-addon">
 <span class="fa fa-calendar"></span>
</span>	
</div>
</div>

<div class="col-sm-4">
<div class="btn-group" style="margin: 0 0 0 -10px;">
<select class="chosen-select form-control" name="usertask">
<option value="">--Select User--</option>
<?php
$usr=$this->db->query("select * from tbl_user_mst where status='A'");
foreach($usr->result() as $getUsr) {
$branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsr->brnh_id' ");
$getBranch=$branch->row();
?>
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['usertask']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")" ;?> </option>
<?php } ?>
</select>
</div>
</div>

<div class="col-sm-1" style="margin: 0 0 0 -25px;">
	<button class="btn btn-sm btn-default" type="submit" name="filtertask" value="filtertask">Go!</button>
</div>
</form>
<?php } ?>
</div><!--row close-->

<div class="pull-right" style="margin: 20px 10px 0px 0px; font-size: 11px;">
<strong>Total Task : </strong><?=$getTtlTask;?><br>
<strong>Phone Call : </strong><?=$getPhnCall;?><br>
<strong>Email : </strong><?=$getEmail;?><br>
<strong>Meeting : </strong><?=$getMeeting;?><br>
<strong>Deadline : </strong><?=$getDeadline;?><br>
<strong>Follow Up : </strong><?=$getFolloup;?><br>
</div>		
<div id="jqChart3" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->

<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Task </strong>Status</h1>
<ul class="controls">
<li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
<li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
</ul>
</li>
<li class="remove"><a role="button" tabindex="0" class="tile-close"><i class="fa fa-times"></i></a></li>
</ul>
</div><!-- /tile header -->
<?php 

/////////////////////////////////////Graph Calculation///////////////////////////////
if($_GET['filtertasksts'] == 'filtertasksts')
{

	$tasks = "select task_status,count('task_status') as countval from tbl_task where status='A' ";

    if($_GET['daterangetsksts'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangetsksts']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$tasks .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";

    }
   
   if($_GET['usertasksts'] != '')
   {
	//=========================User Wise Filter==============

      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$_GET['usertasksts']."' ");
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

	  $tasks .= "AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertasksts']."') ";
      
  }
  	$tasks .= " GROUP BY task_status";
  	$tasksts = $this->db->query($tasks);

}
else
{
	if($this->session->userdata('role') == 1){
	  $tasksts=$this->db->query("select task_status,count('task_status') as countval from tbl_task where status='A' GROUP BY task_status");	
	}
	else{
	  $tasksts=$this->db->query("select task_status,count('task_status') as countval from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) GROUP BY task_status");	
	}
}

//$tasksts=$this->db->query("select task_status,count('task_status') as countval from tbl_task GROUP BY task_status");

foreach ($tasksts->result() as $getTask) 
{ 


      $mst=$this->db->query("select * from tbl_master_data where serial_number='$getTask->task_status'");
      $getMst=$mst->row();

      $arr1[] = $getMst->keyvalue;
      $arr2[] = (int)$getTask->countval;

      $arr3[]=array_merge($arr1,$arr2);  
      unset($arr1);
      unset($arr2);
     // continue;          
  } 

?>
<?php 
/////////////////////////////////////Total Calculation///////////////////////////////
if($_GET['filtertasksts'] == 'filtertasksts')
{
	$totaltask ="select * from tbl_task where status='A' ";
	$ntstrt    ="select * from tbl_task where status='A' and task_status='19' ";
    $inprgrs   ="select * from tbl_task where status='A' and task_status='20' ";
    $cmplt     ="select * from tbl_task where status='A' and task_status='21' ";

    if($_GET['daterangetsksts'] != '' )
    {

    		$daterage=explode("-",$_GET['daterangetsksts']);
			
			$fdate=$daterage[0];
			$fdate = str_replace(' ','',$fdate);
			$tdate=$daterage[1];
			$tdate = str_replace(' ','',$tdate);

			$frmdtrng=explode("/",$fdate);
			$todtrng=explode("/",$tdate);

			$fdate1=$frmdtrng[2]."-".$frmdtrng[0]."-".$frmdtrng[1];
			$todate1=$todtrng[2]."-".$todtrng[0]."-".$todtrng[1];
	        
			$totaltask    .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$ntstrt   .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$inprgrs  .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";
			$cmplt   .=" AND maker_date >='$fdate1' AND maker_date <='$todate1'";


    }

	if($_GET['usertasksts'] != '')
	{

		//=========================User Wise Filter==============

	      $usrfltr = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$_GET['usertasksts']."' ");
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


		$totaltask .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertasksts']."') ";
		$ntstrt .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertasksts']."') ";
		$inprgrs .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertasksts']."') ";
		$cmplt .= " AND (task_id in ($allTaskIDs) OR maker_id='".$_GET['usertasksts']."') ";

	}

	//====================
	
	$getTotalTask=$this->db->query($totaltask)->num_rows();
	$getNotStrtd=$this->db->query($ntstrt)->num_rows();
	$getPrgrs=$this->db->query($inprgrs)->num_rows();
	$getCmplt=$this->db->query($cmplt)->num_rows();


	//====================

	//$lead .= " GROUP BY lead_state ";
	//$leadsts = $this->db->query($lead);
	//echo $lead;

}else{
		
		if($this->session->userdata('role') == 1){
		$totaltask=$this->db->query("select * from tbl_task where status='A' ");
		$getTotalTask=$totaltask->num_rows();

		$ntstrt=$this->db->query("select * from tbl_task where status='A' and task_status='19' ");
		$getNotStrtd=$ntstrt->num_rows();

		$inprgrs=$this->db->query("select * from tbl_task where status='A' and task_status='20' ");
		$getPrgrs=$inprgrs->num_rows();

		$cmplt=$this->db->query("select * from tbl_task where status='A' and task_status='21' ");
		$getCmplt=$cmplt->num_rows();
	
	}else{
	
		$totaltask=$this->db->query("select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getTotalTask=$totaltask->num_rows();

		$ntstrt=$this->db->query("select * from tbl_task where status='A' and task_status='19' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getNotStrtd=$ntstrt->num_rows();

		$inprgrs=$this->db->query("select * from tbl_task where status='A' and task_status='20' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getPrgrs=$inprgrs->num_rows();

		$cmplt=$this->db->query("select * from tbl_task where status='A' and task_status='21' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR task_id in ($TaskoldData) OR task_id in ($TasknewData) ) ");
		$getCmplt=$cmplt->num_rows();
	}

}
?>

<div class="tile-body">
<div class="row" style="display: none1;">
<?php if($this->session->userdata('role') == 1) { ?>
<form>

<div class="col-sm-7">
<div class="input-group">
<input type="text" class="form-control reportrange" name="daterangetsksts" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%" value="<?=$_GET['daterangetsksts'];?>">
<span class="input-group-addon">
 <span class="fa fa-calendar"></span>
</span>	
</div>
</div>

<div class="col-sm-4">
<div class="btn-group" style="margin: 0 0 0 -10px;">
<select class="chosen-select form-control" name="usertasksts">
<option value="">--Select User--</option>
<?php
$usr=$this->db->query("select * from tbl_user_mst where status='A'");
foreach($usr->result() as $getUsr) {
$branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUsr->brnh_id' ");
$getBranch=$branch->row();
?>
<option value="<?=$getUsr->user_id;?>" <?php if($_GET['usertasksts']==$getUsr->user_id) { ?>selected <?php } ?> ><?=$getUsr->user_name ." (".$getBranch->brnh_name .")" ;?> </option>
<?php } ?>
</select>
</div>
</div>

<div class="col-sm-1" style="margin: 0 0 0 -25px;">
	<button class="btn btn-sm btn-default" type="submit" name="filtertasksts" value="filtertasksts">Go!</button>
</div>
</form>
<?php } ?>
</div><!--row close-->

<div class="pull-right" style="margin: 20px 10px 0px 0px; font-size: 11px;">
<strong>Total Task : </strong><?=$getTotalTask;?><br>
<strong>Not Started : </strong><?=$getNotStrtd;?><br>
<strong>In Progress : </strong><?=$getPrgrs;?><br>
<strong>Complete : </strong><?=$getCmplt;?><br>
</div>	
<div id="jqChart4" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->
</div><!--row close-->

<!-- -----------------------------Task Close --------------------------- -->



<div class="row" style="display: none;">
<div class="col-md-12">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Lead</strong></h1>
<ul class="controls">
<li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle settings" data-toggle="dropdown"> <i class="fa fa-cog"></i> <i class="fa fa-spinner fa-spin"></i> </a>
<ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
<li> <a role="button" tabindex="0" class="tile-toggle"> <span class="minimize"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;Minimize</span> <span class="expand"><i class="fa fa-angle-up"></i>&nbsp;&nbsp;&nbsp;Expand</span> </a> </li>
<li> <a role="button" tabindex="0" class="tile-refresh"> <i class="fa fa-refresh"></i> Refresh </a> </li>
</ul>
</li>
<li class="remove"><a role="button" tabindex="0" class="tile-close"><i class="fa fa-times"></i></a></li>
</ul>
</div><!-- /tile header -->

<div class="tile-body">
<div id="bar_chart"></div>
</div><!-- /tile body -->
</section>
</div><!--col-md-12 close-->
</div><!--row close-->



</div>
</section><!--/ CONTENT -->

</div> <!-- ajax_content -->




<!--dashboard js-->   
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/chart_dashboard/labelschart_css/jquery.jqChart.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/chart_dashboard/labelschart_css/styles.css" />
<!-- <script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery-1.11.1.min.js" type="text/javascript"></script> -->
<script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery.jqChart.min.js" type="text/javascript"></script> 
<!--dashboard js close-->   

<!----------------------------------------Funnel Chart 1 ------------------------------------------------->

<script type="text/javascript">
        $(document).ready(function () {

            var background = {
                type: 'linearGradient',
                x0: 0,
                y0: 0,
                x1: 0,
                y1: 1,
                //colorStops: [{ offset: 0, color: '#d2e6c9' },
                             //{ offset: 1, color: 'white' }]
            };

            $('#jqChart1').jqChart({
                //title: { text: 'Funnel Chart' },
                legend: { title: 'Lead Stage' },
                border: { strokeStyle: '#ffffff' },
                background: background,
                animation: { duration: 1 },
                shadows: {
                    enabled: true
                },
                series: [
                    {
                    	type: 'funnel',
                    	spacing: 0,
                    	dynamicSlope: false,
                    	dynamicHeight: false,
                    	inverted: false,
                    	neckRatio: 0.2,
                        fillStyles: ['#418CF0', '#FCB441', '#E0400A', '#056492', '#BFBFBF', '#1A3B69', '#FFE382'],
                        labels: {
                            font: '15px sans-serif',
                            fillStyle: 'white'
                        },
                        data: <?php echo json_encode($arr33); ?>
                    }
                ]
            });
        });

</script> 

<!----------------------------------------Pie Chart 2 ------------------------------------------------->


<script type="text/javascript">
        $(document).ready(function () {
            $('#jqChart2').jqChart({
                //title: { text: 'Labels Formatting' },
                legend: { title: { text: 'Lead Status' } },
				 border: { strokeStyle: '#ffffff' },
                animation: { duration: 1 },
                series: [
                    {
                        type: 'pie',
                        labels: {
                            stringFormat: '%d%%',
                            valueType: 'percentage',
                            font: '15px sans-serif',
                            fillStyle: 'white'
                        },
                        data: <?php echo json_encode($arr9); ?>
                    }
                ]
            });
        });
</script>   



<!----------------------------------------Pie Chart 3 ------------------------------------------------->


<script lang="javascript" type="text/javascript">
        $(document).ready(function () {

            var background = {
                type: 'linearGradient',
                x0: 0,
                y0: 0,
                x1: 0,
                y1: 1,
                colorStops: [{ offset: 0, color: '#d2e6c9' },
                             { offset: 1, color: 'white' }]
            };

            $('#jqChart3').jqChart({
               // title: { text: 'Pie Chart Labels' },
                legend: { title: 'Task' },
                border: { strokeStyle: '#ffffff' },
               // background: background,
                animation: { duration: 1 },
                shadows: {
                    enabled: true
                },
                series: [
                    {
                        type: 'pie',
                        fillStyles: ['#418CF0', '#FCB441', '#E0400A', '#056492', '#BFBFBF', '#1A3B69', '#FFE382'],
                        labels: {
                            stringFormat: '%.1f%%',
                            valueType: 'percentage',
                            font: '15px sans-serif',
                            fillStyle: 'black'
                        },
                        explodedRadius: 10,
                        explodedSlices: [5],
                        data: <?php echo json_encode($arr6);?>,
                        labelsPosition: 'outside', // inside, outside
                        labelsAlign: 'circle', // circle, column
                        labelsExtend: 20,
                        leaderLineWidth: 1,
                        leaderLineStrokeStyle: 'black'
                    }
                ]
            });
        });
  </script>

<!----------------------------------------Doughnut Chart 4 ------------------------------------------------->


<script type="text/javascript">

        $(document).ready(function () {

            var background = {
                type: 'linearGradient',
                x0: 0,
                y0: 0,
                x1: 0,
                y1: 1,
                colorStops: [{ offset: 0, color: '#d2e6c9' },
                             { offset: 1, color: 'white' }]
            };

            $('#jqChart4').jqChart({
            	//title: { text: 'Task Status' },
                legend: { title: 'Task Status' },
                border: { strokeStyle: '#ffffff' },
               // background: background,
                animation: { duration: 1 },
                shadows: {
                    enabled: true
                },
                series: [
                    {
                    	type: 'doughnut',
                    	innerExtent: 0.5,
                    	outerExtent: 1.0,
                        fillStyles: ['#418CF0', '#FCB441', '#E0400A', '#056492', '#BFBFBF', '#1A3B69', '#FFE382'],
                        labels: {
                            stringFormat: '%.1f%%',
                            valueType: 'percentage',
                            font: '15px sans-serif',
                            fillStyle: 'white'
                        },
                        data: <?php echo json_encode($arr3);?>
                    }
                ]
            });
        });

</script>







<!-- ------------------Expected Business Per Month------------------- -->


<script type="text/javascript">
      // Load the Visualization API and the line package.
      google.charts.load('current', {'packages':['bar']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart3);
  
     var url3 = "Getdata_lead";
    function drawChart3() {
  
        $.ajax({
        type: 'POST',
        url: url3,
          
        success: function (data1) {
            //alert(data1);
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable();
  
      data.addColumn('string', 'Year/Month');
      data.addColumn('number', 'Sales');
      //data.addColumn('number', 'Expense');
        
      var jsonData = $.parseJSON(data1);
      
      for (var i = 0; i < jsonData.length; i++) {
            data.addRow([jsonData[i].exptdyear, parseInt(jsonData[i].sales)]);
      }
      var options = {
        chart: {
          title: 'Expected Business Per Month ',
          subtitle: ''
        },
        width: 900,
        height: 500,
        axes: {
          x: {
            0: {side: 'top'}
          }
        }
         
      };
      var chart = new google.charts.Bar(document.getElementById('bar_chart'));
      chart.draw(data, options);
       }
     });
    }
</script>




<script type="text/javascript">

$(function() {

   var start = moment().subtract(29, 'days');
   var end = moment();

   function cb(start, end) {
       $('.reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
   }

   $('.reportrange').daterangepicker({
       // startDate: start,
       // endDate: end,
       ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
       }
   }, cb);

   cb(start, end);

});

</script>

<?php $this->load->view('footer.php'); ?>

<script type="text/javascript" src="<?=base_url();?>/assets/assets/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>/assets/assets/daterangepicker/daterangepicker.css">
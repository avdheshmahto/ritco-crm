<?php  
if($this->input->post('id') == "")
{
    $this->load->view('header.php'); 
} ?>
<?php

  $contact = $this->db->query("select contact_id,contact_name  from tbl_contact_m where status = 'A' ");
  $arr = array();
  foreach ($contact->result() as $getContact) 
  { 
     
    $arr[] = $getContact->contact_name."^".$getContact->contact_id;
  } 


  $json_contact   = json_encode($arr,true);

  //print_r($arr);

  $contact = $this->db->query("select * from tbl_organization where status='A' ORDER BY org_name ");
  $arrorgnization = array();

    foreach ($contact->result() as $getContact) 
    { 
       $arrorgnization[] = $getContact->org_name.'^'.$getContact->org_id;
    } 

  $json_orgnization = json_encode($arrorgnization,true);

  ?>


<?php 

     $lead_id   = "";   $contact_id  = ""; $lead_number  = ""; $user_resp    = ""; $industry   = "";
     $no_of_emp = "";   $source      = ""; $probability  = ""; $closuredate  = ""; $opp_value  = "";
     $stage     = ""; $discription   = ""; $org_id       = ""; $lead_state   = ""; $maker_id = "";

if($result != "")
{
      // echo '<pre>';
      // print_r($result);
      // echo '</pre>';die;

     $lead_id     = $result->lead_id;
     $stage       = $result->stage;
     $lead_state  = $result->lead_state;
     
     $cntpName = $this->db->query("select * from tbl_contact_m where contact_id='".$result->contact_id."' ");
     $getCntName = $cntpName->row();
     $cntperson = $getCntName->contact_name;

     $orgName = $this->db->query("select * from tbl_organization where org_id='".$result->org_id."' ");
     $getOrgName = $orgName->row();
     $orgname = $getOrgName->org_name;

     $lead_number  = $result->lead_number;
     
     $usrLead = $this->db->query("select * from tbl_user_mst where user_id='".$result->user_resp."'");
     $getUsrLead = $usrLead->row();
     $usrresp = $getUsrLead->user_name;

     $lownr = $this->db->query("select * from tbl_user_mst where user_id='".$result->maker_id."'");
     $getOwner = $lownr->row();
     $leadowner = $getOwner->user_name;

     $Cemail     = $getCntName->email;
     $Cphone   = $getCntName->phone;
     $Caddress   = $getCntName->address;
    
     $Oemail      = $getOrgName->email;
     $Ophone    = $getOrgName->phone_no;
     $Oaddress  = $getOrgName->address;
     $website = $getOrgName->website;

     $idtry = $this->db->query("select * from tbl_master_data where serial_number='".$result->industry."' ");
     $getIndtry       = $idtry->row();
     $industry       = $getIndtry->keyvalue;

     $no_of_emp = $result->no_of_emp;
     
     $lsource = $this->db->query("select * from tbl_master_data where serial_number='".$result->source."' ");
     $getLsource       = $lsource->row();
     $leadsource = $getLsource->keyvalue;

     $probability = $result->probability;
     $closuredate  = $result->closuredate;
     $opp_value = $result->opp_value;
     
     $lstage = $this->db->query("select * from tbl_master_data where serial_number='".$result->stage."' ");
     $getStage  = $lstage->row();
     $leadstage = $getStage->keyvalue;

     $discription = $result->discription;

     $Lstate = $this->db->query("select * from tbl_master_data where serial_number='".$result->lead_state."' ");
     $getStateLead  = $Lstate->row();
     $LeadState = $getStateLead->keyvalue;

}

	$userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='3'");
	$getAcc=$userPerQuery->row();

?>

<div id="ajax_content"> <!-- ajax_content -->

<div id="main-content">
<div id="guts">
  
     <input type="hidden" id="json_contact" value='<?=$json_contact;?>'>
     <input type="hidden" id="json_orgnization" value='<?=$json_orgnization;?>'>

<section id="content">
<div class="page page-tables-bootstrap" >
<div class="row">
<div class="col-md-12">
<section class="tile">

<div id="loadStageData"> <!-- loadStageData --> 

<div class="pageheader tile-bg">
<div class="media">

<div class="btn-toolbar pull-right mt-10">
<div class="btn-group">
<a href="<?php echo base_url('lead/Lead/manage_lead');?>"><button class="btn btn-default btn-sm br-2"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button></a> 
</div>
<?php
if($getAcc->edit_id=='1')
{
?>
<div class="btn-group">
<a href="#" onclick="editInnerLead(this);" property = "edit" type="button" data-toggle="modal" data-target="#leadEditModal" arrt= '<?=json_encode($result);?>'  data-backdrop='static' data-keyboard='false'><button class="btn btn-default btn-sm br-2"><i class="fa fa-pencil"></i></button></a> 
</div>
<?php }?>
<div class="btn-group">
<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action<span class="caret"></span></button>
<ul class="dropdown-menu dropdown-menu-right" role="menu">
  <?php
if($getAcc->edit_id=='1')
{
?>
<li><a href="#" onclick="editInnerLead(this);" property = "edit" type="button" data-toggle="modal" data-target="#leadEditModal" arrt= '<?=json_encode($result);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i>Edit This Lead</a></li>
<?php }?>

<?php if($getAcc->delete_id=='1') { ?>
<?php
// $cntct=$this->db->query("select * from tbl_contact_m where org_name='$result->org_id' ");
// $cntctNumRow=$cntct->num_rows();
// $lead=$this->db->query("select * from tbl_leads where contact_id='$result->contact_id' ");
// $leadNumRow=$lead->num_rows();
$task=$this->db->query("select * from tbl_task where lead_id='$result->lead_id' ");
$tskNumRow=$task->num_rows();

$num_rows=$tskNumRow;

if($num_rows > 0) { ?>
  <li><a href="#" onclick="return confirm('Lead already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Lead </a></li>
<?php } else { ?>
<li><a  onclick = "if (! confirm('Are You Sure! You Want To Delete ?')) { return false; }" href="<?=base_url('lead/Lead/deleteLead?lead_id=');?><?=$lead_id?>"><i class="fa fa-trash"></i>Delete This Lead</a></li>
<?php } ?>
<?php } ?>
<li><a href="#" data-toggle="modal" data-target="#taskLeadModal" formid="#TaskLeadForm" id="formreset"><i class="fa fa-plus"></i>Add New Task For This Lead</a></li>
</ul>
</div>

</div>

<div class="media-body">
<p class="media-heading mb-0 mt-5">Lead(<?=$LeadState?>)</p>
<small class="text-lightred"><?=$lead_number?></small>
</div>
</div>
</div><!--pageheader close-->


<div class="pageheader">
<div class="table-responsive">
<table class="table mb-0">
<tbody>
<tr>
<td style="border:none;">
<small class="text-muted">Organization</small>
<h5 class="media-heading mb-0">
     <!-- <a href="#" onclick="getViewOrgPage('<?=$result->org_id;?>');"> --><?=$orgname?> </h5>
</td>
<td style="border:none;">
<small class="text-muted">Expected Closure Date</small>
<h5 class="media-heading mb-0"><?=$closuredate?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Expected Business Per Month</small>
<h5 class="media-heading mb-0"><?=$opp_value?></h5>
</td>
<!-- <td style="border:none;">
<small class="text-muted">Probability Of Winning</small>
<h5 class="media-heading mb-0"><?=$probability?>%</h5>
</td> -->
<?php 
$mkrdt=$result->maker_date;
$crdate=date("Y-m-d");
$earlier = new DateTime($crdate);
$later = new DateTime($mkrdt);

$diff = $later->diff($earlier)->format("%a");
?>
<td style="border:none;">
<small class="text-muted">Lead Age</small>
<h5 class="media-heading mb-0"><?=$diff." days"; ?></h5>
</td>
<!-- <td style="border:none;">
<small class="text-muted">Stage</small>
<h5 class="media-heading mb-0"><?=$leadstage?></h5>
</td> -->
<td style="border:none;">
<small class="text-muted">Lead Assign To</small>
<h5 class="media-heading mb-0"><?=$usrresp?></h5>
</td>
<td style="border:none;">
<small class="text-muted">Lead Owner</small>
<h5 class="media-heading mb-0"><?=$leadowner?></h5>
</td>

</tr>
</tbody>
</table>
</div>
</div><!--pageheader close-->


<div class="tile-body">
<div class="row">
<div class="col-sm-12">
<header class="head-title">
<h2> Pipeline: <span class="lower">Lead Pipeline</span> </h2>
<?php      if($stage == 17)
               $i = 1;
          elseif($stage == 18)
               $i = 2;
          elseif($stage == 53)
               $i = 3;
          elseif($stage == 78)
               $i = 4;
          else
               $i = 0;

$newOpp         = $this->db->query("select * from tbl_master_data where serial_number = 17 ");
$getNewopp      = $newOpp->row();
$newopportunity = $getNewopp->keyvalue;

$cntcting    = $this->db->query("select * from tbl_master_data where serial_number = 18 ");
$getCntcting = $cntcting->row();
$contacting  = $getCntcting->keyvalue;

$rtsQutionSbmtd          = $this->db->query("select * from tbl_master_data where serial_number = 53 ");
$getRtsQutionSbmtd       = $rtsQutionSbmtd->row();
$RatesQuotationSubmitted = $getRtsQutionSbmtd->keyvalue;

$clsd     = $this->db->query("select * from tbl_master_data where serial_number = 78 ");
$getClsd  = $clsd->row();
$closed   = $getClsd->keyvalue;

 ?>
<div class="lower"> 
     <?php if($lead_state == 66 || $lead_state == 67 || $lead_state == 79 ) { ?>
     <b>Lead Status : <?php if($lead_state == 66){ echo "Won"; } elseif($lead_state == 67) { echo "Lost"; } elseif($lead_state == 79) { echo "Freeze"; } ?></b>
     <?php } elseif($stage == 17 || $stage == 18 || $stage == 53 || $stage == 78 ) { ?>
     <b>Stage <?=$i?> of 4 : <?=$leadstage ?></b><?php } ?>
<span>
  <a class="nopjax formresetstageid" onclick="checkStageStaus('<?=$LeadState;?>')" >Change Pipeline Stage</a>
</span>
</div>
</header>

<div id="pipelineTbl-holder">
<div class="table-responsive">
<table id="PipelineTbl">
<thead>
<tr>
<th class="pipeline-stage" data-name="New opportunity" data-current="false" data-last="false"> 
<a class="formresetstageid" onclick="checkStageStaus('<?=$LeadState;?>')" >
<?php if($stage == 17 && $lead_state == 65) { ?>
<div class="stage-lbl">
<div class="stage-lbl-txt"><?= $newopportunity; ?> </div>
</div>
<?php } elseif($lead_state == 67 || $lead_state == 79) { ?>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $newopportunity; ?> </div>
</div>
<?php } elseif($stage == 18 || $stage == 53 || $stage == 78) { ?>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> <i class="icon-ok icon-white icon-white-two"></i>
<div class="stage-won-lbl"><?= $newopportunity; ?> </div>
</div>
</div>

<?php } ?>
</a> 
</th>
<?php 
for($i=1; $i<=3; $i++) {
?>
<th class="pipeline-stage" data-name="Contacting" data-current="false" data-last="false"> 
<a class="nopjax formresetstageid" onclick="checkStageStaus('<?=$LeadState;?>')" >

<?php if($i==1 && $stage == 18) { ?>

<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl">
<div class="stage-lbl-txt"> <?= $contacting; ?> </div>
</div>
<?php } elseif($i==1 && ($lead_state == 67 || $lead_state == 79)) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-off"></div>
</div>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $contacting; ?> </div>
</div>
<?php } elseif($i==1 && $stage == 17) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner"></div>
</div>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $contacting; ?> </div>
</div>
<?php } elseif($i==1) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> <i class="icon-ok icon-white icon-white-two"></i>
<div class="stage-won-lbl"> <?= $contacting; ?> </div>
</div>
</div>
  
<?php } if($i==2 && $lead_state == 66) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> <i class="icon-ok icon-white icon-white-two"></i>
<div class="stage-won-lbl"> <?= $RatesQuotationSubmitted; ?> </div>
</div>
</div>
<?php }else if($i==2 && $stage == 53) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl">
<div class="stage-lbl-txt"> <?= $RatesQuotationSubmitted; ?> </div>
</div>
<?php } elseif($i==2 && $stage == 18) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner"></div>
</div>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $RatesQuotationSubmitted; ?> </div>
</div>
<?php } elseif($i==2 && ($stage == 53 || $stage == 18 || $stage == 17)) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-off"></div>
</div>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $RatesQuotationSubmitted; ?> </div>
</div>
<?php } elseif($i==2) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> <i class="icon-ok icon-white icon-white-two"></i>
<div class="stage-won-lbl"> <?= $RatesQuotationSubmitted; ?> </div>
</div>
</div>


<?php } if($i==3 && $lead_state == 66) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> <i class="icon-ok icon-white icon-white-two"></i>
<div class="stage-won-lbl"> <?= $closed; ?> </div>
</div>
</div>
<?php }else if($i==3 && $stage == 78) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl">
<div class="stage-lbl-txt"> <?= $closed; ?> </div>
</div>
<?php } elseif($i==3 && $stage == 53) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner"></div>
</div>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $closed; ?> </div>
</div>
<?php } elseif($i==3 && ($stage == 78 || $stage == 53 || $stage == 18 || $stage == 17)) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-off"></div>
</div>
<div class="stage-lbl stage-off">
<div class="stage-lbl-txt"> <?= $closed; ?> </div>
</div>
<?php } elseif($i==3) { ?>
<div class="pipeline-arrow">
<div class="arrow-inner stage-won"></div>
</div>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> <i class="icon-ok icon-white icon-white-two"></i>
<div class="stage-won-lbl"> <?= $closed; ?> </div>
</div>
</div>
<?php } ?>
</a>
</th>
<?php } ?>

</tr>
</thead>
</table>
</div>
</div>
</div>
</div>
</div><!--tile-body close-->

</div> <!-- Close loadStageData -->


<div class="add-nav">
<div role="tabpanel">
<!-- Nav tabs -->
<ul class="nav nav-tabs pt-15-" role="tablist">
<li role="presentation" class="active">
     <a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
<li role="presentation">
     <a href="#related" aria-controls="related" role="tab" data-toggle="tab">Related</a></li>
<li role="presentation">
     <a href="#activity" aria-controls="activity" role="tab" data-toggle="tab">All Activity</a></li>
</ul>
<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="details">
<div class="row">
<div class="col-md-12">
<section class="tile____ time-simple">
<div class="tile-body">
<div class="tile-body p-0">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="nameandoccupation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation1" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Lead Information</span> </a> </h4>
</div>
<div id="nameandoccupation1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20___">
<div class="entity-detail">
<div class="col-sm-9">
<div class="table-responsive">
<table class="property-table">
<tbody>
<tr>
<td class="ralign"><span class="title">Record ID </span></td>
<td><div class="info">
<?=$lead_id;?>
</div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Contact Person Name </span></td>
<td><div class="info">
<?=$cntperson;?>
</div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Organization Name </span></td>
<td><div class="info">
<?=$orgname;?>
</div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Lead Number </span></td>
<td><div class="info">
<?=$lead_number;?>
</div></td>
</tr>
<?php if($LeadState == 'Open') {?>
<tr>
<td class="ralign"><span class="title">Lead Status </span></td>
<td><div class="info"><span class="open-bt">
<?=$LeadState;?>
</span> </div></td>
</tr>
<?php } elseif($LeadState == 'Won') {?>
<tr>
<td class="ralign"><span class="title">Lead Status </span></td>
<td><div class="info"><span class="won">
<?=$LeadState;?>
</span> (Docket No :
<?=$result->docket_no;?>
) </div></td>
</tr>
<?php } elseif($LeadState == 'Lost' || $LeadState == 'Freeze' ) {?>
<tr>
<td class="ralign"><span class="title">Lead Status </span></td>
<td><div class="info"><span class="lost">
<?=$LeadState;?>
</span> (Reason :
<?=$result->lead_stat_desc;?>
) </div></td>
</tr>
<?php } ?>
<tr>
<td class="ralign"><span class="title">Assign To </span></td>
<td><div class="info">
<?=$usrresp;?>
</div></td>
</tr>
</tbody>
</table>
</div>
</div><!--col-sm-9 close-->

<div class="col-sm-3">
<div class="table-responsive___">
<header class="head-title">
<h2> Select Status: </h2>
</header>
<div class="table-responsive__">
<table id="PipelineTbl">
<thead>
<tr>
<th class="pipeline-stage" data-name="Closed" data-current="false" data-last="true"> <a href="#" data-toggle="modal" data-target="#leadStateModal" formid="#LeadStateForm" id="formresetstate">
<?php if($lead_state == 66) { ?>
<div class="stage-lbl stage-won">
<div class="stage-lbl-txt"> Won </div>
</div>
<?php } elseif($lead_state == 67) { ?>
<div class="stage-lbl stage-lost">
<div class="stage-lbl-txt"> Lost </div>
</div>
<?php } elseif($lead_state == 79) { ?>
<div class="stage-lbl stage-lost">
<div class="stage-lbl-txt"> Freeze </div>
</div>
<?php } elseif($stage == 78) { ?>
<div class="stage-lbl">
<div class="stage-lbl-txt"> Open </div>
</div>
<?php } else { ?>
<div class="stage-lbl">
<div class="stage-lbl-txt"> Open </div>
</div>
<?php  } ?>
</a> </th>
</tr>
</thead>
</table>
</div>
</div>
</div><!--col-sm-3 close-->

</div><!--entity-detail close-->
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!-- name and occupation close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="ContactDetails">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#ContactDetails2" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Lead <!-- Contact  -->Details</span> </a> </h4>
</div>
<div id="ContactDetails2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="ContactDetails">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="property-table">
<tbody>

<?php 
if($Cphone!='')
{
     $jsondata = $Cphone;
     $arr = json_decode($jsondata, true);
     foreach ($arr as $key => $value) { ?>
     <tr>
          <td class="ralign"><span class="title">Phone</span></td>
          <td><div class="info"><?=$value;?></div></td>
     </tr>
<?php }  } ?>


<?php 
if($Cemail!='')
{
    $jsondata = $Cemail;
    $arr = json_decode($jsondata, true);
    foreach ($arr as $k=>$v) { ?>
     <tr>
          <td class="ralign"><span class="title">Email</span></td>
          <td><div class="info"><?=$v;?></div></td>
      </tr>
<?php } } ?>
<tr>
<td class="ralign"><span class="title">Address</span></td>
<td><div class="info"><?=$Caddress;?></div></td>
</tr>
</tbody>
</table>
</div>
</div>
</section>

</article>
</div> <!--close-->
</div>
</div>
</div> <!--Contact Details close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="AdditionalInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#AddressInformation5" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i> Additional Information</span> </a> </h4>
</div>
<div id="AddressInformation5" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="AdditionalInformation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="property-table">
<tbody>
<tr>
<td class="ralign"><span class="title">Lead Created</span></td>
<td><div class="info"><?=$result->maker_date;?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Industry</span></td>
<td><div class="info"><?=$industry?> </div></td>
</tr>
<!-- <tr>
<td class="ralign"><span class="title">Source</span></td>
<td><div class="info"><?=$leadsource?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Probability of winning</span></td>
<td><div class="info"><?=$probability?>%</div></td>
</tr> -->
<tr>
<td class="ralign"><span class="title">Expected Closure Date</span></td>
<td><div class="info"><?=$closuredate?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Expected Business Per Month :</span></td>
<td><div class="info"><?=$opp_value?> </div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Stage</span></td>
<td><div class="info"><span class="stage"><?=$leadstage?></span></div></td>
</tr>

<!-- <?php if($result->lead_rates != '') { ?>
<tr>
<td class="ralign"><span class="title">Rates </span></td>
<td><div class="info"><?=$result->lead_rates;?>   
(From : <?= $result->lead_from;?>, To : <?= $result->lead_to; ?>, Product : <?= $result->lead_product; ?>, Rate Type : <?= $result->rate_type; ?>, Basic Freight : <?= $result->bsc_frght; ?>, Gr Charge : <?= $result->gr_chrg; ?>, Labour Charge : <?= $result->lbr_chrg; ?>, Enroute Charge : <?= $result->enrt_chrg;?>, Delivery Charge : <?= $result->dlvry_charge;?>, Misc Charge : <?= $result->misc_charge;?>, Risk Charge : <?= $result->risk_charge;?>, Remarks : <?= $result->stage_rmrks;?>) 
</div> </td>
</tr>
<?php } ?> -->

</tbody>
</table>
</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Additional Information close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="DescriptionInformation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#DescriptionInformation6" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>  Description Information</span> </a> </h4>
</div>

<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#leadNoteModal" formid="#LeadNotesForm" id="formreset" style="margin: -35px 0px 0px 0px;">Add Remarks</button>
</p>
</div>

<div id="DescriptionInformation6" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="DescriptionInformation">
<div class="panel-body">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="property-table">
<tbody>

<section class="tile- time-simple">
<div class="tile-body">
<div class="streamline mt-20">

<?php 
$i=1;

$tskDes = $this->db->query("select * from tbl_note where note_logid='".$lead_id."' AND (main_lead_id_note='main_lead' OR main_lead_id_note='main_task' OR main_lead_id_note='Inner Lead') AND (note_type='Lead' OR note_type='Task') ORDER BY note_id DESC ");
foreach ($tskDes->result() as $getTskdesc) {

$tskOwnr = $this->db->query("select * from tbl_user_mst where user_id='".$getTskdesc->maker_id."' ");
$getTskownr = $tskOwnr->row();
$takowner = $getTskownr->user_name;
?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-file-text-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<!-- <div class="panel-heading bg-white"> Lead Notes </div> --> 
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?= $takowner; ?></span></li>
<li>|</li>
<li><span><?=$getTskdesc->note_date;?></span></li>
<li>|</li>
<li><span><?= $getTskdesc->note_type;?></span></li>
</ul>
</div>
<div class="call-footer">
<?php $big=$getTskdesc->note_desc;
$bigs = strip_tags($big);
echo $bigs;
 ?> 
</div>
</div><!--panel panel-default close-->
</div>
</article>
<?php $i++; } ?>

</div><!--streamline mt-20 close-->
</div>
</section>

</tbody>
</table>
</div>
</div>
</section>

</article>
</div> <!--close-->
</div>
</div>
</div> <!--Description Information close-->


</div>
</div><!--tile-body p-0 close-->

</div>
</section>
</div>
</div>
</div><!-- details close -->


<div role="tabpanel" class="tab-pane" id="related">
<div class="row">
<div class="col-md-12">
<div class="tile-body">
<div class="activity-stats" data-total-count="0">
<div class="block-items">

<?php 
$orgrow = $this->db->query("select * from tbl_mulit_orgz where morg_logid='".$lead_id."' AND morg_type='Organization' AND orgid !='' ORDER BY morg_id ");
$cntOrg = $orgrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Organization" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#Opportunities9" aria-expanded="true">
<span class="top-label">Organization</span>
<div class="block-item-count"><?=$cntOrg?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$cntct = $this->db->query("select * from tbl_mulit_orgz where morg_logid='".$lead_id."' AND morg_type='Contact' AND orgid !='' ORDER BY morg_id ");
$cntrows = $cntct->num_rows();
?>
<div id="note-stats" class="block-item large-block" title="Contacts" data-target="#notes-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation8" aria-expanded="true"> 
<span class="top-label">Contacts</span>
<div class="block-item-count"><?=$cntrows?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->


<?php 
$taskrow = $this->db->query("select * from tbl_task where lead_id='".$result->lead_id."' ");
$cntTask = $taskrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Task" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#projects10" aria-expanded="true">
<span class="top-label">Task</span>
<div class="block-item-count"><?=$cntTask?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

<?php 
$notesrow = $this->db->query("select * from tbl_note where note_logid='".$lead_id."' AND note_type='Lead' AND main_lead_id_note='Inner Lead' ");
$cntNotes = $notesrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Notes" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#notes12" aria-expanded="true">
<span class="top-label">Remarks</span>
<div class="block-item-count"><?=$cntNotes?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->


<?php 
$rates = $this->db->query("select * from tbl_lead_rates where lead_id='".$lead_id."' ");
$cntRates = $rates->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Rates" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#rates" aria-expanded="true">
<span class="top-label">Rates</span>
<div class="block-item-count"><?=$cntRates?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--rates close-->


<?php 
$filesrow = $this->db->query("select * from tbl_file where file_logid='".$lead_id."' AND file_type='Lead' ");
$cntFiles = $filesrow->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="Files" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#files" aria-expanded="true">
<span class="top-label">Files</span>
<div class="block-item-count"><?=$cntFiles?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->


<?php 
$usr_row = $this->db->query("select * from tbl_software_log where slog_id='".$lead_id."' AND slog_name='Lead' AND slog_type='User' ");
$cntUsr = $usr_row->num_rows();
?>
<div id="file-stats" class="block-item large-block" title="User" data-target="#files-grid-container">
<a data-toggle="collapse" data-parent="#accordion" href="#user" aria-expanded="true">
<span class="top-label">User</span>
<div class="block-item-count"><?=$cntUsr?></div>
<div class="fp-product-count-holder">
<div class="fp-product-count-total"></div>
<div class="fp-product-count-percent" style="width: 0%;"></div>
</div>
</a>
</div><!--note-stats close-->

</div>
</div><!--activity-stats close-->



<div class="tile-body p-0">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">


<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="notes">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#rates" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Rates</span> <span class="badge bg-lightred"><?=$cntRates?></span> </a></h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#modalRates" formid="#LeadRatesForm" id="formreset">Add Rate</button>
</p>
</div>
</div>
</div>
<div id="rates" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="notes">
<div class="panel-body__">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">

<div id="listingData"> <!-- listdataid -->
<div class="table-responsive table-overflow__">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Rates</th>
<th>From</th>
<th>To</th>
<th>Product</th>
<th>Rate Type</th>
<th>Basic Freight</th>
<th>Gr Charge </th>
<th>Labour Charge</th>
<th>Enroute Charge</th>
<th>Delivery Charge</th>
<th>Misc Charge</th>
<th>Risk Charge</th>
<th>Remarks</th>

<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>
<?php 
$i=1;
foreach($rates->result() as $getRates){
?>
<tr class="record" data-row-id="<?=$getRates->rates_id; ?>">
<td><?=$getRates->lead_rates?></td>
<td><?=$getRates->lead_from?></td>
<td><?=$getRates->lead_to?></td>
<td><?=$getRates->lead_product?></td>
<td><?=$getRates->rate_type?></td>

<td style="text-align: center;"><?=$getRates->bsc_frght?></td>
<td style="text-align: center;"><?=$getRates->gr_chrg?></td>
<td style="text-align: center;"><?=$getRates->lbr_chrg?></td>
<td style="text-align: center;"><?=$getRates->enrt_chrg?></td>
<td style="text-align: center;"><?=$getRates->dlvry_charge?></td>
<td style="text-align: center;"><?=$getRates->misc_charge?></td>
<td style="text-align: center;"><?=$getRates->risk_charge?></td>

<td>
<div class="tooltip-col">
<?php 
$big = $getRates->rate_rmrks;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>

<td>
<?php 
  $pri_col='rates_id ';
  $table_name='tbl_lead_rates';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <li><a href="#" onclick="editRate(this);" property = "edit" type="button" data-toggle="modal" data-target="#modalRates" arrt= '<?=json_encode($getRates);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Rate</a></li>
  <li><a href="#" class="delbutton" id="<?php echo $getRates->rates_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Rate</a></li>
</ul>
</div>
</td>
</tr>

<?php $i++; } ?>

<!-- <tr>
  <td colspan="14" style="height:60px;">&nbsp;</td>
</tr> -->

</tbody>
</table>
</div>
</div><!-- listdataid -->

</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Rate Information close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="DescriptionInformation">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a  data-toggle="collapse" data-parent="#accordion" href="#files" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Files</span> <span class="badge bg-lightred"><?=$cntFiles?></span> </a>   </h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#fileLeadModal" formid="#FilesLeadForm" id="formreset">Add Files</button>
</p>
</div>
</div>
</div>
<div id="files" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="DescriptionInformation">
<div class="panel-body__">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">

<div id="listingFilesData"> <!-- listdataid -->
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>S. No.</th>
<th>Name</th>
<th>Description</th>
<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>
<?php 
$i=1;
foreach($filesrow->result() as $getFiles){
?>
<tr class="record" data-row-id="<?=$getFiles->file_id; ?>">
<td><?=$i?></td>
<td><a href="<?=base_url();?>crmfiles/leadfile/<?=$getFiles->files_name;?>" target="_blank"><?=$getFiles->files_name?></a></td>
<td>
<div class="tooltip-col">
<?php 
$big = $getFiles->files_desc;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<td>
<?php 
  $pri_col='file_id ';
  $table_name='tbl_file';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <li><a href="#" onclick="editFiles(this);" property = "edit" type="button" data-toggle="modal" data-target="#fileLeadModal" arrt= '<?=json_encode($getFiles);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This File</a></li>
  <li><a href="#" class="delbutton" id="<?php echo $getFiles->file_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This File</a></li>
</ul>
</div>
</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</div>
</div><!-- listdataid -->

</div>
</section>

</article>
</div> <!--close-->
</div>
</div>
</div> <!--File Information close-->



<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="notes">
<div class="">
<div class="btn-group">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#notes12" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Remarks</span> <span class="badge bg-lightred"><?=$cntNotes?></span> </a></h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#leadNoteModal" formid="#LeadNotesForm" id="formreset">Add Remarks</button>
</p>
</div>
</div>
</div>
<div id="notes12" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="notes">
<div class="panel-body__">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">

<div id="listingData"> <!-- listdataid -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>S. No.</th>
<th>Remarks Date</th>
<th>Remarks Desc</th>

<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>
<?php 
$i=1;
foreach($notesrow->result() as $getNote){
?>
<tr class="record" data-row-id="<?=$getNote->note_id; ?>">
<td><?=$i?></td>
<td><?=$getNote->note_date?></td>

<td>
<div class="tooltip-col">
<?php 
$big = $getNote->note_desc;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>

<td>
<?php 
  $pri_col='note_id ';
  $table_name='tbl_note';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <li><a href="#" onclick="editNote(this);mainLeadIdNote('<?=$getNote->main_lead_id_note;?>');" property = "edit" type="button" data-toggle="modal" data-target="#leadNoteModal" arrt= '<?=json_encode($getNote);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Remarks</a></li>
  <li><a href="#" class="delbutton" id="<?php echo $getNote->note_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Remarks</a></li>
</ul>
</div>
</td>
</tr>
<?php $i++; } ?>
</tbody>
</table>
</div><!-- listdataid -->

</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Note Information close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="nameandoccupation">
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#user" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Users</span><span class="badge bg-lightred"><?=$cntUsr?></span> </a> </h4>
</div>
<div id="user" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body">
<div>
<?php 

$i=1;
foreach ($usr_row->result() as $getSftLog) { 

$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getRespUsr = $RespUsr->row();
$MkrNm = $getRespUsr->user_name;

$slog_type = $getSftLog->slog_type;

$oldusr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->old_id."' ");
$getOldUsr = $oldusr->row();
$oldUsrName = $getOldUsr->user_name;

$newUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->new_id."' ");
$getNewUsr = $newUsr->row();
$newUsrName = $getNewUsr->user_name;



?>
<article class="streamline-post streamline:after streamline-post-to">

<div class="post-container">
<div class="panel panel-default panel-to">

<div class="bg-white"><?=$i; ?> . &nbsp; <?=$oldUsrName?> <i class="fa fa-long-arrow-right"></i> <?=$newUsrName?> </div>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $getSftLog->maker_date; ?> </span></li>
<li>|</li>
<li><span> <?= $MkrNm; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div> <br>
</article>
<?php  $i++; } ?>
</div><!--close-->
</div>
</div>
</div><!--User Details close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="Opportunities">
<div class="btn-group">  
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#Opportunities9" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Organization</span> <span class="badge bg-lightred"><?=$cntOrg?></span> </a></h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#mutiOrgModal" formid="#MultiOrgzForm" id="formreset">Add Organization Link</button>
</p>
</div>
</div>

<div id="Opportunities9" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="Opportunities" aria-expanded="true" style="">
<div class="panel-body__">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">

<div id="listingMultiOrgData"> <!-- listdataid -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Organization Name</th>
<th>Details </th>
<th>Link Created</th>
<th>Default</th>
<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>

<?php 
foreach ($orgrow->result() as $getOrg) {
?>

<tr class="record" data-row-id="<?=$getOrg->morg_id; ?>">
<td>
<?php
$onam = $this->db->query("select * from tbl_organization where org_id='".$getOrg->orgid."' ");
$getOnm = $onam->row();
//echo $getOnm->org_name;
?> 

<!-- <a href="#" onclick="getViewOrgPage('<?=$getOrg->orgid;?>');"><?=$getOnm->org_name?></a>  -->
<a href="<?=base_url('organization/Organization/view_organization?id=');?><?=$getOrg->orgid; ?>"><?=$getOnm->org_name?></a> 
</td>
<td><?=$getOrg->morg_details ?></td>
<td><?=$getOrg->maker_date ?></td>
<td>
<input type="radio" onchange="changeDefaultOrg(this,'<?=$getOrg->morg_id;?>','<?=$getOrg->morg_logid ?>','<?=$getOrg->orgid ?>','<?=$getOrg->morg_type ?>');" <?=$getOrg->default_org=='Y'?'checked':'';?> >
</td>
<td>
<?php 
  $pri_col='morg_id ';
  $table_name='tbl_mulit_orgz';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
<!--   <li><a href="#" onclick="editMultiOrgz(this);" property = "edit" type="button" data-toggle="modal" data-target="#mutiOrgModal" arrt= '<?=json_encode($getOrg);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Link</a></li> -->
  
  <?php if($getOrg->default_org=='Y')  {?>
    <li><a href="#" onclick="return confirm('Its a primary organization. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Link </a></li>
  <?php } else { ?>

  <li><a href="#" class="delbutton" id="<?php echo $getOrg->morg_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Link</a></li>
  <?php } ?>


</ul>
</div>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Org Information close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="nameandoccupation">
<div class="btn-group"> 
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#nameandoccupation8" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Contacts</span><span class="badge bg-lightred"><?=$cntrows?></span> </a> </h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#mutiCntModal" formid="#MultiCntForm" id="formreset">Add Contact Link</button>
</p>
</div>
</div>
<div id="nameandoccupation8" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="nameandoccupation">
<div class="panel-body__">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Contact Person Name</th>
<th>Details </th>
<th>Link Created</th>
<th>Default</th>
<th style="text-align:right;">Action</th>
</tr>
</thead>
<tbody>
<?php 
foreach ($cntct->result() as $getCntct) {
?>
<tr class="record" data-row-id="<?=$getCntct->morg_id; ?>">
<td>
<?php
$cnt = $this->db->query("select * from tbl_contact_m where contact_id='".$getCntct->orgid."' ");
$getCnam = $cnt->row();

?> 

<!-- <a href="#" onclick="getViewContactPage('<?=$getCntct->orgid;?>');"><?=$getCntct->orgid?></a> -->
<a href="<?=base_url('contact/Contact/view_contact?id=');?><?=$getCntct->orgid; ?>"><?=$getCnam->contact_name?></a>
</td>
<td><?=$getCntct->morg_details ?></td>
<td><?=$getCntct->maker_date ?></td>
<td>
<input type="radio" onchange="changeDefaultOrg(this,'<?=$getCntct->morg_id;?>','<?=$getCntct->morg_logid ?>','<?=$getCntct->orgid ?>','<?=$getCntct->morg_type ?>');" <?=$getCntct->default_org=='Y'?'checked':'';?> >
</td>
<td>
<?php 
  $pri_col='morg_id ';
  $table_name='tbl_mulit_orgz';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  
  <?php if($getCntct->default_org=='Y')  {?>
    <li><a href="#" onclick="return confirm('Its a primary contact. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Link </a></li>
  <?php } else { ?>

  <li><a href="#" class="delbutton" id="<?php echo $getCntct->morg_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Link</a></li>
  <?php } ?>

</ul>
</div>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Contact Details close-->



<div class="panel panel-default panel-transparent">
<div class="panel-heading accordion-heading" role="tab" id="projects">
<div class="btn-group">  
<h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#projects10" aria-expanded="true"> <span><i class="fa fa-minus text-sm mr-5"></i>Task</span> <span class="badge bg-lightred"><?=$cntTask?></span></a></h4>
</div>
<div class="btn-toolbar pull-right">
<p>
<button type="button" class="btn btn-default btn-xs mb-10" data-toggle="modal" data-target="#taskLeadModal" formid="#TaskLeadForm" id="formreset">Add Task</button>
</p>
</div>
</div>
<div id="projects10" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="projects" aria-expanded="true" style="">
<div class="panel-body__">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="entity-detail">
<div class="table-responsive">

<div id="listTaskData"> <!-- listTaskData -->
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Name</th>
<!-- <th>Reminder Date</th> -->
<th>Date Due</th>
<th>Assign To</th>
<th>Description</th>
<th>Status</th>
<th>Progress Percent</th>
<th style="text-align:right;">Action</th> 
</tr>
</thead>
<tbody>
<?php 
foreach($taskrow->result() as $getTask){
?>
<tr class="record" data-row-id="<?=$getTask->task_id; ?>">
<td>
<!-- <a href="#" onclick="getViewTaskPage('<?=$getTask->task_id;?>');"> -->
<a href="<?=base_url('task/Task/view_task?id=');?><?=$getTask->task_id;?>">
<?php
$tname = $this->db->query("select * from tbl_master_data where serial_number='".$getTask->task_name."' ");
$getTname = $tname->row();
echo $getTname->keyvalue; ?>  
</a>
</td>
<!-- <td><?=$getTask->reminder_date?></td> -->
<td><?=$getTask->date_due?></td>
<td>
<?php
$tusr = $this->db->query("select * from tbl_user_mst where user_id='".$getTask->user_resp."' ");
$getTusr = $tusr->row();
echo $getTusr->user_name; ?>
</td>
<td>
<div class="tooltip-col">
<?php 
$big = $getTask->description;

$big = strip_tags($big);
$small = substr($big, 0, 20);
echo $small ."....."; ?>
<span class="tooltiptext3"><?=$big;?> </span>
</div>
</td>
<td>
<?php
$tstatus = $this->db->query("select * from tbl_master_data where serial_number='".$getTask->task_status."' ");
$getTstatus = $tstatus->row();
echo $getTstatus->keyvalue; ?>
</td>
<td>
<div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: 150px; margin-right: 5px">
<div class="progress-bar progress-bar-greensea" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: <?=$getTask->progress_percnt?>%;"></div>
</div>
<small><?=$getTask->progress_percnt?>%</small></td>
<td>
<?php 
  $pri_col='task_id ';
  $table_name='tbl_task';
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
  <li><a href="#" class="delbutton" id="<?php echo $getTask->task_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Task</a></li>
</ul>
</div>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
</div>
</section>

</article>
</div><!--close-->
</div>
</div>
</div><!--Task close-->





</div>
</div><!--tile-body p-0 close-->


</div>
</div>
</div>
</div><!-- related close -->




<div role="tabpanel" class="tab-pane" id="activity">
<div class="row">
<div class="col-md-12">

<section class="tile- time-simple">
<div class="tile-body">
<div class="streamline mt-20">

<?php 

$SftLog = $this->db->query("select * from tbl_software_log where slog_id='".$result->lead_id."' AND mdl_name='Lead' ORDER BY sid DESC ");


foreach ($SftLog->result() as $getSftLog) {  

if($getSftLog->slog_name == 'Task') {

$tskname = $getSftLog->slog_type;

$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->old_id."'");
$getMakr = $makr->row();
$makrNm = $getMakr->user_name;

$asnUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->new_id."'");
$getAsnusr = $asnUsr->row();
$AsinName = $getAsnusr->user_name;


?>

<article class="streamline-post">
<aside>
<?php if($tskname == 'Phone Call') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-phone"></i></button>
<?php } elseif($tskname == 'Email') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-envelope"></i></button>
<?php } elseif($tskname == 'Meeting') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-building-o"></i></button>
<?php } elseif($tskname == 'Deadline') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-ellipsis-v"></i></button>
<?php } elseif($tskname == 'Follow Up') { ?>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-users"></i></button>
<?php } ?>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-heading bg-white">
	<span class="badge bg-greensea call"> <i class="fa fa-check"></i> </span> <?=$tskname; ?>
</div>
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$getSftLog->maker_date?></span></li>
<li>|</li>
<!-- <li><span><?=$getTasklog->designation?></span></li>
<li>|</li> -->
<li><span><i class="fa fa-user"></i> <!-- <a href="#"> --><?= $AsinName; ?></span></li>
<li>|</li>
<li><span><!-- <i class="fa fa-user" aria-hidden="true"></i> --><?= $makrNm; ?></span></li>
</ul>
</div>
<div class="call-footer">
<?php echo $getSftLog->remarks; ?>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php  } ?>

<?php 
if($getSftLog->slog_name == 'Status' && $getSftLog->slog_type == 'Won'){

$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getRespUsr = $RespUsr->row();
$resp_usr_name = $getRespUsr->user_name;
?>
<article class="streamline-post streamline:after streamline-post-to">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width: 10px;height: 10px;padding: 5px;margin: 0 0 0 14px;"></button>
</aside>
<div class="post-container">
<div class="panel panel-default panel-to">
<div class="bg-white">Status<i class="fa fa-long-arrow-right"></i>Won<br> 
  Docket No ( <?=$getSftLog->remarks?> )
</div>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li><span><?= $getSftLog->maker_date; ?> </span></li>
<li>|</li>
<li><span> <?= $resp_usr_name; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php
}
?>

<?php 

if($getSftLog->slog_name == 'Lead' && ($getSftLog->slog_type == 'User' || $getSftLog->slog_type == 'Stage' || $getSftLog->slog_type == 'Status' )) {


$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getRespUsr = $RespUsr->row();
$resp_usr_name = $getRespUsr->user_name;

$slog_type = $getSftLog->slog_type;

$oldusr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->old_id."' ");
$getOldUsr = $oldusr->row();
$oldUsrName = $getOldUsr->user_name;

$newUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->new_id."' ");
$getNewUsr = $newUsr->row();
$newUsrName = $getNewUsr->user_name;

$oldkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog->old_id."' ");
$getOldKey = $oldkey->row();
$oldKeyName = $getOldKey->keyvalue;

$newkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog->new_id."' ");
$getNewKey = $newkey->row();
$newKeyName = $getNewKey->keyvalue;


?>
<article class="streamline-post streamline:after streamline-post-to">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width: 10px;height: 10px;padding: 5px;margin: 0 0 0 14px;"></button>
</aside>
<div class="post-container">
<div class="panel panel-default panel-to">
<?php if($getSftLog->slog_type == 'User') { ?>
<div class="bg-white"><?=$slog_type__?>  <?=$oldUsrName?> <i class="fa fa-long-arrow-right"></i> <?=$newUsrName?> </div>
<?php } elseif($getSftLog->slog_type == 'Stage' || $getSftLog->slog_type == 'Status') { ?>
<div class="bg-white"><?=$slog_type__?>  <?=$oldKeyName?> <i class="fa fa-long-arrow-right"></i> <?=$newKeyName?> </div>
<?php } ?>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li><span><?= $getSftLog->maker_date; ?> </span></li>
<li>|</li>
<li><span> <?= $resp_usr_name; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php } ?>

<?php 

if($getSftLog->slog_name == 'File') {

$filename = $getSftLog->slog_type;
$file_ext = pathinfo($filename);
$file_extn = $file_ext[extension];

$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getMakr = $makr->row();
$makrNm = $getMakr->user_name;

?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-files-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-heading bg-white">
<?php if($file_extn == 'tif' || $file_extn == 'jpg' || $file_extn == 'gif' || $file_extn == 'png'){?>
<i class="fa fa-file-image-o"></i>
<?php } elseif($file_extn == 'xls' || $file_extn == 'csv' || $file_extn == 'xlsx') { ?>
<i class="fa fa-file-excel-o"></i>
<?php } elseif($file_extn == 'pdf') { ?>
<i class="fa fa-file-pdf-o"></i> 
<?php } elseif($file_extn == 'docx' || $file_extn == 'doc') { ?>
<i class="fa fa-file-word-o"></i> 
<?php } else { ?>
<i class="fa fa-file-text-o"></i> 
<?php } ?>     
<a href="<?=base_url();?>crmfiles/leadfile/<?=$filename;?>" target="_blank">&nbsp;<?=$filename;?></a>
</div>
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$getSftLog->maker_date?></span></li>
<li>|</li>
<li><span><!-- <i class="fa fa-user" aria-hidden="true"></i> --> &nbsp;<?= $makrNm; ?></span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php  } ?>

<?php 

if($getSftLog->slog_name == 'Note' && $getSftLog->slog_type == 'Lead Notes') {

$notename = $getSftLog->slog_type;

$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->maker_id."'");
$getMakr = $makr->row();
$makrNm = $getMakr->user_name;

?>

<article class="streamline-post">
<aside>
<button type="button" class="btn btn-rounded-20 btn-default btn-sm mr-5" style="width:40px; height:40px;"><i class="fa fa-file-text-o"></i></button>
</aside>
<div class="post-container">
<div class="panel panel-default">
<div class="panel-heading bg-white"> <?=$notename?>

</div> 
<div class="panel-body"> 
<ul class="list-inline list-unstyled">
<li><span><?=$getSftLog->maker_date?></span></li>
<li>|</li>
<li><span><!-- <i class="fa fa-user" aria-hidden="true"></i> --> &nbsp;<?= $makrNm; ?></span></li>
</ul>
</div>
<div class="call-footer">
<?php echo $getSftLog->remarks; ?>
</div>
</div><!--panel panel-default close-->
</div>
</article>

<?php  } ?>

<?php } ?>

<article class="streamline-post streamline:after streamline-post-to">
<aside>
<button type="button" class="btn btn-rounded-20" style="width: 10px;height: 10px;padding: 5px;margin: 0 0 0 14px;"></button>
</aside>
<div class="post-container">
<div class="panel panel-default panel-to">
<div class="bg-white">Lead Created</div>
<div class="panel-body-to"> 
<ul class="list-inline list-unstyled">
<li>by &nbsp;<span><?= $leadowner; ?> &nbsp; <?= $result->maker_date; ?> </span></li>
</ul>
</div>
</div><!--panel panel-default close-->
</div>
</article>  

</div><!--streamline mt-20 close-->

</div>
</section>
</div>
</div>
</div><!-- activity close -->


</div>
</div>
</div>


</section>
</div>
</div>

</div>
</section>

</div>
</div>

</div> <!-- Close ajax_content -->




<!-- Modal leadEditModal-->
<div class="modal fade" id="leadEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add New</span> Lead</h4>
<div id="resultarea" style="font-size: 15px;color: red; text-align:center;"></div> 
</div>
<form  id="LeadEditForm"> 
<div class="modal-body">
<div class="sb-container container-example1">
<!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#LEADINFO" aria-expanded="true" aria-controls="LEADINFO">
<span><i class="fa fa-minus text-sm mr-5"></i>LEAD INFORMATION</span>
</a>
</h4>
</div>
<div id="LEADINFO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label>*Contact Person Name : <span class="label label-success" id="ldnewid" style="display: none;">new</span></label>
<input type="hidden" name="lead_idz" id="lead_idz" class="hiddenField">
<input type="hidden" name="oldcontact" id="oldcontact" class="hiddenField">
<input type="hidden" name="orgidcontant" id="orgidcontant" class="hiddenField">

<div class="typeahead__container ">
<div class="typeahead__field">
    <div class="typeahead__query input-group mb-10">
       <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
      <input class="js-typeaheadlead form-control"
          name="contact"
          type="search"
          autofocus
          autocomplete="off" id="contacttypahead" required="" ="">
    </div>
</div>
</div>
</div>
<div class="form-group col-md-6" id="cntctorg">
<label>*Organization Name : <span class="label label-success" id="ldnewidorg" style="display: none;">new</span></label>
<div class="typeahead__container ">
<div class="typeahead__field">
<div class="typeahead__query">
<input class="orgnizationjs-typeaheadlead form-control"
       name="org_name" 
       id="org_name"
       type="search"
       autofocus
       autocomplete="off" required="" ="">
</div>
</div>
</div>
</div>
<div class="form-group col-md-6" id='mltiorg' style="display: none;">
<label>*Organization Names :</label>
<select name="corg" id="corg" class="form-control">
   <option value="">----Select----</option>
</select>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>*Lead Name : </label>
<input type="text" name="lead_number" id="lead_number" class="form-control" readonly="" required="">
</div>
<div class="form-group col-md-6">
<label>Assign To : </label>
<select name="assin_user" id="assin_user" class="chosen-select form-control">
<option value="">----Select User----</option>
          <?php 
                $sqlgroup=$this->db->query("select * from tbl_user_mst where  status='A' ");
                foreach ($sqlgroup->result() as $fetchgroup){

          $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$fetchgroup->brnh_id' ");
          $getBranch=$branch->row();
          ?>
    <option value="<?php echo $fetchgroup->user_id; ?>" <?php if($this->session->userdata('user_id') == $fetchgroup->user_id) { ?> selected <?php } ?> ><?php echo $fetchgroup->user_name ." (".$getBranch->brnh_name .")" ?></option>
    <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#LEADCONTACT" aria-expanded="true" aria-controls="LEADCONTACT">
<span><i class="fa fa-minus text-sm mr-5"></i>LEAD DETAILS</span>
</a>
</h4>
</div>
<div id="LEADCONTACT" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label>Email : </label>
  <table id="compaddrowboxemail">
    <tr style="line-height: 3;">
     <td style="width: 90%;">
       <input type="email" name="email_id[]" id="email_id0" class="form-control">
     </td>
     <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
     </td>
  </tr>
  </table>
  <a style="cursor: pointer;" onclick="addRowCompemail();"><small>+ add one more</small></a>
</div>
<div class="form-group col-md-6">
<label>Phone : </label>

<table id="compaddrowboxphone">
  <tr style="line-height:3;">
    <td style="width: 90%;">
     <input type='tel' name="phone_no[]" id="phone_no0" minlength="10" maxlength="11" class="form-control" required="">
    </td>
    <td align="center" style="width: 150px;">
     <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
    </td>
  </tr>
</table>
  <a  style="cursor: pointer;" onclick="addRowComphone();"><small>+ add one more</small></a>

</div>
</div>

<div class="row">
<div class="form-group col-md-12">
<label>Address : </label>
<textarea name="address" id="address"  class="form-control" ></textarea>
</div>
</div>

</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#ADDITIONALINFO" aria-expanded="true" aria-controls="ADDITIONALINFO">
<span><i class="fa fa-minus text-sm mr-5"></i>ADDITIONAL INFORMATION</span>
</a>
</h4>
</div>
<div id="ADDITIONALINFO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label>Industry : </label>
<select name="industry" id="industry" class="chosen-select form-control">
<option value="">----select----</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='24'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
</div>
<div class="form-group col-md-6">
<label>Stage : </label>
<select name="stage" id="stage" class="chosen-select__ form-control">
<!-- <option value="">----select----</option> -->
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='20'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } elseif($fetchPrio->serial_number != 17){?> style="display: none;" <?php  } ?> ><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
</div>
</div>

<!-- <div class="row">
  <div class="form-group col-md-6">
  <label>Source : </label>
  <select name="source" id="source"  class="chosen-select form-control">
  <option value="">--Select--</option>
  <?php 
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='18'");
    foreach ($sqlprio->result() as $fetchPrio){
  ?>
  <option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchPrio->keyvalue ; ?></option>
  <?php }?>
  </select>
  </div>
<div class="form-group col-md-6">
<label>Probability of Winning % : </label>
<input type='number' min="0" max="100" name="probability" id="probability" class="form-control" >
</div>
</div> -->

<div class="row">
<div class="form-group col-md-6">
<label>Expected Business Per Month : </label>
<input type='number' name="opp_value" id="opp_value" class="form-control" >
</div>
<div class="form-group col-md-6">
<label>Expected Closure Date: </label>
<input type='text' name="closuredate" id="closuredate" class="form-control datetimepicker_mask">
</div>
</div>

</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATION" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATION">
<span><i class="fa fa-minus text-sm mr-5"></i> NOTES</span>
</a>
</h4>
</div>
<div id="DESCRIPTIONINFORMATION" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea name="summernote" id="summernote"></textarea>
</div>
</div>
</div>
</div>
</div><!--panel close-->

</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="formsave" class="btn btn-primary">Save</button>
  <span id="saveload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>

</form>

</div>
</div>
</div>
<!-- Modal leadEditModal close-->



<!-- Modal taskLeadModal -->
<div class="modal fade" id="taskLeadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Task</h4>
<div id="resultstask" style="font-size: 15px;color: red; text-align:center"></div> 
</div>

<form  id="TaskLeadForm"> 
<div class="modal-body">
<!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
<div class="sb-container container-example1">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#TASKDETAILS" aria-expanded="true" aria-controls="TASKDETAILS">
<span><i class="fa fa-minus text-sm mr-5"></i>TASK DETAILS</span>
</a>
</h4>
</div>
<div id="TASKDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label for="name">Task Name : </label>
<input type="hidden" name="lead_idz" id="lead_idz" value="<?=$lead_id ?>">
<select name="task_name" id="task_name" class="chosen-select form-control">
      <option value="">----Select ----</option>
          <?php 
            $sqlgroup=$this->db->query("select * from tbl_master_data where status='A' and param_id='23'");
            foreach ($sqlgroup->result() as $fetchgroup){
          ?>
        <option value="<?php echo $fetchgroup->serial_number; ?>" <?php if($fetchgroup->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchgroup->keyvalue ; ?></option>
        <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Priority : </label>
<select name="priority" id="priority"  class="chosen-select form-control">
<option value="">--Select--</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='17'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Status : </label>
<select name="status" id="status" class="chosen-select form-control">
<option value="">----select----</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='21'");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v=='Y') {?>selected <?php } ?> ><?php echo $fetchPrio->keyvalue ; ?></option> 
<?php }?>
</select>
</div>
<div class="form-group col-md-6">
<label for="contactemail">Progress % : </label>
<input type="number" name="progress"  value="0" id="progress" class="form-control" readonly="">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="contactemail">Due Date : </label>
<input type='text' name="due_date" id="due_date" class="form-control datetimepicker_mask" required="">
</div>  
<!-- <div class="form-group col-md-6">
<label for="contactemail">Reminder Date : </label>
<input type='text' name="reminder_date" id="reminder_date" class="form-control datetimepicker_mask">
</div> -->  
<div class="form-group col-md-6">
<label>Assign To : </label>
<select name="user_resp" id="user_resp" class="chosen-select form-control">
<option value="">----Select User----</option>
          <?php 
                $sqlgroup=$this->db->query("select * from tbl_user_mst where  status='A' ");
                foreach ($sqlgroup->result() as $fetchgroup){

          $branch=$this->db->query("select * from tbl_branch_mst where brnh_id='$fetchgroup->brnh_id' ");
          $getBranch=$branch->row();
          ?>
    <option value="<?php echo $fetchgroup->user_id; ?>" <?php if($this->session->userdata('user_id') == $fetchgroup->user_id) { ?> selected <?php } ?> ><?php echo $fetchgroup->user_name ." (".$getBranch->brnh_name .")" ?></option>
    <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#RELATEDTO" aria-expanded="true" aria-controls="RELATEDTO">
<span><i class="fa fa-minus text-sm mr-5"></i>RELATED TO</span>
</a>
</h4>
</div>
<div id="RELATEDTO" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">


<div class="row">
<div class="form-group col-md-6">
<label>Linked To : </label>
<input type="hidden" name="leadid" id="leadid" value="<?= $result->lead_id ?>">
<select class="form-control" disabled="true">
<option value="">------Select option------</option>
<?php
  $contact = $this->db->query("select * from tbl_leads where status='A' ORDER BY lead_number ");
  foreach ($contact->result() as $getContact) {    ?>
   <option value="<?=$getContact->lead_id; ?>" <?php if($getContact->lead_id == $lead_id) { ?> selected <?php } ?> ><?php echo $getContact->lead_number; ?></option>
 <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Contact Name : </label>
<input type="text" id="tcontact_person"  value="<?= $cntperson ?>" class="form-control" readonly>
<input type="hidden" name="tcontact_person" id="tcontact_personid" value="<?= $result->contact_id ?>" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Organization Name : </label>
<input type="text" id="torg_name" value="<?=$orgname?>" class="form-control" readonly>
<input type="hidden" name="torg_name" id="torg_nameid" value="<?=$result->org_id?>" class="form-control">
</div>
</div>


</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATION" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATION">
<span><i class="fa fa-minus text-sm mr-5"></i>NOTES</span>
</a>
</h4>
</div>
<div id="DESCRIPTIONINFORMATION" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea id="summernotess"></textarea>
</div>
</div>
</div>
</div>
</div><!--panel close-->


</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="tasksave" class="btn btn-primary">Save</button>
  <span id="taskload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>

</form>

</div>
</div>
</div>
<!-- /Modal taskLeadModal close-->




<!-- Multiple Contact-->
<div class="modal fade" id="mutiCntModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title"><span class="top_title">Add</span> Contact</h4>
<div id="resultmulticnt" style="font-size: 15px;color: red; text-align:center;"></div> 
</div>

    <form  id="MultiCntForm"> 
    <div class="modal-body">
    <div class="sb-container container-example1">
    <!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
    <div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

    <div class="panel panel-default panel-transparent">
    <div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a data-toggle="collapse" data-parent="#accordion" href="#CONTACTDETAILS" aria-expanded="true" aria-controls="CONTACTDETAILS">
    <span><i class="fa fa-minus text-sm mr-5"></i>CONTACT DETAILS</span>
    </a>
    </h4>
    </div>
    <div id="CONTACTDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
    <div class="panel-body">

    <div class="row">
    <div class="form-group col-md-6">

    <input type="hidden" name="cleadid" id="cleadid" value="<?=$lead_id?>">
    <input type="hidden" name="cmorgid" id="cmorgid" value="">

    <input type="hidden" name="moldcontact_id" id="moldcontact_id" class="hiddenField">
    <label>*Contact Person Name : <span class="label label-success" id="newid" style="display: none;">new</span></label>
    <div class="typeahead__container ">
    <div class="typeahead__field">
    <div class="typeahead__query">
    <input class="js-typeahead form-control"
           name="mcontact_name" 
           id="mcontact_name"
           type="search"
           autofocus
           autocomplete="off" required="">
    </div>
    </div>
    </div>
    </div>
    <div class="form-group col-md-6">
    <label>Designation : </label>
    <input type="text" name="mdesignation" id="mdesignation"  class="form-control" required="">
    </div>
    </div>

    <div class="row">
    <div class="form-group col-md-6">
     <label>Email : </label>
      <table id="cntaddrowbox">
        <tr style="line-height: 3;">
         <td style="width:100%;">
           <input type="email" name="memail_id[]" id="memail_id0" class="form-control">
         </td>
         <td align="center" style="width: 150px;">
           <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
         </td>
      </tr>
      </table>
      <a style="cursor: pointer;" onclick="addRowCntemail();"><small>+ add one more</small></a>
    </div>

      <div class="form-group col-md-6">
      <label>Phone : </label>
      <table id="cntaddrowboxp">
        <tr style="line-height:3;">
          <td style="width:100%;">
           <input type='text' name="mphone_no[]" id="mphone_no0" class="form-control">
          </td>
          <td align="center" style="width: 150px;">
           <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
          </td>
        </tr>
      </table>
      <a  style="cursor: pointer;" onclick="addRowCntphone();"><small>+ add one more</small></a>
    </div>
    
    </div>

  

    <div class="row">
    <div class="form-group col-md-6">
    <label>Address : </label>
    <textarea name="maddress" id="maddress"  class="form-control" ></textarea>
    </div>
    <div class="form-group col-md-6">
    <label>City : </label>
    <input type='text' name="mcity" id="mcity" class="form-control" >
    </div>
    </div>

    <div class="row">
    <div class="form-group col-md-6">
    <label>State : </label>
    <select name="mstate_id" id="mstate_id" class="chosen-select form-control">
      <option value="">--Select--</option>
      <?php
      $stateQquery=$this->db->query("select * from tbl_state_m where status='A'  ORDER BY stateName ");
      foreach($stateQquery->result() as $getState){
      ?>
      <option value="<?=$getState->stateid;?>"><?=$getState->stateName;?></option>
      <?php } ?>
    </select>
    </div>
    <div class="form-group col-md-6">
    <label>Pin Code : </label>
    <input type="number" name="mpin_code" id="mpin_code" class="form-control">
    </div>
    </div>

    <div class="row">
    <div class="form-group col-md-6">
    <label>Country : </label>
    <select name="mcountry_id" id="mcountry_id" class="chosen-select form-control">
      <option value="">--Select--</option>
      <?php
      $stateQquery=$this->db->query("select * from tbl_country_m where status='A'  ORDER BY countryName ");
      foreach($stateQquery->result() as $getState){
      ?>
      <option value="<?=$getState->contryid;?>" <?=$getState->contryid==1?'selected':'';?> ><?=$getState->countryName;?></option>
      <?php } ?>
    </select>
    </div>
    </div>

    </div>
    </div>
    </div><!--panel close-->


    <div class="panel panel-default panel-transparent">
    <div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a data-toggle="collapse" data-parent="#accordion" href="#NAMEDETAILS" aria-expanded="true" aria-controls="NAMEDETAILS">
    <span><i class="fa fa-minus text-sm mr-5"></i>ORGANIZATION DETAILS</span>
    </a>
    </h4>
    </div>
    <div id="NAMEDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
    <div class="panel-body">

    <div class="row">
    <div class="form-group col-md-6">
    <input type="hidden" name="moldorgid" id="moldorgid" class="hiddenField">
    <label>*Organization Name : <span class="label label-success" id="newidorg" style="display: none;">new</span></label>
    <div class="typeahead__container ">
    <div class="typeahead__field">
    <div class="typeahead__query">
    <input class="orgnizationjs-typeahead form-control"
       name="morg_name" 
       id="morg_name"
       type="search"
       autofocus
       autocomplete="off" >
    </div>
    </div>
    </div>

    </div>
    <div class="form-group col-md-6">
    <label for="contactemail">Website : </label>
    <input type="text" name="mwebsite" id="mwebsite" class="form-control mb-10" >
    </div>
    </div>

    <div class="row">
    <div class="form-group col-md-6">
    <label>Email : </label>
      <table id="orgaddrowbox">
        <tr style="line-height: 3;">
         <td style="width:100%;">
        <input type="email" name="moemail_id[]" id="moemail_id0" class="form-control">
         </td>
         <td align="center" style="width: 150px;">
           <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
         </td>
      </tr>
      </table>
      <a style="cursor: pointer;" onclick="addRowOrgEmail();"><small>+ add one more</small></a>
    </div>
    <div class="form-group col-md-6">
    <label>Phone : </label>
    <table id="orgaddrowboxp">
      <tr style="line-height:3;">
        <td style="width:100%;">
         <input type='text' name="mophone_no[]" id="mophone_no0" class="form-control">
        </td>
        <td align="center" style="width: 150px;">
         <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
        </td>
      </tr>
    </table>
      <a  style="cursor: pointer;" onclick="addRowOrgPhone();"><small>+ add one more</small></a>
    </div>
    </div>

    </div>
    </div>
    </div><!--panel close-->

    <div class="panel panel-default panel-transparent">
    <div class="panel-heading" role="tab" id="headingThree">
    <h4 class="panel-title">
    <a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATIONS" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATIONS">
    <span><i class="fa fa-minus text-sm mr-5"></i> DESCRIPTION</span>
    </a>
    </h4>
    </div>
    <div id="DESCRIPTIONINFORMATIONS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
    <div class="panel-body">

    <div class="form-group">
    <div class="col-sm-12">
    <textarea id="multi_cnt_notes" class="summernote"></textarea>
    </div>
    </div>

    </div>
    </div>
    </div><!--panel close-->


    </div>
    </div>
    </div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="mcntsave" class="btn btn-primary">Save</button>
<span id="mcntload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>

</form>

</div>
</div>
</div>
<!-- Multiple Contact close-->

<!-- Multiple Organization  -->
<div class="modal fade" id="mutiOrgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span>Add Organization</span></h4>
<div id="resultmultiorg" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="MultiOrgzForm">
<!-- <div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label for="email">Organization : </label>
<select name="multi_org" id="multi_org" class="chosen-select form-control">
<option value="">----select----</option>
<?php 
  // $sqlprio=$this->db->query("select * from tbl_organization where status='A' ");
  // foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php //echo $fetchPrio->org_id; ?>"><?php //echo $fetchPrio->org_name ; ?></option>
<?php // } ?>
</select>
<input type="hidden" name="ldid" id="ldid" value="<?=$lead_id?>">
<input type="hidden" name="morgid" id="morgid" value="">
</div>
<div class="form-group col-md-6">
<label for="email">Details : </label>
<textarea name="org_details" id="org_details" class="form-control"></textarea>
</div>
</div>

</div>
</div>-->
<!--panel close
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="morgsave" class="btn btn-primary">Save</button>
<span id="morgload" style="display: none;">
<img src="<?php //echo base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div> -->
 	
 
<div class="modal-body">
<div class="sb-container container-example1">
<!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#ORGDETAILS" aria-expanded="true" aria-controls="ORGDETAILS">
<span><i class="fa fa-minus text-sm mr-5"></i> ORGANIZATION DETAILS</span>
</a>
</h4>
</div>
<div id="ORGDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">

<input type="hidden" name="oleadid" id="oleadid" value="<?=$lead_id?>">
<input type="hidden" name="omorgid" id="omorgid" value="">

<input type="hidden" name="doldorgid" id="doldorgid" class="hiddenField">
<label>*Organization Name : <span class="label label-success" id="dnewidorg" style="display: none;">new</span></label>
<div class="typeahead__container ">
<div class="typeahead__field">
<div class="typeahead__query">
<input class="orgnizationjs-typeaheadmulti form-control"
   name="dorg_name" 
   id="dorg_name"
   type="search"
   autofocus
   autocomplete="off" required="">
</div>
</div>
</div>

</div>
<div class="form-group col-md-6">
<label for="contactemail">Website : </label>
<input type="text" name="dwebsite" id="dwebsite" class="form-control mb-10" required="">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Email : </label>
  <table id="daddrowbox">
    <tr style="line-height: 3;">
     <td style="width:100%;">
    <input type="email" name="demail_id[]" id="demail_id0" class="form-control">
     </td>
     <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
     </td>
  </tr>
  </table>
  <a style="cursor: pointer;" onclick="daddRowOrgEmail();"><small>+ add one more</small></a>
</div>
<div class="form-group col-md-6">
<label>Phone : </label>
<table id="daddrowboxp">
  <tr style="line-height:3;">
    <td style="width:100%;">
     <input type='text' name="dphone_no[]" id="dphone_no0" class="form-control">
    </td>
    <td align="center" style="width: 150px;">
     <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
    </td>
  </tr>
</table>
  <a  style="cursor: pointer;" onclick="daddRowOrgPhone();"><small>+ add one more</small></a>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Address : </label>
<textarea name="daddress" id="daddress"  class="form-control" ></textarea>
</div>
<div class="form-group col-md-6">
<label>City : </label>
<input type='text' name="dcity" id="dcity" class="form-control" >
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>State : </label>
<select name="dstate_id" id="dstate_id" class="chosen-select form-control">
  <option value="">--Select--</option>
  <?php
  $stateQquery=$this->db->query("select * from tbl_state_m where status='A'  ORDER BY stateName ");
  foreach($stateQquery->result() as $getState){
  ?>
  <option value="<?=$getState->stateid;?>"><?=$getState->stateName;?></option>
  <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Pin Code : </label>
<input type="number" name="dpin_code" id="dpin_code" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Country : </label>
<select name="dcountry_id" id="dcountry_id" class="chosen-select form-control">
  <option value="">--Select--</option>
  <?php
  $stateQquery=$this->db->query("select * from tbl_country_m where status='A'  ORDER BY countryName ");
  foreach($stateQquery->result() as $getState){
  ?>
  <option value="<?=$getState->contryid;?>" <?php if($getState->contryid == 1) { ?>selected <?php } ?> ><?=$getState->countryName;?></option>
  <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#NAMEDETAILS" aria-expanded="true" aria-controls="NAMEDETAILS">
<span><i class="fa fa-minus text-sm mr-5"></i>ORGANIZATION CONTACT PERSON DETAILS</span>
</a>
</h4>
</div>
<div id="NAMEDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<input type="hidden" name="doldcontact_id" id="doldcontact_id" class="hiddenField">
<label>*Contact Person Name : <span class="label label-success" id="dnewid" style="display: none;">new</span></label>
<div class="typeahead__container ">
<div class="typeahead__field">
<div class="typeahead__query">
<input class="js-typeaheadmulti form-control"
       name="dcontact_name" 
       id="dcontact_name"
       type="search"
       autofocus
       autocomplete="off" >
</div>
</div>
</div>
</div>
<div class="form-group col-md-6">
<label>Designation : </label>
<input type="text" name="ddesignation" id="ddesignation"  class="form-control">
</div>
</div>

<div class="row">

<div class="form-group col-md-6">
 <label>Email : </label>
  <table id="dcntaddrowbox">
    <tr style="line-height: 3;">
     <td style="width:100%;">
       <input type="email" name="dcemail_id[]" id="dcemail_id0" class="form-control">
     </td>
     <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
     </td>
  </tr>
  </table>
  <a style="cursor: pointer;" onclick="daddRowCompemail();"><small>+ add one more</small></a>
 </div>
  <div class="form-group col-md-6">
  <label>Phone : </label>
  <table id="dcntaddrowboxp">
    <tr style="line-height:3;">
      <td style="width:100%;">
       <input type='text' name="dcphone_no[]" id="dcphone_no0" class="form-control">
      </td>
      <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
      </td>
    </tr>
  </table>
  <a  style="cursor: pointer;" onclick="daddRowComphone();"><small>+ add one more</small></a>
</div>

</div>

</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATION" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATION">
<span><i class="fa fa-minus text-sm mr-5"></i> DESCRIPTION</span>
</a>
</h4>
</div>
<div id="DESCRIPTIONINFORMATION" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea id="multi_org_notes" class="summernote"></textarea>
</div>
</div>
</div>
</div>
</div><!--panel close-->


</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="morgsave" class="btn btn-primary">Save</button>
  <span id="morgload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>



 </form>
</div>
</div>
</div>
<!-- Multiple Organization Close -->


<!-- Modal Notes -->
<div class="modal fade" id="leadNoteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Remarks</h4>
<div id="resultnote" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="LeadNotesForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">
<div class="row" style="display: none;">
<div class="form-group col-md-6">
<label for="email">Remarks Title : </label>
<input type="text" name="note_name" id="note_name" class="form-control">

<input type="text" name="leadidno" id="leadidno" value="<?=$lead_id?>">
<input type="text" name="main_id" id="main_id" value="" >
<input type="text" name="noteid" id="noteid" value="">
</div>
<div class="form-group col-md-6">
<label for="email">Date : </label>
<div class='input-group datepicker'>
<input type='text' class="form-control" name="note_date" id="note_date" >
<span class="input-group-addon"> <span class="fa fa-calendar"></span> </span> 
</div>
</div>
</div>

<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea id="summernotesid" class="form-control summernote"></textarea>
</div>
</div>
</div>

</div>
</div>
<!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="leadnotesave" class="btn btn-primary">Save</button>
<span id="notesaveload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div>
<!-- Modal Notes Close-->



<!-- Modal Files -->
<div class="modal fade" id="fileLeadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Files</h4>
<div id="resultfile" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="FilesLeadForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label for="email">Files : </label>
<input type="file" name="files_name" id="files_name" class="input-sm form-control" onchange="loadFile(this)" required="">
<input type="hidden" name="leadidno" id="leadidno" value="<?=$lead_id?>">
<input type="hidden" name="fileid" id="fileid" value="" class="hiddenField">
<a id="image" target="_blank" href="<?=base_url()?>img/no_image.png" >Uploaded File</a>
</div>
<div class="form-group col-md-6">
<label for="email">Description : </label>
<textarea name="files_desc" id="files_desc" class="form-control"></textarea>
</div>
</div>

</div>
</div>
<!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="fileformsave" class="btn btn-primary">Save</button>
<span id="filesaveload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div>
<!-- Modal Files Close -->


<!-- Lead Rates -->
<div class="modal fade" id="modalRates" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Lead Rate</h4>
<div id="resultrate" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="LeadRatesForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">


<div class="row">
<div class="form-group col-md-6">
<label for="email">Rates : </label>  
<input type="hidden" name="rateleadid" id="rateleadid" value="<?=$lead_id?>">
<input type="hidden" name="rateid" id="rateid" value="" class="hiddenField">
<select name="lead_rates" id="lead_rates" class="form-control" required="">
<option value="" >--Select--</option>  
<option value="General" >General</option>
<option value="Special" >Special</option>
</select>
</div>
<div class="form-group col-md-6">
<label for="email">From : </label>
<input name="lead_from" id="lead_from" class="form-control" required="">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">To : </label>
<input name="lead_to" id="lead_to" class="form-control">
</div>
<div class="form-group col-md-6">
<label for="email">Product : </label>
<input name="lead_product" id="lead_product" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">Rate Type : </label>
<select name="rate_type" id="rate_type" class="form-control">
<option value="" >--Select--</option>
<option value="Package" >Package</option>
<option value="Weight" >Weight</option>
</select>
</div>
<div class="form-group col-md-6">
<label for="email">Basic Freight : </label>
<input name="bsc_frght" id="bsc_frght" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">Gr Charge : </label>
<input name="gr_chrg" id="gr_chrg" class="form-control">
</div>
<div class="form-group col-md-6">
<label for="email">Labour Charge : </label>
<input name="lbr_chrg" id="lbr_chrg" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">Enroute Charge : </label>
<input name="enrt_chrg" id="enrt_chrg" class="form-control">
</div>
<div class="form-group col-md-6">
<label for="email">Delivery Charge : </label>
<input name="dlvry_charge" id="dlvry_charge" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">Misc Charges : </label>
<input name="misc_charge" id="misc_charge" class="form-control">
</div>
<div class="form-group col-md-6">
<label for="email">Risk  Charge : </label>
<input name="risk_charge" id="risk_charge" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">Remarks : </label>
<textarea name="rate_rmrks" id="rate_rmrks" class="form-control"></textarea>
</div>
<div class="form-group col-md-6">
<label for="email">Files : </label>
<input type="file" name="rates_file" id="rates_file" class="input-sm form-control" onchange="loadFile(this)">
<a id="image" target="_blank" href="<?=base_url()?>img/no_image.png" >Uploaded File</a>
</div>
</div>


</div>
</div>
<!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="ratesave" class="btn btn-primary">Save</button>
<span id="rateload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div>
<!-- Close Lead Rates -->


<!-- Pipeline Stage -->
<div class="modal fade" id="modalStage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span>Change Pipeline Stage</span></h4>
<div id="resultstage" style="font-size: 15px;color: red; text-align:center"></div>
</div>
<form id="changeStageForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">

<div class="row">

<div class="form-group col-md-6">
<label for="email">New Stage : </label>
<select name="new_stage" id="new_stage" class="form-control" onchange="chkStage(this.value);">
<option value="">----select----</option>
<?php 
  if($this->session->userdata('role') == 1){
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='20' ");  
  }
  else
  {
    $sqlprio=$this->db->query("select * from tbl_master_data where param_id='20' AND serial_number !='78' ");
  }
  
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php //if($fetchPrio->serial_number == $result->stage) {?><?php //} ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
<input type="hidden" name="stage_leadid" id="stage_leadid" value="<?=$lead_id?>">
</div>

</div>



</div>
</div>
<!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="stagesave" class="btn btn-primary">Save</button>
<span id="stageload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div><!-- Pipeline Stage Close -->


<!-- Lead state -->
<div class="modal fade" id="leadStateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span>New Lead Status</span></h4>
<div id="resultstate" style="font-size: 15px;color: red; text-align:center"></div>
</div>

<form id="LeadStateForm">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div class="panel panel-default panel-transparent">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<label for="email">New State : </label>
<select name="new_state" id="new_state" class="form-control" onchange="chkStatus(this.value);" >
<option value="">----select----</option>
<?php 
  $sqlprio=$this->db->query("select * from tbl_master_data where param_id='22' AND serial_number !='79' ");
  foreach ($sqlprio->result() as $fetchPrio){
?>
<option value="<?php echo $fetchPrio->serial_number; ?>" <?php if($fetchPrio->default_v == 'Y') {?>selected <?php } ?>><?php echo $fetchPrio->keyvalue ; ?></option>
<?php }?>
</select>
<input type="hidden" name="state_leadid" id="state_leadid" value="<?=$lead_id?>">
<input type="hidden" name="ldstg" id="ldstg" value="<?=$stage?>">
</div>

<div class="form-group col-md-6">
<label for="email">Reason : </label>
<textarea name="lead_stat_desc" id="lead_stat_id" class="form-control"></textarea>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label for="email">Files : </label>
<input type="file" name="sts_file" id="sts_file" class="input-sm form-control" onchange="loadFile(this)">
<a id="image" target="_blank" href="<?=base_url()?>img/no_image.png" >Uploaded File</a>
</div>

<div id="docket" style="display: none;">
<div class="form-group col-md-6">
<label for="email">Docket No. : </label>
<input name="docket_no" id="docket_no" class="form-control" required="">
</div>
</div>
</div>

</div><!--panel close-->
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="statesave" class="btn btn-primary">Save</button>
<span id="stateload" style="display: none;">
<img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
</span>
</div>
</form>
</div>
</div>
</div>
</div>
<!-- Close Lead State -->







<?php  
if($this->input->post('id') == "")
{
    $this->load->view('footer.php');
} ?>

          
          <!--///////////////////////// lead script /////////////////////////////-->


<script type="text/javascript">
     function chkStage(v)
     {
      var ur = "ajax_chKRates";
      var abc = $('#stage_leadid').val();
       //alert(abc);
          if(v==53)
          {
              $.ajax({
              type:'POST',
              url:ur,
              data:{'id':abc},
              success:function(data)
              {
                  //alert(data);
                 // console.log(data);
                 if(data == 1){
                   //alert("Please Fill Rate First!");
                   $("#resultstage").html("Please Fill Rate First!");
                   $("#stagesave").prop('disabled', true);
                 }

              }
           }); 
          }else{
             $("#resultstage").html('');
             $("#stagesave").prop('disabled', false);
           } 
     }

    function chkStatus(v){
          //alert(v);
          if(v == 66)
          {
               $("#docket").css("display","block");              
          }
          else
          {     
               $("#docket").css("display","none");
          }
    }
     

$("#docket_no").keyup(function(){

var dktno=$("#docket_no").val();
  
  $.ajax({
    type : 'POST',
    url : "ajax_chk_docketno",
    data : {'dkt_no':dktno},
    success:function(data)
    {
      if(data == 1)
      {
        $("#resultstate").html("Docket Number Already Exists!");
        $("#statesave").prop('disabled', true);
      }
      else
      {
        $("#resultstate").html("");
        $("#statesave").prop('disabled', false);
      }
    }

  });

});


$(document).ready(function(){
    
  $("#status").change(function(){
        var status=$("#status").val();
          
     if(status == 19)
     {
          $("#progress").val("0");   
     }
     else if(status == 20)
     {
          $("#progress").val("50");          
     }
     else if(status == 21)
     {
          $("#progress").val("100");    
     }
     
    });   

});


function checkStageStaus(v)
{
 //alert(v);
 if(v == 'Won' || v == 'Lost'){
  alert("First Change The Lead Status To Open ? Then You Can Change The Lead Stage !");
 }else{
  //href="#" data-toggle="modal" data-target="#modalStage" formid="#changeStageForm" id="formresetstage"
  $(".formresetstageid").attr("href", "#");
  $(".formresetstageid").attr("id", "formresetstage");
  $(".formresetstageid").attr("data-toggle", "modal");
  $(".formresetstageid").attr("data-target", "#modalStage");
  $(".formresetstageid").attr("formid", "#changeStageForm");
 }
}

function mainLeadIdNote(v)
{    
     // alert(v);
     $("#main_id").val(v);
} 


function changeDefaultOrg(ths,idval,mlead_id,multiorgid,type){
      //alert(idval);

      ur ="<?=base_url('master/master/ajax_defaultOrg');?>";
      var mode   = $(ths).prop('checked');
     
      //alert(multiorgid);
      
       $.ajax({
          type:'POST',
          url:ur,
          data:{'mode':mode,'id':idval,'leadid':mlead_id,'orgid':multiorgid,'mtype':type},
          success:function(data)
          {
              //alert(data);
             // console.log(data);
             if(data == 1){
               location.reload();
             }             
          }
       });
    }

  //===============End===============  

function addRowCompemail(emailval = "",rowid = "")
{

    var  style = "";

    var compemailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="email_id[]" id="email_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#compaddrowboxemail').append(compemailData);

}
 

function addRowComphone(phoneval = "",prowid = "")
{

    var  style = "";

    var comphoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="tel" name="phone_no[]" id="phone_no'+prowid+'" minlength="10" maxlength="11" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#compaddrowboxphone').append(comphoneData);

}
 
   function removeRows(ths){
     $(ths).parent().parent().remove();
   }




    var json_contact     = JSON.parse($('#json_contact').val());

      //var json_orgnization = JSON.parse($('#json_orgnization').val());

      //console.log(json_contact);
     
      var data = {countries:json_contact};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeaheadlead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new contact !",
            source: {
                country: {
                    data: data.countries
                },
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    event.preventDefault();

                    var splitjs = item.display.split('^');
                    var sindex  = "";
                    if(splitjs.indexOf(1)==-1)
                      sindex = splitjs[1];

                      ur = "ajaxget_contactAlldata";
                      $.ajax({
                          type : "POST",
                          url  :  ur,
                          data :  {'id':sindex}, // serializes the form's elements.
                            success : function (data) {
                             // alert(data); // show response from the php script.
                             if(data != false){
                                 console.log(data);
                                 data = JSON.parse(data);
                                
                                 $("#ldnewid").css("display","none");
                                 $('#contacttypahead').val(data.contact_name);
                                 $('#lead_number').val(data.contact_name+'_');
                                 $('#oldcontact').val(data.contact_id);
                               
                                
                                  if(data.cemail != ""){
                                    j_email = JSON.parse(data.cemail);
                                    console.log(j_email);
                                       if(j_email != ""){
                                        for(var i=0;i<j_email.length;i++){
                                          if(i == 0)
                                            $('#email_id0').val(j_email[0]);
                                          else
                                            addRowCompemail(j_email[i],i);

                                     }
                                    }
                                  }

                                   if(data.cphone != ""){
                                    j_phone = JSON.parse(data.cphone);
                                    if(j_phone != ""){
                                    for(var i=0;i<j_phone.length;i++){
                                      if(i == 0)
                                       $('#phone_no0').val(j_phone[0]);
                                      else
                                       addRowComphone(j_phone[i],i);

                                      }
                                    }
                                  }
                                 
                                 $('#address').val(data.caddress);
                                 $('#orgidcontant').val(data.org_id);
                                                              
                              }
                          }
                      });          
                    $('.js-result-container').text('');
               },
                onSearch:function (node, query) {
                  console.log(node);
                  if(query != "")
                  $("#ldnewid").css("display","inline");
                  $(".project_images").remove();
                  $('#oldcontact').val("");
                  $('#org_name').val("");
                  $('#orgidcontant').val("");
                  $('lead_number').val("");
                  $('#email_id0').val("");
                  $('#phone_no0').val("");
                  $('#address').val("");
                  $('#lead_number').val($('#contacttypahead').val()+'_'+$('#org_name').val());
                  $('#summernote').code("");
                },
                onResult: function (node, query, obj, objCount) {
                    var text = "";
                    if (query !== "") {
                        text = objCount + ' elements matching "' + query + '"';
                    }
                   console.log(node);
                   $("#ldnewid").css("display","none");
                   if(objCount == 0)
                    $("#ldnewid").css("display","inline");
                    $('.js-result-container').text(text);
               }
            },
           // debug: true
        });



      var json_orgnization = JSON.parse($('#json_orgnization').val());

      var data = {countries:json_orgnization};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".orgnizationjs-typeaheadlead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new organization !",
            source: {
              country: {
                data: data.countries
              },
            },
            callback: {
              onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                var splitjs = item.display.split('^');
                var sindex  = "";
                  if(splitjs.indexOf(1)==-1)
                    sindex = splitjs[1];
                    ur = "ajaxget_organizationAlldata";
                    $.ajax({
                        type : "POST",
                        url  :  ur,
                        data :  {'id':sindex}, // serializes the form's elements.
                          success : function (data) {
                          // alert(data); // show response from the php script.
                          if(data != false){
                            console.log(data);
                            data = JSON.parse(data);
                            $('#orgidcontant').val(data.org_id);
                            $('#org_name').val(data.org_name);
                            $('#lead_number').val($('#contacttypahead').val()+'_'+data.org_name);
                          }
                        }
                      });          
                    $('.js-result-container').text('');
                },
                onSearch:function (node, query) {
                 console.log(node);
                 $('#orgidcontant').val("");
                 $('#lead_number').val($('#contacttypahead').val()+'_'+$('#org_name').val());
              },
                onResult: function (node, query, obj, objCount) {
                  var text = "";
                  if(query !== ""){
                    text = objCount + ' elements matching "' + query + '"';
                  }
                   console.log(node);
                   $("#ldnewidorg").css("display","none");
                  if(objCount == 0)
                   $("#ldnewidorg").css("display","inline");
                   $('.js-result-container').text(text);
                }
             },
            // debug: true
        });
 
</script> 







              <!--//////////////////////// multiple contact /////////////////////-->


<script type="text/javascript">


function addRowCntphone(phoneval = "",prowid = "")
{

    var style = "";

    var cphoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="text" name="mphone_no[]" id="mphone_no'+prowid+'" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#cntaddrowboxp').append(cphoneData);

}

function addRowCntemail(emailval = "",rowid = "")
{

  var  style = "";

  var emailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="memail_id[]" id="memail_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
  
   $('#cntaddrowbox').append(emailData);

}


   function removeRows(ths){
     $(ths).parent().parent().remove();
   }


function addRowOrgEmail(emailval = "",rowid = "")
{

     var style = "";

     var orgEmailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="moemail_id[]" id="moemail_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#orgaddrowbox').append(orgEmailData);

}

function addRowOrgPhone(phoneval = "",prowid = "")
{
  
   var  style = "";

    var orgPhoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="text" name="mophone_no[]" id="mophone_no'+prowid+'" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#orgaddrowboxp').append(orgPhoneData);

}
</script>



<script type="text/javascript">
  
       var base_urls = "ritco-crm";

       var json_contact     = JSON.parse($('#json_contact').val());
       //console.log(json_contact);
       var data = {countries:json_contact};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new contact !",
            source: {
                country: {
                    data: data.countries
                },
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    event.preventDefault();

                    var splitjs = item.display.split('^');
                    var sindex  = "";
                    if(splitjs.indexOf(1)==-1)
                      sindex = splitjs[1];

                      ur = "/"+base_urls+"/contact/Contact/ajaxget_contactAlldata";
                      $.ajax({
                          type : "POST",
                          url  :  ur,
                          data :  {'id':sindex}, // serializes the form's elements.
                            success : function (data) {
                             // alert(data); // show response from the php script.
                             if(data != false){
                                 console.log(data);
                                 data = JSON.parse(data);

                                 $("#newid").css("display","none");
                                 $('#moldcontact_id').val(data.contact_id);
                                 $('#mcontact_name').val(data.contact_name);
                                 $('#mdesignation').val(data.designation);
                                
                                  if(data.email != ""){
                                    j_email = JSON.parse(data.email);
                                    console.log(j_email);
                                       if(j_email != ""){
                                        for(var i=0;i<j_email.length;i++){
                                          if(i == 0)
                                            $('#memail_id0').val(j_email[0]);
                                          else
                                            addRowCntemail(j_email[i],i);

                                     }
                                    }
                                  }

                                   if(data.phone != ""){
                                    j_phone = JSON.parse(data.phone);
                                    if(j_phone != ""){
                                    for(var i=0;i<j_phone.length;i++){
                                      if(i == 0)
                                       $('#mphone_no0').val(j_phone[0]);
                                      else
                                       addRowCntphone(j_phone[i],i);

                                      }
                                    }
                                  }

                                $('#maddress').val(data.address);
                                $('#mcity').val(data.city_name);
                                $('#mstate_id').val(data.state_id).trigger("chosen:updated");
                                $('#mpin_code').val(data.pincode);
                                $('#mcountry_id').val(data.country).trigger("chosen:updated");
  
                              }
                          }
                      });          
                    $('.js-result-container').text('');
               },
                onSearch:function (node, query) {
                  console.log(node);
                  if(query != "")
                  $("#newid").css("display","inline");
                  $('.project_images').remove();
                  $('#moldcontact_id').val("");
                  $('#mdesignation').val("");
                  $('#memail_id0').val("");
                  $('#mphone_no0').val("");
                  $('#maddress').val("");
                  $('#mcity').val("");
                  $('#mstate_id').val("").trigger("chosen:updated");
                  $('#mpin_code').val("");
                  $('#mcountry_id').val("").trigger("chosen:updated");
        
                },
                onResult: function (node, query, obj, objCount) {
                    var text = "";
                    if (query !== "") {
                        text = objCount + ' elements matching "' + query + '"';
                    }
                   console.log(node);
                   $("#newid").css("display","none");
                   if(objCount == 0)
                    $("#newid").css("display","inline");
                    $('.js-result-container').text(text);
               }
            },
           // debug: true
        });



     var json_orgnization = JSON.parse($('#json_orgnization').val());
      //console.log(json_orgnization);
     var data = {countries:json_orgnization};
        typeof $.typeahead === 'function' && $.typeahead({
            input: ".orgnizationjs-typeahead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new organization !",
            source: {
              country: {
                data: data.countries
              },
            },
            callback: {
              onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                var splitjs = item.display.split('^');
                var sindex  = "";
                  if(splitjs.indexOf(1)==-1)
                    sindex = splitjs[1];
                    ur = "/"+base_urls+"/contact/Contact/ajaxget_organizationAlldata";
                    $.ajax({
                        type : "POST",
                        url  :  ur,
                        data :  {'id':sindex}, // serializes the form's elements.
                          success : function (data) {
                          // alert(data); // show response from the php script.
                          if(data != false){
                            console.log(data);
                            data = JSON.parse(data);
                            $('#moldorgid').val(data.org_id);
                            $('#morg_name').val(data.org_name);
                            $('#mwebsite').val(data.website);

                                 if(data.oemail != ""){
                                    j_oemail = JSON.parse(data.oemail);
                                       if(j_oemail != ""){
                                        for(var i=0;i<j_oemail.length;i++){
                                          if(i == 0)
                                            $('#moemail_id0').val(j_oemail[0]);
                                          else
                                            addRowOrgEmail(j_oemail[i],i);

                                     }
                                    }
                                  }


                                  if(data.ophone_no != ""){
                                    j_ophone = JSON.parse(data.ophone_no);
                                  if(j_ophone != ""){
                                    for(var i=0;i<j_ophone.length;i++){
                                      if(i == 0)
                                       $('#mophone_no0').val(j_ophone[0]);
                                      else
                                       addRowOrgPhone(j_ophone[i],i);
                                      
                                      }
                                    }
                                  }

                          }
                        }
                      });          
                    $('.js-result-container').text('');
                },
                onSearch:function (node, query) {
                 console.log(node);
                  $('#moldorgid').val("");
                  $('.project_images').remove();
                  $('#mwebsite').val("");
                  $('#moemail_id0').val("");
                  $('#mophone_no0').val("");
              },
                onResult: function (node, query, obj, objCount) {
                  var text = "";
                  if(query !== ""){
                    text = objCount + ' elements matching "' + query + '"';
                  }
                   console.log(node);
                   $("#newidorg").css("display","none");
                  if(objCount == 0)
                   $("#newidorg").css("display","inline");
                   $('.js-result-container').text(text);
                }
             },
            // debug: true
        });



</script>





              <!--////////////////////// multiple organization /////////////////////////-->



<script type="text/javascript">


  function daddRowOrgEmail(emailval = "",rowid = ""){

     var style = "";


    var orgEmailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="demail_id[]" id="demail_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#daddrowbox').append(orgEmailData);

   }

  function daddRowOrgPhone(phoneval = "",prowid = ""){
    
	
    var  style = "";

    var orgPhoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="text" name="dphone_no[]" id="dphone_no'+prowid+'" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#daddrowboxp').append(orgPhoneData);

   }
 
   function removeRows(ths){
     $(ths).parent().parent().remove();
   }



function daddRowComphone(phoneval = "",prowid = "")
{

    var style = "";

    var cphoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="text" name="dcphone_no[]" id="dcphone_no'+prowid+'" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#dcntaddrowboxp').append(cphoneData);

}

function daddRowCompemail(emailval = "",rowid = "")
{
  
    var  style = "";

    var emailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="dcemail_id[]" id="dcemail_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#dcntaddrowbox').append(emailData);

}

</script>

<script type="text/javascript">
	  
      var base_urls = "ritco-crm";

      var json_orgnization = JSON.parse($('#json_orgnization').val());
      //console.log(json_orgnization);
      var data = {countries:json_orgnization};
        typeof $.typeahead === 'function' && $.typeahead({
            input: ".orgnizationjs-typeaheadmulti",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new organization !",
            source: {
              country: {
                data: data.countries
              },
            },
            callback: {
              onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                var splitjs = item.display.split('^');
                var sindex  = "";
                  if(splitjs.indexOf(1)==-1)
                    sindex = splitjs[1];
                    ur = "/"+base_urls+"/organization/Organization/ajaxget_organizationAlldata";
                    $.ajax({
                        type : "POST",
                        url  :  ur,
                        data :  {'id':sindex}, // serializes the form's elements.
                          success : function (data) {
                          // alert(data); // show response from the php script.
                          if(data != false){
                            console.log(data);
                            data = JSON.parse(data);
                            $('#doldorgid').val(data.org_id);
                            $('#dorg_name').val(data.org_name);
                            $('#dwebsite').val(data.website);

                                 if(data.email != ""){
                                    j_oemail = JSON.parse(data.email);
                                       if(j_oemail != ""){
                                        for(var i=0;i<j_oemail.length;i++){
                                          if(i == 0)
                                            $('#demail_id0').val(j_oemail[0]);
                                          else
                                            daddRowOrgEmail(j_oemail[i],i);

                                     }
                                    }
                                  }


                                  if(data.phone_no != ""){
                                    j_ophone = JSON.parse(data.phone_no);
                                  if(j_ophone != ""){
                                    for(var i=0;i<j_ophone.length;i++){
                                      if(i == 0)
                                       $('#dphone_no0').val(j_ophone[0]);
                                      else
                                       daddRowOrgPhone(j_ophone[i],i);
                                      
                                      }
                                    }
                                  }

                              $('#daddress').val(data.address);
            								  $('#dcity').val(data.city);
            								  $('#dstate_id').val(data.state_id).trigger("chosen:updated");
            								  $('#dpin_code').val(data.pin_code);
            								  $('#dcountry_id').val(data.country).trigger("chosen:updated");
                          }
                        }
                      });          
                    $('.js-result-container').text('');
                },
                onSearch:function (node, query) {
                 console.log(node);
                  $('#doldorgid').val("");
                  $('.project_images').remove();
                  $('#dwebsite').val("");
                  $('#demail_id0').val("");
                  $('#dphone_no0').val("");
                  $('#daddress').val("");
                  $('#dcity').val("");
                  $('#dstate_id').val("").trigger("chosen:updated");
                  $('#dpin_code').val("");
                  $('#dcountry_id').val("").trigger("chosen:updated");
              },
                onResult: function (node, query, obj, objCount) {
                  var text = "";
                  if(query !== ""){
                    text = objCount + ' elements matching "' + query + '"';
                  }
                   console.log(node);
                   $("#dnewidorg").css("display","none");
                  if(objCount == 0)
                   $("#dnewidorg").css("display","inline");
                   $('.js-result-container').text(text);
                }
             },
            // debug: true
        });




       var json_contact     = JSON.parse($('#json_contact').val());
       //console.log(json_contact);
       var data = {countries:json_contact};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeaheadmulti",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new contact !",
            source: {
                country: {
                    data: data.countries
                },
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    event.preventDefault();

                    var splitjs = item.display.split('^');
                    var sindex  = "";
                    if(splitjs.indexOf(1)==-1)
                      sindex = splitjs[1];

                      ur = "/"+base_urls+"/organization/Organization/ajaxget_contactAlldata";
                      $.ajax({
                          type : "POST",
                          url  :  ur,
                          data :  {'id':sindex}, // serializes the form's elements.
                            success : function (data) {
                             // alert(data); // show response from the php script.
                             if(data != false){
                                 console.log(data);
                                 data = JSON.parse(data);

                                 $("#dnewid").css("display","none");
                                 $('#doldcontact_id').val(data.contact_id);
							                   $('#dcontact_name').val(data.contact_name);
                                 $('#ddesignation').val(data.designation);
                                
                                  if(data.cemail != ""){
                                    j_email = JSON.parse(data.cemail);
                                    console.log(j_email);
                                       if(j_email != ""){
                                        for(var i=0;i<j_email.length;i++){
                                          if(i == 0)
                                            $('#dcemail_id0').val(j_email[0]);
                                          else
                                            daddRowCompemail(j_email[i],i);

                                     }
                                    }
                                  }

                                   if(data.cphone != ""){
                                    j_phone = JSON.parse(data.cphone);
                                    if(j_phone != ""){
                                    for(var i=0;i<j_phone.length;i++){
                                      if(i == 0)
                                       $('#dcphone_no0').val(j_phone[0]);
                                      else
                                       addRowComphone(j_phone[i],i);

                                      }
                                    }
                                  }
  
                              }
                          }
                      });          
                    $('.js-result-container').text('');
               },
                onSearch:function (node, query) {
                  console.log(node);
                  if(query != "")
                  $("#dnewid").css("display","inline");
                  $('.project_images').remove();
                  $('#doldcontact_id').val("");
                  $('#ddesignation').val("");
                  $('#dcemail_id0').val("");
                  $('#dcphone_no0').val("");
        
                },
                onResult: function (node, query, obj, objCount) {
                    var text = "";
                    if (query !== "") {
                        text = objCount + ' elements matching "' + query + '"';
                    }
                   console.log(node);
                   $("#dnewid").css("display","none");
                   if(objCount == 0)
                    $("#dnewid").css("display","inline");
                    $('.js-result-container').text(text);
               }
            },
           // debug: true
        });

</script>
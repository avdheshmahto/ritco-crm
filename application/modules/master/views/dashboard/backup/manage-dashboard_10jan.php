
<?php $this->load->view('header.php'); ?>

<section id="content">
<div class="page page-forms-common">
<div class="pageheader"></div>


<!-- -----------------------------Lead--------------------------- -->

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

?>


<div class="tile-body">
<div class="row">	
<?php if($this->session->userdata('role') == 1) { ?>		
<div class="col-sm-7">
<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%"> <i class="fa fa-calendar"></i>&nbsp; <span></span> <i class="fa fa-caret-down pull-right"></i> </div>
</div>
<div class="col-sm-5">
<div class="btn-group">
<select class="chosen-select form-control" url="<?=base_url('master/master/manage_dashboard?');?>" id="userleadstage" >
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
<?php } ?>
</div>  <!-- row close -->

<div class="pull-right" style="margin: 20px 50px 0px 0px; font-size: 11px;">
<strong>Total Lead : </strong><?=$getTotalLead;?><br>
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
$totalds=$this->db->query("select * from tbl_leads where status='A' ");
$getTtlLead=$totalds->num_rows();

$opn=$this->db->query("select * from tbl_leads where status='A' and lead_state='65' ");
$getOpn=$opn->num_rows();

$won=$this->db->query("select * from tbl_leads where status='A' and lead_state='66' ");
$getWon=$won->num_rows();

$lost=$this->db->query("select * from tbl_leads where status='A' and lead_state='67' ");
$getLost=$lost->num_rows();

$frz=$this->db->query("select * from tbl_leads where status='A' and lead_state='79' ");
$getFreeze=$clsd->num_rows();

?>

<div class="tile-body">
<div class="row">
<?php if($this->session->userdata('role') == 1) { ?>  	
<div class="col-sm-7">
<div id="reportrange1" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%"> <i class="fa fa-calendar"></i>&nbsp; <span></span> <i class="fa fa-caret-down pull-right"></i> </div>
</div>
<div class="col-sm-5">
<div class="btn-group">
<select class="chosen-select form-control" url="<?=base_url('master/master/manage_dashboard?');?>" id="userleadstatus" >
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
<?php } ?>
</div><!--row close-->

<div class="pull-right" style="margin: 20px 50px 0px 0px; font-size: 11px;">
<strong>Total Lead : </strong><?=$getTtlLead;?><br>
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

?>

<div class="tile-body">
<div class="pull-right" style="margin: 20px 50px 0px 0px; font-size: 11px;">
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
$totaltask=$this->db->query("select * from tbl_task where status='A' ");
$getTotalTask=$totaltask->num_rows();

$ntstrt=$this->db->query("select * from tbl_task where status='A' and task_status='19' ");
$getNotStrtd=$ntstrt->num_rows();

$inprgrs=$this->db->query("select * from tbl_task where status='A' and task_status='20' ");
$getPrgrs=$inprgrs->num_rows();

$cmplt=$this->db->query("select * from tbl_task where status='A' and task_status='21' ");
$getCmplt=$cmplt->num_rows();

?>

<div class="tile-body">
<div class="pull-right" style="margin: 20px 50px 0px 0px; font-size: 11px;">
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






<!--dashboard js-->   
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/chart_dashboard/labelschart_css/jquery.jqChart.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/chart_dashboard/labelschart_css/styles.css" />
<script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery.jqChart.min.js" type="text/javascript"></script> 
<!--dashboard js close-->   

<!----------------------------------------Funnel Chart 1 ------------------------------------------------->
<?php 

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

	  $leadstg = $this->db->query("select *,count('stage') as countval from tbl_leads where status='A' AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstage']."') GROUP BY stage");
      
}
else
{
	  $leadstg=$this->db->query("select *,count('stage') as countval from tbl_leads GROUP BY stage");	
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

<?php 


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


	$leadsts=$this->db->query("select *,count('lead_state') as countval from tbl_leads where status='A' AND (lead_id in ($allLeadIDs) OR maker_id='".$_GET['userleadstatus']."') GROUP BY lead_state ");
}
else
{
	$leadsts=$this->db->query("select *,count('lead_state') as countval from tbl_leads GROUP BY lead_state ");	
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

<?php 
$tasknm=$this->db->query("select task_name,count('task_name') as countval from tbl_task GROUP BY task_name");

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

<?php 
$task=$this->db->query("select task_status,count('task_status') as countval from tbl_task GROUP BY task_status");

foreach ($task->result() as $getTask) 
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


<script type="text/javascript" src="<?=base_url();?>/assets/assets/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>/assets/assets/daterangepicker/daterangepicker.css">

<script type="text/javascript">
                               $(function() {

                                   var start = moment().subtract(29, 'days');
                                   var end = moment();

                                   function cb(start, end) {
                                       $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                                   }

                                   $('#reportrange').daterangepicker({
                                       startDate: start,
                                       endDate: end,
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



                               $(function() {

                                   var start = moment().subtract(29, 'days');
                                   var end = moment();

                                   function cb(start, end) {
                                       $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                                   }

                                   $('#reportrange1').daterangepicker({
                                       startDate: start,
                                       endDate: end,
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


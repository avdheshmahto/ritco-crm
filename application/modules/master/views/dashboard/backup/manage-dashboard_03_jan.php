
<?php $this->load->view('header.php'); ?>

<!--/ CONTROLS Content -->
<section id="content">
<div class="page page-forms-common">

<div class="pageheader">
<!-- <h2> Dashboard</h2>
<div class="page-bar">
<ul class="page-breadcrumb">
<li> <a href="#"><i class="fa fa-home"></i> Ricto</a> </li>
<li> <a href="#">Dashboard</a> </li>
</ul>
</div> -->
</div>



<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Task Status</strong></h1>
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
<div id="container2"></div>
</div><!-- /tile body -->
</section>
</div><!--col-md-12 close-->
</div><!--row close-->


<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Lead </strong>Stage
<?php if($this->session->userdata('role') == 1) { ?>	
<div class="btn-group" style="margin: 0px 0px 0px 100px;">
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
<?php } ?>
</h1>
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
<div id="chart_div_stage"></div> 
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->
</div><!--row close-->


<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Lead </strong>Status
<?php if($this->session->userdata('role') == 1) { ?>  
<div class="btn-group" style="margin: 0px 0px 0px 100px;">
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
<?php } ?>
</h1>

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
<div id="chart_div"> </div> 
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->
</div><!--row close-->



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






<!--Load the AJAX API--> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 

<script type="text/javascript">
    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 
    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(drawChart); 
    
    var ur = "Getdata_status";
    var userleadstatus = $('#userleadstatus').val();
    //alert(userleadstatus);
    function drawChart() { 
      var jsonData = $.ajax({ 
          type : 'POST',
          url: ur, 
          data : {'userlead': userleadstatus},
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      //alert(jsonData);
      var data = new google.visualization.DataTable(jsonData); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_div')); 
      chart.draw(data, {width: 1000, height: 400}); 
    } 
 
</script> 
    
<script type="text/javascript">
    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 
     // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(drawChart2); 
    
    var url2 = "Getdata_stage";
    var userleadstage = $('#userleadstage').val();
    function drawChart2() { 
      var jsonDataStage = $.ajax({ 
          type : 'POST',
          url: url2, 
          data : {'userstage':userleadstage},
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      //alert(jsonDataStage);
      var data = new google.visualization.DataTable(jsonDataStage); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_div_stage')); 
      chart.draw(data, {width: 1000, height: 400}); 
    } 
</script>   



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

<!-- jQuery -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- jQuery -->

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

jQuery(document).ready(function() {

$('#container2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: 'Task Status'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
        showInLegend: true
            }
        },
        series: [{
            type: 'pie',
            name: 'Task',
            data: <?php echo json_encode($arr3);?>
        }]
     });
  });
</script>









<?php $this->load->view('footer.php'); ?>

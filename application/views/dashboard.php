
<?php $this->load->view('header.php'); ?>

<style>
input.form-control {
    font-size: 14px;
}

.collapsable {
    /*border: 1px solid #eee;*/
    padding: 12px;
    display: block;
}

label {
    font-size: 13px;
    font-weight: bold;
}

.gist {
   overflow: auto;
}

.gist .blob-wrapper.data {
   max-height: 350px;
   overflow: auto;
}

.list-group-item {
    padding: 4px 0;
    border: 0;
    font-size: 16px;
}

.leftcol {
    position: absolute;
    top: 180px;
}

.rightcol {
    max-width: 950px;
}

@media (min-width: 980px) {
    .rightcol {
       /* margin-left: 320px;*/
    }
}

p, pre {
    margin-bottom: 2em;
}

ul.nobullets {
    margin: 0;
    padding: 0;
    list-style: none;
}
ul.nobullets li {
    padding-bottom: 1em;
    margin-bottom: 1em;
    border-bottom: 1px dotted #ddd;
}

input[type="text"] {
    padding: 6px;
    width:auto;
    border-radius: 4px;
}


</style>
   
<!--dashboard js-->   
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/chart_dashboard/labelschart_css/jquery.jqChart.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/chart_dashboard/labelschart_css/styles.css" />
<script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/assets/chart_dashboard/labelschart_js/jquery.jqChart.min.js" type="text/javascript"></script>


<!----------------------------------------Pie Chart 1 ------------------------------------------------->
<script>
        $(document).ready(function () {
            $('#jqChart1').jqChart({
                //title: { text: 'Labels Formatting' },
                legend: { title: { text: 'Countries' } },
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
                        data: [['United States', 65], ['United Kingdom', 58],
                               ['Germany', 30], ['India', 60], ['Russia', 65], ['China', 75]]
                    }
                ]
            });
        });
</script>  

<!----------------------------------------Funnel Chart 2 ------------------------------------------------->

<script>
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

            $('#jqChart2').jqChart({
                //title: { text: 'Funnel Chart' },
                legend: { title: 'Countries' },
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
                        data: [['United States', 650], ['United Kingdom', 530], ['Germany', 440],
                               ['India', 280], ['Russia', 150], ['China', 75]]
                    }
                ]
            });
        });

</script>

<!----------------------------------------bar Chart 3 ------------------------------------------------->

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
                legend: { title: 'Countries' },
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
                        data: [['United States', 65], ['United Kingdom', 58], ['Germany', 30],
                               ['India', 60], ['Russia', 65], ['China', 75]],
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


<!----------------------------------------doughnut Chart 4 ------------------------------------------------->
<script>

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
              //	title: { text: 'Doughnut Chart' },
                legend: { title: 'Countries' },
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
                        data: [['United States United States', 65], ['United Kingdom', 58], ['Germany', 30],
                               ['India', 60], ['Russia', 65], ['China', 75]]
                    }
                ]
            });
        });

</script>

<!--dashboard js close-->     
	
	
	
	
	
<!--/ CONTROLS Content -->
<section id="content">
<div class="page page-forms-common">
<div class="pageheader">
<h2>Ritco Dashboard <span> </span></h2>
<div class="page-bar">
<ul class="page-breadcrumb">
<li> <a href="dashboar"><i class="fa fa-home"></i> Ritco</a> </li>
<li> <a href="dashboar">Dashboard</a> </li>
</ul>
</div>
</div>


<div class="row">
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-slategray">
<div class="row">
<div class="col-xs-4"> <i class="fa fa-eye fa-4x"></i> </div>
<div class="col-xs-8">
<p class="text-elg text-strong mb-0">29 651</p>
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
<p class="text-elg text-strong mb-0">165 984</p>
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
<p class="text-elg text-strong mb-0">3 659</p>
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
<p class="text-elg text-strong mb-0">19 364</p>
<span>Total Profile</span> </div>
</div>
</div>
</div>
</div>
</div><!--row close-->


<div class="row">
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

<div class="tile-body">
<div class="row">
<div class="col-sm-7">
<div id="reportrange" class="pull-right___" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width:100%"> <i class="fa fa-calendar"></i>&nbsp; <span></span> <i class="fa fa-caret-down pull-right"></i> </div>

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
</script>

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
</div><!--row close-->

<div class="pull-right" style="margin: 20px 50px 0px 0px; font-size: 11px;">
<strong>Total Lead : </strong>15<br>
<strong>New Opportunity : </strong>5<br>
<strong>Contacting : </strong>1<br>
<strong>Rates/Quotation Submitted : </strong>8<br>
<strong>Closed : </strong>1<br>
</div>

<div id="jqChart1" style="width: 500px; height: 300px;"></div>
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->


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

<div class="tile-body">
<div id="jqChart2" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->
</div><!--row close-->

<div class="row">
<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Revenue </strong>$15,341,110</h1>
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
<div id="jqChart3" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->

<div class="col-md-6">
<section class="tile">
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Number </strong>of Opps by State</h1>
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
<div id="jqChart4" style="width: 500px; height: 300px;">
</div><!-- /tile body -->
</section>
</div><!--col-md-6 close-->
</div><!--row close-->

</div>
</section><!--/ CONTENT -->

<?php $this->load->view('footer.php'); ?>



<script type="text/javascript" src="http://www.daterangepicker.com/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.daterangepicker.com/daterangepicker.css">



<?php $this->load->view('header.php'); ?>

    <!--Load the AJAX API--> 
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 

<script type="text/javascript">

    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 

    // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(drawChart); 
    
    var ur = "Getdata_status";
    function drawChart() { 
      var jsonData = $.ajax({ 
          url: ur, 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      //alert(jsonData);
      var data = new google.visualization.DataTable(jsonData); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_div')); 
      chart.draw(data, {width: 500, height: 400}); 
    } 
 
    </script> 

<html>
  <body> 
    <!--Div that will hold the pie chart--> 
    <h4 style="margin: 80px 0px 0px 200px;">Lead Status</h4> 
    <div id="chart_div" style="margin-left: 200px;">    </div> 
  </body> 
</html>




<script type="text/javascript">

    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart']}); 

     // Set a callback to run when the Google Visualization API is loaded. 
    google.charts.setOnLoadCallback(drawChart2); 
    
    var urls = "Getdata_stage";
    function drawChart2() { 
      var jsonDataStage = $.ajax({ 
          url: urls, 
          dataType: "json", 
          async: false 
          }).responseText; 
           
      // Create our data table out of JSON data loaded from server. 
      //alert(jsonDataStage);
      var data = new google.visualization.DataTable(jsonDataStage); 
 
      // Instantiate and draw our chart, passing in some options. 
      var chart = new google.visualization.PieChart(document.getElementById('chart_div_stage')); 
      chart.draw(data, {width: 500, height: 400}); 
    } 
</script>


<html>
  <body> 
    <!--Div that will hold the pie chart--> 
    <h4 style="margin: -417px 0px 0px 750px;">Lead Stage</h4> 
    <div id="chart_div_stage" style="margin-left: 750px;">    </div> 
  </body> 
</html>    





<?php 
$exptd_bus_pm=$this->db->query("select *,SUM('opp_value') as expbpm from tbl_leads where status='A' GROUP BY SUBSTRING_INDEX('closuredate',' ',1) ");
$jsondata = json_encode($exptd_bus_pm,true);
?>

<input type="hidden" id="jsondata" value="<?=$jsondata?>">

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?=base_url();?>chart/assets/bootstrap/bootstrap4-alpha3.min.css">
        
        <style>
            body {
                background-color: #fafafa;
                font-size: 16px;
                line-height: 1.5;
            }
            
            h1,h2,h3,h4,h5,h6 {
                font-weight: 400;   
            }
            
            #header {
                border-bottom: 5px solid #37474F;
                color: #37474F;
                margin-bottom: 1.5rem;
                padding: 1rem 0;
            }
            
            #revenue-tag {
                font-weight: inherit !important;
                border-radius: 0px !important;
            }
            
            .card {
                border: 0rem;
                border-radius: 0rem;
            }
            
            .card-header {
                background-color: #37474F;
                border-radius: 0 !important;
                color:  white;
                margin-bottom: 0;
                padding:    1rem;
            }
            
            .card-block {
                border: 1px solid #cccccc;  
            }
            
            .shadow {
                box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.14),
                                        0 1px 18px 0 rgba(0, 0, 0, 0.12),
                                        0 3px 5px -1px rgba(0, 0, 0, 0.2);
            }
            
            #revenue-column-chart, #products-revenue-pie-chart, #orders-spline-chart {
                height: 300px;
                width: 100%;
            }           
        </style>
        
        <!-- Scripts -->
        <script src="<?=base_url();?>chart/assets/jquery/jquery-3.1.0.min.js"></script>
        <script src="<?=base_url();?>chart/assets/tether/tether.min.js"></script>
        <script src="<?=base_url();?>chart/assets/bootstrap/bootstrap4-alpha3.min.js"></script>
        <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
    
        <script>
            $(function () {
                var totalRevenue = 15341110;
                var jsondata = $('#jsondata').val();
                //alert(jsondata);
                // CanvasJS column chart to show revenue from Jan 2015 - Dec 2015
                var revenueColumnChart = new CanvasJS.Chart("revenue-column-chart", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    theme: "theme2",
                    axisX: {
                        labelFontSize: 14,
                        valueFormatString: "MMM YYYY"
                    },
                    axisY: {
                        labelFontSize: 14,
                        prefix: ""
                    },
                    toolTip: {
                        borderThickness: 0,
                        cornerRadius: 0
                    },
                    data: [
                        {
                            type: "column",
                            yValueFormatString: "###,###.##",
                            dataPoints: [
                                { x: new Date("1 Jan 2015"), y: 868800 },
                                { x: new Date("1 Feb 2015"), y: 1071550 },
                                { x: new Date("1 Mar 2015"), y: 1286200 },
                                { x: new Date("1 Apr 2015"), y: 1106900 },
                                { x: new Date("1 May 2015"), y: 1033800 },
                                { x: new Date("1 Jun 2015"), y: 1017160 },
                                { x: new Date("1 Jul 2015"), y: 1458000 },
                                { x: new Date("1 Aug 2015"), y: 1165850 },
                                { x: new Date("1 Sep 2015"), y: 1594150 },
                                { x: new Date("1 Oct 2015"), y: 1501700 },
                                { x: new Date("1 Nov 2015"), y: 1588400 },
                                { x: new Date("1 Dec 2015"), y: 1648600 }
                            ]
                        }
                    ]
                });
                
                revenueColumnChart.render();
                
                
                
                
            });
        </script>
        
    </head>
    <body>
        <div class="container">

            <div class="row m-b-1">
                <div class="col-xs-12" style="margin: 10px 0px 0px 75px">
                    <div class="card shadow">
                        <h4 class="card-header">Expected Business Per Month <span class="tag tag-success" id="revenue-tag"></span></h4>
                        <div class="card-block">
                            <div id="revenue-column-chart"></div>
                        </div>
                    </div>
                </div>
            </div> <!-- row -->

        </div> <!-- container -->
    </body>
</html>


<div style="display: none;">
<?php $this->load->view('footer.php'); ?>
</div>
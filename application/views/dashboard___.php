<?php $this->load->view('header.php'); ?>


    
<!--/ CONTROLS Content -->
<section id="content">
<div class="page page-dashboard">
<!-- cards row -->
<div class="row">
<!-- col -->
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-greensea">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <i class="fa fa-users fa-4x"></i> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-8">
<p class="text-elg text-strong mb-0">3 659</p>
<span>New Users</span> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
<div class="back bg-greensea">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-cog fa-2x"></i> Settings</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-chain-broken fa-2x"></i> Content</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-ellipsis-h fa-2x"></i> More</a> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
</div>
</div>
<!-- /col -->
<!-- col -->
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-lightred">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <i class="fa fa-shopping-cart fa-4x"></i> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-8">
<p class="text-elg text-strong mb-0">19 364</p>
<span>New Orders</span> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
<div class="back bg-lightred">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-cog fa-2x"></i> Settings</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-chain-broken fa-2x"></i> Content</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-ellipsis-h fa-2x"></i> More</a> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
</div>
</div>
<!-- /col -->
<!-- col -->
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-blue">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <i class="fa fa-usd fa-4x"></i> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-8">
<p class="text-elg text-strong mb-0">165 984</p>
<span>Sales</span> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
<div class="back bg-blue">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-cog fa-2x"></i> Settings</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-chain-broken fa-2x"></i> Content</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-ellipsis-h fa-2x"></i> More</a> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
</div>
</div>
<!-- /col -->
<!-- col -->
<div class="card-container col-lg-3 col-sm-6 col-sm-12">
<div class="card">
<div class="front bg-slategray">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <i class="fa fa-eye fa-4x"></i> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-8">
<p class="text-elg text-strong mb-0">29 651</p>
<span>Visits</span> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
<div class="back bg-slategray">
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-cog fa-2x"></i> Settings</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-chain-broken fa-2x"></i> Content</a> </div>
<!-- /col -->
<!-- col -->
<div class="col-xs-4"> <a href=#><i class="fa fa-ellipsis-h fa-2x"></i> More</a> </div>
<!-- /col -->
</div>
<!-- /row -->
</div>
</div>
</div>
<!-- /col -->
</div>
<!-- /row -->
<!-- row -->
<div class="row">
<!-- col -->
<div class="col-md-8">
<!-- tile -->
<section class="tile">
<!-- tile header -->
<div class="tile-header bg-greensea dvd dvd-btm">
<h1 class="custom-font"><strong>Statistics </strong>Graph</h1>
</div>
<!-- /tile header -->
<!-- tile widget -->
<div class="tile-widget bg-greensea">
<div id="statistics-chart" style="height: 250px;"></div>
</div>

</section>
<!-- /tile -->
</div>
<!-- /col -->
<!-- col -->
<div class="col-md-4">
<!-- tile -->
<section class="tile" fullscreen="isFullscreen02">
<!-- tile header -->
<div class="tile-header dvd dvd-btm">
<h1 class="custom-font"><strong>Total </strong>Value of Leads</h1>
</div>
<!-- /tile header -->
<!-- tile widget -->
<div class="tile-widget">
<div id="browser-usage" style="height: 250px"></div>
</div>
<!-- /tile widget -->
</section>
</div>
</div>


</div><!--pageheader close-->

</section><!--/ CONTENT -->

<?php $this->load->view('footer.php'); ?>
<script>
            $(window).load(function(){
                // Initialize Statistics chart
                var data = [{
                    data: [[1,15],[2,40],[3,35],[4,39],[5,42],[6,50],[7,46],[8,49],[9,59],[10,60],[11,58],[12,74]],
                    label: 'Unique Visits',
                    points: {
                        show: true,
                        radius: 4
                    },
                    splines: {
                        show: true,
                        tension: 0.45,
                        lineWidth: 4,
                        fill: 0
                    }
                }, {
                    data: [[1,50],[2,80],[3,90],[4,85],[5,99],[6,125],[7,114],[8,96],[9,130],[10,145],[11,139],[12,160]],
                    label: 'Page Views',
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        lineWidth: 0,
                        fillColor: { colors: [{ opacity: 0.3 }, { opacity: 0.8}] }
                    }
                }];

                var options = {
                    colors: ['#e05d6f','#61c8b8'],
                    series: {
                        shadowSize: 0
                    },
                    legend: {
                        backgroundOpacity: 0,
                        margin: -7,
                        position: 'ne',
                        noColumns: 2
                    },
                    xaxis: {
                        tickLength: 0,
                        font: {
                            color: '#fff'
                        },
                        position: 'bottom',
                        ticks: [
                            [ 1, 'JAN' ], [ 2, 'FEB' ], [ 3, 'MAR' ], [ 4, 'APR' ], [ 5, 'MAY' ], [ 6, 'JUN' ], [ 7, 'JUL' ], [ 8, 'AUG' ], [ 9, 'SEP' ], [ 10, 'OCT' ], [ 11, 'NOV' ], [ 12, 'DEC' ]
                        ]
                    },
                    yaxis: {
                        tickLength: 0,
                        font: {
                            color: '#fff'
                        }
                    },
                    grid: {
                        borderWidth: {
                            top: 0,
                            right: 0,
                            bottom: 1,
                            left: 1
                        },
                        borderColor: 'rgba(255,255,255,.3)',
                        margin:0,
                        minBorderMargin:0,
                        labelMargin:20,
                        hoverable: true,
                        clickable: true,
                        mouseActiveRadius:6
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: '%s: %y',
                        defaultTheme: false,
                        shifts: {
                            x: 0,
                            y: 20
                        }
                    }
                };

                var plot = $.plot($("#statistics-chart"), data, options);

                $(window).resize(function() {
                    // redraw the graph in the correctly sized div
                    plot.resize();
                    plot.setupGrid();
                    plot.draw();
                });
                // * Initialize Statistics chart

                //Initialize morris chart
                Morris.Donut({
                    element: 'browser-usage',
                    data: [
                        {label: 'Chrome', value: 25, color: '#00a3d8'},
                        {label: 'Safari', value: 20, color: '#2fbbe8'},
                        {label: 'Firefox', value: 15, color: '#72cae7'},
                        {label: 'Opera', value: 5, color: '#d9544f'},
                        {label: 'Internet Explorer', value: 10, color: '#ffc100'},
                        {label: 'Other', value: 25, color: '#1693A5'}
                    ],
                    resize: true
                });
                //*Initialize morris chart


                // Initialize owl carousels
                $('#todo-carousel, #feed-carousel, #notes-carousel').owlCarousel({
                    autoPlay: 5000,
                    stopOnHover: true,
                    slideSpeed : 300,
                    paginationSpeed : 400,
                    singleItem : true,
                    responsive: true
                });

                $('#appointments-carousel').owlCarousel({
                    autoPlay: 5000,
                    stopOnHover: true,
                    slideSpeed : 300,
                    paginationSpeed : 400,
                    navigation: true,
                    navigationText : ['<i class=\'fa fa-chevron-left\'></i>','<i class=\'fa fa-chevron-right\'></i>'],
                    singleItem : true
                });
                //* Initialize owl carousels


                // Initialize rickshaw chart
                var graph;

                var seriesData = [ [], []];
                var random = new Rickshaw.Fixtures.RandomData(50);

                for (var i = 0; i < 50; i++) {
                    random.addData(seriesData);
                }

                graph = new Rickshaw.Graph( {
                    element: document.querySelector("#realtime-rickshaw"),
                    renderer: 'area',
                    height: 133,
                    series: [{
                        name: 'Series 1',
                        color: 'steelblue',
                        data: seriesData[0]
                    }, {
                        name: 'Series 2',
                        color: 'lightblue',
                        data: seriesData[1]
                    }]
                });

                var hoverDetail = new Rickshaw.Graph.HoverDetail( {
                    graph: graph,
                });

                graph.render();

                setInterval( function() {
                    random.removeData(seriesData);
                    random.addData(seriesData);
                    graph.update();

                },1000);
                //* Initialize rickshaw chart

                //Initialize mini calendar datepicker
                $('#mini-calendar').datetimepicker({
                    inline: true
                });
                //*Initialize mini calendar datepicker


                //todo's
                $('.widget-todo .todo-list li .checkbox').on('change', function() {
                    var todo = $(this).parents('li');

                    if (todo.hasClass('completed')) {
                        todo.removeClass('completed');
                    } else {
                        todo.addClass('completed');
                    }
                });
                //* todo's


                //initialize datatable
                $('#project-progress').DataTable({
                    "aoColumnDefs": [
                      { 'bSortable': false, 'aTargets': [ "no-sort" ] }
                    ],
                });
                //*initialize datatable

                //load wysiwyg editor
                $('#summernote').summernote({
                    toolbar: [
                        //['style', ['style']], // no style button
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        //['insert', ['picture', 'link']], // no insert buttons
                        //['table', ['table']], // no table button
                        //['help', ['help']] //no help button
                    ],
                    height: 143   //set editable area's height
                });
                //*load wysiwyg editor
            });
        </script>